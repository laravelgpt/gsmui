#!/usr/bin/env python3
"""
Skills Manager - Install and manage MCP skills and extensions
"""

import argparse
import json
import subprocess
import sys
from pathlib import Path
from typing import Dict, Any, List, Optional
import urllib.request
import tarfile
import zipfile
import os


class SkillsManager:
    """Manage MCP skills installation and updates."""
    
    def __init__(self, config_path: Optional[str] = None):
        self.config_path = config_path
        self.skills_dir = Path.home() / ".mcp_skills"
        self.installed_skills = self._load_installed_skills()
    
    def _load_installed_skills(self) -> Dict[str, Dict]:
        """Load list of installed skills."""
        skills_index = self.skills_dir / "index.json"
        if skills_index.exists():
            with open(skills_index, 'r') as f:
                return json.load(f)
        return {}
    
    def _save_installed_skills(self):
        """Save installed skills index."""
        self.skills_dir.mkdir(parents=True, exist_ok=True)
        index_file = self.skills_dir / "index.json"
        with open(index_file, 'w') as f:
            json.dump(self.installed_skills, f, indent=2)
    
    def install_skill(self, skill_name: str, source: str,
                     version: str = "latest",
                     force: bool = False) -> Dict[str, Any]:
        """Install a skill from various sources."""
        print(f"Installing skill '{skill_name}'...")
        
        if skill_name in self.installed_skills and not force:
            print(f"Skill '{skill_name}' already installed. Use --force to reinstall.")
            return {"success": False, "error": "Already installed"}
        
        # Determine source type
        if source.startswith("http://") or source.startswith("https://"):
            result = self._install_from_url(skill_name, source, version)
        elif source.startswith("github:"):
            result = self._install_from_github(skill_name, source, version)
        elif source.startswith("npm:"):
            result = self._install_from_npm(skill_name, source, version)
        elif Path(source).exists():
            result = self._install_from_local(skill_name, source)
        else:
            result = self._install_from_registry(skill_name, source, version)
        
        if result.get("success"):
            self.installed_skills[skill_name] = {
                "name": skill_name,
                "source": source,
                "version": version,
                "installed_at": str(Path.cwd()),
                "path": result.get("path", "")
            }
            self._save_installed_skills()
        
        return result
    
    def _install_from_url(self, skill_name: str, url: str,
                         version: str) -> Dict[str, Any]:
        """Install skill from URL."""
        print(f"  Downloading from {url}...")
        
        try:
            # Download file
            skill_path = self.skills_dir / skill_name
            skill_path.mkdir(parents=True, exist_ok=True)
            
            if url.endswith(".zip"):
                zip_path = skill_path / "skill.zip"
                urllib.request.urlretrieve(url, zip_path)
                
                with zipfile.ZipFile(zip_path, 'r') as zip_ref:
                    zip_ref.extractall(skill_path)
                
                zip_path.unlink()
            elif url.endswith(".tar.gz") or url.endswith(".tgz"):
                tar_path = skill_path / "skill.tar.gz"
                urllib.request.urlretrieve(url, tar_path)
                
                with tarfile.open(tar_path, 'r:gz') as tar_ref:
                    tar_ref.extractall(skill_path)
                
                tar_path.unlink()
            else:
                # Assume it's a direct file
                file_path = skill_path / "skill.json"
                urllib.request.urlretrieve(url, file_path)
            
            return {"success": True, "path": str(skill_path)}
        
        except Exception as e:
            return {"success": False, "error": str(e)}
    
    def _install_from_github(self, skill_name: str, repo: str,
                           version: str) -> Dict[str, Any]:
        """Install skill from GitHub repository."""
        # Extract repo from github:owner/repo format
        repo_name = repo.replace("github:", "")
        
        print(f"  Cloning {repo_name}...")
        
        try:
            skill_path = self.skills_dir / skill_name
            
            # Clone repository
            result = subprocess.run(
                ["git", "clone", "--depth", "1",
                 f"https://github.com/{repo_name}.git",
                 str(skill_path)],
                capture_output=True,
                text=True
            )
            
            if result.returncode != 0:
                return {"success": False, "error": result.stderr}
            
            # Checkout specific version if needed
            if version != "latest":
                subprocess.run(
                    ["git", "checkout", version],
                    cwd=skill_path,
                    capture_output=True
                )
            
            return {"success": True, "path": str(skill_path)}
        
        except Exception as e:
            return {"success": False, "error": str(e)}
    
    def _install_from_npm(self, skill_name: str, package: str,
                         version: str) -> Dict[str, Any]:
        """Install skill from npm registry."""
        package_name = package.replace("npm:", "")
        
        if version != "latest":
            package_name = f"{package_name}@{version}"
        
        print(f"  Installing npm package {package_name}...")
        
        try:
            result = subprocess.run(
                ["npm", "install", "-g", package_name],
                capture_output=True,
                text=True
            )
            
            if result.returncode != 0:
                return {"success": False, "error": result.stderr}
            
            skill_path = self.skills_dir / skill_name
            
            return {
                "success": True,
                "path": str(skill_path),
                "note": "Installed globally via npm"
            }
        
        except Exception as e:
            return {"success": False, "error": str(e)}
    
    def _install_from_local(self, skill_name: str,
                           local_path: str) -> Dict[str, Any]:
        """Install skill from local directory."""
        print(f"  Copying from {local_path}...")
        
        try:
            source_path = Path(local_path).resolve()
            skill_path = self.skills_dir / skill_name
            
            # Copy directory
            import shutil
            if skill_path.exists():
                shutil.rmtree(skill_path)
            shutil.copytree(source_path, skill_path)
            
            return {"success": True, "path": str(skill_path)}
        
        except Exception as e:
            return {"success": False, "error": str(e)}
    
    def _install_from_registry(self, skill_name: str,
                              registry: str, version: str) -> Dict[str, Any]:
        """Install skill from skill registry."""
        # For now, just search and install from known skills
        known_skills = {
            "mcp-install": {
                "name": "MCP Install Skill",
                "description": "Install and manage MCP servers",
                "source": "local",
                "path": "/root/.openclaw/workspace/mcp-install-skill"
            }
        }
        
        if registry in known_skills:
            skill = known_skills[registry]
            print(f"  Installing {skill['name']}...")
            
            # Copy from known location
            source_path = Path(skill["path"])
            if source_path.exists():
                skill_path = self.skills_dir / skill_name
                import shutil
                if skill_path.exists():
                    shutil.rmtree(skill_path)
                shutil.copytree(source_path, skill_path)
                
                return {
                    "success": True,
                    "path": str(skill_path),
                    "info": skill
                }
        
        return {"success": False, "error": "Skill not found"}
    
    def list_skills(self) -> List[Dict]:
        """List all installed skills."""
        return list(self.installed_skills.values())
    
    def remove_skill(self, skill_name: str, force: bool = False) -> bool:
        """Remove an installed skill."""
        if skill_name not in self.installed_skills:
            print(f"Skill '{skill_name}' not installed.")
            return False
        
        if not force:
            confirm = input(f"Remove skill '{skill_name}'? (y/N): ")
            if confirm.lower() != 'y':
                return False
        
        skill = self.installed_skills[skill_name]
        skill_path = Path(skill.get("path", ""))
        
        if skill_path.exists():
            import shutil
            shutil.rmtree(skill_path)
        
        del self.installed_skills[skill_name]
        self._save_installed_skills()
        
        print(f"Removed skill '{skill_name}'.")
        return True
    
    def update_skill(self, skill_name: str) -> bool:
        """Update an installed skill."""
        if skill_name not in self.installed_skills:
            print(f"Skill '{skill_name}' not installed.")
            return False
        
        skill = self.installed_skills[skill_name]
        
        # Reinstall with force
        result = self.install_skill(
            skill_name,
            skill["source"],
            skill.get("version", "latest"),
            force=True
        )
        
        return result.get("success", False)
    
    def search_skills(self, query: str) -> List[Dict]:
        """Search for available skills."""
        # Mock search - would connect to registry
        known_skills = [
            {
                "name": "mcp-install",
                "title": "MCP Install Skill",
                "description": "Install and manage MCP servers",
                "tags": ["installation", "management", "mcp"]
            },
            {
                "name": "laravel-mcp",
                "title": "Laravel MCP Integration",
                "description": "Laravel-specific MCP tools",
                "tags": ["laravel", "php", "mcp"]
            },
            {
                "name": "code-review",
                "title": "Code Review MCP",
                "description": "Automated code review tools",
                "tags": ["review", "analysis", "quality"]
            },
            {
                "name": "token-monitor",
                "title": "Token Burn Monitor",
                "description": "Monitor and fix token burn issues",
                "tags": ["tokens", "security", "laravel"]
            }
        ]
        
        query_lower = query.lower()
        results = []
        
        for skill in known_skills:
            if (query_lower in skill["name"].lower() or
                query_lower in skill["title"].lower() or
                query_lower in skill["description"].lower() or
                any(query_lower in tag for tag in skill["tags"])):
                results.append(skill)
        
        return results


