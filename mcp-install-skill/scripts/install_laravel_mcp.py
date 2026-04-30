#!/usr/bin/env python3
"""
Laravel/PHP MCP Server Installation Script

Specialized installer for Laravel-based MCP servers and PHP tools.
"""

import argparse
import json
import os
import subprocess
import sys
from pathlib import Path
from typing import Dict, Any


class LaravelMCPInstaller:
    """Installer for Laravel/PHP MCP servers."""
    
    def __init__(self, config_path: Optional[str] = None):
        self.config_path = config_path
        self.installed_servers = {}
    
    def install_laravel_mcp_package(self, package_name: str, name: str,
                                   project_path: str,
                                   client: str = "openclaw",
                                   config_path: Optional[str] = None) -> Dict[str, Any]:
        """Install Laravel MCP package via Composer."""
        print(f"Installing Laravel MCP package '{package_name}'...")
        
        project_path = Path(project_path).resolve()
        if not (project_path / "composer.json").exists():
            raise FileNotFoundError(f"Not a Laravel project: {project_path}")
        
        # Install package via Composer
        try:
            result = subprocess.run(
                ["composer", "require", package_name],
                cwd=project_path,
                capture_output=True,
                text=True,
                check=True
            )
            print(f"✓ Package installed: {package_name}")
        except subprocess.CalledProcessError as e:
            print(f"✗ Composer install failed: {e.stderr}")
            raise
        
        # Publish configuration if available
        self._publish_laravel_config(package_name, project_path)
        
        # Run migrations if needed
        self._run_laravel_migrations(project_path)
        
        server_info = {
            "name": name,
            "method": "composer",
            "package": package_name,
            "language": "php",
            "framework": "laravel",
            "project_path": str(project_path),
            "transport": "stdio",
            "command": "php",
            "args": ["artisan", "mcp:serve", "--server", name],
            "env": {
                "APP_ENV": "production",
                "MCP_SERVER_NAME": name
            }
        }
        
        self.installed_servers[name] = server_info
        self._update_client_config(client, config_path)
        
        return server_info
    
    def create_laravel_mcp_server(self, name: str, output_path: str,
                                  description: str = "") -> Dict[str, Any]:
        """Create a new Laravel MCP server package."""
        print(f"Creating Laravel MCP server '{name}'...")
        
        output_path = Path(output_path).resolve()
        
        # Create package structure
        self._create_laravel_package_structure(output_path, name)
        
        # Create Service Provider
        self._create_service_provider(output_path, name)
        
        # Create MCP Server class
        self._create_mcp_server_class(output_path, name)
        
        # Create routes/controllers
        self._create_mcp_routes(output_path, name)
        
        # Create composer.json
        self._create_package_composer_json(output_path, name, description)
        
        # Create README
        self._create_package_readme(output_path, name)
        
        return {
            "success": True,
            "type": "laravel-package",
            "path": str(output_path),
            "name": name
        }
    
    def _create_laravel_package_structure(self, path: Path, name: str):
        """Create Laravel package directory structure."""
        src_dir = path / "src"
        src_dir.mkdir(parents=True, exist_ok=True)
        
        # Create config directory
        config_dir = path / "config"
        config_dir.mkdir(exist_ok=True)
        
        # Create resources directory
        resources_dir = path / "resources"
        resources_dir.mkdir(exist_ok=True)
        
        # Create routes directory
        routes_dir = path / "routes"
        routes_dir.mkdir(exist_ok=True)
    
    def _create_service_provider(self, path: Path, name: str):
        """Create Laravel Service Provider."""
        class_name = ''.join(word.capitalize() for word in name.split('-'))
        
        provider_code = f'''<?php

namespace {class_name};  

use Illuminate\Support\ServiceProvider;
use {class_name}\McpServerProvider;

class {class_name}ServiceProvider extends ServiceProvider
{{
    /**
     * Register services.
     */
    public function register(): void
    {{
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/mcp.php', 'mcp'
        );
        
        // Register MCP Server
        $this->app->singleton('mcp.server.{name}', function ($app) {{
            return new McpServerProvider($app);
        }});
    }}
    
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {{
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/mcp.php' => config_path('mcp.php'),
        ], 'config');
        
        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'migrations');
        
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        
        // Register commands
        if ($this->app->runningInConsole()) {{
            $this->commands([
                \{class_name}\Console\ServeCommand::class,
            ]);
        }}
    }}
}}
'''
        
        provider_path = path / "src" / f"{class_name}ServiceProvider.php"
        with open(provider_path, 'w') as f:
            f.write(provider_code)
    
    def _create_mcp_server_class(self, path: Path, name: str):
        """Create MCP Server provider class."""
        class_name = ''.join(word.capitalize() for word in name.split('-'))
        
        server_code = f'''<?php

namespace {class_name};

use Mcp\Server;
use Mcp\Transport\StdioTransport;

class McpServerProvider
{{
    protected $server;
    
    public function __construct(protected $app)
    {{
        $this->server = new Server([
            'name' => '{name}',
            'version' => '1.0.0',
        ]);
        
        $this->registerTools();
    }}
    
    protected function registerTools(): void
    {{
        // Register Laravel-specific tools
        $this->server->registerTool('list_routes', [
            'description' => 'List all Laravel routes',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'method' => [
                        'type' => 'string',
                        'description' => 'Filter by HTTP method'
                    ]
                ]
            ],
            'handler' => function ($arguments) {{
                return $this->listRoutes($arguments);
            }}
        ]);
        
        $this->server->registerTool('run_migration', [
            'description' => 'Run database migrations',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'path' => [
                        'type' => 'string',
                        'description' => 'Migration path'
                    ]
                ]
            ],
            'handler' => function ($arguments) {{
                return $this->runMigrations($arguments);
            }}
        ]);
        
        $this->server->registerTool('check_env', [
            'description' => 'Check Laravel environment configuration',
            'inputSchema' => [
                'type' => 'object',
                'properties' => []
            ],
            'handler' => function ($arguments) {{
                return $this->checkEnvironment($arguments);
            }}
        ]);
    }}
    
    public function serve(): void
    {{
        $transport = new StdioTransport();
        $this->server->connect($transport);
    }}
    
    protected function listRoutes($arguments)
    {{
        $routes = app('router')->getRoutes();
        
        $result = [];
        foreach ($routes as $route) {{
            if (isset($arguments['method']) && !in_array($arguments['method'], $route->methods())) {{
                continue;
            }}
            
            $result[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'action' => $route->getActionName(),
                'middleware' => implode(',', $route->gatherMiddleware()),
            ];
        }}
        
        return [
            'count' => count($result),
            'routes' => $result
        ];
    }}
    
    protected function runMigrations($arguments)
    {{
        try {{
            \Artisan::call('migrate', $arguments ?? []);
            
            return [
                'success' => true,
                'output' => \Artisan::output()
            ];
        }} catch (\Exception $e) {{
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }}
    }}
    
    protected function checkEnvironment($arguments)
    {{
        return [
            'app_name' => config('app.name'),
            'app_env' => app()->environment(),
            'debug' => config('app.debug'),
            'database' => config('database.default'),
            'cache' => config('cache.default'),
            'queue' => config('queue.default'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
        ];
    }}
}}
'''
        
        server_path = path / "src" / "McpServerProvider.php"
        with open(server_path, 'w') as f:
            f.write(server_code)
    
    def _create_mcp_routes(self, path: Path, name: str):
        """Create MCP routes."""
        routes_code = '''<?php

use Illuminate\Support\Facades\Route;

Route::post('/mcp', function () {
    $server = app('mcp.server.''' + name + '''');
    return $server->serve();
});
'''
        
        routes_path = path / "routes" / "web.php"
        with open(routes_path, 'w') as f:
            f.write(routes_code)
    
    def _create_package_composer_json(self, path: Path, name: str, description: str):
        """Create composer.json for the package."""
        composer = {
            "name": f"vendor/{name}",
            "description": description or f"Laravel MCP Server: {name}",
            "type": "library",
            "minimum-stability": "stable",
            "license": "MIT",
            "authors": [
                {
                    "name": "Author",
                    "email": "author@example.com"
                }
            ],
            "require": {
                "php": ">=8.1",
                "illuminate/support": "^10.0|^11.0|^12.0"
            },
            "autoload": {
                "psr-4": {
                    f"{''.join(word.capitalize() for word in name.split('-'))}\\": "src/"
                }
            },
            "extra": {
                "laravel": {
                    "providers": [
                        f"{''.join(word.capitalize() for word in name.split('-'))}\\{''.join(word.capitalize() for word in name.split('-'))}ServiceProvider"
                    ]
                }
            }
        }
        
        composer_path = path / "composer.json"
        with open(composer_path, 'w') as f:
            json.dump(composer, f, indent=2)
    
    def _create_package_readme(self, path: Path, name: str):
        """Create package README."""
        readme = f'''# Laravel MCP Server: {name}

A Laravel package providing Model Context Protocol (MCP) server functionality.

## Installation

```bash
composer require vendor/{name}
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="{''.join(word.capitalize() for word in name.split('-'))}\\{''.join(word.capitalize() for word in name.split('-'))}ServiceProvider" --tag="config"
```

## Usage

### Start MCP Server

```bash
php artisan mcp:serve --server={name}
```

### Available Tools

- `list_routes` - List all Laravel routes
- `run_migration` - Run database migrations
- `check_env` - Check environment configuration

## Configuration

Edit `config/mcp.php` to customize server settings.

## License

MIT
'''
        
        readme_path = path / "README.md"
        with open(readme_path, 'w') as f:
            f.write(readme)
    
    def _publish_laravel_config(self, package_name: str, project_path: Path):
        """Publish Laravel package configuration."""
        try:
            subprocess.run(
                ["php", "artisan", "vendor:publish", "--provider=\"{package_name}\"{package_name}ServiceProvider\""],
                cwd=project_path,
                capture_output=True
            )
        except:
            pass
    
    def _run_laravel_migrations(self, project_path: Path):
        """Run Laravel migrations."""
        try:
            subprocess.run(
                ["php", "artisan", "migrate"],
                cwd=project_path,
                capture_output=True
            )
        except:
            pass
    
    def _update_client_config(self, client: str, config_path: Optional[str]):
        """Update client configuration."""
        # Similar to base installer
        pass


