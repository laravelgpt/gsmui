#!/usr/bin/env python3
"""Advanced UI Library Builder - MCP Skill"""
import argparse
import json
import subprocess
import sys
from pathlib import Path
from typing import Dict, Any, List


class AdvancedUILibraryBuilder:
    def __init__(self, output_dir: str):
        self.output_dir = Path(output_dir).resolve()
        self.ui_components = {
            'card': {'name': 'Card', 'type': 'display', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'filament', 'gsmui', 'vue'], 'variants': ['default', 'elevated', 'outlined', 'hoverable']},
            'button': {'name': 'Button', 'type': 'action', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'filament', 'gsmui', 'vue'], 'variants': ['primary', 'secondary', 'success', 'warning', 'danger', 'ghost']},
            'input': {'name': 'Input', 'type': 'form', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'filament', 'gsmui', 'vue'], 'variants': ['default', 'outlined', 'filled', 'error']},
            'textarea': {'name': 'Textarea', 'type': 'form', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'filament'], 'variants': ['default', 'outlined']},
            'select': {'name': 'Select', 'type': 'form', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'filament', 'gsmui', 'vue'], 'variants': ['default', 'multiple']},
            'checkbox': {'name': 'Checkbox', 'type': 'form', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'filament', 'gsmui'], 'variants': ['default', 'indeterminate']},
            'switch': {'name': 'Switch', 'type': 'form', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'filament', 'gsmui', 'vue'], 'variants': ['default', 'ios', 'pill']},
            'table': {'name': 'Table', 'type': 'display', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'filament', 'gsmui', 'vue'], 'variants': ['default', 'striped', 'hover', 'sortable']},
            'datagrid': {'name': 'DataGrid', 'type': 'display', 'frameworks': ['flux', 'filament', 'gsmui'], 'variants': ['server-side', 'client-side']},
            'list': {'name': 'List', 'type': 'display', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'avatars', 'divided']},
            'accordion': {'name': 'Accordion', 'type': 'display', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'multiple']},
            'tabs': {'name': 'Tabs', 'type': 'navigation', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'gsmui'], 'variants': ['default', 'pills', 'underline']},
            'modal': {'name': 'Modal', 'type': 'overlay', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'filament', 'gsmui', 'vue'], 'variants': ['default', 'slide', 'fade', 'large']},
            'alert': {'name': 'Alert', 'type': 'feedback', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'success', 'error', 'warning', 'info']},
            'toast': {'name': 'Toast', 'type': 'feedback', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['top-right', 'bottom-left', 'stacked']},
            'tooltip': {'name': 'Tooltip', 'type': 'feedback', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'filament', 'gsmui', 'vue'], 'variants': ['top', 'bottom', 'left', 'right']},
            'badge': {'name': 'Badge', 'type': 'display', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'dot', 'pill']},
            'avatar': {'name': 'Avatar', 'type': 'display', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'rounded', 'circle']},
            'progress': {'name': 'Progress', 'type': 'display', 'frameworks': ['tallstack', 'flux', 'daisy', 'gsmui'], 'variants': ['linear', 'circular']},
            'navbar': {'name': 'Navbar', 'type': 'navigation', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'sticky', 'transparent']},
            'sidebar': {'name': 'Sidebar', 'type': 'navigation', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'gsmui'], 'variants': ['default', 'collapsible', 'mini']},
            'breadcrumb': {'name': 'Breadcrumb', 'type': 'navigation', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'separator', 'icon']},
            'pagination': {'name': 'Pagination', 'type': 'navigation', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'rounded', 'outline']},
            'container': {'name': 'Container', 'type': 'layout', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'fluid', 'boxed']},
            'grid': {'name': 'Grid', 'type': 'layout', 'frameworks': ['gridcn', 'tallstack', 'flux', 'gsmui', 'vue'], 'variants': ['2-col', '3-col', '4-col', '12-col']},
            'grid-col': {'name': 'GridCol', 'type': 'layout', 'frameworks': ['gridcn', 'tallstack', 'flux', 'gsmui', 'vue'], 'variants': ['auto', 'span-1', 'span-6', 'span-12']},
            'stack': {'name': 'Stack', 'type': 'layout', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui'], 'variants': ['sm', 'md', 'lg', 'xl']},
            'divider': {'name': 'Divider', 'type': 'layout', 'frameworks': ['tallstack', 'flux', 'daisy', 'gsmui'], 'variants': ['solid', 'dashed', 'dotted']},
            'comment': {'name': 'Comment', 'type': 'social', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui', 'comment-lib'], 'variants': ['default', 'threaded', 'nested']},
            'comment-thread': {'name': 'CommentThread', 'type': 'social', 'frameworks': ['comment-lib', 'gsmui'], 'variants': ['default', 'nested', 'flat']},
            'comment-form': {'name': 'CommentForm', 'type': 'social', 'frameworks': ['comment-lib', 'gsmui'], 'variants': ['default', 'with-upload', 'markdown']},
            'filament-resource': {'name': 'FilamentResource', 'type': 'admin', 'frameworks': ['filament'], 'variants': ['default', 'with-actions']},
            'widget': {'name': 'Widget', 'type': 'widget', 'frameworks': ['tallstack', 'flux', 'gsmui'], 'variants': ['default', 'stat', 'chart']},
            'chart': {'name': 'Chart', 'type': 'widget', 'frameworks': ['tallstack', 'flux', 'gsmui'], 'variants': ['bar', 'line', 'pie', 'donut']},
            'alert-dialog': {'name': 'AlertDialog', 'type': 'overlay', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['default', 'destructive']},
            'command': {'name': 'Command', 'type': 'overlay', 'frameworks': ['tallstack', 'flux', 'daisy', 'gsmui'], 'variants': ['default', 'with-icons']},
            'context-menu': {'name': 'ContextMenu', 'type': 'overlay', 'frameworks': ['marry', 'gsmui'], 'variants': ['default', 'nested']},
            'resizable': {'name': 'Resizable', 'type': 'layout', 'frameworks': ['tallstack', 'flux', 'gsmui'], 'variants': ['default', 'vertical', 'horizontal']},
            'scroll-area': {'name': 'ScrollArea', 'type': 'layout', 'frameworks': ['tallstack', 'flux', 'gsmui'], 'variants': ['default', 'with-bar']},
            'sheet': {'name': 'Sheet', 'type': 'overlay', 'frameworks': ['tallstack', 'flux', 'marry', 'daisy', 'gsmui'], 'variants': ['bottom', 'top', 'left', 'right']},
        }
    
    def build_ui_library(self, frameworks: List[str], components: List[str],
                        theme: str = 'default', output_path: str = None,
                        include_comment_lib: bool = True) -> Dict[str, Any]:
        print(f"\n{'='*70}\n  ADVANCED UI LIBRARY BUILDER\n{'='*70}")
        print(f"Frameworks: {', '.join(frameworks)}\nComponents: {len(components) if components[0] != 'all' else 'all'}\nTheme: {theme}")
        
        build_dir = Path(output_path or self.output_dir) / f"ui-library-{theme}"
        build_dir.mkdir(parents=True, exist_ok=True)
        
        results = {'success': True, 'build_dir': str(build_dir), 'frameworks': {},
                   'components_built': [], 'errors': []}
        
        dirs = {'src': ['components', 'themes', 'utilities'], 'dist': ['css', 'js'],
                'docs': ['api'], 'examples': ['laravel', 'vue', 'react']}
        for b, sub in dirs.items():
            for s in sub:
                (build_dir / b / s).mkdir(parents=True, exist_ok=True)
        
        print(f"\n{'─'*70}\n  Shared Foundation\n{'─'*70}")
        self._build_shared(build_dir, theme)
        
        print(f"\n{'─'*70}\n  Framework Components\n{'─'*70}")
        for fw in frameworks:
            fw_comps = [c for c in components if c in self.ui_components and fw in self.ui_components[c]['frameworks']]
            if fw_comps:
                print(f"\n  {fw.upper()}")
                try:
                    self._build_fw(fw, build_dir / fw, fw_comps, theme)
                    results['frameworks'][fw] = {'status': 'success', 'components': fw_comps, 'path': str(build_dir / fw)}
                    results['components_built'].extend(fw_comps)
                except Exception as e:
                    print(f"    Error: {e}")
                    results['errors'].append(str(e))
                    results['frameworks'][fw] = {'status': 'error', 'error': str(e)}
        
        if include_comment_lib:
            print(f"\n  Comment Library")
            self._build_comments(build_dir, theme)
        
        results['components_built'] = list(set(results['components_built']))
        
        print(f"\n  Docs")
        self._build_docs(build_dir, results)
        
        self._create_meta(build_dir, frameworks, theme, results)
        
        print(f"\n{'='*70}\n  COMPLETE: {build_dir}\n  Frameworks: {len(results['frameworks'])}, Components: {len(results['components_built'])}\n{'='*70}")
        return results
    
    def _build_shared(self, dir_path: Path, theme: str):
        ad = dir_path / "src" / "themes"
        tc = {'default': {'primary': '#3b82f6', 'secondary': '#64748b', 'success': '#10b981', 'warning': '#f59e0b', 'danger': '#ef4444', 'light': '#f8fafc', 'dark': '#1e293b', 'border': '#e2e8f0', 'radius': '0.5rem'}, 'dark': {'primary': '#60a5fa', 'secondary': '#94a3b8', 'success': '#34d399', 'warning': '#fbbf24', 'danger': '#f87171', 'light': '#f8fafc', 'dark': '#0f172a', 'border': '#334155', 'radius': '0.5rem'}}.get(theme, {'primary': '#00d4ff', 'secondary': '#6366f1', 'success': '#39ff14', 'warning': '#ff6b35', 'danger': '#ff1744', 'light': '#0b0f19', 'dark': '#000000', 'border': '#1a1f2e', 'radius': '0.25rem'})
        t = f":root {{\n  --ui-primary: {tc['primary']}; --ui-secondary: {tc['secondary']}; --ui-success: {tc['success']};\n  --ui-warning: {tc['warning']}; --ui-danger: {tc['danger']}; --ui-light: {tc['light']}; --ui-dark: {tc['dark']};\n  --ui-border: {tc['border']}; --ui-radius: {tc['radius']};\n}}\n*{{box-sizing:border-box}}\n"
        with open(ad / "theme.css", 'w') as f:
            f.write(t)
        with open(dir_path / "src" / "utilities" / "base.css", 'w') as f:
            f.write(".m-0{margin:0}.m-1{margin:.25rem}.m-2{margin:.5rem}.m-3{margin:1rem}.p-0{padding:0}.p-1{padding:.25rem}.p-2{padding:.5rem}.p-3{padding:1rem}.flex{display:flex}.grid{display:grid}.w-full{width:100%}.h-full{height:100%}.rounded{border-radius:var(--ui-radius)}.shadow{box-shadow:0 1px 3px rgba(0,0,0,0.1)}.btn{display:inline-flex;align-items:center;justify-content:center;padding:.5rem 1rem;border-radius:var(--ui-radius);font-weight:500;transition:all .2s;cursor:pointer;border:none}.btn-primary{background:var(--ui-primary);color:#fff}.btn-primary:hover{background:var(--ui-primary);opacity:.9}.input{width:100%;padding:.5rem .75rem;border:1px solid var(--ui-border);border-radius:var(--ui-radius);transition:all .2s}.input:focus{outline:none;border-color:var(--ui-primary)}.card{background:var(--ui-light);border:1px solid var(--ui-border);border-radius:var(--ui-radius);padding:1.5rem;box-shadow:var(--shadow)}.modal{position:fixed;inset:0;z-index:50;overflow-y:auto;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;padding:1rem}.modal-content{background:var(--ui-light);border-radius:var(--ui-radius);padding:2rem;max-width:500px;width:100%}.comment-thread{max-width:800px}.comment{background:#fff;padding:1rem;margin-bottom:1rem;border-radius:.5rem;border:1px solid #e2e8f0}.comment-header{display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem}.comment-body{margin:.5rem 0;line-height:1.6}")
        with open(dir_path / "src" / "utilities" / "base.js", 'w') as f:
            f.write("class Component{constructor(el){this.el=el;this.init();}init(){}show(){this.el.classList.remove('hidden');}hide(){this.el.classList.add('hidden');}toggle(){this.el.classList.toggle('hidden');}}")
    
    def _build_fw(self, fw: str, dir_path: Path, components: List[str], theme: str):
        builders = {'tallstack': self._build_tallstack, 'flux': self._build_flux, 'marry': self._build_marry,
                    'daisy': self._build_daisy, 'tallstack-ui': self._build_tsui, 'filament': self._build_filament,
                    'gridcn': self._build_gridcn, 'gsmui': self._build_gsmui, 'comment-lib': self._build_comments_lib}
        if fw in builders:
            builders[fw](dir_path, components, theme)
    
    def _build_tallstack(self, d: Path, cs: List[str], t: str):
        (d / "Livewire/Components").mkdir(parents=True, exist_ok=True)
        (d / "Livewire/views").mkdir(parents=True, exist_ok=True)
        for c in cs:
            n = ''.join(w.capitalize() for w in c.split('-'))
            with open(d / "Livewire/Components" / f"{n}.php", 'w') as f:
                f.write(f'<?php\nnamespace App\\Livewire\\Components;\nuse Livewire\\Component;\nclass {n} extends Component {{\n  public function render() {{ return view(\'livewire.{c}-ts\'); }}\n}}\n')
            with open(d / "Livewire/views" / f"{c}-ts.blade.php", 'w') as f:
                f.write(f'<div class="{c} p-4 rounded border">\n  <!-- TallStack {self.ui_components[c]["name"]} -->\n  @if(isset($slot)){{ $slot }}@endif\n</div>\n')
        with open(d / "tailwind.config.js", 'w') as f:
            f.write("module.exports={content:['./src/**/*.{blade.php,html,js,vue}'],theme:{extend:{}},plugins:[]}\n")
    
    def _build_flux(self, d: Path, cs: List[str], t: str):
        (d / "src").mkdir(parents=True, exist_ok=True)
        for c in cs:
            with open(d / "src" / f"{c}.blade.php", 'w') as f:
                f.write(f'<!-- Flux {self.ui_components[c]["name"]} -->\n<div class="flux-{c} p-4 rounded border">\n  {{$slot ?? ""}}\n</div>\n')
        with open(d / "src" / "flux.css", 'w') as f:
            f.write(f'.flux-{cs[0] if cs else "component"}{{padding:1rem;border-radius:.5rem}}\n')
    
    def _build_marry(self, d: Path, cs: List[str], t: str):
        (d / "ui").mkdir(parents=True, exist_ok=True)
        for c in cs:
            n = "".join(w.capitalize() for w in c.split("-"))
            # Use simple string concatenation to avoid f-string JSX parsing issues
            jsx = "export const " + n + " = ({className,children,...props}) => "
            jsx += "(<div className={['" + c + "',className].join(' ')} {...props}>{children}</div>);"
            with open(d / "ui" / (c + ".jsx"), "w") as f:
                f.write(jsx + "\n")
    def _build_daisy(self, d: Path, cs: List[str], t: str):
        (d / "daisy").mkdir(parents=True, exist_ok=True)
        for c in cs:
            with open(d / "daisy" / f"{c}.html", 'w') as f:
                f.write(f'<!-- DaisyUI {self.ui_components[c]["name"]} -->\n<div class="daisy-{c}">\n  <div class="{{$class ?? ""}}">{{$slot ?? ""}}</div>\n</div>\n')
        with open(d / "daisy" / "daisy.css", 'w') as f:
            f.write('[data-theme="custom"]{--p:#3b82f6;--pf:#2563eb;--b1:#fff;--b2:#f1f5f9;--b3:#e2e8f0;--bc:#1e293b}\n')
    
    def _build_tsui(self, d: Path, cs: List[str], t: str):
        (d / "TallStackUI").mkdir(parents=True, exist_ok=True)
        for c in cs:
            n = ''.join(w.capitalize() for w in c.split('-'))
            with open(d / "TallStackUI" / f"{n}.php", 'w') as f:
                f.write(f'<?php\nnamespace TallStackUI\\Components;\nclass {n} extends \\TallStackUI\\BaseComponent {{\n  protected function setUp(): void {{ parent::setUp(); }}\n  protected function blade(): string {{ return \'tallstack-ui::{c}\'; }}\n}}\n')
    
    def _build_filament(self, d: Path, cs: List[str], t: str):
        (d / "Filament/Components").mkdir(parents=True, exist_ok=True)
        for c in cs:
            n = ''.join(w.capitalize() for w in c.split('-'))
            with open(d / "Filament/Components" / f"{n}.php", 'w') as f:
                f.write(f'<?php\nnamespace App\\Filament\\Components;\nuse Filament\\Forms\\Components\\Component;\nclass {n} extends Component {{\n  protected function setUp(): void {{ parent::setUp(); }}\n}}\n')
    
    def _build_gridcn(self, d: Path, cs: List[str], t: str):
        (d / "GridCN/src").mkdir(parents=True, exist_ok=True)
        with open(d / "GridCN/src" / "gridcn.css", 'w') as f:
            f.write('.gridcn-container{max-width:1200px;margin:0 auto;padding:0 15px}\n.gridcn-row{display:flex;flex-wrap:wrap;margin:0 -15px}\n.gridcn-col{flex:1;padding:0 15px}\n.col-1{flex:0 0 8.333333%;max-width:8.333333%}\n.col-2{flex:0 0 16.666667%;max-width:16.666667%}\n.col-3{flex:0 0 25%;max-width:25%}\n.col-4{flex:0 0 33.333333%;max-width:33.333333%}\n.col-6{flex:0 0 50%;max-width:50%}\n.col-12{flex:0 0 100%;max-width:100%}\n')
        for c in cs:
            with open(d / "GridCN/src" / f"{c}.css", 'w') as f:
                f.write(f'/* GridCN {self.ui_components[c]["name"]} */\n.{c}{{display:grid;gap:1rem}}\n')
    
    def _build_gsmui(self, d: Path, cs: List[str], t: str):
        (d / "GSMUI").mkdir(parents=True, exist_ok=True)
        for c in cs:
            n = "".join(w.capitalize() for w in c.split("-"))
            info = self.ui_components[c]
            # Simple string for JSX
            jsx = "export const " + n + " = ({className,children,...props}) => "
            jsx += "(<div className={'gsm-' + c + ' p-4 rounded border'} {...props}>{children}</div>);"
            content = "// GSMUI " + info["name"] + " - Multi-framework support\n"
            content += "// Blade\n<div class=\"gsm-" + c + " p-4 rounded border\">\n"
            content += "  @if(isset($slot)){{ $slot }}@endif\n</div>\n\n<!-- Vue -->\n"
            content += "<template>\n  <div class=\"gsm-" + c + " p-4 rounded border\">\n"
            content += "    <slot />\n  </div>\n</template>\n\n<!-- React -->\n"
            content += jsx + "\n"
            with open(d / "GSMUI" / (c + ".gsm"), "w") as f:
                f.write(content)
        # CSS
        css_path = d / "GSMUI" / "gsmui.css"
        with open(css_path, "w") as f:
            f.write(".gsm-card{background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,.1);transition:all .2s}\n.gsm-card:hover{box-shadow:0 10px 15px -3px rgba(0,0,0,.1);transform:translateY(-2px)}\n.gsm-btn{display:inline-flex;align-items:center;justify-content:center;padding:.5rem 1rem;border-radius:.5rem;font-weight:500;cursor:pointer;border:none;background:#3b82f6;color:#fff}\n.gsm-btn:hover{background:#2563eb}\n.gsm-input{width:100%;padding:.5rem .75rem;border:1px solid #cbd5e1;border-radius:.5rem;transition:all .2s}\n.gsm-input:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}\n.gsm-modal{position:fixed;inset:0;z-index:50;overflow-y:auto;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;padding:1rem}\n.gsm-modal-content{background:#fff;border-radius:.5rem;padding:2rem;max-width:500px;width:100%}\n.gsm-grid{display:grid;gap:1rem}\n.gsm-grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}\n.gsm-grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}\n.gsm-grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}\n.gsm-grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr))}\n.gsm-grid-cols-12{grid-template-columns:repeat(12,minmax(0,1fr))}\n")
    def _build_comments_lib(self, d: Path, cs: List[str], t: str):
        (d / "CommentLib").mkdir(parents=True, exist_ok=True)
        with open(d / "CommentLib" / "thread.vue", 'w') as f:
            f.write('<div class="comment-thread"><div v-for="c in comments" :key="c.id" class="comment"><div class="comment-header"><img :src="c.avatar" class="comment-avatar"><span class="comment-author">{{c.author}}</span><span class="comment-time">{{c.time}}</span></div><div class="comment-body">{{c.text}}</div><div class="comment-actions"><button @click="like(c)">Like({{c.likes}})</button><button @click="reply(c)">Reply</button></div><div v-if="c.replies" class="comment-replies"><comment-thread :comments="c.replies" /></div></div></div>\n')
        with open(d / "CommentLib" / "form.vue", 'w') as f:
            f.write('<div class="comment-form"><textarea v-model="text" placeholder="Write a comment..." rows="3"></textarea><div class="comment-form-actions"><button @click="attach">Attach</button><button @click="submit" :disabled="!text.trim()">Comment</button></div></div>\n')
    
    def _build_comments(self, d: Path, t: str):
        (d / "CommentLibrary").mkdir(parents=True, exist_ok=True)
        with open(d / "CommentLibrary" / "thread.vue", 'w') as f:
            f.write('<div class="comment-thread"><div class="comment"><div class="comment-header"><img src="/avatar.jpg" class="comment-avatar"><span class="comment-author">Author</span><span class="comment-time">2h ago</span></div><div class="comment-body">Comment text</div><div class="comment-actions"><button>Like</button><button>Reply</button></div><div class="comment-replies"><comment-thread :comments="replies" /></div></div></div>\n')
        with open(d / "CommentLibrary" / "form.vue", 'w') as f:
            f.write('<div class="comment-form"><textarea v-model="text" placeholder="Write a comment..." rows="3"></textarea><div class="comment-form-actions"><button @click="attach">Attach</button><button @click="submit" :disabled="!text.trim()">Comment</button></div></div>\n')
        with open(d / "CommentLibrary" / "comment.css", 'w') as f:
            f.write('.comment-thread{max-width:800px}.comment{background:#fff;padding:1rem;margin-bottom:1rem;border-radius:.5rem;border:1px solid #e2e8f0}.comment-header{display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem}.comment-avatar{width:32px;height:32px;border-radius:50%}.comment-author{font-weight:600}.comment-time{color:#64748b;font-size:.875rem}.comment-body{margin:.5rem 0;line-height:1.6}.comment-actions{display:flex;gap:1rem}.comment-replies{margin-top:1rem;padding-left:2rem;border-left:2px solid #e2e8f0}.comment-form textarea{width:100%;padding:.75rem;border:1px solid #cbd5e1;border-radius:.5rem;resize:vertical}.comment-form-actions{display:flex;justify-content:flex-end;gap:.5rem;margin-top:.5rem}\n')
    
    def _build_docs(self, d: Path, results: Dict):
        dd = d / "docs" / "api"
        ad = f"# API Documentation\n\n## Frameworks ({len(results['frameworks'])})\n"
        for fw, info in results['frameworks'].items():
            ad += f"- **{fw}**: {len(info['components'])} components\n"
        ad += "\n## Components (" + str(len(results['components_built'])) + ")\n"
        for c in sorted(set(results['components_built'])):
            if c in self.ui_components:
                i = self.ui_components[c]
                ad += f"\n### {i['name']}\n- Type: `{i['type']}`\n- Variants: {', '.join(i['variants'])}\n"
        with open(dd / "api.md", 'w') as f:
            f.write(ad)
    
    def _create_meta(self, d: Path, frameworks: List[str], theme: str, results: Dict):
        with open(d / "package.json", 'w') as f:
            json.dump({'name':f'ui-library-{theme}','version':'1.0.0','description':'Advanced multi-framework UI library','frameworks':frameworks,'components':len(results['components_built'])}, f, indent=2)
        with open(d / "README.md", 'w') as f:
            f.write(f"# UI Library - {theme.title()}\n\n{' '.join(f'**{fw}**' for fw in frameworks)}\n\n{len(results['components_built'])} components\n")
        with open(d / "manifest.json", 'w') as f:
            json.dump({'name':f'ui-library-{theme}','version':'1.0.0','frameworks':frameworks,'components':results['components_built'],'theme':theme}, f, indent=2)


def main():
    parser = argparse.ArgumentParser(description="Build advanced multi-framework UI libraries")
    af = ['tallstack', 'flux', 'marry', 'daisy', 'tallstack-ui', 'filament', 'gridcn', 'gsmui', 'comment-lib']
    parser.add_argument('-f', '--frameworks', nargs='+', default=af)
    parser.add_argument('-c', '--components', nargs='+', default=['all'])
    parser.add_argument('-t', '--theme', default='default', choices=['default', 'dark', 'gsmui'])
    parser.add_argument('-o', '--output', help='Output directory')
    parser.add_argument('--no-comment-lib', action='store_true')
    args = parser.parse_args()
    b = AdvancedUILibraryBuilder(args.output or '.')
    if 'all' in args.components or args.components == ['all']:
        args.components = list(b.ui_components.keys())
    try:
        r = b.build_ui_library(args.frameworks, args.components, args.theme, args.output, not args.no_comment_lib)
        sys.exit(0 if r['success'] else 1)
    except Exception as e:
        print(f"Fatal: {e}")
        import traceback
        traceback.print_exc()
        sys.exit(1)

if __name__ == '__main__':
    main()
