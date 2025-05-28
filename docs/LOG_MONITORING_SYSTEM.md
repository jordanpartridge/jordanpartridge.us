# Automated Log Monitoring System

## Overview

This system provides intelligent, automated monitoring of Laravel logs with smart GitHub issue creation. It addresses the key concerns you raised:

1. ✅ **Automated Frequency** - Runs every 15 minutes via Laravel scheduler
2. ✅ **Issue Validation** - Confirms errors still occur before creating issues  
3. ✅ **Duplicate Prevention** - Won't create multiple issues for the same pattern
4. ✅ **Severity Filtering** - Only creates issues for significant problems

## How It Works

### 1. Automated Monitoring

```bash
# Runs automatically every 15 minutes via Laravel scheduler
php artisan logs:monitor --interval=15 --threshold=3 --validate
```

### 2. Error Pattern Analysis

- Groups similar errors together (removing timestamps, IDs, etc.)
- Tracks frequency and severity
- Identifies recurring vs. one-off issues

### 3. Intelligent Issue Creation

- Only creates issues for:
  - **Critical/High severity** errors (any frequency)  
  - **Medium severity** errors with 10+ occurrences
- Validates the error still occurs before creating issue
- Prevents duplicate issues for 24 hours

### 4. Error Validation

Before creating a GitHub issue, the system:

- **Database errors**: Checks if tables actually exist
- **Class errors**: Verifies if classes are available
- **Recent errors**: Only considers patterns active if seen in last 5 minutes

## Commands

### Monitor Logs (Manual)

```bash
# Dry run - see what would happen
php artisan logs:monitor --dry-run

# Monitor last hour with threshold of 1
php artisan logs:monitor --interval=60 --threshold=1 --validate

# Skip validation (create issues for all patterns)
php artisan logs:monitor --interval=15 --threshold=3
```

### Check Status

```bash
# View monitoring dashboard
php artisan logs:monitor-status

# Clear monitoring cache
php artisan logs:monitor-status --clear-cache
```

### Create Issues Manually

```bash
# Analyze specific error and create issue
php artisan logs:analyze-error --recent --create-issue --bot

# Create custom bot issue
php artisan issues:create-as-bot "Title" "Body content"
```

## Configuration

### Scheduling (in `app/Console/Kernel.php`)

```php
// Runs every 15 minutes automatically
$schedule->command('logs:monitor --interval=15 --threshold=3 --validate')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->emailOutputOnFailure(config('mail.from.address'));
```

### Severity Thresholds

- **Critical**: `EMERGENCY`, `ALERT`, fatal errors, memory issues
- **High**: `CRITICAL`, database, connection, auth, security issues  
- **Medium**: `ERROR` level messages
- **Low**: Everything else

### Issue Creation Rules

- **Critical/High**: Create issue immediately (any count)
- **Medium**: Create issue only if 10+ occurrences  
- **Low**: Never create automatic issues

## Example Workflow

1. **15-minute intervals**: System checks logs automatically
2. **Pattern detection**: Groups `SQLSTATE[42S02]` errors together.
3. **Threshold check**: 3+ occurrences of the same pattern
4. **Validation**: Checks if `performance_metrics` table exists
5. **Decision**: Table exists → mark as resolved, no issue created
6. **Alternative**: Table missing → create GitHub issue with bot attribution

## Monitoring Dashboard

The status command shows:

- ⏰ Last monitoring run details
- 📝 Recently created issues  
- ✅ Resolved error patterns
- ⚙️ Current configuration
- 📈 Error statistics

## Cache Management

The system uses Laravel cache to track:

- **Recent issues**: Prevents duplicates for 24 hours
- **Resolved patterns**: Tracks self-resolved issues
- **Monitoring state**: Stores run statistics

Cache keys:

- `log_monitor_recent_issues`
- `log_monitor_resolved_patterns`  
- `log_monitor_last_run`

## Benefits

### For Development

- **Proactive issue detection** before users report problems
- **Intelligent filtering** reduces noise from temporary issues
- **Structured documentation** with consistent GitHub issues
- **Historical tracking** of resolved vs. ongoing problems

### For Operations  

- **Automated monitoring** without manual log review
- **Smart validation** prevents false positives
- **Severity-based prioritization** focuses on critical issues
- **Bot attribution** clearly marks automated vs. manual issues

## Real-World Example

**Detected**: Database connection timeout (10 occurrences in 15 minutes)

**Validation**: Attempts database query to confirm issue exists

**Result**: Creates high-priority GitHub issue with:

- Clear error description
- Occurrence timeline  
- Recommended investigation steps
- Environment context
- Bot attribution

**Avoided**: Temporary table missing error (1 occurrence, table now exists)

**Validation**: Confirms table exists in database

**Result**: Marks pattern as resolved, no issue created

This system provides the intelligent, automated error monitoring you requested while avoiding spam and false positives! 🚀