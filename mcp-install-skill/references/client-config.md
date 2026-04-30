# MCP Client Configuration Guide

## Overview

This guide provides detailed information on configuring various MCP clients to work with MCP servers.

## Supported Clients

### 1. Claude Desktop

**Configuration File Location:**
- **macOS**: `~/Library/Application Support/Claude/claude_desktop_config.json`
- **Windows**: `%APPDATA%\Claude\claude_desktop_config.json`
- **Linux**: `~/.config/Claude/claude_desktop_config.json`

**Configuration Format:**
```json
{
  "mcpServers": {
    "server-name": {
      "command": "node",
      "args": ["/path/to/server/index.js"],
      "env": {
        "API_KEY": "your-api-key-here"
      }
    }
  }
}
```

**Fields:**
- `command`: The executable to run (e.g., "node", "python", "npx")
- `args`: Array of command-line arguments
- `env`: Environment variables (optional)

**Example - Filesystem Server:**
```json
{
  "mcpServers": {
    "filesystem": {
      "command": "node",
      "args": ["/Users/username/.npm-global/lib/node_modules/@modelcontextprotocol/server-filesystem/dist/index.js"],
      "env": {
        "ALLOWED_PATHS": "/Users/username/Documents,/Users/username/Projects"
      }
    }
  }
}
```

**Example - PostgreSQL Server:**
```json
{
  "mcpServers": {
    "postgres": {
      "command": "npx",
      "args": ["@databutton/mcp-server-postgres"],
      "env": {
        "POSTGRES_URL": "postgresql://user:pass@localhost:5432/dbname"
      }
    }
  }
}
```

### 2. OpenClaw

**Configuration File Location:**
`~/.openclaw/config/mcp.json`

**Configuration Format:**
```json
{
  "mcp": {
    "servers": {
      "server-name": {
        "command": "python",
        "args": ["-m", "my_server"],
        "transport": "stdio",
        "env": {
          "API_KEY": "your-api-key-here"
        }
      }
    },
    "settings": {
      "timeout": 30000,
      "retries": 3,
      "autoRestart": true
    }
  }
}
```

**Fields:**
- `command`: The executable to run
- `args`: Array of command-line arguments (optional)
- `transport`: Transport type ("stdio", "sse", "websocket", "http")
- `env`: Environment variables (optional)

**Settings:**
- `timeout`: Connection timeout in milliseconds (default: 30000)
- `retries`: Number of retry attempts (default: 3)
- `autoRestart`: Automatically restart failed servers (default: true)

**Example - Local Python Server:**
```json
{
  "mcp": {
    "servers": {
      "my-python-server": {
        "command": "python",
        "args": ["/path/to/server.py"],
        "transport": "stdio",
        "env": {
          "PYTHONPATH": "/path/to/server",
          "API_KEY": "secret-key"
        }
      }
    },
    "settings": {
      "timeout": 30000,
      "retries": 3,
      "autoRestart": true
    }
  }
}
```

**Example - Remote Node.js Server:**
```json
{
  "mcp": {
    "servers": {
      "remote-node-server": {
        "command": "node",
        "args": ["remote-server.js"],
        "transport": "sse",
        "env": {
          "SERVER_URL": "http://localhost:3000"
        }
      }
    }
  }
}
```

### 3. Cursor

**Configuration File Location:**
`~/.cursor/mcp.json`

**Configuration Format:**
Similar to Claude Desktop:
```json
{
  "mcpServers": {
    "server-name": {
      "command": "node",
      "args": ["/path/to/server"],
      "env": {}
    }
  }
}
```

### 4. Windsurf

**Configuration File Location:**
`~/.windsurf/mcp.json`

**Configuration Format:**
```json
{
  "mcp": {
    "servers": {
      "server-name": {
        "command": "command",
        "args": [],
        "transport": "stdio"
      }
    }
  }
}
```

## Common Configuration Patterns

### Pattern 1: Local Development Server

```json
{
  "mcpServers": {
    "dev-server": {
      "command": "npm",
      "args": ["run", "dev"],
      "env": {
        "NODE_ENV": "development",
        "PORT": "3000"
      }
    }
  }
}
```

### Pattern 2: Production Server with Secrets

```json
{
  "mcpServers": {
    "prod-server": {
      "command": "node",
      "args": ["dist/index.js"],
      "env": {
        "NODE_ENV": "production",
        "DATABASE_URL": "${DATABASE_URL}",
        "API_KEY": "${API_KEY}"
      }
    }
  }
}
```

**Note**: Use environment variable substitution for sensitive data.

