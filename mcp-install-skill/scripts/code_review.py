#!/usr/bin/env python3
"""
Code Review Automation Framework
"""

import re
import json
from pathlib import Path
from datetime import datetime
from typing import Dict, List, Optional, Any
from dataclasses import dataclass, asdict


@dataclass
class ReviewFinding:
    severity: str
    category: str
    file: str
    line: int
    column: Optional[int]
    message: str
    suggestion: Optional[str]
    code_snippet: Optional[str]
    rule_id: str


class CodeReviewAutomation:
    SECURITY_RULES = {
        'sql_injection': {
            'pattern': r'\$query.*\.\s*["\']\s*\.\s*\$\w+|DB::\(\s*["\'].*\$\w+',
            'severity': 'critical',
            'message': 'Potential SQL injection vulnerability',
            'suggestion': 'Use parameterized queries with ? placeholders or Eloquent ORM'
        },
        'xss_vulnerability': {
            'pattern': r'echo\s+\$\w+|print\s+\$\w+',
            'severity': 'high',
            'message': 'Potential XSS vulnerability - unescaped output',
            'suggestion': "Use Laravel's {{ }} blade escaping or e() helper"
        },
        'hardcoded_credentials': {
            'pattern': r'(?:password|secret|key|token)[^=]*=>\s*["\'][^"\'\n]{4,}["\']',
            'severity': 'critical',
            'message': 'Hardcoded credentials detected',
            'suggestion': 'Use environment variables (.env file)'
        },
        'mass_assignment': {
            'pattern': r'\$\w+->create\(\$request->all\(\)\)',
            'severity': 'high',
            'message': 'Mass assignment vulnerability',
            'suggestion': 'Use $fillable or $guarded properties'
        },
        'path_traversal': {
            'pattern': r'file_get_contents\s*\(\s*\$_(?:GET|POST)',
            'severity': 'high',
            'message': 'Potential path traversal vulnerability',
            'suggestion': 'Validate and sanitize file paths'
        },
        'csrf_missing': {
            'pattern': r'<form[^>]*method=["\']POST["\'][^>]*>(?!.*@csrf)',
            'severity': 'high',
            'message': 'CSRF token missing in POST form',
            'suggestion': 'Add @csrf blade directive'
        },
        'command_injection': {
            'pattern': r'(?:exec|shell_exec|system|passthru)\s*\(',
            'severity': 'critical',
            'message': 'Command injection risk',
            'suggestion': 'Avoid shell execution with user input'
        },
        'excessive_debug_info': {
            'pattern': r'dd\(\s*\$|var_dump\(',
            'severity': 'medium',
            'message': 'Debug code in production',
            'suggestion': 'Remove debug statements'
        },
    }

    PERFORMANCE_RULES = {
        'n_plus_one_query': {
            'pattern': r'@foreach.*\$\w+->\w+\s*@endforeach',
            'severity': 'high',
            'message': 'Potential N+1 query problem',
            'suggestion': 'Use eager loading with with()'
        },
        'missing_pagination': {
            'pattern': r'::all\(\s*\)|->get\(\)',
            'severity': 'medium',
            'message': 'Retrieving all records without pagination',
            'suggestion': 'Use paginate() for large datasets'
        },
        'inefficient_loop': {
            'pattern': r'db\(\s*\)|Model::find\s*\(',
            'severity': 'high',
            'message': 'Database query inside loop',
            'suggestion': 'Move queries outside the loop'
        },
    }

    BEST_PRACTICE_RULES = {
        'missing_validation': {
            'pattern': r'public\s+function\s+(?:store|update).*Request\s+\$request\s*(?!.*validate)',
            'severity': 'medium',
            'message': 'Missing request validation',
            'suggestion': 'Use $request->validate()'
        },
        'missing_exception_handling': {
            'pattern': r'try\s*{[^}]*}(?!.*catch)',
            'severity': 'medium',
            'message': 'Try block without catch',
            'suggestion': 'Handle exceptions with try-catch'
        },
        'php_tag_in_blade': {
            'pattern': r'<\?php(?!xml)',
            'severity': 'info',
            'message': 'Raw PHP in Blade template',
            'suggestion': 'Use Blade directives'
        },
    }

    def __init__(self, project_path: str):
        self.project_path = Path(project_path)
        self.findings: List[ReviewFinding] = []
        self.stats = {'files_scanned': 0, 'total_lines': 0, 'issues_found': 0, 'by_severity': {}, 'by_category': {}}

    def scan_php_file(self, filepath: Path):
        findings = []
        try:
            content = filepath.read_text()
            lines = content.split('\n')
            all_rules = {**self.SECURITY_RULES, **self.PERFORMANCE_RULES, **self.BEST_PRACTICE_RULES}
            for line_num, line in enumerate(lines, 1):
                for rule_id, rule in all_rules.items():
                    if re.search(rule['pattern'], line, re.IGNORECASE):
                        category = 'security' if rule_id in self.SECURITY_RULES else 'performance' if rule_id in self.PERFORMANCE_RULES else 'best-practice'
                        finding = ReviewFinding(
                            severity=rule['severity'], category=category,
                            file=str(filepath.relative_to(self.project_path)),
                            line=line_num, column=None, message=rule['message'],
                            suggestion=rule['suggestion'], code_snippet=line.strip()[:200],
                            rule_id=rule_id)
                        findings.append(finding)
        except Exception as e:
            print(f"Error: {e}")
        return findings

    def scan_python_file(self, filepath: Path):
        findings = []
        try:
            lines = filepath.read_text().split('\n')
            for line_num, line in enumerate(lines, 1):
                if re.search(r'eval\s*\(|exec\s*\(', line):
                    findings.append(ReviewFinding(
                        severity='high', category='security',
                        file=str(filepath.relative_to(self.project_path)),
                        line=line_num, column=None, message='Dangerous eval/exec usage',
                        suggestion='Avoid dangerous functions',
                        code_snippet=line.strip()[:200], rule_id='python_security'))
        except: pass
        return findings

    def scan_directory(self, directory=None):
        scan_dir = Path(directory) if directory else self.project_path
        php_files = list(scan_dir.rglob('*.php'))
        py_files = list(scan_dir.rglob('*.py'))
        all_files = [f for f in php_files + py_files if not any(s in str(f) for s in ['/vendor/', '/node_modules/', '__pycache__', '.git'])]
        for filepath in all_files:
            findings = self.scan_python_file(filepath) if filepath.suffix == '.py' else self.scan_php_file(filepath)
            self.findings.extend(findings)
            self.stats['files_scanned'] += 1
            try:
                self.stats['total_lines'] += len(filepath.read_text().split('\n'))
            except: pass
        for f in self.findings:
            self.stats['issues_found'] += 1
            self.stats['by_severity'][f.severity] = self.stats['by_severity'].get(f.severity, 0) + 1
            self.stats['by_category'][f.category] = self.stats['by_category'].get(f.category, 0) + 1
        return self.get_report()

    def get_report(self):
        severity_order = {'critical': 0, 'high': 1, 'medium': 2, 'low': 3, 'info': 4}
        sorted_findings = sorted(self.findings, key=lambda f: severity_order.get(f.severity, 5))
        crit = self.stats['by_severity'].get('critical', 0)
        high = self.stats['by_severity'].get('high', 0)
        if crit > 0:
            rl, rec = 'CRITICAL', 'Fix critical issues immediately'
        elif high > 0:
            rl, rec = 'HIGH', 'Fix high-severity issues'
        elif self.stats['issues_found'] > 10:
            rl, rec = 'MEDIUM', 'Review issues in next sprint'
        elif self.stats['issues_found'] > 0:
            rl, rec = 'LOW', 'Minor issues, address as needed'
        else:
            rl, rec = 'NONE', 'Excellent code quality'
        return {
            'timestamp': datetime.now().isoformat(),
            'project_path': str(self.project_path),
            'statistics': self.stats,
            'findings': [asdict(f) for f in sorted_findings],
            'summary': {
                'risk_level': rl,
                'recommendation': rec,
                'total_findings': self.stats['issues_found'],
                'files_analyzed': self.stats['files_scanned'],
                'lines_analyzed': self.stats['total_lines'],
            },
        }

    def export_json(self, output_path: str):
        with open(output_path, 'w') as f:
            json.dump(self.get_report(), f, indent=2)

    def print_report(self):
        r = self.get_report()
        s = r['summary']
        icons = ['❌', '⚠️', '🔍', 'ℹ️', '💡']
        sevs = ['critical', 'high', 'medium', 'low', 'info']
        print("\n" + "=" * 70)
        print("           CODE REVIEW AUTOMATION REPORT")
        print("=" * 70)
        print("  Project: " + r['project_path'])
        print("  Scanned: " + r['timestamp'])
        print("  Files:   " + str(s['files_analyzed']))
        print("  Lines:   " + f"{s['lines_analyzed']:,}")
        print("-" * 70)
        print("  Risk Level:    " + s['risk_level'])
        print("  Findings:      " + str(s['total_findings']))
        print("  Recommendation: " + s['recommendation'])
        print("-" * 70)
        if r['statistics']['by_severity']:
            print("\n  Issues by Severity:")
            for i, sev in enumerate(sevs):
                c = r['statistics']['by_severity'].get(sev, 0)
                if c > 0:
                    print("    " + icons[i] + " " + sev.upper().ljust(12) + " : " + str(c))
        if r['statistics']['by_category']:
            print("\n  Issues by Category:")
            for cat, cnt in r['statistics']['by_category'].items():
                print("    * " + cat.ljust(20) + " : " + str(cnt))
        print("\n" + "=" * 70)
        print("  OK: Code review complete")
        print("=" * 70 + "\n")


if __name__ == '__main__':
    import sys
    project_path = sys.argv[1] if len(sys.argv) > 1 else '/root/.openclaw/workspace'
    print("\n=== Code Review Automation: " + project_path + " ===\n")
    reviewer = CodeReviewAutomation(project_path)
    reviewer.scan_directory()
    reviewer.print_report()
    output_file = project_path + "/code_review_report.json"
    reviewer.export_json(output_file)
    print("Report: " + output_file + "\n")
    exit(0 if reviewer.stats['by_severity'].get('critical', 0) == 0 else 1)
ENDOFFILE
