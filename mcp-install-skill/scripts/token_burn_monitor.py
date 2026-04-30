#!/usr/bin/env python3
"""
Token Burn/Lost Monitor and Fix Tool

Monitors token burn rates, detects lost tokens,
and provides automated fixes for Laravel applications.
"""

import argparse
import json
import subprocess
import sys
from datetime import datetime, timedelta
from pathlib import Path
from typing import Dict, List, Any, Optional
import re


class TokenBurnMonitor:
    """Monitor and fix token burn/lost issues."""
    
    def __init__(self, project_path: str):
        self.project_path = Path(project_path).resolve()
        self.laravel_path = self.project_path
        self.issues_found = []
        self.fixes_applied = []
    
    def scan_project(self) -> Dict[str, Any]:
        """Scan Laravel project for token-related issues."""
        print("🔍 Scanning Laravel project for token issues...")
        
        results = {
            "project": str(self.project_path),
            "scan_time": datetime.now().isoformat(),
            "issues": [],
            "warnings": [],
            "recommendations": []
        }
        
        # Check 1: Session configuration
        session_issues = self._check_session_config()
        results["issues"].extend(session_issues)
        
        # Check 2: CSRF token configuration
        csrf_issues = self._check_csrf_config()
        results["issues"].extend(csrf_issues)
        
        # Check 3: API token configuration
        api_issues = self._check_api_token_config()
        results["issues"].extend(api_issues)
        
        # Check 4: Sanctum configuration
        sanctum_issues = self._check_sanctum_config()
        results["issues"].extend(sanctum_issues)
        
        # Check 5: Passport configuration
        passport_issues = self._check_passport_config()
        results["issues"].extend(passport_issues)
        
        # Check 6: JWT configuration
        jwt_issues = self._check_jwt_config()
        results["issues"].extend(jwt_issues)
        
        # Check 7: Cache configuration
        cache_issues = self._check_cache_config()
        results["warnings"].extend(cache_issues)
        
        # Check 8: Queue configuration
        queue_issues = self._check_queue_config()
        results["warnings"].extend(queue_issues)
        
        # Check 9: Environment variables
        env_issues = self._check_env_variables()
        results["issues"].extend(env_issues)
        
        # Check 10: Database migrations
        db_issues = self._check_database_migrations()
        results["issues"].extend(db_issues)
        
        return results
    
    def _check_session_config(self) -> List[Dict]:
        """Check session configuration for token issues."""
        issues = []
        
        # Check config/session.php
        session_config = self.laravel_path / "config" / "session.php"
        if not session_config.exists():
            issues.append({
                "type": "missing_config",
                "severity": "high",
                "component": "session",
                "issue": "Session configuration file not found",
                "description": "config/session.php is missing",
                "fix": "Run: php artisan config:publish laravel/session"
            })
        else:
            with open(session_config, 'r') as f:
                content = f.read()
                
                # Check for secure cookie settings
                if "'secure'" in content and "env" in content:
                    if "true" in content:
                        issues.append({
                            "type": "config_warning",
                            "severity": "low",
                            "component": "session",
                            "issue": "Secure cookies enabled",
                            "description": "Session cookies require HTTPS",
                            "fix": "Ensure HTTPS is properly configured"
                        })
                
                # Check SameSite attribute
                if "'same_site'" not in content:
                    issues.append({
                        "type": "missing_config",
                        "severity": "medium",
                        "component": "session",
                        "issue": "Missing SameSite attribute",
                        "description": "Session cookies should have SameSite attribute",
                        "fix": "Add 'same_site' => 'lax' to session config"
                    })
        
        # Check .env for session driver
        env_file = self.laravel_path / ".env"
        if env_file.exists():
            with open(env_file, 'r') as f:
                env_content = f.read()
                if "SESSION_DRIVER" not in env_content:
                    issues.append({
                        "type": "missing_config",
                        "severity": "medium",
                        "component": "session",
                        "issue": "No SESSION_DRIVER in .env",
                        "description": "Default session driver not specified",
                        "fix": "Add SESSION_DRIVER=file to .env"
                    })
        
        return issues
    
    def _check_csrf_config(self) -> List[Dict]:
        """Check CSRF token configuration."""
        issues = []
        
        # Check middleware
        middleware_file = self.laravel_path / "app" / "Http" / "Middleware" / "VerifyCsrfToken.php"
        if not middleware_file.exists():
            issues.append({
                "type": "missing_middleware",
                "severity": "high",
                "component": "csrf",
                "issue": "CSRF middleware not found",
                "description": "App\\Http\\Middleware\\VerifyCsrfToken is missing",
                "fix": "Restore VerifyCsrfToken middleware"
            })
        
        # Check kernel
        kernel_file = self.laravel_path / "app" / "Http" / "Kernel.php"
        if kernel_file.exists():
            with open(kernel_file, 'r') as f:
                content = f.read()
                if "VerifyCsrfToken" not in content:
                    issues.append({
                        "type": "missing_middleware",
                        "severity": "critical",
                        "component": "csrf",
                        "issue": "CSRF middleware not registered",
                        "description": "VerifyCsrfToken not in $middlewareGroups",
                        "fix": "Add \\App\\Http\\Middleware\\VerifyCsrfToken::class to web middleware group"
                    })
        
        # Check for excluded URIs
        if middleware_file.exists():
            with open(middleware_file, 'r') as f:
                content = f.read()
                if "$except" in content and "api/" in content:
                    issues.append({
                        "type": "config_warning",
                        "severity": "low",
                        "component": "csrf",
                        "issue": "API routes excluded from CSRF",
                        "description": "API routes should use token authentication",
                        "fix": "Ensure API routes use sanctum/passport tokens"
                    })
        
        return issues
    
    def _check_api_token_config(self) -> List[Dict]:
        """Check API token configuration."""
        issues = []
        
        # Check if API routes have auth middleware
        api_routes = self.laravel_path / "routes" / "api.php"
        if api_routes.exists():
            with open(api_routes, 'r') as f:
                content = f.read()
                if "auth:api" not in content and "auth:sanctum" not in content:
                    issues.append({
                        "type": "security",
                        "severity": "high",
                        "component": "api",
                        "issue": "API routes not protected",
                        "description": "API routes missing authentication middleware",
                        "fix": "Add auth middleware to API routes"
                    })
        
        # Check API guard
        auth_config = self.laravel_path / "config" / "auth.php"
        if auth_config.exists():
            with open(auth_config, 'r') as f:
                content = f.read()
                if "'api'" in content and "'driver'" in content:
                    if "'token'" not in content and "'sanctum'" not in content:
                        issues.append({
                            "type": "config_warning",
                            "severity": "medium",
                            "component": "api",
                            "issue": "API guard not configured",
                            "description": "API authentication guard not set",
                            "fix": "Configure 'api' guard to use sanctum or passport"
                        })
        
        return issues
    
    def _check_sanctum_config(self) -> List[Dict]:
        """Check Laravel Sanctum configuration."""
        issues = []
        
        # Check if sanctum is installed
        composer_lock = self.laravel_path / "composer.lock"
        sanctum_installed = False
        
        if composer_lock.exists():
            with open(composer_lock, 'r') as f:
                if "laravel/sanctum" in f.read():
                    sanctum_installed = True
        
        if not sanctum_installed:
            # Check if passport is used instead
            has_passport = False
            if composer_lock.exists():
                with open(composer_lock, 'r') as f:
                    if "laravel/passport" in f.read():
                        has_passport = True
            
            if not has_passport:
                issues.append({
                    "type": "missing_package",
                    "severity": "medium",
                    "component": "sanctum",
                    "issue": "No token auth package installed",
                    "description": "Neither Sanctum nor Passport found",
                    "fix": "composer require laravel/sanctum or laravel/passport"
                })
        else:
            # Check sanctum config
            sanctum_config = self.laravel_path / "config" / "sanctum.php"
            if not sanctum_config.exists():
                issues.append({
                    "type": "missing_config",
                    "severity": "medium",
                    "component": "sanctum",
                    "issue": "Sanctum config not published",
                    "description": "config/sanctum.php is missing",
                    "fix": "Run: php artisan vendor:publish --provider=Laravel\\Sanctum\\SanctumServiceProvider"
                })
            
            # Check if HasApiTokens trait is used
            user_model = self.laravel_path / "app" / "Models" / "User.php"
            if not user_model.exists():
                user_model = self.laravel_path / "app" / "User.php"
            
            if user_model.exists():
                with open(user_model, 'r') as f:
                    content = f.read()
                    if "HasApiTokens" not in content and "HasFactory" in content:
                        issues.append({
                            "type": "missing_trait",
                            "severity": "high",
                            "component": "sanctum",
                            "issue": "HasApiTokens trait not used",
                            "description": "User model missing HasApiTokens trait",
                            "fix": "Add use HasApiTokens; to User model"
                        })
        
        return issues
    
    def _check_passport_config(self) -> List[Dict]:
        """Check Laravel Passport configuration."""
        issues = []
        
        # Check if passport is installed
        composer_lock = self.laravel_path / "composer.lock"
        if composer_lock.exists():
            with open(composer_lock, 'r') as f:
                if "laravel/passport" in f.read():
                    # Check Passport config
                    passport_config = self.laravel_path / "config" / "passport.php"
                    if not passport_config.exists():
                        issues.append({
                            "type": "missing_config",
                            "severity": "medium",
                            "component": "passport",
                            "issue": "Passport config not published",
                            "description": "config/passport.php is missing",
                            "fix": "Run: php artisan vendor:publish --provider=Laravel\\Passport\\PassportServiceProvider"
                        })
        
        return issues
    
    def _check_jwt_config(self) -> List[Dict]:
        """Check JWT configuration."""
        issues = []
        
        # Check for tymon/jwt-auth
        composer_lock = self.laravel_path / "composer.lock"
        jwt_found = False
        
        if composer_lock.exists():
            with open(composer_lock, 'r') as f:
                if "tymon/jwt-auth" in f.read():
                    jwt_found = True
        
        if jwt_found:
            # Check JWT config
            jwt_config = self.laravel_path / "config" / "jwt.php"
            if not jwt_config.exists():
                issues.append({
                    "type": "missing_config",
                    "severity": "medium",
                    "component": "jwt",
                    "issue": "JWT config not published",
                    "description": "config/jwt.php is missing",
                    "fix": "Run: php artisan vendor:publish --provider=\"Tymon\\JWTAuth\\Providers\\LaravelServiceProvider\""
                })
        
        return issues
    
    def _check_cache_config(self) -> List[Dict]:
        """Check cache configuration for token storage."""
        issues = []
        
        env_file = self.laravel_path / ".env"
        if env_file.exists():
            with open(env_file, 'r') as f:
                env_content = f.read()
                
                # Check if cache driver is array in production
                if "CACHE_DRIVER=array" in env_content:
                    if "APP_ENV=production" in env_content or "APP_ENV=prod" in env_content:
                        issues.append({
                            "type": "config_warning",
                            "severity": "high",
                            "component": "cache",
                            "issue": "Array cache driver in production",
                            "description": "Cache tokens will be lost on each request",
                            "fix": "Use redis, file, or database cache driver in production"
                        })
        
        return issues
    
    def _check_queue_config(self) -> List[Dict]:
        """Check queue configuration."""
        issues = []
        
        env_file = self.laravel_path / ".env"
        if env_file.exists():
            with open(env_file, 'r') as f:
                env_content = f.read()
                
                # Check if queue driver is sync in production
                if "QUEUE_CONNECTION=sync" in env_content:
                    if "APP_ENV=production" in env_content:
                        issues.append({
                            "type": "config_warning",
                            "severity": "medium",
                            "component": "queue",
                            "issue": "Sync queue in production",
                            "description": "Jobs run synchronously, may timeout",
                            "fix": "Use redis, database, or sqs queue driver"
                        })
        
        return issues
    
    def _check_env_variables(self) -> List[Dict]:
        """Check environment variables for token settings."""
        issues = []
        
        env_file = self.laravel_path / ".env"
        if env_file.exists():
            with open(env_file, 'r') as f:
                env_content = f.read()
                
                # Check APP_KEY
                if "APP_KEY=" in env_content:
                    match = re.search(r'APP_KEY=([\w]+)', env_content)
                    if match:
                        key = match.group(1)
                        if key == "base64:" or len(key) < 32:
                            issues.append({
                                "type": "security",
                                "severity": "critical",
                                "component": "env",
                                "issue": "Weak APP_KEY",
                                "description": "Application key is weak or default",
                                "fix": "Run: php artisan key:generate"
                            })
                else:
                    issues.append({
                        "type": "missing_config",
                        "severity": "critical",
                        "component": "env",
                        "issue": "No APP_KEY",
                        "description": "Application key is missing",
                        "fix": "Run: php artisan key:generate"
                    })
                
                # Check encryption
                if "APP_CIPHER=" in env_content:
                    if "AES-256-CBC" not in env_content:
                        issues.append({
                            "type": "security",
                            "severity": "medium",
                            "component": "env",
                            "issue": "Weak encryption cipher",
                            "description": "APP_CIPHER should be AES-256-CBC",
                            "fix": "Set APP_CIPHER=AES-256-CBC"
                        })
        else:
            issues.append({
                "type": "missing_file",
                "severity": "critical",
                "component": "env",
                "issue": ".env file missing",
                "description": "No .env file found",
                "fix": "Copy .env.example to .env and run php artisan key:generate"
            })
        
        return issues
    
    def _check_database_migrations(self) -> List[Dict]:
        """Check database migrations for token tables."""
        issues = []
        
        database_path = self.laravel_path / "database" / "migrations"
        if database_path.exists():
            migrations = list(database_path.glob("*.php"))
            
            has_sessions_table = False
            has_failed_jobs_table = False
            
            for migration in migrations:
                with open(migration, 'r') as f:
                    content = f.read()
                    if "sessions" in content:
                        has_sessions_table = True
                    if "failed_jobs" in content:
                        has_failed_jobs_table = True
            
            if not has_sessions_table:
                # Check if sessions are stored in cache
                env_file = self.laravel_path / ".env"
                if env_file.exists():
                    with open(env_file, 'r') as f:
                        if "SESSION_DRIVER=file" in f.read():
                            pass  # File sessions don't need table
                        elif "SESSION_DRIVER=database" in f.read():
                            issues.append({
                                "type": "missing_migration",
                                "severity": "high",
                                "component": "database",
                                "issue": "Session table migration missing",
                                "description": "Database session driver but no sessions table",
                                "fix": "Run: php artisan session:table && php artisan migrate"
                            })
            
            if not has_failed_jobs_table:
                issues.append({
                    "type": "missing_migration",
                    "severity": "low",
                    "component": "database",
                    "issue": "No failed_jobs table",
                    "description": "Failed jobs won't be tracked",
                    "fix": "Add failed_jobs table migration"
                })
        
        return issues
    
    def fix_issues(self, issues: List[Dict]) -> Dict[str, Any]:
        """Automatically fix detected issues."""
        print("🔧 Applying automatic fixes...")
        
        results = {
            "fixed": [],
            "failed": [],
            "skipped": []
        }
        
        for issue in issues:
            severity = issue.get("severity", "medium")
            fix = issue.get("fix", "")
            component = issue.get("component", "")
            
            # Apply automatic fixes based on issue type
            if severity == "critical" or severity == "high":
                fixed = self._apply_fix(issue, component)
                if fixed:
                    results["fixed"].append(issue)
                else:
                    results["failed"].append(issue)
            else:
                results["skipped"].append(issue)
        
        return results
    
    def _apply_fix(self, issue: Dict, component: str) -> bool:
        """Apply a specific fix."""
        try:
            if component == "env":
                return self._fix_env_issue(issue)
            elif component == "session":
                return self._fix_session_issue(issue)
            elif component == "csrf":
                return self._fix_csrf_issue(issue)
            elif component == "sanctum":
                return self._fix_sanctum_issue(issue)
            elif component == "database":
                return self._fix_database_issue(issue)
            else:
                return False
        except Exception:
            return False
    
    def _fix_env_issue(self, issue: Dict) -> bool:
        """Fix environment variable issues."""
        env_file = self.laravel_path / ".env"
        
        if "APP_KEY" in issue.get("issue", ""):
            # Generate new app key
            result = subprocess.run(
                ["php", "artisan", "key:generate"],
                cwd=self.laravel_path,
                capture_output=True
            )
            return result.returncode == 0
        
        return False
    
    def _fix_session_issue(self, issue: Dict) -> bool:
        """Fix session configuration issues."""
        # This would require editing config files
        # For now, just report
        return False
    
    def _fix_csrf_issue(self, issue: Dict) -> bool:
        """Fix CSRF issues."""
        # Would require editing middleware
        return False
    
    def _fix_sanctum_issue(self, issue: Dict) -> bool:
        """Fix Sanctum issues."""
        if "config" in issue.get("issue", ""):
            result = subprocess.run(
                ["php", "artisan", "vendor:publish",
                 "--provider=Laravel\\Sanctum\\SanctumServiceProvider"],
                cwd=self.laravel_path,
                capture_output=True
            )
            return result.returncode == 0
        return False
    
    def _fix_database_issue(self, issue: Dict) -> bool:
        """Fix database issues."""
        if "session" in issue.get("issue", "") or "Session" in issue.get("issue", ""):
            result = subprocess.run(
                ["php", "artisan", "session:table"],
                cwd=self.laravel_path,
                capture_output=True
            )
            if result.returncode == 0:
                result = subprocess.run(
                    ["php", "artisan", "migrate"],
                    cwd=self.laravel_path,
                    capture_output=True
                )
                return result.returncode == 0
        return False
    
    def generate_report(self, results: Dict) -> str:
        """Generate a detailed report."""
        report = []
        report.append("=" * 70)
        report.append("TOKEN BURN/LOST ISSUE REPORT")
        report.append("=" * 70)
        report.append(f"Project: {results['project']}")
        report.append(f"Scan Time: {results['scan_time']}")
        report.append("=" * 70)
        
        # Critical Issues
        critical = [i for i in results["issues"] if i.get("severity") == "critical"]
        if critical:
            report.append("\n🚨 CRITICAL ISSUES (Fix Immediately):")
            for i, issue in enumerate(critical, 1):
                report.append(f"\n{i}. {issue['issue']}")
                report.append(f"   Component: {issue['component']}")
                report.append(f"   Description: {issue['description']}")
                report.append(f"   Fix: {issue['fix']}")
        

        return "\n".join(report)

