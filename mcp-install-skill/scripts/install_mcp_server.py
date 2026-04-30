#!/usr/bin/env python3
"""
MCP Server Installation Script

Installs and configures Model Context Protocol (MCP) servers
from various sources with automatic client configuration.
"""

import argparse
import json
import os
import subprocess
import sys
import shutil
import tempfile
from pathlib import Path
from typing import Dict, Optional, Any
import urllib.request
import tarfile
import zipfile


class MCPInstaller:
    """Main class for MCP server installation and configuration."""
    
    def __init__(self, config_path: Optional[str] = None):
        self.config_path = config_path
        self.installed_servers: Dict[str, Dict[str, Any]] = {}
        
    def install_from_npm(self, package: str, name: str, 
                        client: str = "openclaw",
                        config_path: Optional[str] = None) -> Dict[str, Any]:
        """Install MCP server from npm registry."""
        print(f"Installing MCP server '{name}' from npm package '{package}'...")
        
        # Install via npm
        try:
            result = subprocess.run(
                ["npm", "install", "-g", package],
                capture_output=True,
                text=True,
                check=True
            )
            print(f"✓ npm install completed for {package}")
        except subprocess.CalledProcessError as e:
            print(f"✗ npm install failed: {e.stderr}")
            raise
        
        # Determine executable name (usually same as package without scope)
        executable = package.split("/")[-1].replace("@", "").replace("/", "-")
        
        server_info = {
            "name": name,
            "method": "npm",
            "package": package,
            "executable": executable,
            "transport": "stdio",
            "command": executable,
            "args": [],
            "env": {}
        }
        
        self.installed_servers[name] = server_info
        self._update_client_config(client, config_path or self.config_path)
        
        return server_info
    
    def install_from_github(self, repo: str, name: str,
                           client: str = "openclaw",
                           branch: str = "main",
                           config_path: Optional[str] = None) -> Dict[str, Any]:
        """Install MCP server from GitHub repository."""
        print(f"Installing MCP server '{name}' from GitHub repo '{repo}'...")
        
        # Create temporary directory for clone
        with tempfile.TemporaryDirectory() as tmpdir:
            clone_path = Path(tmpdir) / "repo"
            
            # Clone repository
            try:
                result = subprocess.run(
                    ["git", "clone", "--depth", "1", "-b", branch,
                     f"https://github.com/{repo}.git", str(clone_path)],
                    capture_output=True,
                    text=True,
                    check=True
                )
                print(f"✓ Repository cloned: {repo}")
            except subprocess.CalledProcessError as e:
                print(f"✗ Git clone failed: {e.stderr}")
                raise
            
            # Check for package.json (Node.js project)
            package_json = clone_path / "package.json"
            if package_json.exists():
                return self._install_nodejs_server(clone_path, name, repo, client, config_path)
            
            # Check for pyproject.toml or setup.py (Python project)
            pyproject = clone_path / "pyproject.toml"
            setup_py = clone_path / "setup.py"
            if pyproject.exists() or setup_py.exists():
                return self._install_python_server(clone_path, name, repo, client, config_path)
            
            # Default: try to find server entry point
            return self._install_generic_server(clone_path, name, repo, client, config_path)
    
    def _install_nodejs_server(self, path: Path, name: str, repo: str,
                              client: str, config_path: Optional[str]) -> Dict[str, Any]:
        """Install Node.js based MCP server."""
        print("Detected Node.js project, installing dependencies...")
        
        try:
            # Install dependencies
            subprocess.run(
                ["npm", "install"],
                cwd=path,
                capture_output=True,
                check=True
            )
            
            # Try to build if build script exists
            package_json = json.loads((path / "package.json").read_text())
            if "scripts" in package_json and "build" in package_json["scripts"]:
                subprocess.run(
                    ["npm", "run", "build"],
                    cwd=path,
                    capture_output=True,
                    check=True
                )
                print("✓ Build completed")
        except subprocess.CalledProcessError as e:
            print(f"Warning: npm install/build failed: {e}")
        
        # Find main entry point
        main_file = package_json.get("main", "index.js")
        main_path = path / main_file
        
        server_info = {
            "name": name,
            "method": "github",
            "repo": repo,
            "path": str(path),
            "language": "nodejs",
            "transport": "stdio",
            "command": "node",
            "args": [str(main_path)],
            "env": {"NODE_ENV": "production"}
        }
        
        self.installed_servers[name] = server_info
        self._update_client_config(client, config_path or self.config_path)
        
        return server_info
    
    def _install_python_server(self, path: Path, name: str, repo: str,
                              client: str, config_path: Optional[str]) -> Dict[str, Any]:
        """Install Python based MCP server."""
        print("Detected Python project, installing dependencies...")
        
        try:
            # Install in development mode
            subprocess.run(
                [sys.executable, "-m", "pip", "install", "-e", str(path)],
                capture_output=True,
                check=True
            )
            print("✓ Python package installed")
        except subprocess.CalledProcessError as e:
            print(f"Warning: pip install failed: {e}")
        
        # Try to determine module name from setup or pyproject
        module_name = name.replace("-", "_").replace(" ", "_")
        
        server_info = {
            "name": name,
            "method": "github",
            "repo": repo,
            "path": str(path),
            "language": "python",
            "transport": "stdio",
            "command": sys.executable,
            "args": ["-m", module_name],
            "env": {"PYTHONPATH": str(path)}
        }
        
        self.installed_servers[name] = server_info
        self._update_client_config(client, config_path or self.config_path)
        
        return server_info
    
    def _install_generic_server(self, path: Path, name: str, repo: str,
                               client: str, config_path: Optional[str]) -> Dict[str, Any]:
        """Install MCP server with generic configuration."""
        print("Generic project detected, creating basic configuration...")
        
        server_info = {
            "name": name,
            "method": "github",
            "repo": repo,
            "path": str(path),
            "language": "unknown",
            "transport": "stdio",
            "command": "",
            "args": [],
            "env": {}
        }
        
        self.installed_servers[name] = server_info
        self._update_client_config(client, config_path or self.config_path)
        
        return server_info
    
    def install_from_local(self, path: str, name: str,
                          client: str = "openclaw",
                          config_path: Optional[str] = None) -> Dict[str, Any]:
        """Install MCP server from local directory."""
        print(f"Installing MCP server '{name}' from local path '{path}'...")
        
        local_path = Path(path).resolve()
        if not local_path.exists():
            raise FileNotFoundError(f"Path does not exist: {path}")
        
        # Detect project type
        if (local_path / "package.json").exists():
            return self._install_nodejs_server(local_path, name, "local", client, config_path)
        elif (local_path / "pyproject.toml").exists() or (local_path / "setup.py").exists():
            return self._install_python_server(local_path, name, "local", client, config_path)
        else:
            return self._install_generic_server(local_path, name, "local", client, config_path)
    
    def _update_client_config(self, client: str, config_path: Optional[str]):
        """Update client configuration file with installed servers."""
        if not config_path:
            # Determine default config path based on client
            if client == "claude-desktop":
                home = Path.home()
                if sys.platform == "darwin":
                    config_path = home / "Library/Application Support/Claude/claude_desktop_config.json"
                elif sys.platform == "win32":
                    config_path = home / "AppData/Roaming/Claude/claude_desktop_config.json"
                else:
                    config_path = home / ".config/Claude/claude_desktop_config.json"
            elif client == "openclaw":
                config_path = Path.home() / ".openclaw/config/mcp.json"
            else:
                print(f"Warning: Unknown client '{client}', skipping config update")
                return
        
        config_path = Path(config_path)
        
        # Create config directory if it doesn't exist
        config_path.parent.mkdir(parents=True, exist_ok=True)
        
        # Load existing config or create new
        if config_path.exists():
            with open(config_path, 'r') as f:
                try:
                    config = json.load(f)
                except json.JSONDecodeError:
                    config = {}
        else:
            config = {}
        
        # Update config based on client type
        if client == "claude-desktop":
            if "mcpServers" not in config:
                config["mcpServers"] = {}
            
            for name, server in self.installed_servers.items():
                config["mcpServers"][name] = {
                    "command": server["command"],
                    "args": server["args"],
                    "env": server["env"]
                }
        elif client == "openclaw":
            if "mcp" not in config:
                config["mcp"] = {"servers": {}, "settings": {}}
            
            for name, server in self.installed_servers.items():
                config["mcp"]["servers"][name] = {
                    "command": server["command"],
                    "args": server["args"],
                    "transport": server["transport"],
                    "env": server["env"]
                }
        
        # Write updated config
        with open(config_path, 'w') as f:
            json.dump(config, f, indent=2)
        
        print(f"✓ Updated {client} configuration: {config_path}")


