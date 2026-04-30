#!/usr/bin/env python3
"""
MCP Install Skill Initialization Script

Initializes the MCP Install skill by setting up necessary
configuration and verifying the environment.
"""

import json
import os
import sys
from pathlib import Path


def init_skill():
    """Initialize the MCP Install skill."""
    print("=" * 60)
    print("MCP Install Skill - Initialization")
    print("=" * 60)
    
    # Check Python version
    print("\n1. Checking Python version...")
    if sys.version_info < (3, 11):
        print(f"   ✗ Python 3.11+ required (found {sys.version})")
        return False
    print(f"   ✓ Python {sys.version.split()[0]}")
    
    # Check for required packages
    print("\n2. Checking required packages...")
    required_packages = ['mcp']
    missing_packages = []
    
    for package in required_packages:
        try:
            __import__(package)
            print(f"   ✓ {package}")
        except ImportError:
            print(f"   ✗ {package} (not installed)")
            missing_packages.append(package)
    
    if missing_packages:
        print(f"\n   Install missing packages with:")
        print(f"   pip install {' '.join(missing_packages)}")
    
    # Create default config directory
    print("\n3. Setting up configuration directory...")
    config_dir = Path.home() / ".openclaw" / "config"
    config_dir.mkdir(parents=True, exist_ok=True)
    print(f"   ✓ Config directory: {config_dir}")
    
    # Create default MCP config if it doesn't exist
    mcp_config = config_dir / "mcp.json"
    if not mcp_config.exists():
        print("\n4. Creating default MCP configuration...")
        default_config = {
            "mcp": {
                "servers": {},
                "settings": {
                    "timeout": 30000,
                    "retries": 3,
                    "autoRestart": True
                }
            }
        }
        
        with open(mcp_config, 'w') as f:
            json.dump(default_config, f, indent=2)
        print(f"   ✓ Created: {mcp_config}")
    else:
        print(f"\n4. MCP configuration already exists: {mcp_config}")
    
    # Create directories for server management
    print("\n5. Setting up server management directories...")
    pid_dir = Path.home() / ".mcp_servers" / "pids"
    log_dir = Path.home() / ".mcp_servers" / "logs"
    
    pid_dir.mkdir(parents=True, exist_ok=True)
    log_dir.mkdir(parents=True, exist_ok=True)
    
    print(f"   ✓ PID directory: {pid_dir}")
    print(f"   ✓ Log directory: {log_dir}")
    
    # Print summary
    print("\n" + "=" * 60)
    print("Initialization Complete!")
    print("=" * 60)
    
    print("\nNext Steps:")
    print("1. Install an MCP server:")
    print("   python scripts/install_mcp_server.py \\")
    print("     --method npm \\")
    print("     --package @modelcontextprotocol/server-filesystem \\")
    print("     --name filesystem-server")
    
    print("\n2. Manage servers:")
    print("   python scripts/manage_mcp_server.py list")
    print("   python scripts/manage_mcp_server.py start --name my-server")
    
    print("\n3. Test connections:")
    print("   python scripts/test_mcp_connection.py --server my-server")
    
    print("\n4. Create a new MCP server:")
    print("   python scripts/create_mcp_server.py \\")
    print("     --name my-server \\")
    print("     --language python")
    
    print("\nSee SKILL.md for complete documentation.")
    print("=" * 60)
    
    return True


if __name__ == "__main__":
    success = init_skill()
    sys.exit(0 if success else 1)
