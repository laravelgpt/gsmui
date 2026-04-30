---
name: mcp-install
---

# MCP Install Skill

## Overview

This skill provides comprehensive capabilities for installing, configuring, and managing Model Context Protocol (MCP) servers. It handles MCP server discovery, installation, configuration, authentication, and troubleshooting across different environments and use cases.

## When to Use This Skill

Use this skill when Codex needs to:

1. **Install MCP servers** - Set up new MCP servers from various sources (npm, GitHub, local files, or custom builds)
2. **Configure MCP clients** - Set up client configurations for Claude Desktop, OpenClaw, or other MCP-compatible applications
3. **Manage MCP servers** - List, start, stop, restart, and monitor running MCP servers
4. **Troubleshoot MCP issues** - Diagnose and fix connection, authentication, or configuration problems
5. **Deploy MCP servers** - Set up production-ready MCP server deployments with proper security and monitoring
6. **Create MCP servers** - Scaffold and build new MCP servers from templates
7. **Test MCP connections** - Verify server functionality, tool availability, and resource access

## Core Functionality

### Installation Methods

- **npm/yarn installation** - Install MCP servers from npm registry with dependency management
- **GitHub installation** - Clone and build MCP servers from GitHub repositories
- **Local installation** - Install from local directories or tarballs
- **Docker deployment** - Containerized MCP server deployment
- **Custom builds** - Compile and build MCP servers from source

### Configuration Management

- **Client configuration** - Generate and update MCP client config files (Claude Desktop, OpenClaw, etc.)
- **Server configuration** - Create and validate MCP server configuration files
- **Environment setup** - Configure environment variables, paths, and dependencies
- **Security configuration** - Set up authentication, CORS, and access controls

### Server Lifecycle Management

- **Process management** - Start, stop, restart, and monitor MCP server processes
- **Health checks** - Verify server availability and functionality
- **Logging** - Configure and access server logs for debugging
- **Resource monitoring** - Track server resource usage and performance

### Testing & Validation

- **Connection testing** - Verify client-server connections
- **Tool availability** - Check available tools and resources
- **Schema validation** - Validate MCP message schemas and protocols
- **Performance testing** - Benchmark server response times and throughput

## Implementation Details

### Scripts Available

**`scripts/install_mcp_server.py`**
- Main installation script with support for multiple installation methods
- Handles dependency resolution and environment setup
- Configures client applications automatically

**`scripts/manage_mcp_server.py`**
- Server lifecycle management (start/stop/restart/status)
- Process monitoring and health checks
- Log management and rotation

**`scripts/test_mcp_connection.py`**
- Connection testing and validation
- Tool and resource enumeration
- Performance benchmarking

**`scripts/create_mcp_server.py`**
- Scaffolds new MCP server projects
- Generates boilerplate code and configuration
- Sets up development environment

### Configuration Files

**`references/mcp-client-config-schema.json`**
- JSON schema for MCP client configuration files
- Validates configuration structure and options

**`references/mcp-server-manifest-schema.json`**
- JSON schema for MCP server manifest files
- Defines required server capabilities and metadata

**`references/mcp-protocol-spec.md`**
- MCP protocol specification reference
- Message formats, lifecycle, and best practices

## Usage Examples

### Install an MCP Server from npm

```bash
# Install a specific MCP server
python scripts/install_mcp_server.py \
  --method npm \
  --package @modelcontextprotocol/server-filesystem \
  --name filesystem-server \
  --client claude-desktop \
  --config-path ~/Library/Application\ Support/Claude/claude_desktop_config.json
```

### Install from GitHub

```bash
# Clone and install from GitHub repository
python scripts/install_mcp_server.py \
  --method github \
  --repo modelcontextprotocol/servers \
  --name github-servers \
  --client openclaw \
  --config-path ~/.openclaw/config/mcp.json
```

### Manage Server Lifecycle

```bash
# Start an MCP server
python scripts/manage_mcp_server.py start --name my-server

# Check server status
python scripts/manage_mcp_server.py status --name my-server

# Stop a server
python scripts/manage_mcp_server.py stop --name my-server

# Restart a server
python scripts/manage_mcp_server.py restart --name my-server
```

### Test MCP Connection

```bash
# Test connection to a server
python scripts/test_mcp_connection.py \
  --server my-server \
  --transport stdio \
  --check-tools \
  --check-resources

# Run performance benchmarks
python scripts/test_mcp_connection.py \
  --server my-server \
  --benchmark \
  --iterations 100
```

### Create a New MCP Server

```bash
# Scaffold a new MCP server project
python scripts/create_mcp_server.py \
  --name my-custom-server \
  --language python \
  --template basic \
  --output ./my-mcp-server
```

## Client Support

### Supported Clients

- **Claude Desktop** - Official Anthropic desktop application
- **OpenClaw** - OpenClaw agent framework
- **Cursor** - AI-powered code editor
- **Windsurf** - AI-powered development environment
- **Custom Applications** - Any MCP-compatible client

### Configuration Examples

**Claude Desktop Configuration:**
```json
{
  "mcpServers": {
    "filesystem": {
      "command": "node",
      "args": ["/path/to/server/index.js"],
      "env": {
        "PATH": "/usr/local/bin:/usr/bin:/bin"
      }
    }
  }
}
```