def main():
    parser = argparse.ArgumentParser(
        description="Install and configure MCP servers"
    )
    parser.add_argument(
        "--method", "-m",
        choices=["npm", "github", "local"],
        required=True,
        help="Installation method"
    )
    parser.add_argument(
        "--package", "-p",
        help="NPM package name (for npm method)"
    )
    parser.add_argument(
        "--repo", "-r",
        help="GitHub repository (owner/repo) (for github method)"
    )
    parser.add_argument(
        "--path",
        help="Local directory path (for local method)"
    )
    parser.add_argument(
        "--name", "-n",
        required=True,
        help="Name for the MCP server"
    )
    parser.add_argument(
        "--client", "-c",
        default="openclaw",
        choices=["claude-desktop", "openclaw", "cursor"],
        help="Target client for configuration"
    )
    parser.add_argument(
        "--config",
        help="Path to client configuration file"
    )
    parser.add_argument(
        "--branch", "-b",
        default="main",
        help="GitHub branch (for github method)"
    )
    
    args = parser.parse_args()
    
    installer = MCPInstaller()
    
    try:
        if args.method == "npm":
            if not args.package:
                print("Error: --package is required for npm method")
                sys.exit(1)
            installer.install_from_npm(
                args.package, args.name,
                client=args.client,
                config_path=args.config
            )
        
        elif args.method == "github":
            if not args.repo:
                print("Error: --repo is required for github method")
                sys.exit(1)
            installer.install_from_github(
                args.repo, args.name,
                client=args.client,
                branch=args.branch,
                config_path=args.config
            )
        
        elif args.method == "local":
            if not args.path:
                print("Error: --path is required for local method")
                sys.exit(1)
            installer.install_from_local(
                args.path, args.name,
                client=args.client,
                config_path=args.config
            )
        
        print(f"\n✓ Successfully installed MCP server '{args.name}'!")
        
    except Exception as e:
        print(f"\n✗ Installation failed: {e}")
        sys.exit(1)


if __name__ == "__main__":
    main()
