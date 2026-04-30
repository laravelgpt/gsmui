#!/usr/bin/env python3
"""Component Library Builder - MCP Skill"""
import argparse
import json
import sys
from pathlib import Path
from typing import Dict, Any, List


class ComponentLibraryBuilder:
    def __init__(self, output_dir: str):
        self.output_dir = Path(output_dir).resolve()
        self.components = {
            'card': {'name': 'Card', 'frameworks': ['livewire', 'blade', 'vue', 'react']},
            'button': {'name': 'Button', 'frameworks': ['livewire', 'blade', 'vue', 'react', 'svelte', 'tailwind']},
            'input': {'name': 'Input', 'frameworks': ['livewire', 'blade', 'vue', 'react', 'svelte']},
            'table': {'name': 'Table', 'frameworks': ['livewire', 'blade', 'vue', 'react']},
            'modal': {'name': 'Modal', 'frameworks': ['livewire', 'vue', 'react', 'svelte']},
            'grid-container': {'name': 'GridContainer', 'frameworks': ['gridcn', 'tailwind']},
            'grid-row': {'name': 'GridRow', 'frameworks': ['gridcn', 'tailwind']},
            'grid-col': {'name': 'GridCol', 'frameworks': ['gridcn', 'tailwind']},
            'navbar': {'name': 'Navbar', 'frameworks': ['blade', 'vue', 'react']},
            'sidebar': {'name': 'Sidebar', 'frameworks': ['livewire', 'vue', 'react']},
        }
    
    def build_library(self, frameworks: List[str], components: List[str],
                     theme: str = 'default', output_path: str = None) -> Dict[str, Any]:
        print(f"Building component library...")
        build_dir = Path(output_path or self.output_dir) / f"component-library-{theme}"
        build_dir.mkdir(parents=True, exist_ok=True)
        results = {'success': True, 'build_dir': str(build_dir), 'frameworks': {},
                   'components_built': [], 'errors': []}
        
        # Shared assets
        assets_dir = build_dir / "assets"
        assets_dir.mkdir(exist_ok=True)
        with open(assets_dir / "theme.css", 'w') as f:
            f.write(":root { --primary: #3b82f6; }\n* { box-sizing: border-box; }\n")
        with open(assets_dir / "utilities.css", 'w') as f:
            f.write(".m-0 { margin: 0; } .p-0 { padding: 0; } .flex { display: flex; } .grid { display: grid; }\n")
        with open(assets_dir / "components.js", 'w') as f:
            f.write("class Component {} \n")
        
        for fw in frameworks:
            fw_dir = build_dir / fw
            fw_dir.mkdir(exist_ok=True)
            fw_components = [c for c in components if c in self.components and fw in self.components[c]['frameworks']]
            if fw_components:
                print(f"  Building {fw}...")
                self._build_framework(fw, fw_dir, fw_components)
                results['frameworks'][fw] = {'status': 'success', 'components': fw_components, 'path': str(fw_dir)}
                results['components_built'].extend(fw_components)
        
        results['components_built'] = list(set(results['components_built']))
        
        with open(build_dir / "package.json", 'w') as f:
            json.dump({'name': f'component-library-{theme}', 'version': '1.0.0', 'frameworks': frameworks}, f, indent=2)
        with open(build_dir / "README.md", 'w') as f:
            f.write(f"# Component Library - {theme.title()}\n\nFrameworks: {', '.join(frameworks)}\nComponents: {len(results['components_built'])}\n")
        
        print(f"Built: {build_dir}")
        return results
    
    def _build_framework(self, fw: str, dir_path: Path, components: List[str]):
        if fw == 'livewire':
            self._build_livewire(dir_path, components)
        elif fw == 'blade':
            self._build_blade(dir_path, components)
        elif fw == 'vue':
            self._build_vue(dir_path, components)
        elif fw == 'react':
            self._build_react(dir_path, components)
        elif fw == 'svelte':
            self._build_svelte(dir_path, components)
        elif fw == 'tailwind':
            with open(dir_path / "tailwind.config.js", 'w') as f:
                f.write("module.exports = { content: [], theme: {}, plugins: [] };\n")
        elif fw == 'gridcn':
            (dir_path / "src").mkdir(parents=True, exist_ok=True)
            with open(dir_path / "src" / "gridcn.css", 'w') as f:
                f.write(".grid-container { max-width: 1200px; margin: 0 auto; }\n")
    
    def _build_livewire(self, dir_path: Path, components: List[str]):
        (dir_path / "Components").mkdir(parents=True, exist_ok=True)
        (dir_path / "views").mkdir(exist_ok=True)
        for comp in components:
            name = ''.join(w.capitalize() for w in comp.split('-'))
            with open(dir_path / "Components" / f"{name}.php", 'w') as f:
                f.write(f'<?php\nnamespace App\\Components;\nclass {name} extends \\Livewire\\Component {{\n  public function render() {{\n    return view(\'livewire.{comp}\');\n  }}\n}}\n')
            with open(dir_path / "views" / f"{comp}.blade.php", 'w') as f:
                f.write(f'<div class="{comp}">{{ $slot }}</div>\n')
    
    def _build_blade(self, dir_path: Path, components: List[str]):
        (dir_path / "components").mkdir(parents=True, exist_ok=True)
        for comp in components:
            with open(dir_path / "components" / f"{comp}.blade.php", 'w') as f:
                f.write(f'<div class="{comp}">{{ $slot }}</div>\n')
    
    def _build_vue(self, dir_path: Path, components: List[str]):
        for comp in components:
            info = self.components[comp]
            with open(dir_path / f"{comp}.vue", 'w') as f:
                f.write(f'<template><div class="{comp}"><slot /></div></template>\n<script setup>// {info["name"]}</script>\n')
    
    def _build_react(self, dir_path: Path, components: List[str]):
        for comp in components:
            name = ''.join(w.capitalize() for w in comp.split('-'))
            with open(dir_path / f"{comp}.jsx", 'w') as f:
                f.write(f'import React from "react";\nexport const {name} = ({{ className, children, ...props }}) => (\n  <div className="{comp} " + className {{...props}}>{{children}}</div>\n);\n')
    
    def _build_svelte(self, dir_path: Path, components: List[str]):
        for comp in components:
            with open(dir_path / f"{comp}.svelte", 'w') as f:
                f.write(f'<div class="{comp}"><slot /></div>\n')


def main():
    parser = argparse.ArgumentParser(description="Build component libraries")
    parser.add_argument('-f', '--frameworks', nargs='+', default=['livewire', 'blade', 'vue', 'react', 'svelte', 'tailwind', 'gridcn'], help='Frameworks')
    parser.add_argument('-c', '--components', nargs='+', default=['all'], help='Components or all')
    parser.add_argument('-t', '--theme', default='default', choices=['default', 'dark', 'midnight'], help='Theme')
    parser.add_argument('-o', '--output', help='Output directory')
    args = parser.parse_args()
    builder = ComponentLibraryBuilder(args.output or '.')
    if 'all' in args.components or args.components == ['all']:
        args.components = list(builder.components.keys())
    try:
        results = builder.build_library(args.frameworks, args.components, args.theme, args.output)
        if results['success']:
            print(f"\nSuccess! Built {len(results['components_built'])} components")
        else:
            print(f"\nErrors: {results['errors']}")
            sys.exit(1)
    except Exception as e:
        print(f"\nFailed: {e}")
        sys.exit(1)


if __name__ == '__main__':
    main()