def main():
    parser = argparse.ArgumentParser(
        description="Manage MCP skills and extensions"
    )
    parser.add_argument(
        "action",
        choices=["install", "list", "remove", "update", "search"],
        help="Action to perform"
    )
    parser.add_argument(
        "--skill", "-s",
        help="Skill name"
    )
    parser.add_argument(
        "--source", "-S",
        help="Source (URL, github:repo, npm:package, local path, or registry name)"
    )
    parser.add_argument(
        "--version", "-v",
        default="latest",
        help="Version to install (default: latest)"
    )
    parser.add_argument(
        "--force", "-f",
        action="store_true",
        help="Force installation or removal"
    )
    parser.add_argument(
        "--query", "-q",
        help="Search query"
    )
    
    args = parser.parse_args()
    
    manager = SkillsManager()
    
    if args.action == "install":
        if not args.skill or not args.source:
            print("Error: --skill and --source are required for install")
            sys.exit(1)
        
        result = manager.install_skill(
            args.skill, args.source,
            version=args.version, force=args.force
        )
        
        if result["success"]:
            print(f"\n✓ Successfully installed '{args.skill}'!")
            print(f"  Path: {result.get('path', 'N/A')}")
        else:
            print(f"\n✗ Installation failed: {result.get('error')}")
            sys.exit(1)
    
    elif args.action == "list":
        skills = manager.list_skills()
        
        if not skills:
            print("No skills installed.")
            print("\nSearch available skills with: skills_manager.py search <query>")
        else:
            print(f"\nInstalled Skills ({len(skills)}):")
            print("=" * 60)
            for skill in skills:
                print(f"  • {skill['name']}")
                print(f"    Source: {skill['source']}")
                print(f"    Version: {skill.get('version', 'unknown')}")
                print(f"    Path: {skill.get('path', 'N/A')}")
                print()
    
    elif args.action == "remove":
        if not args.skill:
            print("Error: --skill is required for remove")
            sys.exit(1)
        
        success = manager.remove_skill(args.skill, force=args.force)
        if not success:
            sys.exit(1)
    
    elif args.action == "update":
        if not args.skill:
            print("Error: --skill is required for update")
            sys.exit(1)
        
        success = manager.update_skill(args.skill)
        if success:
            print(f"✓ Updated '{args.skill}'")
        else:
            print(f"✗ Update failed")
            sys.exit(1)
    
    elif args.action == "search":
        if not args.query:
            print("Error: --query is required for search")
            sys.exit(1)
        
        results = manager.search_skills(args.query)
        
        if not results:
            print(f"No skills found matching '{args.query}'")
        else:
            print(f"\nFound {len(results)} skills matching '{args.query}':")
            print("=" * 60)
            for skill in results:
                print(f"  • {skill['name']}")
                print(f"    {skill['title']}")
                print(f"    {skill['description']}")
                print(f"    Tags: {', '.join(skill['tags'])}")
                print()


if __name__ == "__main__":
    main()