### Pattern 3: Multiple Servers

```json
{
  "mcpServers": {
    "filesystem": {
      "command": "node",
      "args": ["/path/to/fs-server.js"],
      "env": {
        "ALLOWED_PATHS": "/home/user/docs,/home/user/projects"
      }
    },
    "postgres": {
      "command": "npx",
      "args": ["@modelcontextprotocol/server-postgres"],
      "env": {
        "POSTGRES_URL": "postgresql://localhost/mydb"
      }
    }
  }
}
```

### Pattern 4: Docker Container

```json
{
  "mcpServers": {
    "docker-server": {
      "command": "docker",
      "args": [
        "run",
        "--rm",
        "-i",
        "my-mcp-server:latest"
      ],
      "env": {
        "DOCKER_HOST": "unix:///var/run/docker.sock"
      }
    }
  }
}
```

## Transport Configuration

### stdio (Default)

For local processes:
```json
{
  "transport": "stdio",
  "command": "node",
  "args": ["server.js"]
}
```

### SSE (Server-Sent Events)

For HTTP-based servers:
```json
{
  "transport": "sse",
  "command": "node",
  "args": ["server.js"],
  "env": {
    "PORT": "3000"
  }
}
```

### WebSocket

For real-time bidirectional communication:
```json
{
  "transport": "websocket",
  "command": "node",
  "args": ["server.js"],
  "env": {
    "WS_PORT": "8080"
  }
}
```

## Environment Variables

### Common Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `NODE_ENV` | Environment mode | `production`, `development` |
| `API_KEY` | API authentication key | `sk-1234567890abcdef` |
| `DATABASE_URL` | Database connection string | `postgresql://...` |
| `PORT` | Server port | `3000` |
| `LOG_LEVEL` | Logging verbosity | `debug`, `info`, `warn`, `error` |

### Secret Management

**Using .env files** (OpenClaw example):
```bash
# ~/.openclaw/.env
API_KEY=your-secret-key
DATABASE_URL=postgresql://user:pass@localhost/db
```

**Configuration references environment variables:**
```json
{
  "mcp": {
    "servers": {
      "my-server": {
        "env": {
          "API_KEY": "${API_KEY}"
        }
      }
    }
  }
}
```

## Troubleshooting

### Server Not Starting

1. **Check command path**
   ```bash
   which node  # or which python
   ```

2. **Verify executable permissions**
   ```bash
   chmod +x /path/to/server.js
   ```

3. **Test manually**
   ```bash
   node /path/to/server.js
   ```

### Connection Refused

1. **Check if server is running**
   ```bash
   ps aux | grep server-name
   ```

2. **Verify port is available**
   ```bash
   netstat -tlnp | grep :3000
   ```

3. **Check firewall rules**
   ```bash
   sudo ufw status
   ```

### Authentication Failed

1. **Verify API key**
   ```bash
   echo $API_KEY
   ```

2. **Check key validity**
   ```bash
   curl -H "Authorization: Bearer $API_KEY" https://api.example.com/verify
   ```

3. **Update configuration**
   ```json
   {
     "env": {
       "API_KEY": "new-key-here"
     }
   }
   ```

## Best Practices

### 1. Use Absolute Paths

```json
{
  "command": "/usr/local/bin/node",
  "args": ["/full/path/to/server.js"]
}
```

### 2. Manage Secrets Securely

- Use environment variables for sensitive data
- Never commit secrets to version control
- Rotate keys regularly

### 3. Enable Logging

```json
{
  "env": {
    "LOG_LEVEL": "debug",
    "LOG_FILE": "/var/log/mcp-server.log"
  }
}
```

### 4. Set Timeouts

```json
{
  "settings": {
    "timeout": 30000,
    "retries": 3
  }
}
```

### 5. Monitor Health

```json
{
  "env": {
    "HEALTH_CHECK_PORT": "9090",
    "METRICS_ENABLED": "true"
  }
}
```

## Migration Guide

### From Claude Desktop to OpenClaw

**Claude Desktop Config:**
```json
{
  "mcpServers": {
    "server": {
      "command": "node",
      "args": ["server.js"]
    }
  }
}
```

**OpenClaw Config:**
```json
{
  "mcp": {
    "servers": {
      "server": {
        "command": "node",
        "args": ["server.js"],
        "transport": "stdio"
      }
    }
  }
}
```

### Adding Transport Specification

Old format:
```json
{
  "command": "node",
  "args": ["server.js"]
}
```

New format with transport:
```json
{
  "command": "node",
  "args": ["server.js"],
  "transport": "stdio"
}
```
