
<?php

namespace GSMUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GSMUITestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsmui:test 
                            {--unit : Run unit tests only} 
                            {--feature : Run feature tests only} 
                            {--filter= : Filter tests by name} 
                            {--coverage : Generate code coverage report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run GSM-UI test suite';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Running GSM-UI Test Suite...');
        $this->info('=================================');

        $arguments = [];

        if ($this->option('unit')) {
            $arguments[] = '--testsuite=Unit';
        }

        if ($this->option('feature')) {
            $arguments[] = '--testsuite=Feature';
        }

        if ($this->option('filter')) {
            $arguments[] = '--filter=' . $this->option('filter');
        }

        if ($this->option('coverage')) {
            $arguments[] = '--coverage-html';
            $arguments[] = 'bootstrap/cache/clover';
        }

        // Run PHPUnit
        $exitCode = Artisan::call('test', $arguments);

        $output = Artisan::output();
        $this->info($output);

        if ($exitCode === 0) {
            $this->info('\n✅ All tests passed!');
        } else {
            $this->error('\n❌ Some tests failed!');
        }

        // Run security audit
        $this->info('\n🔒 Running security audit...');
        $this->runSecurityAudit();

        // Check component integrity
        $this->info('\n🔍 Checking component integrity...');
        $this->checkComponents();

        return $exitCode;
    }

    /**
     * Run security audit
     */
    protected function runSecurityAudit()
    {
        $auditFile = base_path('security_audit.php');
        
        if (File::exists($auditFile)) {
            $output = [];
            $returnCode = 0;
            
            exec('php ' . $auditFile . ' 2>&1', $output, $returnCode);
            
            foreach ($output as $line) {
                if (strpos($line, '✅') !== false) {
                    $this->info($line);
                } elseif (strpos($line, '❌') !== false) {
                    $this->error($line);
                } elseif (strpos($line, '⚠️') !== false) {
                    $this->warn($line);
                } else {
                    $this->line($line);
                }
            }
        } else {
            $this->warn('Security audit script not found');
        }
    }

    /**
     * Check component integrity
     */
    protected function checkComponents()
    {
        $components = [
            'blade' => resource_path('views/components/blade'),
            'volt' => app_path('Components/Livewire/Volt'),
            'react' => app_path('Components/React/components'),
            'vue' => app_path('Components/Vue/components'),
        ];

        $missing = [];
        $found = 0;

        foreach ($components as $type => $path) {
            if (File::exists($path)) {
                $files = File::files($path);
                $found += count($files);
                $this->info("   ✅ {$type}: " . count($files) . ' components');
            } else {
                $missing[] = $type;
                $this->warn("   ⚠️ {$type}: directory not found");
            }
        }

        if (!empty($missing)) {
            $this->warn('\n⚠️  Missing directories: ' . implode(', ', $missing));
        }

        $this->info('\n📊 Total components found: ' . $found);

        // Check for common issues
        $this->checkCommonIssues();
    }

    /**
     * Check for common component issues
     */
    protected function checkCommonIssues()
    {
        $issues = [];

        // Check for missing stubs
        $stubPath = base_path('stubs');
        if (File::exists($stubPath)) {
            $stubs = File::files($stubPath);
            if (count($stubs) < 10) {
                $issues[] = 'Less than 10 component stubs found';
            }
        } else {
            $issues[] = 'Stub directory not found';
        }

        // Check for missing documentation
        $docsPath = resource_path('views/components/docs');
        if (File::exists($docsPath)) {
            $docs = File::files($docsPath);
            if (count($docs) < 10) {
                $issues[] = 'Less than 10 component docs found';
            }
        }

        // Report issues
        if (!empty($issues)) {
            $this->warn('\n⚠️  Potential issues found:');
            foreach ($issues as $issue) {
                $this->warn('   - ' . $issue);
            }
        } else {
            $this->info('\n✅ No common issues found');
        }
    }
}
