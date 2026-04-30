#!/usr/bin/env python3
"""
MCP Server Management Script

Manages the lifecycle of MCP servers including start, stop, restart,
and monitoring.
"""

import argparse
import json
import os
import re
import signal
import subprocess
import sys
import time
from pathlib import Path
from typing import Dict, Optional, List


class MCPServerManager:
    """Manages MCP server processes and lifecycle."""
    
    def __init__(self, config_path: Optional[str] = None):
        self.config_path = config_path or self._get_default_config_path()
        self.servers = self._load_server_configs()
        self.pid_dir = Path.home() / ".mcp_servers" / "pids"
        self.log_dir = Path.home() / ".mcp_servers" / "logs"
        self.pid_dir.mkdir(parents=True, exist_ok=True)
        self.log_dir.mkdir(parents=True, exist_ok=True)
    
    def _get_default_config_path(self) -> Path:
        """Get default configuration file path."""
        return Path.home() / ".openclaw" / "config" / "mcp.json"
    
    def _load_server_configs(self) -> Dict[str, Dict]:
        """Load server configurations from file."""
        if not self.config_path.exists():
            print(f"Warning: Config file not found: {self.config_path}")
            return {}
        
        with open(self.config_path, 'r') as f:
            config = json.load(f)
        
        # Extract servers from config
        servers = {}
        if "mcp" in config and "servers" in config["mcp"]:
            servers = config["mcp"]["servers"]
        elif "mcpServers" in config:
            servers = config["mcpServers"]
        
        return servers
    
    def start_server(self, name: str, timeout: int = 30) -> bool:
        """Start an MCP server."""
        if name not in self.servers:
            print(f"Error: Server '{name}' not found in configuration")
            return False
        
        server = self.servers[name]
        
        # Check if already running
        if self.is_server_running(name):
            print(f"Server '{name}' is already running (PID: {self._get_pid(name)})")
            return True
        
        print(f"Starting MCP server '{name}'...")
        
        # Build command
        command = server.get("command", "")
        args = server.get("args", [])
        env = server.get("env", {})
        
        if not command:
            print(f"Error: No command specified for server '{name}'")
            return False
        
        # Prepare environment
        server_env = os.environ.copy()
        server_env.update(env)
        
        # Prepare command list
        cmd_list = [command] + args if isinstance(args, list) else [command] + [args]
        
        # Start process
        try:
            log_file = self.log_dir / f"{name}.log"
            with open(log_file, 'a') as log:
                process = subprocess.Popen(
                    cmd_list,
                    env=server_env,
                    stdout=log,
                    stderr=subprocess.STDOUT,
                    start_new_session=True
                )
            
            # Save PID
            self._save_pid(name, process.pid)
            
            # Wait for server to be ready
            print(f"Waiting for server to start (PID: {process.pid})...")
            time.sleep(2)  # Give it time to initialize
            
            # Verify it's running
            if self.is_server_running(name):
                print(f"✓ Server '{name}' started successfully (PID: {process.pid})")
                return True
            else:
                print(f"✗ Server '{name}' failed to start")
                return False
        
        except FileNotFoundError:
            print(f"Error: Command not found: {command}")
            return False
        except Exception as e:
            print(f"Error starting server: {e}")
            return False
    
    def stop_server(self, name: str, force: bool = False) -> bool:
        """Stop an MCP server."""
        pid = self._get_pid(name)
        
        if not pid:
            print(f"Server '{name}' is not running")
            return True
        
        print(f"Stopping MCP server '{name}' (PID: {pid})...")
        
        try:
            # Try to get process info
            proc_info = self._get_proc_info(pid)
            
            if force:
                os.kill(pid, signal.SIGKILL)
                print(f"Sent SIGKILL to process {pid}")
            else:
                os.kill(pid, signal.SIGTERM)
                print(f"Sent SIGTERM to process {pid}")
            
            # Wait for process to terminate
            for _ in range(20):  # Wait up to 2 seconds
                if not self._is_pid_running(pid):
                    print(f"✓ Server '{name}' stopped")
                    self._remove_pid(name)
                    return True
                time.sleep(0.1)
            
            # Force kill if still running
            if force:
                try:
                    os.kill(pid, signal.SIGKILL)
                except ProcessLookupError:
                    pass
            
            self._remove_pid(name)
            print(f"✓ Server '{name}' stopped (forced)")
            return True
        
        except ProcessLookupError:
            print(f"Process {pid} not found")
            self._remove_pid(name)
            return True
        except Exception as e:
            print(f"Error stopping server: {e}")
            return False
    
    def restart_server(self, name: str, force: bool = False) -> bool:
        """Restart an MCP server."""
        print(f"Restarting MCP server '{name}'...")
        
        self.stop_server(name, force)
        time.sleep(1)
        return self.start_server(name)
    
    def status_server(self, name: str) -> Dict:
        """Get status of an MCP server."""
        if name not in self.servers:
            return {"name": name, "status": "not_configured"}
        
        is_running = self.is_server_running(name)
        pid = self._get_pid(name)
        
        status = {
            "name": name,
            "status": "running" if is_running else "stopped",
            "pid": pid,
            "configured": True,
        }
        
        if is_running and pid:
            proc_info = self._get_proc_info(pid)
            if proc_info:
                status.update(proc_info)
        
        return status
    
    def list_servers(self) -> List[Dict]:
        """List all configured MCP servers and their status."""
        servers_status = []
        for name in self.servers:
            servers_status.append(self.status_server(name))
        return servers_status
    
    def is_server_running(self, name: str) -> bool:
        """Check if a server is currently running."""
        pid = self._get_pid(name)
        
        if not pid:
            return False
        
        return self._is_pid_running(pid)
    
    def _get_pid(self, name: str) -> Optional[int]:
        """Get PID of a running server."""
        pid_file = self.pid_dir / f"{name}.pid"
        
        if not pid_file.exists():
            return None
        
        try:
            with open(pid_file, 'r') as f:
                pid = int(f.read().strip())
            return pid
        except (ValueError, IOError):
            return None
    
    def _save_pid(self, name: str, pid: int):
        """Save PID of a running server."""
        pid_file = self.pid_dir / f"{name}.pid"
        with open(pid_file, 'w') as f:
            f.write(str(pid))
    
    def _remove_pid(self, name: str):
        """Remove PID file for a server."""
        pid_file = self.pid_dir / f"{name}.pid"
        if pid_file.exists():
            pid_file.unlink()
    
    def _is_pid_running(self, pid: int) -> bool:
        """Check if a PID is running using /proc."""
        try:
            os.kill(pid, 0)
            return True
        except ProcessLookupError:
            return False
        except PermissionError:
            return True
    
    def _get_proc_info(self, pid: int) -> Optional[Dict]:
        """Get process information from /proc."""
        try:
            proc_dir = Path(f"/proc/{pid}")
            if not proc_dir.exists():
                return None
            
            # Get command
            cmdline_file = proc_dir / "cmdline"
            if cmdline_file.exists():
                with open(cmdline_file, 'rb') as f:
                    cmdline = f.read().decode('utf-8', errors='replace').replace('\x00', ' ').strip()
            else:
                cmdline = "unknown"
            
            # Get memory info
            mem_info = {}
            status_file = proc_dir / "status"
            if status_file.exists():
                with open(status_file, 'r') as f:
                    for line in f:
                        if line.startswith('VmRSS:'):
                            mem_str = line.split()[1]
                            try:
                                mem_info['memory_mb'] = float(mem_str) / 1024
                            except ValueError:
                                pass
                        elif line.startswith('Uptime:'):
                            # This is actually from /proc/uptime
                            pass
            
            # Get uptime
            uptime = self._get_process_uptime(pid)
            if uptime:
                mem_info['uptime'] = uptime
            
            # Get CPU percent (approximate)
            try:
                stat_file = proc_dir / "stat"
                if stat_file.exists():
                    with open(stat_file, 'r') as f:
                        stat_content = f.read()
                    
                    # Parse stat file (see proc(5))
                    # Format: pid (comm) state ppid pgrp session tty_nr ...
                    parts = stat_content.split()
                    if len(parts) >= 14:
                        utime = int(parts[13])  # User time
                        stime = int(parts[14])  # System time
                        # Get system uptime for percentage calculation
                        mem_info['cpu_approx'] = utime + stime
            except:
                pass
            
            result = {"command": cmdline}
            result.update(mem_info)
            return result
        
        except (ProcessLookupError, FileNotFoundError, PermissionError):
            return None
    
    def _get_process_uptime(self, pid: int) -> Optional[float]:
        """Get process uptime in seconds."""
        try:
            # Get process start time from /proc/pid/stat
            stat_file = Path(f"/proc/{pid}/stat")
            if not stat_file.exists():
                return None
            
            with open(stat_file, 'r') as f:
                stat_content = f.read()
            
            # Parse stat file to get starttime (field 22)
            parts = stat_content.split()
            if len(parts) < 22:
                return None
            
            starttime = int(parts[21])
            
            # Get system uptime
            with open('/proc/uptime', 'r') as f:
                uptime_seconds = float(f.read().split()[0])
            
            # Get clock ticks per second
            import os
            clk_tck = os.sysconf(os.sysconf_names['SC_CLK_TCK'])
            
            # Calculate process uptime
            process_uptime = uptime_seconds - (starttime / clk_tck)
            return max(0, process_uptime)
        
        except (ValueError, FileNotFoundError, KeyError, ProcessLookupError):
            return None
    
    def get_server_logs(self, name: str, lines: int = 50) -> str:
        """Get recent logs for a server."""
        log_file = self.log_dir / f"{name}.log"
        
        if not log_file.exists():
            return f"No logs found for server '{name}'"
        
        try:
            with open(log_file, 'r') as f:
                all_lines = f.readlines()
                last_lines = all_lines[-lines:] if len(all_lines) > lines else all_lines
                return ''.join(last_lines)
        except IOError as e:
            return f"Error reading logs: {e}"


