
@extends('layouts.admin')

@section('title', 'Forensic Log Viewer')

@section('content')
<div class="h-full flex flex-col bg-[#0B0F19]">
    <!-- Top Toolbar -->
    <div class="h-16 bg-[#131828] border-b border-[rgba(0,212,255,0.2)] flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 rounded bg-gradient-to-br from-[#39FF14] to-[#00D4FF]"></div>
            <span class="text-xl font-bold glow-green">Forensic Log Viewer</span>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-sm">
                <input type="text" placeholder="Search all logs..." class="input-glow text-sm w-64" />
                <button class="btn-secondary text-sm py-2 px-4">Search</button>
            </div>
            <select class="input-glow text-sm w-40">
                <option>Last 24 hours</option>
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Custom range</option>
            </select>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <!-- Sidebar Filters -->
        <div class="w-72 bg-[#131828] border-r border-[rgba(0,212,255,0.1)] p-4 overflow-y-auto">
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-300 mb-3 uppercase">Log Level</h4>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#00D4FF]">
                        <input type="checkbox" checked class="accent-[#00D4FF]" />
                        <span class="text-sm">ERROR <span class="text-[#EF4444]">(42)</span></span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#F59E0B]">
                        <input type="checkbox" checked class="accent-[#F59E0B]" />
                        <span class="text-sm">WARN <span class="text-[#F59E0B]">(128)</span></span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#39FF14]">
                        <input type="checkbox" checked class="accent-[#39FF14]" />
                        <span class="text-sm">INFO <span class="text-[#39FF14]">(1,245)</span></span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-gray-400">
                        <input type="checkbox" class="accent-gray-400" />
                        <span class="text-sm">DEBUG <span class="text-gray-400">(0)</span></span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-300 mb-3 uppercase">Source</h4>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#00D4FF]">
                        <input type="checkbox" checked class="accent-[#00D4FF]" />
                        <span class="text-sm">GSM Modem</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#39FF14]">
                        <input type="checkbox" checked class="accent-[#39FF14]" />
                        <span class="text-sm">Network Scanner</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#6366F1]">
                        <input type="checkbox" checked class="accent-[#6366F1]" />
                        <span class="text-sm">Forensic Tool</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-300 mb-3 uppercase">Tags</h4>
                <div class="flex flex-wrap gap-2">
                    <span class="badge badge-premium cursor-pointer">Authentication</span>
                    <span class="badge badge-free cursor-pointer">Network</span>
                    <span class="badge badge-premium cursor-pointer">Encryption</span>
                    <span class="badge badge-free cursor-pointer">SMS</span>
                </div>
            </div>

            <div>
                <button class="w-full btn-secondary text-sm py-2">Reset Filters</button>
            </div>
        </div>

        <!-- Main Content - Split View -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Datagrid -->
            <div class="flex-1 overflow-hidden flex flex-col">
                <div class="glass-card m-4 flex-1 flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-[rgba(0,212,255,0.1)]">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Log Entries: <span class="text-[#00D4FF] font-semibold">1,415</span></span>
                            <div class="flex gap-2">
                                <button class="btn-secondary text-sm py-1 px-3">Export CSV</button>
                                <button class="btn-primary text-sm py-1 px-3">Export JSON</button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 overflow-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Level</th>
                                    <th>Source</th>
                                    <th>Message</th>
                                    <th>Thread ID</th>
                                </tr>
                            </thead>
                            <tbody id="log-table-body">
                                <tr class="hover:bg-[rgba(0,212,255,0.05)] cursor-pointer">
                                    <td class="font-mono text-xs text-gray-500">14:32:18.245</td>
                                    <td><span class="badge badge-free">INFO</span></td>
                                    <td class="text-[#39FF14]">GSM Modem</td>
                                    <td class="text-gray-300">Connection established on /dev/ttyUSB0</td>
                                    <td class="font-mono text-xs text-gray-500">0x7F2A</td>
                                </tr>
                                <tr class="hover:bg-[rgba(0,212,255,0.05)] cursor-pointer">
                                    <td class="font-mono text-xs text-gray-500">14:32:19.102</td>
                                    <td><span class="badge badge-premium">WARN</span></td>
                                    <td class="text-[#00D4FF]">Network Scanner</td>
                                    <td class="text-gray-300">Port scan detected on 192.168.1.100</td>
                                    <td class="font-mono text-xs text-gray-500">0x7F2B</td>
                                </tr>
                                <tr class="hover:bg-[rgba(0,212,255,0.05)] cursor-pointer">
                                    <td class="font-mono text-xs text-gray-500">14:32:20.001</td>
                                    <td><span class="badge badge-free">ERROR</span></td>
                                    <td class="text-[#6366F1]">Forensic Tool</td>
                                    <td class="text-gray-300">Failed to decrypt data block - wrong key?</td>
                                    <td class="font-mono text-xs text-gray-500">0x7F2C</td>
                                </tr>
                                <tr class="hover:bg-[rgba(0,212,255,0.05)] cursor-pointer">
                                    <td class="font-mono text-xs text-gray-500">14:32:21.445</td>
                                    <td><span class="badge badge-free">INFO</span></td>
                                    <td class="text-[#39FF14]">GSM Modem</td>
                                    <td class="text-gray-300">SMS received from +1234567890: "Test message"</td>
                                    <td class="font-mono text-xs text-gray-500">0x7F2D</td>
                                </tr>
                                <tr class="hover:bg-[rgba(0,212,255,0.05)] cursor-pointer">
                                    <td class="font-mono text-xs text-gray-500">14:32:22.890</td>
                                    <td><span class="badge badge-premium">WARN</span></td>
                                    <td class="text-[#00D4FF]">Network Scanner</td>
                                    <td class="text-gray-300">Unusual traffic pattern detected on port 443</td>
                                    <td class="font-mono text-xs text-gray-500">0x7F2E</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evidence Timeline Panel -->
        <div class="w-80 bg-[#131828] border-l border-[rgba(0,212,255,0.1)] overflow-y-auto">
            <div class="p-4 border-b border-[rgba(0,212,255,0.1)]">
                <h3 class="text-sm font-semibold text-[#39FF14]">Evidence Timeline</h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#EF4444] mt-1"></div>
                    <div>
                        <div class="text-xs text-gray-400">14:32:20</div>
                        <div class="text-sm">Decryption Failed</div>
                        <div class="text-xs text-gray-500">Wrong encryption key</div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#F59E0B] mt-1"></div>
                    <div>
                        <div class="text-xs text-gray-400">14:32:19</div>
                        <div class="text-sm">Port Scan Detected</div>
                        <div class="text-xs text-gray-500">192.168.1.100</div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#39FF14] mt-1"></div>
                    <div>
                        <div class="text-xs text-gray-400">14:32:01</div>
                        <div class="text-sm">Device Connected</div>
                        <div class="text-xs text-gray-500">Device Alpha</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
