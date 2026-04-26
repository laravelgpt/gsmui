<?php

namespace Database\Seeders;

use App\Models\Component;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GSMUIDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gsm-ui.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'subscription_status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create regular users
        User::factory(50)->create();

        // Seed Components
        $components = [
            [
                'name' => 'Data Grid Pro',
                'slug' => 'data-grid-pro',
                'description' => 'Advanced data grid with sorting, filtering, pagination, and inline editing capabilities. Perfect for forensic data analysis interfaces.',
                'category' => 'data-display',
                'type' => 'premium',
                'code_snippet' => $this->getDataGridCode(),
                'preview_html' => $this->getDataGridPreview(),
                'metadata' => json_encode(['price' => 49.99, 'version' => '2.1.0', 'downloads' => 0]),
            ],
            [
                'name' => 'Log Viewer Panel',
                'slug' => 'log-viewer-panel',
                'description' => 'Real-time log viewer with syntax highlighting, filtering, and export capabilities for GSM forensic data.',
                'category' => 'data-display',
                'type' => 'free',
                'code_snippet' => $this->getLogViewerCode(),
                'preview_html' => $this->getLogViewerPreview(),
                'metadata' => json_encode(['price' => 0, 'version' => '1.5.0', 'downloads' => 0]),
            ],
            [
                'name' => 'Multi-Select Filter',
                'slug' => 'multi-select-filter',
                'description' => 'Advanced filter component with search, multi-select, and tag-based filtering for complex data queries.',
                'category' => 'filters',
                'type' => 'premium',
                'code_snippet' => $this->getMultiSelectFilterCode(),
                'preview_html' => $this->getMultiSelectFilterPreview(),
                'metadata' => json_encode(['price' => 29.99, 'version' => '1.8.0', 'downloads' => 0]),
            ],
            [
                'name' => 'Action Dropdown',
                'slug' => 'action-dropdown',
                'description' => 'Context-aware action dropdown with icons, keyboard navigation, and bulk action support.',
                'category' => 'actions',
                'type' => 'free',
                'code_snippet' => $this->getActionDropdownCode(),
                'preview_html' => $this->getActionDropdownPreview(),
                'metadata' => json_encode(['price' => 0, 'version' => '1.2.0', 'downloads' => 0]),
            ],
            [
                'name' => 'GSM Status Indicator',
                'slug' => 'gsm-status-indicator',
                'description' => 'Dynamic status indicator with pulsing animation for GSM signal strength and connection status.',
                'category' => 'feedback',
                'type' => 'premium',
                'code_snippet' => $this->getStatusIndicatorCode(),
                'preview_html' => $this->getStatusIndicatorPreview(),
                'metadata' => json_encode(['price' => 19.99, 'version' => '1.0.0', 'downloads' => 0]),
            ],
            [
                'name' => 'Date Range Picker',
                'slug' => 'date-range-picker',
                'description' => 'Intelligent date range picker with presets for forensic time analysis and custom range selection.',
                'category' => 'filters',
                'type' => 'free',
                'code_snippet' => $this->getDateRangePickerCode(),
                'preview_html' => $this->getDateRangePickerPreview(),
                'metadata' => json_encode(['price' => 0, 'version' => '1.3.0', 'downloads' => 0]),
            ],
            [
                'name' => 'Data Export Button',
                'slug' => 'data-export-button',
                'description' => 'Multi-format export button supporting CSV, JSON, PDF, and Excel formats with progress indicators.',
                'category' => 'actions',
                'type' => 'premium',
                'code_snippet' => $this->getDataExportCode(),
                'preview_html' => $this->getDataExportPreview(),
                'metadata' => json_encode(['price' => 34.99, 'version' => '2.0.0', 'downloads' => 0]),
            ],
            [
                'name' => 'Alert Banner',
                'slug' => 'alert-banner',
                'description' => 'Dismissible alert banners with multiple severity levels and animated transitions.',
                'category' => 'feedback',
                'type' => 'free',
                'code_snippet' => $this->getAlertBannerCode(),
                'preview_html' => $this->getAlertBannerPreview(),
                'metadata' => json_encode(['price' => 0, 'version' => '1.1.0', 'downloads' => 0]),
            ],
        ];

        foreach ($components as $componentData) {
            Component::create($componentData);
        }

        // Seed Templates
        $templates = [
            [
                'name' => 'GSM Flasher Dashboard',
                'slug' => 'gsm-flasher-dashboard',
                'description' => 'Complete dashboard template for GSM flasher operations with sidebar navigation, terminal output panel, and device monitoring widgets.',
                'type' => 'premium',
                'preview_html' => $this->getFlasherTemplatePreview(),
                'metadata' => json_encode([
                    'price' => 199.99,
                    'version' => '1.0.0',
                    'features' => ['Terminal Output', 'Device Monitor', 'Flash Progress', 'Log Viewer'],
                    'screenshots' => []
                ]),
            ],
            [
                'name' => 'Forensic Log Viewer',
                'slug' => 'forensic-log-viewer',
                'description' => 'Full-width forensic log analysis template with advanced datagrid, timeline view, and evidence tagging system.',
                'type' => 'premium',
                'preview_html' => $this->getForensicTemplatePreview(),
                'metadata' => json_encode([
                    'price' => 149.99,
                    'version' => '1.0.0',
                    'features' => ['Full-width Datagrid', 'Timeline View', 'Evidence Tags', 'Export Tools'],
                    'screenshots' => []
                ]),
            ],
            [
                'name' => 'Server Node Monitor',
                'slug' => 'server-node-monitor',
                'description' => 'Grid-based server monitoring template with circular progress indicators, real-time sparklines, and status overview cards.',
                'type' => 'premium',
                'preview_html' => $this->getServerMonitorTemplatePreview(),
                'metadata' => json_encode([
                    'price' => 179.99,
                    'version' => '1.0.0',
                    'features' => ['Circular Progress', 'Sparkline Charts', 'Node Grid', 'Real-time Updates'],
                    'screenshots' => []
                ]),
            ],
            [
                'name' => 'Network Scanner Interface',
                'slug' => 'network-scanner-interface',
                'description' => 'Comprehensive network scanning dashboard with host discovery, port visualization, and vulnerability assessment panels.',
                'type' => 'premium',
                'preview_html' => $this->getNetworkScannerPreview(),
                'metadata' => json_encode([
                    'price' => 169.99,
                    'version' => '1.0.0',
                    'features' => ['Host Discovery', 'Port Visualization', 'Vulnerability Panel', 'Scan History'],
                    'screenshots' => []
                ]),
            ],
            [
                'name' => 'Evidence Management System',
                'slug' => 'evidence-management-system',
                'description' => 'Digital evidence tracking template with case management, file cataloging, and chain of custody documentation.',
                'type' => 'premium',
                'preview_html' => $this->getEvidenceMgmtPreview(),
                'metadata' => json_encode([
                    'price' => 189.99,
                    'version' => '1.0.0',
                    'features' => ['Case Management', 'File Catalog', 'Custody Chain', 'Audit Trail'],
                    'screenshots' => []
                ]),
            ],
            [
                'name' => 'GSM Signal Analyzer',
                'slug' => 'gsm-signal-analyzer',
                'description' => 'Real-time GSM signal analysis dashboard with spectrum visualization, strength meters, and carrier information panels.',
                'type' => 'premium',
                'preview_html' => $this->getSignalAnalyzerPreview(),
                'metadata' => json_encode([
                    'price' => 159.99,
                    'version' => '1.0.0',
                    'features' => ['Spectrum View', 'Strength Meters', 'Carrier Info', 'Signal Map'],
                    'screenshots' => []
                ]),
            ],
            [
                'name' => 'Incident Response Console',
                'slug' => 'incident-response-console',
                'description' => 'Unified incident response template with ticketing system, team collaboration, and timeline reconstruction tools.',
                'type' => 'premium',
                'preview_html' => $this->getIncidentResponsePreview(),
                'metadata' => json_encode([
                    'price' => 209.99,
                    'version' => '1.0.0',
                    'features' => ['Ticketing System', 'Team Chat', 'Timeline Builder', 'Report Generator'],
                    'screenshots' => []
                ]),
            ],
            [
                'name' => 'Data Breach Analyzer',
                'slug' => 'data-breach-analyzer',
                'description' => 'Breach impact assessment template with affected systems mapping, data classification, and notification workflows.',
                'type' => 'premium',
                'preview_html' => $this->getDataBreachPreview(),
                'metadata' => json_encode([
                    'price' => 199.99,
                    'version' => '1.0.0',
                    'features' => ['Impact Mapping', 'Data Classification', 'Notification Flow', 'Compliance Check'],
                    'screenshots' => []
                ]),
            ],
            [
                'name' => 'Mobile Forensics Workstation',
                'slug' => 'mobile-forensics-workstation',
                'description' => 'Mobile device forensics template with extraction progress, data categorization, and report generation panels.',
                'type' => 'premium',
                'preview_html' => $this->getMobileForensicsPreview(),
                'metadata' => json_encode([
                    'price' => 229.99,
                    'version' => '1.0.0',
                    'features' => ['Extraction Monitor', 'Data Categorizer', 'Report Builder', 'Device Info'],
                    'screenshots' => []
                ]),
            ],
            [
                'name' => 'Security Operations Center',
                'slug' => 'soc-dashboard',
                'description' => 'Enterprise SOC dashboard template with threat intelligence feeds, alerts aggregation, and response coordination tools.',
                'type' => 'premium',
                'preview_html' => $this->getSOCPreview(),
                'metadata' => json_encode([
                    'price' => 299.99,
                    'version' => '1.0.0',
                    'features' => ['Threat Feeds', 'Alert Aggregation', 'Response Coordination', 'Metrics'],
                    'screenshots' => []
                ]),
            ],
        ];

        foreach ($templates as $templateData) {
            Template::create($templateData);
        }
    }

    private function getDataGridCode(): string
    {
        return <<<'BLADE'
<x-gsm.data-display.table :headers="$headers" :items="$items" :paginator="$items" :filters="$filters">
    <!-- Custom column slot -->
    <x-slot name="actions" slot="actions">
        <x-gsm.actions.dropdown>
            <x-slot name="trigger">
                <x-gsm.buttons.icon icon="heroicon-c-ellipsis-vertical" />
            </x-slot>
            <x-gsm.actions.item icon="heroicon-c-eye" label="View" />
            <x-gsm.actions.item icon="heroicon-c-pencil" label="Edit" />
            <x-gsm.actions.item icon="heroicon-c-trash" label="Delete" destructive />
        </x-gsm.actions.dropdown>
    </x-slot>
</x-gsm.data-display.table>
BLADE;
    }

    private function getDataGridPreview(): string
    {
        return '<div class="gsm-datagrid"><table><thead><tr><th>ID</th><th>Name</th><th>Status</th><th>Actions</th></tr></thead><tbody><tr class="active"><td>001</td><td>Device Alpha</td><td><span class="badge-success">Online</span></td><td><button class="btn-icon">···</button></td></tr><tr class="inactive"><td>002</td><td>Device Beta</td><td><span class="badge-warning">Offline</span></td><td><button class="btn-icon">···</button></td></tr></tbody></table><div class="paginator"><span>Page 1 of 5</span><nav><button>1</button><button>2</button><button>3</button></nav></div></div>';
    }

    private function getLogViewerCode(): string
    {
        return <<<'BLADE'
<x-gsm.log-viewer :logs="$logs" :realtime="true">
    <div class="log-controls">
        <x-gsm.filters.date-range />
        <x-gsm.filters.multi-select :options="$logLevels" />
        <x-gsm.buttons.export formats="['csv','json']" />
    </div>
</x-gsm.log-viewer>
BLADE;
    }

    private function getLogViewerPreview(): string
    {
        return '<div class="log-viewer"><div class="log-header"><span class="log-level error">ERROR</span><span class="log-time">14:32:18</span><span class="log-source">gsm-modem</span></div><div class="log-message">Connection timeout on device /dev/ttyUSB0</div><div class="log-stack">at ModemService::connect()<br>Connection refused: timeout after 5000ms</div></div><div class="log-entry"><span class="log-level warn">WARN</span><span class="log-time">14:32:15</span><span class="log-source">gsm-flusher</span><div class="log-message">Retrying connection attempt 3/5</div></div>';
    }

    private function getMultiSelectFilterCode(): string
    {
        return <<<'BLADE'
<x-gsm.filters.multi-select
    :options="$categories"
    :selected="$selectedCategories"
    searchable
    placeholder="Filter by category..."
    @change="filterCategories" />
BLADE;
    }

    private function getMultiSelectFilterPreview(): string
    {
        return '<div class="multi-filter"><div class="search-box"><input placeholder="Search categories..." /><span class="search-icon">🔍</span></div><div class="selected-tags"><span class="tag">GSM <button class="remove">×</button></span><span class="tag">Forensic <button class="remove">×</button></span></div><div class="options-list"><label class="option"><input type="checkbox" /> Network Scanning</label><label class="option"><input type="checkbox" checked /> Data Analysis</label><label class="option"><input type="checkbox" /> Evidence Collection</label></div></div>';
    }

    private function getActionDropdownCode(): string
    {
        return <<<'BLADE'
<x-gsm.actions.dropdown trigger-class="btn-icon">
    <x-slot name="trigger">
        <x-gsm.buttons.icon icon="heroicon-c-ellipsis-horizontal" />
    </x-slot>
    <x-gsm.actions.item icon="heroicon-c-play" label="Start Analysis" />
    <x-gsm.actions.item icon="heroicon-c-pause" label="Pause" />
    <x-gsm.actions.item icon="heroicon-c-stop" label="Stop" />
    <x-gsm.actions.separator />
    <x-gsm.actions.item icon="heroicon-c-download" label="Export Results" />
</x-gsm.actions.dropdown>
BLADE;
    }

    private function getActionDropdownPreview(): string
    {
        return '<div class="action-dropdown"><button class="trigger">⋯</button><div class="dropdown-menu"><div class="item"><span class="icon">▶</span> Start Analysis</div><div class="item"><span class="icon">⏸</span> Pause</div><div class="item"><span class="icon">⏹</span> Stop</div><div class="separator"></div><div class="item"><span class="icon">⬇</span> Export</div></div></div>';
    }

    private function getStatusIndicatorCode(): string
    {
        return <<<'BLADE'
<div class="gsm-status-grid">
    <x-gsm.indicator type="signal" :strength="$signalStrength" pulse />
    <x-gsm.indicator type="connection" :status="$connectionStatus" />
    <x-gsm.indicator type="battery" :level="$batteryLevel" />
</div>
BLADE;
    }

    private function getStatusIndicatorPreview(): string
    {
        return '<div class="status-grid"><div class="indicator signal"><span class="pulse-dot" style="background:#00D4FF"></span><span class="label">Signal: 4/5</span></div><div class="indicator connection"><span class="dot connected"></span><span class="label">Connected</span></div><div class="indicator battery"><div class="battery-bar"><div style="width:78%"></div></div><span class="label">78%</span></div></div>';
    }

    private function getDateRangePickerCode(): string
    {
        return <<<'BLADE'
<x-gsm.filters.date-range
    :initial-range="['2024-01-01', '2024-01-31']"
    presets="['today','yesterday','last7','last30','custom']"
    @change="filterByDate" />
BLADE;
    }

    private function getDateRangePickerPreview(): string
    {
        return '<div class="date-picker"><div class="presets"><button class="active">Today</button><button>Yesterday</button><button>Last 7d</button><button>Last 30d</button><button>Custom</button></div><div class="inputs"><input type="date" value="2024-01-01" /><span>to</span><input type="date" value="2024-01-31" /></div></div>';
    }

    private function getDataExportCode(): string
    {
        return <<<'BLADE'
<x-gsm.buttons.export
    :formats="['csv','json','excel','pdf']"
    :filename="'forensic-export-' . now()->format('Y-m-d')"
    @export-started="onExportStart" />
BLADE;
    }

    private function getDataExportPreview(): string
    {
        return '<div class="export-btn"><span>⬇ Export Data</span><div class="dropdown"><button data-format="csv">CSV</button><button data-format="json">JSON</button><button data-format="excel">Excel</button><button data-format="pdf">PDF</button></div></div>';
    }

    private function getAlertBannerCode(): string
    {
        return <<<'BLADE'
<x-gsm.alerts.banner
    type="warning"
    title="High Memory Usage Detected"
    :dismissible="true">
    Memory consumption has exceeded 80%. Consider clearing cache.
</x-gsm.alerts.banner>
BLADE;
    }

    private function getAlertBannerPreview(): string
    {
        return '<div class="alert-banner warning"><span class="icon">⚠</span><div class="content"><strong>High Memory Usage Detected</strong><p>Memory consumption has exceeded 80%. Consider clearing cache.</p></div><button class="close">×</button></div>';
    }

    private function getFlasherTemplatePreview(): string
    {
        return '<div class="template-flasher"><div class="sidebar"><div class="logo">GSM Flasher</div><nav><a class="active">Dashboard</a><a>Devices</a><a>Logs</a><a>Settings</a></nav></div><div class="main"><div class="header"><h2>Flasher Terminal</h2><div class="status"><span class="dot online"></span> Device Connected</div></div><div class="terminal"><div class="terminal-line">> Initializing flash process...</div><div class="terminal-line">> Erasing sector 0x08000000</div><div class="terminal-line success">> Flash complete: 128KB written</div></div><div class="progress-bar"><div style="width:100%"></div></div></div></div>';
    }

    private function getForensicTemplatePreview(): string
    {
        return '<div class="template-forensic"><div class="toolbar"><div class="filters"><input placeholder="Search logs..." /><select><option>All Types</option></select></div><div class="actions"><button>Export</button><button>Analyze</button></div></div><div class="grid"><div class="datagrid"><table><thead><tr><th>Timestamp</th><th>Event</th><th>Severity</th></tr></thead><tbody><tr><td>14:32:18</td><td>File accessed</td><td class="high">HIGH</td></tr></tbody></table></div><div class="timeline"><div class="event">14:32 - File Modified</div></div></div></div>';
    }

    private function getServerMonitorTemplatePreview(): string
    {
        return '<div class="template-monitor"><div class="header"><h2>Server Nodes</h2><div class="stats"><div class="stat"><span class="value">99.8%</span><span class="label">Uptime</span></div></div></div><div class="grid"><div class="node"><div class="circle-progress"><svg><circle r="40" cx="50" cy="50"></circle><circle r="40" cx="50" cy="50" class="progress"></circle></svg><span>98%</span></div><span>Node Alpha</span></div></div></div>';
    }

    private function getNetworkScannerPreview(): string
    {
        return '<div class="template-scanner"><div class="scan-panel"><h3>Active Hosts</h3><div class="host-list"><div class="host"><span class="ip">192.168.1.1</span><span class="status up">●</span></div></div></div><div class="port-viz"><canvas></canvas></div></div>';
    }

    private function getEvidenceMgmtPreview(): string
    {
        return '<div class="template-evidence"><div class="case-header"><h2>Case #2024-001</h2><span class="status">Active</span></div><div class="file-grid"><div class="file-card"><span class="icon">📄</span><span>disk_image_01.dd</span></div></div></div>';
    }

    private function getSignalAnalyzerPreview(): string
    {
        return '<div class="template-signal"><div class="spectrum"><canvas></canvas></div><div class="meters"><div class="meter"><div class="bar" style="height:70%"></div><span>Cell 1</span></div></div></div>';
    }

    private function getIncidentResponsePreview(): string
    {
        return '<div class="template-incident"><div class="tickets"><div class="ticket"><span class="id">INC-1234</span><span class="priority">P1</span></div></div><div class="timeline"><div class="entry">14:00 - Incident detected</div></div></div>';
    }

    private function getDataBreachPreview(): string
    {
        return '<div class="template-breach"><div class="impact-map"><div class="system" severity="high">Database Server</div></div><div class="classification"><span class="label">PII Exposed</span></div></div>';
    }

    private function getMobileForensicsPreview(): string
    {
        return '<div class="template-mobile"><div class="extraction"><div class="progress-ring"><span>45%</span></div><span>Extracting...</span></div><div class="data-cats"><span>SMS</span><span>Contacts</span><span>Photos</span></div></div>';
    }

    private function getSOCPreview(): string
    {
        return '<div class="template-soc"><div class="threat-feed"><div class="alert">Malicious IP: 1.2.3.4</div></div><div class="metrics"><div class="metric">Alerts: 42</div></div></div>';
    }
}