**OpenClaw Configuration:**
```json
{
  "mcp": {
    "servers": {
      "my-server": {
        "command": "python",
        "args": ["-m", "my_server"],
        "transport": "stdio",
        "env": {
          "API_KEY": "${API_KEY}"
        }
      }
    },
    "settings": {
      "timeout": 30000,
      "retries": 3
    }
  }
}
```

## Transport Options

### stdio (Standard I/O)
- Default transport for local servers
- Bidirectional communication via stdin/stdout
- Best for local development and single-machine deployments

### SSE (Server-Sent Events)
- HTTP-based transport
- Server pushes events to clients
- Good for web-based deployments

### WebSocket
- Full-duplex communication
- Real-time bidirectional messaging
- Suitable for high-frequency updates

### HTTP/REST
- Request-response model
- Stateless communication
- Easy to integrate with existing APIs

## Security Considerations

### Authentication
- API key management and rotation
- OAuth token handling
- Certificate-based authentication

### Access Control
- CORS configuration for web deployments
- IP whitelisting and firewall rules
- Role-based access control (RBAC)

### Data Protection
- Encryption in transit (TLS/SSL)
- Secret management with environment variables
- Secure storage of credentials

## Troubleshooting

### Common Issues

**Connection Refused**
- Verify server is running
- Check port availability
- Validate firewall rules

**Authentication Failed**
- Verify API keys or tokens
- Check environment variable configuration
- Validate credential expiration

**Tool Not Found**
- Confirm server registration
- Check tool name spelling
- Verify server capabilities

**Timeout Errors**
- Increase timeout settings
- Check server performance
- Review network latency

### Debug Commands

```bash
# Enable verbose logging
export MCP_LOG_LEVEL=debug

# Check server logs
tail -f /var/log/mcp-server.log

# Test with verbose output
python scripts/test_mcp_connection.py --verbose --server my-server
```

## Best Practices

1. **Use Version Control** - Track MCP server versions and configurations
2. **Implement Health Checks** - Monitor server availability proactively
3. **Secure Credentials** - Use environment variables or secret managers
4. **Document Configuration** - Maintain clear documentation of setups
5. **Test Thoroughly** - Validate connections and tool availability
6. **Monitor Performance** - Track response times and resource usage
7. **Plan for Scale** - Design for horizontal scaling when needed

## References

See the `references/` directory for detailed documentation:
- `mcp-protocol.md` - Complete MCP protocol specification
- `client-config.md` - Client configuration guides
- `server-development.md` - MCP server development guide
- `troubleshooting.md` - Common issues and solutions

## PHP/Laravel Integration

### Laravel MCP Server Package

Create fully-featured MCP servers as Laravel packages:

```bash
python scripts/install_laravel_mcp.py   --create   --name my-laravel-server   --output ./laravel-mcp-server
```

### Install Laravel MCP Package

```bash
# Install into existing Laravel project
python scripts/install_laravel_mcp.py   --package vendor/laravel-mcp   --name laravel-mcp-server   --path /path/to/laravel/project
```

### Available Laravel Tools

- `list_routes` - List all Laravel routes with middleware
- `run_migration` - Execute database migrations
- `check_env` - Verify environment configuration
- `cache_clear` - Clear application cache
- `queue_work` - Process queued jobs
- `schedule_run` - Run scheduled tasks

## Token Burn/Lost Issue Monitor

### Monitor Token Issues

Detect and fix common token burn/authentication problems:

```bash
python scripts/token_burn_monitor.py   --project /path/to/laravel/project   --scan
```

### Auto-Fix Token Issues

```bash
python scripts/token_burn_monitor.py   --project /path/to/laravel/project   --fix
```

### Common Issues Detected

1. **Missing CSRF Protection** - Verify CSRF middleware
2. **Session Driver Issues** - Check session storage
3. **Weak APP_KEY** - Generate strong encryption key
4. **API Route Exposure** - Ensure proper authentication
5. **Sanctum Configuration** - Verify token authentication
6. **Cache Driver Warning** - Array driver loses tokens
7. **Database Migration Gaps** - Missing session/token tables
8. **Environment Variables** - Missing critical configs

## Skills Manager

### Install Skills from Registry

```bash
python scripts/skills_manager.py install   --skill mcp-install   --source local
```

### Search Available Skills

```bash
python scripts/skills_manager.py search --query laravel
```

### List Installed Skills

```bash
python scripts/skills_manager.py list
```

### Update Skills

```bash
python scripts/skills_manager.py update --skill mcp-install
```

### Remove Skills

```bash
python scripts/skills_manager.py remove --skill old-skill --force
```

## Code Review MCP

### Automated Code Review

Integrate code quality checks into your MCP workflow:

```bash
# Run code review on changes
python scripts/code_review_mcp.py   --path /path/to/code   --checks security,quality,performance
```

### Review Checks

- Security vulnerabilities
- Code quality metrics
- Performance bottlenecks
- Best practice violations
- Documentation coverage
- Test coverage

## Advanced Features

### Custom Transports
Implement custom transport layers for specialized use cases

### Plugin Architecture
Extend MCP servers with plugins for additional functionality

### Multi-Server Orchestration
Manage multiple MCP servers as a coordinated system

### Load Balancing
Distribute requests across multiple server instances

### Monitoring Integration
Integrate with monitoring systems (Prometheus, Grafana, etc.)
