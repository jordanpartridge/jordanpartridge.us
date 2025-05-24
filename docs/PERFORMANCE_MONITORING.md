# Performance Monitoring System

## Overview

The performance monitoring system automatically tracks application performance metrics for every request and provides a comprehensive dashboard for analysis.

## Components

### 1. PerformanceMetric Model
Located: `app/Models/PerformanceMetric.php`

Tracks the following metrics:
- **Response Time** (milliseconds)
- **Memory Usage** (bytes)
- **Peak Memory** (bytes)
- **CPU Usage** (load average)
- **Database Queries** (count and total time)
- **Cache Operations** (hits and misses)
- **HTTP Method and Status**
- **User Association**
- **Additional Context Data**

### 2. PerformanceMonitoring Middleware
Located: `app/Http/Middleware/PerformanceMonitoring.php`

Automatically collects metrics for all requests. Features:
- **Asynchronous Storage** - Metrics stored after response to avoid blocking
- **Environment Aware** - Direct storage in testing, queued in production
- **Selective Tracking** - Skips static assets and health checks
- **Debug Headers** - Adds performance headers in debug mode

### 3. Performance Dashboard
Located: `app/Filament/Pages/PerformanceMonitoringDashboard.php`

Provides comprehensive analytics:
- **Overview Stats** - Response times, error rates, memory usage
- **Interactive Charts** - Response time trends, error distribution
- **Slow Endpoints** - Identification of performance bottlenecks
- **Database Metrics** - Query count and timing analysis
- **Memory Trends** - Memory usage patterns over time
- **Cache Efficiency** - Hit rates and performance

### 4. Cleanup Command
Located: `app/Console/Commands/CleanupPerformanceMetrics.php`

Maintains data hygiene:
- **Configurable Retention** - Keep data for specified days
- **Table Optimization** - Optimizes MySQL table after cleanup
- **Scheduled Execution** - Runs daily at 3:00 AM
- **Interactive Confirmation** - Prevents accidental data loss

## Configuration

### Middleware Registration
The middleware is automatically registered in `bootstrap/app.php`:

```php
$middleware->web(append: [
    SecurityHeadersMiddleware::class,
    PerformanceMonitoring::class,
    LogRequests::class,
]);
```

### Scheduled Commands
Auto-cleanup is configured in `app/Console/Kernel.php`:

```php
$schedule->command('performance:cleanup --days=30')
    ->daily()
    ->at('03:00')
    ->withoutOverlapping()
    ->runInBackground();
```

## Usage

### Accessing the Dashboard
1. Navigate to Admin Panel: `/admin`
2. Login with administrator credentials
3. Click "Performance Monitoring" in the navigation

### Manual Cleanup
```bash
# Clean metrics older than 30 days
php artisan performance:cleanup --days=30

# Clean metrics older than 7 days
php artisan performance:cleanup --days=7
```

### Querying Metrics Programmatically
```php
use App\Models\PerformanceMetric;

// Get recent metrics (last 24 hours)
$recentMetrics = PerformanceMetric::recent(24)->get();

// Find slow requests (> 1000ms)
$slowRequests = PerformanceMetric::slow(1000)->get();

// Get metrics for specific URL
$urlMetrics = PerformanceMetric::forUrl('/api/data')->get();

// Calculate average response time
$avgTime = PerformanceMetric::recent(24)->avg('response_time');
```

## Performance Impact

The monitoring system is designed for minimal performance impact:

- **Asynchronous Processing** - Metrics stored after response
- **Selective Tracking** - Static assets and health checks excluded
- **Efficient Queries** - Indexed database queries
- **Automatic Cleanup** - Prevents database bloat

## Troubleshooting

### High Memory Usage
- Check for memory leaks in long-running processes
- Review peak memory vs average memory trends
- Consider increasing PHP memory limits if legitimate

### Slow Response Times
- Use "Slowest Endpoints" report to identify bottlenecks
- Check database query count and timing
- Review caching efficiency

### Missing Metrics
- Verify middleware is registered and active
- Check queue worker status for async processing
- Ensure database permissions for metrics table

### Large Database Size
- Adjust cleanup retention period
- Run manual cleanup: `php artisan performance:cleanup --days=7`
- Consider archiving old data instead of deletion

## Database Schema

```sql
CREATE TABLE performance_metrics (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    url VARCHAR(255) NOT NULL,
    method VARCHAR(10) NOT NULL,
    response_time INT NOT NULL, -- milliseconds
    response_status INT NOT NULL,
    memory_usage BIGINT NOT NULL, -- bytes
    peak_memory BIGINT NOT NULL, -- bytes
    cpu_usage FLOAT NULL,
    db_queries INT NOT NULL DEFAULT 0,
    db_time INT NOT NULL DEFAULT 0, -- milliseconds
    cache_hits INT NOT NULL DEFAULT 0,
    cache_misses INT NOT NULL DEFAULT 0,
    user_agent TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_id BIGINT NULL,
    additional_data JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_url_created (url, created_at),
    INDEX idx_response_time (response_time),
    INDEX idx_created_at (created_at)
);
```

## Security Considerations

- **IP Address Storage** - Collected for analysis but consider privacy implications
- **User Agent Logging** - May contain sensitive information
- **URL Logging** - Avoid logging URLs with sensitive parameters
- **Access Control** - Dashboard restricted to authenticated admin users

## Future Enhancements

- Real-time alerting for performance thresholds
- Integration with external monitoring services
- Performance baseline establishment
- Automated performance regression detection
- Custom metric collection endpoints

---

🤖 Generated with [Claude Code](https://claude.ai/code)