#!/bin/bash

# Laravel Dusk ChromeDriver Startup Script
# Starts ChromeDriver for Laravel Dusk browser testing

CHROMEDRIVER_PATH="vendor/laravel/dusk/bin/chromedriver-mac-arm"
PORT=9515
LOGFILE="storage/logs/chromedriver.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🔧 Laravel Dusk ChromeDriver Startup Script${NC}"
echo "=================================================="

# Check if ChromeDriver binary exists
if [ ! -f "$CHROMEDRIVER_PATH" ]; then
    echo -e "${RED}❌ ChromeDriver binary not found at $CHROMEDRIVER_PATH${NC}"
    echo "Run: php artisan dusk:chrome-driver"
    exit 1
fi

# Check if port is already in use
if lsof -Pi :$PORT -sTCP:LISTEN -t >/dev/null ; then
    echo -e "${YELLOW}⚠️  Port $PORT is already in use${NC}"
    echo "ChromeDriver may already be running. Use 'pkill -f chromedriver' to stop it."
    echo "Current process using port $PORT:"
    lsof -i :$PORT
    exit 1
fi

# Create logs directory if it doesn't exist
mkdir -p storage/logs

# Start ChromeDriver
echo -e "${GREEN}🚀 Starting ChromeDriver on port $PORT...${NC}"
echo "Binary: $CHROMEDRIVER_PATH"
echo "Log file: $LOGFILE"
echo ""

# Start ChromeDriver in background and redirect output to log file
$CHROMEDRIVER_PATH --port=$PORT > "$LOGFILE" 2>&1 &
CHROMEDRIVER_PID=$!

# Wait a moment and check if it started successfully
sleep 2

if kill -0 $CHROMEDRIVER_PID 2>/dev/null; then
    echo -e "${GREEN}✅ ChromeDriver started successfully!${NC}"
    echo "PID: $CHROMEDRIVER_PID"
    echo "Port: $PORT"
    echo "Log: $LOGFILE"
    echo ""
    echo -e "${GREEN}🧪 Ready to run Dusk tests:${NC}"
    echo "  php artisan dusk"
    echo "  php artisan dusk --filter='test_name'"
    echo ""
    echo -e "${YELLOW}💡 To stop ChromeDriver:${NC}"
    echo "  pkill -f chromedriver"
    echo "  # Or kill PID: kill $CHROMEDRIVER_PID"
else
    echo -e "${RED}❌ Failed to start ChromeDriver${NC}"
    echo "Check log file: $LOGFILE"
    exit 1
fi