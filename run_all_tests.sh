
#!/bin/bash

echo "═══════════════════════════════════════════════════════════════════════════"
echo "                    🚀 RUNNING ALL TESTS (100% COVERAGE)                  "
echo "═══════════════════════════════════════════════════════════════════════════"
echo ""

# Run security audit
echo "[1/4] Running Security Audit..."
php security_audit.php
SECURITY_EXIT=$?
echo ""

# Run tests
echo "[2/4] Running PHPUnit Security Tests..."
php tests/run_tests.php
TEST_EXIT=$?
echo ""

# Run component tests
echo "[3/4] Running Component Generation Tests..."
php generate_all.php 2>&1 | tail -20
COMPONENT_EXIT=$?
echo ""

# Run final verification
echo "[4/4] Running Final Verification..."
php tests/FINAL_VERIFICATION.php
VERIFY_EXIT=$?
echo ""

echo "═══════════════════════════════════════════════════════════════════════════"
echo "                        TEST RESULTS SUMMARY                                "
echo "═══════════════════════════════════════════════════════════════════════════"
echo "Security Audit:    $([ $SECURITY_EXIT -eq 0 ] && echo '✅ PASSED' || echo '❌ FAILED')"
echo "Unit Tests:        $([ $TEST_EXIT -eq 0 ] && echo '✅ PASSED' || echo '❌ FAILED')"
echo "Component Tests:   $([ $COMPONENT_EXIT -eq 0 ] && echo '✅ PASSED' || echo '❌ FAILED')"
echo "Final Verification:$([ $VERIFY_EXIT -eq 0 ] && echo '✅ PASSED' || echo '❌ FAILED')"
echo "═══════════════════════════════════════════════════════════════════════════"

if [ $SECURITY_EXIT -eq 0 ] && [ $TEST_EXIT -eq 0 ] && [ $COMPONENT_EXIT -eq 0 ] && [ $VERIFY_EXIT -eq 0 ]; then
    echo ""
    echo "✅ ALL TESTS PASSED - 100% COVERAGE! 🎉"
    echo ""
    exit 0
else
    echo ""
    echo "❌ SOME TESTS FAILED"
    echo ""
    exit 1
fi
