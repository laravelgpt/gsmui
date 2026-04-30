# MCP Install Skill

A comprehensive skill for installing, configuring, and managing Model Context Protocol (MCP) servers.

## Overview

This skill provides everything you need to work with MCP servers:

- **Installation** - Install MCP servers from npm, GitHub, or local directories
- **Configuration** - Configure MCP clients (Claude Desktop, OpenClaw, etc.)
- **Management** - Start, stop, restart, and monitor MCP servers
- **Testing** - Test connections and validate server functionality
- **Creation** - Scaffold new MCP server projects

## Quick Start

1. **Initialize the skill:**
   ```bash
   cd mcp-install-skill/scripts
   python3 init_skill.py
   ```

2. **Install an MCP server:**
   ```bash
   python3 install_mcp_server.py \
     --method npm \
     --package @modelcontextprotocol/server-filesystem \
     --name filesystem-server \
     --client openclaw
   ```

3. **Manage servers:**
   ```bash
   python3 manage_mcp_server.py list
   python3 manage_mcp_server.py start --name filesystem-server
   ```

4. **Test connections:**
   ```bash
   python3 test_mcp_connection.py --server filesystem-server
   ```

## Features

### Installation Methods

- ✅ **npm/yarn** - Install from npm registry
- ✅ **GitHub** - Clone and build from GitHub repos
- ✅ **Local** - Install from local directories
- ✅ **Docker** - Deploy in containers

### Supported Clients

- 🖥️ **Claude Desktop**
- 🤖 **OpenClaw**
- ✍️ **Cursor**
- 🌊 **Windsurf**

### Server Management

- 🚀 Start/stop/restart servers
- 📊 Monitor resource usage
- 📝 Access server logs
- ⚙️ Auto-restart on failure

### Testing & Validation

- 🔍 Connection testing
- 🧪 Tool enumeration
- 📈 Performance benchmarking
- ✅ Configuration validation

## Scripts

| Script | Description |
|--------|-------------|
| `init_skill.py` | Initialize the skill environment |
| `install_mcp_server.py` | Install MCP servers |
| `manage_mcp_server.py` | Manage server lifecycle |
| `test_mcp_connection.py` | Test and validate connections |
| `create_mcp_server.py` | Create new MCP server projects |

## Documentation

- [SKILL.md](SKILL.md) - Detailed skill documentation
- [references/mcp-protocol.md](references/mcp-protocol.md) - MCP protocol reference
- [references/client-config.md](references/client-config.md) - Client configuration guide
- [references/troubleshooting.md](references/troubleshooting.md) - Troubleshooting guide

## Examples

### Install Filesystem Server

```bash
python3 install_mcp_server.py \
  --method npm \
  --package @modelcontextprotocol/server-filesystem \
  --name filesystem-server \
  --env '{"ALLOWED_PATHS": "/home/user/docs,/home/user/projects"}'
```

### Install PostgreSQL Server

```bash
python3 install_mcp_server.py \
  --method npm \
  --package @modelcontextprotocol/server-postgres \
  --name postgres-server \
  --env '{"POSTGRES_URL": "postgresql://user:pass@localhost/mydb"}'
```

### Create New MCP Server

```bash
# Python server
python3 create_mcp_server.py \
  --name my-server \
  --language python \
  --output ./my-mcp-server

# Node.js server
python3 create_mcp_server.py \
  --name my-server \
  --language nodejs \
  --output ./my-mcp-server
```

## Requirements

- Python 3.11+
- MCP-compatible client (Claude Desktop, OpenClaw, etc.)
- npm/Node.js (for Node.js MCP servers)

## License

MIT

## Support

For issues or questions, please refer to the troubleshooting guide or check the MCP documentation.