def main():
    parser = argparse.ArgumentParser(
        description="Manage MCP server lifecycle"
    )
    parser.add_argument(
        "action",
        choices=["start", "stop", "restart", "status", "list", "logs"],
        help="Action to perform"
    )
    parser.add_argument(
        "--name", "-n",
        help="Name of the MCP server"
    )
    parser.add_argument(
        "--config", "-c",
        help="Path to configuration file"
    )
    parser.add_argument(
        "--force", "-f",
        action="store_true",
        help="Force stop (SIGKILL instead of SIGTERM)"
    )
    parser.add_argument(
        "--lines", "-l",
        type=int,
        default=50,
        help="Number of log lines to show (for logs action)"
    )
    parser.add_argument(
        "--timeout", "-t",
        type=int,
        default=30,
        help="Timeout for start action (seconds)"
    )
    
    args = parser.parse_args()
    
    manager = MCPServerManager(config_path=args.config)
    
    if args.action == "list":
        servers = manager.list_servers()
        print(f"\n{'Name':<30} {'Status':<10} {'PID':<10} {'Uptime':<15} {'Memory (MB)':<12}")
        print("-" * 85)
        for server in servers:
            name = server["name"][:28]
            status = server["status"]
            pid = str(server.get("pid", "-") or "-")
            uptime = server.get("uptime")
            memory = server.get("memory_mb")
            
            if uptime:
                uptime_str = f"{uptime:.0f}s"
            else:
                uptime_str = "-"
            
            if memory:
                memory_str = f"{memory:.1f}"
            else:
                memory_str = "-"
            
            print(f"{name:<30} {status:<10} {pid:<10} {uptime_str:<15} {memory_str:<12}")
        print()
    
    elif not args.name:
        print("Error: --name is required for this action")
        sys.exit(1)
    
    elif args.action == "start":
        success = manager.start_server(args.name, timeout=args.timeout)
        sys.exit(0 if success else 1)
    
    elif args.action == "stop":
        success = manager.stop_server(args.name, force=args.force)
        sys.exit(0 if success else 1)
    
    elif args.action == "restart":
        success = manager.restart_server(args.name, force=args.force)
        sys.exit(0 if success else 1)
    
    elif args.action == "status":
        status = manager.status_server(args.name)
        print(f"\nServer: {status['name']}")
        print(f"Status: {status['status']}")
        if status['pid']:
            print(f"PID: {status['pid']}")
        if 'uptime' in status:
            print(f"Uptime: {status['uptime']:.0f}s")
        if 'memory_mb' in status:
            print(f"Memory: {status['memory_mb']:.1f} MB")
        if 'cpu_percent' in status:
            print(f"CPU: {status['cpu_percent']:.1f}%")
        print()
    
    elif args.action == "logs":
        logs = manager.get_server_logs(args.name, lines=args.lines)
        print(logs)


if __name__ == "__main__":
    main()
