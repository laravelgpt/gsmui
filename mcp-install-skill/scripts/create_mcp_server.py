#!/usr/bin/env python3
"""
MCP Server Creation Script

Scaffolds new MCP server projects with templates and boilerplate code.
"""

import argparse
import json
import os
import shutil
from pathlib import Path
from typing import Dict, Any


class MCPServerCreator:
    """Creates new MCP server projects."""
    
    def __init__(self):
        self.templates_dir = Path(__file__).parent.parent / "assets" / "templates"
        self.templates_dir.mkdir(parents=True, exist_ok=True)
    
    def create_nodejs_server(self, name: str, output_dir: Path, 
                            description: str = "") -> Dict[str, Any]:
        """Create a Node.js MCP server project."""
        print(f"Creating Node.js MCP server '{name}'...")
        
        # Create project structure
        output_dir.mkdir(parents=True, exist_ok=True)
        (output_dir / "src").mkdir(exist_ok=True)
        (output_dir / "test").mkdir(exist_ok=True)
        
        # Create package.json
        package_json = {
            "name": name.lower().replace(" ", "-"),
            "version": "1.0.0",
            "description": description or f"MCP Server: {name}",
            "main": "src/index.js",
            "type": "module",
            "scripts": {
                "start": "node src/index.js",
                "dev": "node src/index.js",
                "build": "echo 'No build step required'",
                "test": "node test/test.js"
            },
            "keywords": ["mcp", "model-context-protocol"],
            "author": "",
            "license": "MIT",
            "dependencies": {
                "@modelcontextprotocol/sdk": "^1.0.0"
            }
        }
        
        with open(output_dir / "package.json", 'w') as f:
            json.dump(package_json, f, indent=2)
        
        # Create main server file
        server_code = '''// Node.js MCP Server
import { Server } from "@modelcontextprotocol/sdk/server/index.js";
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js";
import {
  ListToolsRequestSchema,
  CallToolRequestSchema,
} from "@modelcontextprotocol/sdk/types.js";

// Create server instance
const server = new Server(
  {
    name: "''' + name + '''",
    version: "1.0.0",
  },
  {
    capabilities: {
      tools: {},
    },
  }
);

// Define available tools
server.setRequestHandler(ListToolsRequestSchema, async () => {
  return {
    tools: [
      {
        name: "example_tool",
        description: "An example tool that demonstrates MCP functionality",
        inputSchema: {
          type: "object",
          properties: {
            message: {
              type: "string",
              description: "A message to process",
            },
          },
          required: ["message"],
        },
      },
    ],
  };
});

// Handle tool calls
server.setRequestHandler(CallToolRequestSchema, async (request) => {
  const { name, arguments: args } = request.params;
  
  if (name === "example_tool") {
    const message = args?.message || "No message provided";
    
    return {
      content: [
        {
          type: "text",
          text: `Processed: ${message}`,
        },
      ],
    };
  }
  
  throw new Error(`Unknown tool: ${name}`);
});

// Start server
async function main() {
  const transport = new StdioServerTransport();
  await server.connect(transport);
  console.error("''' + name + ''' MCP server running on stdio");
}

main().catch((error) => {
  console.error("Fatal error:", error);
  process.exit(1);
});
'''
        
        with open(output_dir / "src" / "index.js", 'w') as f:
            f.write(server_code)
        
        # Create test file
        test_code = '''// Test file for MCP server
console.log("Testing ''' + name + ''' MCP server...");

// Add your tests here
'''
        
        with open(output_dir / "test" / "test.js", 'w') as f:
            f.write(test_code)
        
        # Create README
        readme = self._generate_readme(name, "Node.js", description)
        with open(output_dir / "README.md", 'w') as f:
            f.write(readme)
        
        # Create .gitignore
        gitignore = self._generate_gitignore("nodejs")
        with open(output_dir / ".gitignore", 'w') as f:
            f.write(gitignore)
        
        return {
            "success": True,
            "language": "nodejs",
            "path": str(output_dir),
            "files": self._list_files(output_dir)
        }
    
    def create_python_server(self, name: str, output_dir: Path,
                            description: str = "") -> Dict[str, Any]:
        """Create a Python MCP server project."""
        print(f"Creating Python MCP server '{name}'...")
        
        # Create project structure
        output_dir.mkdir(parents=True, exist_ok=True)
        (output_dir / "src" / name.replace(" ", "_")).mkdir(parents=True, exist_ok=True)
        (output_dir / "tests").mkdir(exist_ok=True)
        
        module_name = name.lower().replace(" ", "_").replace("-", "_")
        
        # Create pyproject.toml
        pyproject = f'''[build-system]
requires = ["setuptools>=68.0", "wheel"]
build-backend = "setuptools.build_meta"

[project]
name = "{module_name}"
version = "1.0.0"
description = "{description or f'MCP Server: {name}'}"
readme = "README.md"
requires-python = ">=3.11"
license = {{text = "MIT"}}
keywords = ["mcp", "model-context-protocol"]

classifiers = [
    "Development Status :: 4 - Beta",
    "Intended Audience :: Developers",
    "License :: OSI Approved :: MIT License",
    "Programming Language :: Python :: 3",
    "Programming Language :: Python :: 3.11",
    "Programming Language :: Python :: 3.12",
]

dependencies = [
    "mcp>=1.0.0",
]

[project.optional-dependencies]
dev = [
    "pytest>=7.0",
    "pytest-asyncio",
]

[project.scripts]
{module_name} = "{module_name}.server:main"
'''
        
        with open(output_dir / "pyproject.toml", 'w') as f:
            f.write(pyproject)
        
        # Create __init__.py
        init_content = f'"""{name} - MCP Server"""\n\n__version__ = "1.0.0"\n'
        with open(output_dir / "src" / module_name / "__init__.py", 'w') as f:
            f.write(init_content)
        
        # Create main server file
        server_code = f'''"""MCP Server - {name}"""
import asyncio
from typing import Dict, Any
from mcp.server import Server
from mcp.server.stdio import stdio_server
from mcp.types import Tool, TextContent

# Create server instance
server = Server("{name}")

@server.list_tools()
async def handle_list_tools() -> list[Tool]:
    """List available tools."""
    return [
        Tool(
            name="example_tool",
            description="An example tool that demonstrates MCP functionality",
            inputSchema={{
                "type": "object",
                "properties": {{
                    "message": {{
                        "type": "string",
                        "description": "A message to process",
                    }},
                }},
                "required": ["message"],
            }},
        ),
    ]

@server.call_tool()
async def handle_call_tool(name: str, arguments: Dict[str, Any]) -> list[TextContent]:
    """Handle tool calls."""
    if name == "example_tool":
        message = arguments.get("message", "No message provided")
        return [TextContent(type="text", text=f"Processed: {{message}}")]
    
    raise ValueError(f"Unknown tool: {{name}}")

async def main():
    """Run the server."""
    # Run server using stdio
    async with stdio_server() as (read_stream, write_stream):
        await server.run(
            read_stream,
            write_stream,
            server.create_initialization_options(),
        )

if __name__ == "__main__":
    asyncio.run(main())
'''
        
        with open(output_dir / "src" / module_name / "server.py", 'w') as f:
            f.write(server_code)
        
        # Create test file
        test_code = f'''"""Tests for {name} MCP server."""
import pytest


def test_example():
    """Example test."""
    assert True
'''
        
        with open(output_dir / "tests" / "test_server.py", 'w') as f:
            f.write(test_code)
        
        # Create README
        readme = self._generate_readme(name, "Python", description)
        with open(output_dir / "README.md", 'w') as f:
            f.write(readme)
        
        # Create .gitignore
        gitignore = self._generate_gitignore("python")
        with open(output_dir / ".gitignore", 'w') as f:
            f.write(gitignore)
        
        return {
            "success": True,
            "language": "python",
            "path": str(output_dir),
            "files": self._list_files(output_dir)
        }
    
    def create_basic_server(self, name: str, output_dir: Path,
                           description: str = "") -> Dict[str, Any]:
        """Create a basic MCP server project."""
        print(f"Creating basic MCP server '{name}'...")
        
        # Create minimal structure
        output_dir.mkdir(parents=True, exist_ok=True)
        
        # Create README
        readme = self._generate_readme(name, "Generic", description)
        with open(output_dir / "README.md", 'w') as f:
            f.write(readme)
        
        # Create server config template
        config = {
            "name": name,
            "description": description or f"MCP Server: {name}",
            "version": "1.0.0",
            "transport": "stdio",
            "capabilities": {
                "tools": {},
                "resources": {}
            }
        }
        
        with open(output_dir / "mcp-config.json", 'w') as f:
            json.dump(config, f, indent=2)
        
        # Create .gitignore
        gitignore = self._generate_gitignore("generic")
        with open(output_dir / ".gitignore", 'w') as f:
            f.write(gitignore)
        
        return {
            "success": True,
            "language": "generic",
            "path": str(output_dir),
            "files": self._list_files(output_dir)
        }
    
    def _generate_readme(self, name: str, language: str, description: str) -> str:
        """Generate README content."""
        return f'''# {name}

{description or f"MCP Server: {name}"}

## Overview

This is a Model Context Protocol (MCP) server built with {language}.

## Installation

### Prerequisites

- MCP-compatible client (Claude Desktop, OpenClaw, etc.)
- {language} runtime

### Setup

```bash
# Install dependencies
{"npm install" if language == "Node.js" else "pip install -e ."}

# Configure your MCP client
# Add to your client configuration:

# For Claude Desktop:
# {{
#   "mcpServers": {{
#     "{name.lower().replace(' ', '-')}": {{
#       "command": "{'node' if language == 'Node.js' else 'python'}",
#       "args": ["{'src/index.js' if language == 'Node.js' else '-m ' + name.lower().replace(' ', '_')}"],
#       "transport": "stdio"
#     }}
#   }}
# }}
```

## Usage

Once configured, you can use the server with your MCP client.

## Development

```bash
# Run in development mode
{"npm run dev" if language == "Node.js" else "python -m src.server"}

# Run tests
{"npm test" if language == "Node.js" else "pytest"}
```

## License

MIT
'''
    
    def _generate_gitignore(self, language: str) -> str:
        """Generate .gitignore content."""
        ignores = {
            "nodejs": '''node_modules/
*.log
.env
.DS_Store
.vscode/
.idea/
dist/
build/
coverage/
''',
            "python": '''__pycache__/
*.py[cod]
*$py.class
*.so
.Python
build/
develop-eggs/
dist/
downloads/
eggs/
.eggs/
lib/
lib64/
parts/
sdist/
var/
wheels/
*.egg-info/
.installed.cfg
*.egg
.pytest_cache/
.coverage
htmlcov/
.vscode/
.idea/
.env
''',
            "generic": '''*.log
.DS_Store
.vscode/
.idea/
.env
'''
        }
        return ignores.get(language, ignores["generic"])
    
    def _list_files(self, directory: Path) -> list:
        """List all files in directory."""
        files = []
        for root, dirs, filenames in os.walk(directory):
            for filename in filenames:
                filepath = Path(root) / filename
                files.append(str(filepath.relative_to(directory)))
        return sorted(files)


