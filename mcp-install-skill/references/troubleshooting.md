# MCP Troubleshooting Guide

## Common Issues and Solutions

### Installation Issues

#### npm Installation Fails

**Problem:**
```
npm ERR! code E404
npm ERR! 404 Not Found - GET https://registry.npmjs.org/@modelcontextprotocol
```

**Solution:**
1. Check package name spelling
2. Verify npm registry access
3. Try with full package name:
   ```bash
   npm install @modelcontextprotocol/server-filesystem
   ```

**Problem:**
```
npm ERR! code EACCES
npm ERR! syscall open
npm ERR! path /usr/local/lib/node_modules
```

**Solution:**
1. Use sudo (not recommended):
   ```bash
   sudo npm install -g package-name
   ```
2. Fix npm permissions:
   ```bash
   mkdir ~/.npm-global
   npm config set prefix '~/.npm-global'
   echo 'export PATH=~/.npm-global/bin:$PATH' >> ~/.bashrc
   source ~/.bashrc
   ```
3. Use nvm for Node.js version management

#### Git Clone Fails

**Problem:**
```
fatal: could not read from remote repository
```

**Solution:**
1. Check repository URL
2. Verify SSH keys or HTTPS access
3. Test connection:
   ```bash
   git ls-remote https://github.com/modelcontextprotocol/servers.git
   ```

### Configuration Issues

#### Client Can't Find Server

**Problem:** Server starts but client can't connect.

**Diagnosis:**
1. Check server is running:
   ```bash
   ps aux | grep mcp
   ```

2. Verify configuration file location:
   ```bash
   # Claude Desktop
   ls ~/Library/Application\ Support/Claude/claude_desktop_config.json
   
   # OpenClaw
   ls ~/.openclaw/config/mcp.json
   ```

3. Validate JSON syntax:
   ```bash
   python -m json.tool ~/.openclaw/config/mcp.json
   ```

**Solution:**
1. Correct configuration path
2. Fix JSON syntax errors
3. Restart client application

#### Command Not Found

**Problem:**
```
Error: Command not found: node
```

**Solution:**
1. Check command exists:
   ```bash
   which node
   which python
   ```

2. Use full path in configuration:
   ```json
   {
     "command": "/usr/local/bin/node"
   }
   ```

3. Update PATH in environment:
   ```json
   {
     "env": {
       "PATH": "/usr/local/bin:/usr/bin:/bin"
     }
   }
   ```

### Connection Issues

#### Connection Refused

**Problem:**
```
Error: connect ECONNREFUSED 127.0.0.1:3000
```

**Diagnosis:**
1. Check server is running:
   ```bash
   netstat -tlnp | grep :3000
   ```

2. Verify port is correct
3. Check firewall rules:
   ```bash
   sudo ufw status
   ```

**Solution:**
1. Start server:
   ```bash
   node server.js
   ```

2. Check server logs for errors
3. Verify port configuration

#### Timeout Errors

**Problem:**
```
Error: Timeout after 30000ms
```

**Diagnosis:**
1. Server is taking too long to respond
2. Server is blocked on I/O operation
3. Network latency

**Solution:**
1. Increase timeout:
   ```json
   {
     "settings": {
       "timeout": 60000
     }
   }
   ```

2. Optimize server performance
3. Check for blocking operations
4. Monitor server resource usage

#### Authentication Failed

**Problem:**
```
Error: Authentication failed: Invalid API key
```

**Diagnosis:**
1. Check API key is correct
2. Verify key hasn't expired
3. Check key has correct permissions

**Solution:**
1. Update API key:
   ```bash
   export API_KEY=new-key-here
   ```

2. Update configuration:
   ```json
   {
     "env": {
       "API_KEY": "new-key-here"
     }
   }
   ```

3. Rotate credentials

### Runtime Issues

#### Server Crashes

**Problem:** Server exits unexpectedly.

**Diagnosis:**
1. Check server logs:
   ```bash
   tail -f ~/.mcp_servers/logs/server-name.log
   ```

2. Look for error messages
3. Check system resources:
   ```bash
   top -p $(pgrep -f server-name)
   ```

**Solution:**
1. Enable auto-restart:
   ```json
   {
     "settings": {
       "autoRestart": true
     }
   }
   ```

