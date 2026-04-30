# MCP Protocol Reference

## Overview

The Model Context Protocol (MCP) is a standardized protocol for enabling AI models to securely interact with external data sources and tools.

## Core Concepts

### Server
An MCP server exposes tools, resources, and prompts that can be consumed by MCP clients. Servers run as separate processes and communicate with clients via a defined transport layer.

### Client
An MCP client connects to MCP servers and can invoke tools, read resources, and use prompts exposed by the server.

### Tools
Tools are functions that can be called by the client to perform specific operations. Tools have:
- A name
- A description
- An input schema (JSON Schema)
- Optional output schema

### Resources
Resources are data sources that can be read by the client. Resources have:
- A URI
- A name
- A description
- Optional MIME type
- A schema (optional)

### Prompts
Prompts are template-based instructions that can be used to guide model behavior.

## Protocol Lifecycle

### 1. Initialization

The client initiates a connection by sending an `initialize` request:

```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "method": "initialize",
  "params": {
    "protocolVersion": "2024-11-05",
    "capabilities": {
      "tools": {},
      "resources": {},
      "prompts": {}
    },
    "clientInfo": {
      "name": "MyClient",
      "version": "1.0.0"
    }
  }
}
```

The server responds with its capabilities:

```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "result": {
    "protocolVersion": "2024-11-05",
    "capabilities": {
      "tools": {},
      "resources": {},
      "prompts": {}
    },
    "serverInfo": {
      "name": "MyServer",
      "version": "1.0.0"
    }
  }
}
```

### 2. Tool Discovery

Clients can list available tools:

```json
{
  "jsonrpc": "2.0",
  "id": 2,
  "method": "tools/list"
}
```

Server response:

```json
{
  "jsonrpc": "2.0",
  "id": 2,
  "result": {
    "tools": [
      {
        "name": "fetch_data",
        "description": "Fetch data from external source",
        "inputSchema": {
          "type": "object",
          "properties": {
            "url": {
              "type": "string",
              "description": "URL to fetch from"
            }
          },
          "required": ["url"]
        }
      }
    ]
  }
}
```

### 3. Tool Execution

Clients can call tools:

```json
{
  "jsonrpc": "2.0",
  "id": 3,
  "method": "tools/call",
  "params": {
    "name": "fetch_data",
    "arguments": {
      "url": "https://api.example.com/data"
    }
  }
}
```

Server response:

```json
{
  "jsonrpc": "2.0",
  "id": 3,
  "result": {
    "content": [
      {
        "type": "text",
        "text": "Data fetched successfully"
      }
    ]
  }
}
```

### 4. Resource Operations

**List Resources:**

```json
{
  "jsonrpc": "2.0",
  "id": 4,
  "method": "resources/list"
}
```

**Read Resource:**

```json
{
  "jsonrpc": "2.0",
  "id": 5,
  "method": "resources/read",
  "params": {
    "uri": "file:///data/config.json"
  }
}
```

## Transport Layers

### stdio (Standard I/O)

The default transport for local development. Communication happens via stdin/stdout.

**Pros:**
- Simple to implement
- No network configuration required
- Works well for local development

**Cons:**
- Limited to single-machine communication
- No built-in authentication

### SSE (Server-Sent Events)

HTTP-based transport where the server pushes events to the client.

**Endpoint:** `GET /sse`

**Message Format:**
```
event: message
data: {"jsonrpc": "2.0", "id": 1, ...}

```

**Pros:**
- Works over HTTP/HTTPS
- Simple implementation
- Good for read-heavy workloads

**Cons:**
- One-way communication (server → client)
- Requires separate POST endpoint for client → server messages

### WebSocket

Full-duplex communication channel over a single TCP connection.

**Endpoint:** `ws://host:port/ws`

**Pros:**
- Low latency
- Real-time bidirectional communication
- Efficient for high-frequency updates

**Cons:**
- Requires WebSocket support
- More complex connection management

## Error Handling

MCP uses JSON-RPC 2.0 error codes:

- `-32700`: Parse error
- `-32600`: Invalid request
- `-32601`: Method not found
- `-32602`: Invalid params
- `-32603`: Internal error
- `-32000` to `-32099`: Server error (reserved for implementation-defined server errors)

**Example Error Response:**

```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "error": {
    "code": -32603,
    "message": "Internal error",
    "data": {
      "details": "Failed to connect to database"
    }
  }
}
```

## Security Considerations

### Authentication

- Use API keys or tokens for server authentication
- Implement OAuth 2.0 for user-level authentication
- Rotate credentials regularly

### Authorization

- Implement role-based access control (RBAC)
- Validate permissions for each tool/resource access
- Use scope-based limitations

### Data Protection

- Use TLS/SSL for all network communication
- Encrypt sensitive data at rest
- Implement input validation and sanitization

### Rate Limiting

- Implement request throttling
- Use token bucket or leaky bucket algorithms
- Return appropriate error codes for rate limit exceeded

## Best Practices

### 1. Versioning
Include protocol version in all messages for compatibility.

### 2. Timeouts
Set appropriate timeouts for all operations:
- Tool execution: 30 seconds default
- Resource reading: 60 seconds default
- Connection: 10 seconds default

### 3. Logging
Log all requests and responses for debugging:
- Include request IDs
- Log timestamps
- Capture errors with full context

### 4. Testing
Comprehensive test coverage:
- Unit tests for individual tools
- Integration tests for full workflows
- Load testing for performance validation

### 5. Documentation
Document all tools and resources:
- Clear descriptions
- Input/output schemas
- Example usage
- Error conditions

## Implementation Guidelines

### Server Implementation

1. **Initialize properly**
   - Validate client capabilities
   - Negotiate protocol version
   - Send server capabilities

2. **Handle requests asynchronously**
   - Use async/await patterns
   - Don't block the event loop
   - Implement proper error handling

3. **Validate inputs**
   - Check JSON Schema compliance
   - Sanitize user inputs
   - Validate resource URIs

### Client Implementation

1. **Manage connections**
   - Handle reconnection logic
   - Implement health checks
   - Monitor server status

2. **Handle responses**
   - Process results asynchronously
   - Handle errors gracefully
   - Implement retry logic

3. **Respect server limits**
   - Adhere to rate limits
   - Use appropriate timeouts
   - Batch requests when possible

## Examples

### Python Server Example

```python
from mcp import Server, StdioServerTransport

server = Server("example-server")

@server.list_tools()
async def list_tools():
    return [
        {
            "name": "add",
            "description": "Add two numbers",
            "inputSchema": {
                "type": "object",
                "properties": {
                    "a": {"type": "number"},
                    "b": {"type": "number"}
                },
                "required": ["a", "b"]
            }
        }
    ]

@server.call_tool()
async def call_tool(name, arguments):
    if name == "add":
        return {
            "content": [{"type": "text", "text": str(arguments["a"] + arguments["b"])}]
        }

transport = StdioServerTransport()
server.run(transport)
```

### Node.js Client Example

```javascript
import { Client } from '@modelcontextprotocol/sdk/client/index.js';
import { StdioClientTransport } from '@modelcontextprotocol/sdk/client/stdio.js';

const transport = new StdioClientTransport({
  command: 'node',
  args: ['server.js']
});

const client = new Client({
  name: 'example-client',
  version: '1.0.0'
});

await client.connect(transport);

const tools = await client.listTools();
console.log('Available tools:', tools);

const result = await client.callTool('add', { a: 5, b: 3 });
console.log('Result:', result);
```
