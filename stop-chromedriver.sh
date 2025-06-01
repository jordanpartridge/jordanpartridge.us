#!/bin/bash

# Laravel Dusk ChromeDriver Stop Script
# Stops running ChromeDriver instances

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🛑 Laravel Dusk ChromeDriver Stop Script${NC}"
echo "=============================================="

# Check if ChromeDriver is running
CHROMEDRIVER_PIDS=$(pgrep -f chromedriver)

if [ -z "$CHROMEDRIVER_PIDS" ]; then
    echo -e "${YELLOW}ℹ️  No ChromeDriver processes found running${NC}"
    exit 0
fi

echo -e "${GREEN}📋 Found ChromeDriver processes:${NC}"
ps aux | grep chromedriver | grep -v grep

echo ""
echo -e "${GREEN}🛑 Stopping ChromeDriver processes...${NC}"

# Kill all ChromeDriver processes
pkill -f chromedriver

# Wait a moment and verify they're stopped
sleep 2

REMAINING_PIDS=$(pgrep -f chromedriver)

if [ -z "$REMAINING_PIDS" ]; then
    echo -e "${GREEN}✅ All ChromeDriver processes stopped successfully${NC}"
else
    echo -e "${RED}⚠️  Some processes may still be running. Force killing...${NC}"
    pkill -9 -f chromedriver
    sleep 1
    
    FINAL_CHECK=$(pgrep -f chromedriver)
    if [ -z "$FINAL_CHECK" ]; then
        echo -e "${GREEN}✅ All ChromeDriver processes force-stopped${NC}"
    else
        echo -e "${RED}❌ Failed to stop some ChromeDriver processes${NC}"
        echo "Remaining processes:"
        ps aux | grep chromedriver | grep -v grep
    fi
fi

echo ""
echo -e "${GREEN}🧪 ChromeDriver stopped. Safe to run tests with:${NC}"
echo "  ./start-chromedriver.sh"