def main():
    parser = argparse.ArgumentParser(
        description="Install Laravel/PHP MCP servers"
    )
    parser.add_argument(
        "--package", "-p",
        help="Composer package name"
    )
    parser.add_argument(
        "--name", "-n",
        required=True,
        help="Name for the MCP server"
    )
    parser.add_argument(
        "--path",
        required=True,
        help="Laravel project path"
    )
    parser.add_argument(
        "--client", "-c",
        default="openclaw",
        help="Target client"
    )
    parser.add_argument(
        "--config",
        help="Path to configuration file"
    )
    parser.add_argument(
        "--create", "-C",
        action="store_true",
        help="Create new Laravel MCP package"
    )
    parser.add_argument(
        "--output", "-o",
        help="Output directory for new package"
    )
    
    args = parser.parse_args()
    
    installer = LaravelMCPInstaller()
    
    try:
        if args.create:
            if not args.output:
                print("Error: --output is required for create mode")
                sys.exit(1)
            
            result = installer.create_laravel_mcp_server(
                args.name, args.output,
                description=f"Laravel MCP Server: {args.name}"
            )
            
            print(f"\n✓ Created Laravel MCP server package!")
            print(f"  Location: {result['path']}")
            print(f"\nNext steps:")
            print(f"  1. cd {args.output}")
            print(f"  2. composer install")
            print(f"  3. Publish to your Laravel project")
        
        else:
            if not args.package or not args.path:
                print("Error: --package and --path are required")
                sys.exit(1)
            
            result = installer.install_laravel_mcp_package(
                args.package, args.name,
                args.path,
                client=args.client,
                config_path=args.config
            )
            
            print(f"\n✓ Successfully installed Laravel MCP package!")
            print(f"  Package: {result['package']}")
            print(f"  Server: {result['name']}")
    
    except Exception as e:
        print(f"\n✗ Installation failed: {e}")
        sys.exit(1)


if __name__ == "__main__":
    main()
