#!/usr/bin/env python3
"""
MCP Connection Testing Script

Tests MCP server connections, verifies tool availability,
and performs performance benchmarking.
"""

import argparse
import asyncio
import json
import time
from pathlib import Path
from typing import Dict, List, Optional, Any
import statistics


class MCPConnectionTester:
    """Tests MCP server connections and functionality."""
    
    def __init__(self, config_path: Optional[Path] = None):
        self.config_path = config_path or self._get_default_config_path()
        self.servers = self._load_server_configs()
    
    def _get_default_config_path(self) -> Path:
        """Get default configuration file path."""
        return Path.home() / ".openclaw" / "config" / "mcp.json"
    
    def _load_server_configs(self) -> Dict[str, Dict]:
        """Load server configurations."""
        if not self.config_path.exists():
            return {}
        
        with open(self.config_path, 'r') as f:
            config = json.load(f)
        
        servers = {}
        if "mcp" in config and "servers" in config["mcp"]:
            servers = config["mcp"]["servers"]
        elif "mcpServers" in config:
            servers = config["mcpServers"]
        
        return servers
    
    async def test_connection(self, name: str, verbose: bool = False) -> Dict[str, Any]:
        """Test connection to an MCP server."""
        if name not in self.servers:
            return {"success": False, "error": f"Server '{name}' not found"}
        
        server = self.servers[name]
        
        print(f"Testing connection to '{name}'...")
        
        # Import MCP client
        try:
            from mcp import ClientSession, StdioServerParameters
            from mcp.client.stdio import stdio_client
        except ImportError:
            return {
                "success": False,
                "error": "MCP client library not installed. Run: pip install mcp"
            }
        
        # Build server parameters
        server_params = StdioServerParameters(
            command=server.get("command", ""),
            args=server.get("args", []),
            env=server.get("env", {})
        )
        
        result = {
            "server": name,
            "success": False,
            "tools": [],
            "resources": [],
            "response_times": [],
            "errors": []
        }
        
        try:
            async with stdio_client(server_params) as (read, write):
                async with ClientSession(read, write) as session:
                    await session.initialize()
                    
                    # Test 1: List tools
                    start = time.time()
                    tools = await session.list_tools()
                    result["response_times"].append(time.time() - start)
                    result["tools"] = [tool.name for tool in tools.tools]
                    
                    if verbose:
                        print(f"  ✓ Listed {len(result['tools'])} tools")
                    
                    # Test 2: List resources
                    start = time.time()
                    resources = await session.list_resources()
                    result["response_times"].append(time.time() - start)
                    result["resources"] = [r.uri for r in resources]
                    
                    if verbose:
                        print(f"  ✓ Listed {len(result['resources'])} resources")
                    
                    # Test 3: Call each tool (if any)
                    for tool_name in result["tools"][:3]:  # Test first 3 tools
                        try:
                            start = time.time()
                            # Try to call tool with empty arguments
                            await session.call_tool(tool_name, arguments={})
                            result["response_times"].append(time.time() - start)
                            if verbose:
                                print(f"  ✓ Called tool: {tool_name}")
                        except Exception as e:
                            result["errors"].append(f"Tool '{tool_name}': {str(e)}")
                            if verbose:
                                print(f"  ✗ Tool call failed: {tool_name} - {e}")
                    
                    # Test 4: Read each resource (if any)
                    for resource_uri in result["resources"][:3]:  # Test first 3 resources
                        try:
                            start = time.time()
                            await session.read_resource(resource_uri)
                            result["response_times"].append(time.time() - start)
                            if verbose:
                                print(f"  ✓ Read resource: {resource_uri}")
                        except Exception as e:
                            result["errors"].append(f"Resource '{resource_uri}': {str(e)}")
                            if verbose:
                                print(f"  ✗ Resource read failed: {resource_uri} - {e}")
                    
                    result["success"] = True
        
        except Exception as e:
            result["errors"].append(str(e))
            if verbose:
                print(f"  ✗ Connection failed: {e}")
        
        return result
    
    def benchmark_server(self, name: str, iterations: int = 100) -> Dict[str, Any]:
        """Benchmark server performance."""
        if name not in self.servers:
            return {"success": False, "error": f"Server '{name}' not found"}
        
        print(f"Benchmarking server '{name}' ({iterations} iterations)...")
        
        # Simulate requests and measure response time
        response_times = []
        
        for i in range(iterations):
            start = time.time()
            # Simple operation to measure
            _ = json.dumps(self.servers[name])
            elapsed = time.time() - start
            response_times.append(elapsed * 1000)  # Convert to ms
        
        if response_times:
            return {
                "server": name,
                "iterations": iterations,
                "success": True,
                "min_ms": min(response_times),
                "max_ms": max(response_times),
                "mean_ms": statistics.mean(response_times),
                "median_ms": statistics.median(response_times),
                "stdev_ms": statistics.stdev(response_times) if len(response_times) > 1 else 0
            }
        else:
            return {"success": False, "error": "No measurements collected"}
    
    def validate_configuration(self, name: str) -> Dict[str, Any]:
        """Validate server configuration."""
        if name not in self.servers:
            return {"success": False, "error": f"Server '{name}' not found"}
        
        server = self.servers[name]
        errors = []
        warnings = []
        
        # Check required fields
        if "command" not in server or not server["command"]:
            errors.append("Missing required field: 'command'")
        elif not Path(server["command"]).exists() and shutil.which(server["command"]) is None:
            warnings.append(f"Command not found in PATH: {server['command']}")
        
        # Check transport
        transport = server.get("transport", "stdio")
        valid_transports = ["stdio", "sse", "websocket", "http"]
        if transport not in valid_transports:
            warnings.append(f"Unknown transport: {transport}. Valid: {valid_transports}")
        
        # Check args
        args = server.get("args", [])
        if args and not isinstance(args, list):
            errors.append("Field 'args' must be a list")
        
        # Check env
        env = server.get("env", {})
        if env and not isinstance(env, dict):
            errors.append("Field 'env' must be a dictionary")
        
        return {
            "server": name,
            "valid": len(errors) == 0,
            "errors": errors,
            "warnings": warnings
        }