2. Fix underlying bug
3. Increase resources if needed
4. Add error handling

#### Memory Leaks

**Problem:** Server memory usage grows over time.

**Diagnosis:**
1. Monitor memory usage:
   ```bash
   watch -n 1 'ps aux | grep server-name'
   ```

2. Take heap snapshots
3. Profile memory allocation

**Solution:**
1. Restart server periodically:
   ```bash
   # Cron job
   0 * * * * pkill -f server-name && node server.js
   ```

2. Fix memory leak in code
3. Implement connection pooling
4. Add garbage collection hints

#### High CPU Usage

**Problem:** Server uses excessive CPU.

**Diagnosis:**
1. Monitor CPU usage:
   ```bash
   top -p $(pgrep -f server-name)
   ```

2. Profile CPU usage:
   ```bash
   node --prof server.js
   ```

3. Check for infinite loops

**Solution:**
1. Optimize algorithms
2. Add rate limiting
3. Implement caching
4. Scale horizontally

### Tool-Specific Issues

#### Tool Not Found

**Problem:**
```
Error: Unknown tool: my-tool
```

**Diagnosis:**
1. Check server capabilities
2. Verify tool name spelling
3. Ensure server has loaded

**Solution:**
1. List available tools:
   ```bash
   python scripts/test_mcp_connection.py --server my-server --check-tools
   ```

2. Check server implementation
3. Restart server

#### Invalid Tool Arguments

**Problem:**
```
Error: Invalid arguments for tool
```

**Diagnosis:**
1. Check input schema
2. Validate JSON structure
3. Verify required fields

**Solution:**
1. Read tool documentation
2. Use correct argument format
3. Add validation before calling

### Platform-Specific Issues

#### Windows Path Issues

**Problem:**
```
Error: ENOENT: no such file or directory
```

**Solution:**
1. Use forward slashes:
   ```json
   {
     "args": ["C:/path/to/server.js"]
   }
   ```

2. Escape backslashes:
   ```json
   {
     "args": ["C:\\path\\to\\server.js"]
   }
   ```

3. Use environment variables:
   ```json
   {
     "env": {
       "SERVER_PATH": "C:\\path\\to\\server"
     }
   }
   ```

#### macOS Permissions

**Problem:**
```
Error: EACCES: permission denied
```

**Solution:**
1. Grant Terminal permissions:
   - System Settings → Privacy & Security → Full Disk Access
   - Add Terminal or your editor

2. Fix file permissions:
   ```bash
   chmod +x /path/to/server
   ```

3. Run as admin (not recommended):
   ```bash
   sudo node server.js
   ```

#### Linux Systemd Service

**Problem:** Server doesn't start as service.

**Solution:**
1. Create systemd service:
   ```ini
   [Unit]
   Description=MCP Server
   After=network.target
   
   [Service]
   Type=simple
   User=mcp
   WorkingDirectory=/opt/mcp-server
   ExecStart=/usr/bin/node server.js
   Restart=always
   Environment=NODE_ENV=production
   
   [Install]
   WantedBy=multi-user.target
   ```

2. Enable and start:
   ```bash
   sudo systemctl enable mcp-server
   sudo systemctl start mcp-server
   ```

## Diagnostic Commands

### Check Server Status
```bash
python scripts/manage_mcp_server.py status --name my-server
```

### View Server Logs
```bash
python scripts/manage_mcp_server.py logs --name my-server --lines 100
```

### Test Connection
```bash
python scripts/test_mcp_connection.py --server my-server --verbose
```

### Validate Configuration
```bash
python scripts/test_mcp_connection.py --server my-server --validate
```

### List All Servers
```bash
python scripts/manage_mcp_server.py list
```

## Getting Help

### Enable Debug Logging
```bash
export MCP_LOG_LEVEL=debug
export DEBUG=mcp:*  # For Node.js servers
```

### Check MCP Specification
- [MCP Protocol Docs](https://modelcontextprotocol.io)
- [MCP GitHub Repository](https://github.com/modelcontextprotocol)

### Review Server Documentation
Each MCP server should include:
- README.md with setup instructions
- Configuration examples
- Tool documentation
- Troubleshooting guide

### Common Support Channels
- Discord: #mcp channel
- GitHub Issues
- Stack Overflow (tag: modelcontextprotocol)