def main():
    parser = argparse.ArgumentParser(
        description="Create a new MCP server project"
    )
    parser.add_argument(
        "--name", "-n",
        required=True,
        help="Name of the MCP server"
    )
    parser.add_argument(
        "--language", "-l",
        choices=["nodejs", "python", "basic"],
        default="nodejs",
        help="Programming language/template"
    )
    parser.add_argument(
        "--description", "-d",
        help="Description of the MCP server"
    )
    parser.add_argument(
        "--output", "-o",
        default="./mcp-server",
        help="Output directory"
    )
    
    args = parser.parse_args()
    
    creator = MCPServerCreator()
    output_dir = Path(args.output).resolve()
    
    # Check if directory exists
    if output_dir.exists():
        print(f"Error: Directory already exists: {output_dir}")
        print("Please choose a different directory or remove the existing one")
        sys.exit(1)
    
    try:
        if args.language == "nodejs":
            result = creator.create_nodejs_server(
                args.name, output_dir, args.description
            )
        elif args.language == "python":
            result = creator.create_python_server(
                args.name, output_dir, args.description
            )
        else:
            result = creator.create_basic_server(
                args.name, output_dir, args.description
            )
        
        if result["success"]:
            print(f"\n✓ Successfully created MCP server '{args.name}'!")
            print(f"  Location: {result['path']}")
            print(f"  Language: {result['language']}")
            print(f"\nFiles created:")
            for file in result["files"]:
                print(f"  - {file}")
            
            print(f"\nNext steps:")
            print(f"  1. cd {output_dir}")
            if args.language == "nodejs":
                print(f"  2. npm install")
                print(f"  3. npm run dev")
            elif args.language == "python":
                print(f"  2. pip install -e .")
                print(f"  3. python -m src.{args.name.lower().replace(' ', '_')}.server")
            else:
                print(f"  2. Implement your server logic")
            print(f"  4. Configure your MCP client to use the server")
        else:
            print(f"\n✗ Failed to create MCP server")
            sys.exit(1)
    
    except Exception as e:
        print(f"\n✗ Error: {e}")
        sys.exit(1)


if __name__ == "__main__":
    import sys
    main()