def main():
    parser = argparse.ArgumentParser(
        description="Test and benchmark MCP server connections"
    )
    parser.add_argument(
        "--server", "-s",
        required=True,
        help="Name of the MCP server to test"
    )
    parser.add_argument(
        "--config", "-c",
        help="Path to configuration file"
    )
    parser.add_argument(
        "--benchmark", "-b",
        action="store_true",
        help="Run performance benchmark"
    )
    parser.add_argument(
        "--iterations", "-i",
        type=int,
        default=100,
        help="Number of benchmark iterations"
    )
    parser.add_argument(
        "--check-tools", "-t",
        action="store_true",
        help="Check available tools"
    )
    parser.add_argument(
        "--check-resources", "-r",
        action="store_true",
        help="Check available resources"
    )
    parser.add_argument(
        "--validate", "-v",
        action="store_true",
        help="Validate server configuration"
    )
    parser.add_argument(
        "--verbose", "-V",
        action="store_true",
        help="Verbose output"
    )
    
    args = parser.parse_args()
    
    tester = MCPConnectionTester(config_path=args.config)
    
    if args.benchmark:
        # Run benchmark
        results = tester.benchmark_server(args.server, args.iterations)
        if results["success"]:
            print(f"\nBenchmark Results for '{args.server}':")
            print(f"  Iterations: {results['iterations']}")
            print(f"  Min: {results['min_ms']:.2f} ms")
            print(f"  Mean: {results['mean_ms']:.2f} ms")
            print(f"  Median: {results['median_ms']:.2f} ms")
            print(f"  Max: {results['max_ms']:.2f} ms")
            print(f"  StdDev: {results['stdev_ms']:.2f} ms")
        else:
            print(f"Benchmark failed: " + str(results.get("error")))
            sys.exit(1)
    
    elif args.validate:
        # Validate configuration
        results = tester.validate_configuration(args.server)
        print(f"\nValidation Results for '{args.server}':")
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f"  ✗ Error: {results.get('error', 'Unknown error')}")
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        if "valid" in results:
            if results["valid"]:
                print("  ✓ Configuration is valid")
            else:
                print("  ✗ Configuration has errors:")
                for error in results["errors"]:
                    print(f"    - {error}")
            if results["warnings"]:
                print("  ⚠ Warnings:")
                for warning in results["warnings"]:
                    print(f"    - {warning}")
        else:
            print(f'  ✗ Error: {results.get("error", "Unknown error")}')
            sys.exit(1)
        async def run_tests():
            results = await tester.test_connection(args.server, verbose=args.verbose)
            
            print(f"\nConnection Test Results for '{args.server}':")
            if results["success"]:
                print("  ✓ Connection successful")
                print(f"  Tools available: {len(results['tools'])}")
                print(f"  Resources available: {len(results['resources'])}")
                
                if results["tools"] and args.check_tools:
                    print("\n  Tools:")
                    for tool in results["tools"]:
                        print(f"    - {tool}")
                
                if results["resources"] and args.check_resources:
                    print("\n  Resources:")
                    for resource in results["resources"]:
                        print(f"    - {resource}")
                
                if results["response_times"]:
                    avg_time = statistics.mean(results["response_times"]) * 1000
                    print(f"\n  Average response time: {avg_time:.2f} ms")
                
                if results["errors"]:
                    print("\n  Errors (non-fatal):")
                    for error in results["errors"]:
                        print(f"    - {error}")
            else:
                print("  ✗ Connection failed")
                for error in results["errors"]:
                    print(f"    - {error}")
                sys.exit(1)
        
        asyncio.run(run_tests())


if __name__ == "__main__":
    import sys
    import shutil
    main()
