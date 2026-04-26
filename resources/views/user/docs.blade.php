
@extends('layouts.app')

@section('title', 'Documentation')

@section('content')
<div class="min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="glass-card p-6 sticky top-4">
                    <h3 class="text-xl font-bold mb-4">Documentation</h3>
                    <nav class="space-y-2">
                        <a href="#getting-started" class="nav-link block py-2">Getting Started</a>
                        <a href="#installation" class="nav-link block py-2">Installation</a>
                        <a href="#cli" class="nav-link block py-2">CLI Tool</a>
                        <a href="#components" class="nav-link block py-2">Components</a>
                        <a href="#api" class="nav-link block py-2">API Reference</a>
                        <a href="#theme" class="nav-link block py-2">Theme Engine</a>
                        <a href="#templates" class="nav-link block py-2">Templates</a>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-3">
                <!-- Getting Started -->
                <section id="getting-started" class="mb-16">
                    <h1 class="text-4xl font-bold mb-6">Getting Started</h1>
                    <div class="glass-card p-8 mb-8">
                        <h2 class="text-2xl font-bold mb-4 text-[#00D4FF]">What is GSM-UI?</h2>
                        <p class="text-gray-300 mb-6">
                            GSM-UI is a premium UI component library and admin template SaaS platform designed specifically for data-heavy, GSM/Forensic web applications.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-6 bg-[rgba(0,212,255,0.1)] rounded-xl">
                                <div class="text-3xl mb-2">🎨</div>
                                <h3 class="font-bold mb-2">50+ Components</h3>
                                <p class="text-sm text-gray-400">Pre-built UI components for forensic interfaces</p>
                            </div>
                            <div class="p-6 bg-[rgba(57,255,20,0.1)] rounded-xl">
                                <div class="text-3xl mb-2">📄</div>
                                <h3 class="font-bold mb-2">10+ Templates</h3>
                                <p class="text-sm text-gray-400">Ready-to-use admin dashboard layouts</p>
                            </div>
                            <div class="p-6 bg-[rgba(99,102,241,0.1)] rounded-xl">
                                <div class="text-3xl mb-2">🔌</div>
                                <h3 class="font-bold mb-2">CLI Tool</h3>
                                <p class="text-sm text-gray-400">Download components directly to your project</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Installation -->
                <section id="installation" class="mb-16">
                    <h2 class="text-3xl font-bold mb-6 text-[#39FF14]">Installation</h2>
                    <div class="glass-card p-8 mb-6">
                        <h3 class="text-xl font-bold mb-4">Prerequisites</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-300 mb-6">
                            <li>PHP 8.2 or higher</li>
                            <li>Composer</li>
                            <li>MySQL 8.0+</li>
                            <li>Node.js 18+</li>
                            <li>Stripe or Paddle account (for payments)</li>
                        </ul>

                        <h3 class="text-xl font-bold mb-4">Quick Start</h3>
                        <div class="bg-[#0d1117] rounded-lg p-6 font-mono text-sm overflow-x-auto">
                            <div class="text-[#61AFEF]"># Clone and install dependencies</div>
                            <div class="mb-2">git clone https://github.com/your-repo/gsm-ui.git</div>
                            <div class="mb-2">cd gsm-ui</div>
                            <div class="mb-4">composer install</div>
                            
                            <div class="text-[#61AFEF]"># Install npm packages</div>
                            <div class="mb-4">npm install</div>
                            
                            <div class="text-[#61AFEF]"># Copy environment file</div>
                            <div class="mb-4">cp .env.example .env</div>
                            
                            <div class="text-[#61AFEF"># Configure database</div>
                            <div class="mb-4">nano .env</div>
                            
                            <div class="text-[#61AFEF"]># Run migrations and seeders</div>
                            <div class="mb-4">php artisan migrate --seed</div>
                            
                            <div class="text-[#61AFEF"]># Generate application key</div>
                            <div class="mb-4">php artisan key:generate</div>
                            
                            <div class="text-[#61AFEF"]># Install Filament</div>
                            <div class="mb-4">php artisan filament:install</div>
                            
                            <div class="text-[#61AFEF"]># Create admin user</div>
                            <div class="mb-4">php artisan make:filament-user</div>
                            
                            <div class="text-[#61AFEF"]># Build frontend assets</div>
                            <div class="mb-2">npm run build</div>
                        </div>
                    </div>
                </section>

                <!-- CLI Tool -->
                <section id="cli" class="mb-16">
                    <h2 class="text-3xl font-bold mb-6 text-[#6366F1]">CLI Tool</h2>
                    <div class="glass-card p-8 mb-6">
                        <h3 class="text-xl font-bold mb-4">Usage</h3>
                        <p class="text-gray-300 mb-6">
                            The <code class="bg-[#0d1117] px-2 py-1 rounded">gsm:add</code> command downloads components directly to your Laravel project.
                        </p>
                        
                        <div class="bg-[#0d1117] rounded-lg p-6 font-mono text-sm mb-6">
                            <div class="text-[#F59E0B]"># Basic usage</div>
                            <div class="mb-2">php artisan gsm:add data-grid-pro</div>
                            
                            <div class="text-[#F59E0B] mt-4"># With personal access token</div>
                            <div class="mb-2">php artisan gsm:add data-grid-pro --token=pat_xxx</div>
                            
                            <div class="text-[#F59E0B] mt-4"># Set token in .env</div>
                            <div class="mb-2">GSM_TOKEN=pat_xxx</div>
                        </div>

                        <h3 class="text-xl font-bold mb-4">Authentication</h3>
                        <p class="text-gray-300 mb-4">
                            Generate a Personal Access Token in your account settings, then either:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-300 mb-6">
                            <li>Pass it as an argument: <code class="bg-[#0d1117] px-1">--token=pat_xxx</code></li>
                            <li>Set it in your .env: <code class="bg-[#0d1117] px-1">GSM_TOKEN=pat_xxx</code></li>
                        </ul>

                        <h3 class="text-xl font-bold mb-4">Access Control</h3>
                        <p class="text-gray-300 mb-4">
                            The CLI will return a <code class="bg-[#0d1117] px-1">403</code> error if you try to download a premium component without:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-300">
                            <li>An active Pro subscription, OR</li>
                            <li>A one-time purchase of that component</li>
                        </ul>
                    </div>
                </section>

                <!-- Components -->
                <section id="components" class="mb-16">
                    <h2 class="text-3xl font-bold mb-6 text-[#00D4FF]">Components</h2>
                    <div class="glass-card p-8">
                        <h3 class="text-xl font-bold mb-4">Categories</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="p-4 bg-[rgba(0,212,255,0.05)] rounded-lg">
                                <h4 class="font-bold text-[#00D4FF] mb-2">Data Display</h4>
                                <p class="text-sm text-gray-400">Tables, grids, cards, and data visualization components.</p>
                            </div>
                            <div class="p-4 bg-[rgba(57,255,20,0.05)] rounded-lg">
                                <h4 class="font-bold text-[#39FF14] mb-2">Filters</h4>
                                <p class="text-sm text-gray-400">Search, multi-select, date pickers, and filter controls.</p>
                            </div>
                            <div class="p-4 bg-[rgba(99,102,241,0.05)] rounded-lg">
                                <h4 class="font-bold text-[#6366F1] mb-2">Actions</h4>
                                <p class="text-sm text-gray-400">Dropdowns, buttons, bulk actions, and export tools.</p>
                            </div>
                            <div class="p-4 bg-[rgba(245,158,11,0.05)] rounded-lg">
                                <h4 class="font-bold text-[#F59E0B] mb-2">Feedback</h4>
                                <p class="text-sm text-gray-400">Alerts, badges, status indicators, and notifications.</p>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold mb-4">Example Usage</h3>
                        <div class="bg-[#0d1117] rounded-lg p-6 font-mono text-sm overflow-x-auto">
                            <div class="text-[#61AFEF]">&lt;!-- Data Grid Component --&gt;</div>
                            <div class="mb-2">&lt;x-gsm.data-display.table </div>
                            <div class="mb-2">  :headers="$headers" </div>
                            <div class="mb-2">  :items="$items" </div>
                            <div class="mb-2">  :paginator="$items" </div>
                            <div class="mb-2"&gt;</div>
                            <div class="mb-2">  &lt;!-- Custom actions --&gt;</div>
                            <div class="mb-2">  &lt;x-slot name="actions"&gt;</div>
                            <div class="mb-2">    &lt;x-gsm.actions.dropdown&gt;</div>
                            <div class="mb-2">      &lt;!-- Dropdown items --&gt;</div>
                            <div class="mb-2">    &lt;/x-gsm.actions.dropdown&gt;</div>
                            <div class="mb-2">  &lt;/x-slot&gt;</div>
                            <div class="mb-2">&lt;/x-gsm.data-display.table&gt;</div>
                        </div>
                    </div>
                </section>

                <!-- API Reference -->
                <section id="api" class="mb-16">
                    <h2 class="text-3xl font-bold mb-6 text-[#F59E0B]">API Reference</h2>
                    <div class="glass-card p-8">
                        <h3 class="text-xl font-bold mb-4">Base URL</h3>
                        <p class="text-gray-300 mb-6">
                            <code class="bg-[#0d1117] px-2 py-1 rounded">https://your-domain.com/api/v1</code>
                        </p>

                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-left">Endpoint</th>
                                        <th class="text-left">Method</th>
                                        <th class="text-left">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="font-mono text-sm">
                                    <tr>
                                        <td class="py-3">/components</td>
                                        <td class="py-3">GET</td>
                                        <td class="py-3">List all components</td>
                                    </tr>
                                    <tr>
                                        <td>/components/&#123;slug&#125;</td>
                                        <td>GET</td>
                                        <td>Get component details</td>
                                    </tr>
                                    <tr>
                                        <td>/components/&#123;slug&#125;/download</td>
                                        <td>GET</td>
                                        <td>Download component (CLI)</td>
                                    </tr>
                                    <tr>
                                        <td>/templates</td>
                                        <td>GET</td>
                                        <td>List all templates</td>
                                    </tr>
                                    <tr>
                                        <td>/templates/&#123;slug&#125;</td>
                                        <td>GET</td>
                                        <td>Get template details</td>
                                    </tr>
                                    <tr>
                                        <td>/purchases</td>
                                        <td>POST</td>
                                        <td>Create a purchase</td>
                                    </tr>
                                    <tr>
                                        <td>/analytics/dashboard</td>
                                        <td>GET</td>
                                        <td>Get dashboard metrics</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- Theme Engine -->
                <section id="theme" class="mb-16">
                    <h2 class="text-3xl font-bold mb-6 text-[#39FF14]">Theme Engine</h2>
                    <div class="glass-card p-8">
                        <p class="text-gray-300 mb-6">
                            Customize the Midnight Electric theme through the admin panel or directly in the database.
                        </p>
                        
                        <h3 class="text-xl font-bold mb-4">Available Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-[rgba(0,212,255,0.05)] rounded-lg">
                                <code class="text-[#00D4FF]">theme_primary_color</code>
                                <p class="text-sm text-gray-400 mt-1">Default: #00D4FF</p>
                            </div>
                            <div class="p-4 bg-[rgba(57,255,20,0.05)] rounded-lg">
                                <code class="text-[#39FF14]">theme_secondary_color</code>
                                <p class="text-sm text-gray-400 mt-1">Default: #39FF14</p>
                            </div>
                            <div class="p-4 bg-[rgba(99,102,241,0.05)] rounded-lg">
                                <code class="text-[#6366F1]">theme_accent_color</code>
                                <p class="text-sm text-gray-400 mt-1">Default: #6366F1</p>
                            </div>
                            <div class="p-4 bg-[rgba(11,15,25,0.5)] rounded-lg">
                                <code class="text-[#0B0F19]">theme_background_primary</code>
                                <p class="text-sm text-gray-400 mt-1">Default: #0B0F19</p>
                            </div>
                            <div class="p-4 bg-[rgba(19,24,40,0.5)] rounded-lg">
                                <code class="text-[#131828]">theme_background_card</code>
                                <p class="text-sm text-gray-400 mt-1">Default: rgba(19,24,40,0.9)</p>
                            </div>
                            <div class="p-4 bg-[rgba(245,158,11,0.1)] rounded-lg">
                                <code class="text-[#F59E0B]">theme_glow_intensity</code>
                                <p class="text-sm text-gray-400 mt-1">Default: medium</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Templates -->
                <section id="templates" class="mb-16">
                    <h2 class="text-3xl font-bold mb-6">Template Gallery</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="glass-card p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-[#00D4FF]">GSM Flasher</h3>
                                <span class="badge badge-premium">$199.99</span>
                            </div>
                            <p class="text-gray-400 text-sm mb-4">Terminal-based dashboard for device flashing operations with real-time progress monitoring.</p>
                            <a href="/templates/gsm-flasher" class="text-[#00D4FF] hover:text-[#39FF14]">View Template →</a>
                        </div>
                        <div class="glass-card p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-[#39FF14]">Forensic Viewer</h3>
                                <span class="badge badge-premium">$149.99</span>
                            </div>
                            <p class="text-gray-400 text-sm mb-4">Full-width datagrid optimized for log analysis with evidence tagging capabilities.</p>
                            <a href="/templates/forensic-viewer" class="text-[#39FF14] hover:text-[#00D4FF]">View Template →</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
