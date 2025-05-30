# 🚀 jordanpartridge.us

> **Modern Laravel application serving as personal website, portfolio, and development playground**

[![Laravel Forge Deployment](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F1fcb3f58-585a-453a-8a5c-d4af80bf60f0%3Fdate%3D1%26label%3D1%26commit%3D1&style=flat-square)](https://forge.laravel.com/servers/820904/sites/2398933)
[![Tests](https://github.com/jordanpartridge/jordanpartridge.us/actions/workflows/Tests.yml/badge.svg?style=flat-square)](https://github.com/jordanpartridge/jordanpartridge.us/actions/workflows/Tests.yml)
[![WakaTime](https://wakatime.com/badge/user/af39b85c-9dd3-45aa-a975-04ca41a569a7/project/8d750652-7330-42a5-8fab-2a38e85c329f.svg?style=flat-square)](https://wakatime.com/badge/user/af39b85c-9dd3-45aa-a975-04ca41a569a7/project/8d750652-7330-42a5-8fab-2a38e85c329f)

## ✨ What This Is

A full-featured Laravel application that serves as:
- **Personal website & portfolio** showcasing professional work
- **Blog platform** for sharing insights and tutorials  
- **Client management system** with document handling and Gmail integration
- **Development playground** for experimenting with Laravel features
- **API integration hub** connecting Strava, GitHub, and other services

## 🛠️ Tech Stack

### Core Framework
- **Laravel 11** - PHP framework with latest features
- **PHP 8.4** - Modern PHP with performance optimizations
- **MySQL** - Reliable database with optimized queries

### Frontend & Styling  
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Vite** - Fast build tool and development server
- **Laravel Folio** - File-based routing system

### Admin & Management
- **Filament v3** - Modern admin panel with rich UI components
- **Laravel Livewire** - Dynamic interfaces without complex JavaScript

### Integrations & APIs
- **Strava API** - Automatic bike ride syncing every hour
- **Gmail API** - Email management and client communication  
- **GitHub API** - Repository syncing and project showcasing
- **Custom Card API** - Blackjack game integration

### Performance & Monitoring
- **Laravel Horizon** - Queue monitoring and management
- **Laravel Telescope** - Application debugging and profiling
- **Activity Logging** - Comprehensive audit trail (optional)
- **Performance Monitoring** - Custom metrics and alerting

## 🚀 Quick Start

### Prerequisites
- PHP 8.4+
- Composer 2.0+
- Node.js 18+
- MySQL 8.0+

### Installation

```bash
# Clone the repository
git clone https://github.com/jordanpartridge/jordanpartridge.us.git
cd jordanpartridge.us

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Asset compilation
npm run build

# Start development server
php artisan serve
```

### Development Workflow

```bash
# Start all development services
npm run dev          # Vite development server
php artisan serve    # Laravel development server
php artisan horizon  # Queue processing (optional)

# Code quality tools
./vendor/bin/duster fix  # Auto-fix code style issues
php artisan test         # Run the test suite
php artisan dusk         # Run browser tests
```

## 📁 Project Structure

```
├── app/
│   ├── Filament/           # Admin panel resources & pages
│   ├── Http/Controllers/   # Web controllers
│   ├── Jobs/              # Background jobs
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic services
├── resources/
│   ├── views/pages/       # Folio pages (file-based routing)
│   ├── views/components/  # Blade components
│   └── js/                # Frontend JavaScript
├── tests/
│   ├── Feature/           # Feature tests
│   ├── Unit/             # Unit tests
│   └── Browser/          # Dusk browser tests
└── docs/                 # Project documentation
```

## 🔧 Configuration

### Required Environment Variables

```env
# Application
APP_NAME="Jordan Partridge"
APP_URL=https://jordanpartridge.us
APP_ENV=production

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=jordanpartridge
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password

# API Integrations
STRAVA_CLIENT_ID=your_strava_client_id
STRAVA_CLIENT_SECRET=your_strava_secret

GMAIL_CLIENT_ID=your_gmail_client_id  
GMAIL_CLIENT_SECRET=your_gmail_secret
GMAIL_REDIRECT_URI=https://yourdomain.com/gmail/auth/callback

# Card API (for Blackjack game)
CARD_API_URL=https://card-api.jordanpartridge.us
CARD_API_KEY=your_card_api_key
```

### Optional Configuration

```env
# Performance Optimization
APP_LOG_REQUESTS=false          # Disable request logging for performance
DEBUGBAR_ENABLED=false          # Disable debug bar in production
TELESCOPE_ENABLED=true          # Enable for debugging

# Queue Configuration  
QUEUE_CONNECTION=database       # Use database queues
HORIZON_PREFIX=jordanpartridge  # Horizon Redis prefix
```

## 🎯 Key Features

### Client Management System
- **Contact form** with email notifications
- **Client dashboard** with status tracking  
- **Document upload/download** with S3 storage
- **Gmail integration** for email management
- **Activity logging** for audit trails

### Content Management
- **Blog system** with categories and tags
- **Portfolio showcase** with GitHub integration
- **RSS feed** generation
- **SEO optimization** with meta tags and sitemaps

### API Integrations
- **Strava sync** - Hourly bike ride imports
- **GitHub repos** - Automatic repository syncing  
- **Gmail API** - Email management and statistics
- **Performance metrics** - Custom monitoring dashboard

### Performance Features
- **Asset optimization** with Vite bundling
- **Database query caching** for expensive operations
- **CDN integration** for static assets
- **Comprehensive test suite** with performance monitoring

## 🧪 Testing

### Test Suite Overview
- **Unit tests** - Core business logic testing
- **Feature tests** - HTTP endpoint and integration testing  
- **Browser tests** - End-to-end user workflow testing
- **Performance tests** - Page load time monitoring

### Running Tests

```bash
# Run all tests
php artisan test

# Run browser tests (requires ChromeDriver)
./start-chromedriver.sh  # Start ChromeDriver
php artisan dusk         # Run browser tests
./stop-chromedriver.sh   # Stop ChromeDriver

# Run specific test categories
php artisan dusk tests/Browser/PerformanceTest.php
php artisan dusk tests/Browser/CoreFunctionalityTest.php

# Performance testing
php artisan dusk --filter="test_homepage_performance"
```

### Test Environment Setup
Browser tests use a dedicated testing environment with optimized settings:
- DebugBar disabled for performance
- Request logging disabled  
- Simplified caching and session handling
- Production-like asset compilation

## 📊 Performance Monitoring

### Performance Optimization Results
Our comprehensive test suite identified and resolved major bottlenecks:

- **59.7% performance improvement** in development environment
- **DebugBar optimization** - 43% faster page loads  
- **Asset bundling** - 33% improvement with production builds
- **Database query caching** - Reduced homepage load times
- **Request logging optimization** - Configurable for performance

### Performance Baselines
- **Homepage**: Target <1000ms (production)
- **Blog pages**: Target <1000ms  
- **404 pages**: Target <2000ms
- **Admin dashboard**: Target <1000ms

## 🚀 Deployment

### Production Requirements
- **PHP 8.4+** with required extensions
- **MySQL 8.0+** with optimized configuration
- **Redis** for caching and queues (recommended)
- **HTTPS** with valid SSL certificate
- **CDN** for static asset delivery

### Laravel Forge Deployment
This application is deployed using Laravel Forge with:
- **Automatic deployments** from main branch
- **Zero-downtime deployments** with health checks
- **SSL certificate** management
- **Database backup** automation
- **Queue worker** management

### Environment-Specific Configuration

**Production:**
```env
APP_ENV=production
APP_DEBUG=false
APP_LOG_REQUESTS=false
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

**Staging:**
```env
APP_ENV=staging  
APP_DEBUG=true
DEBUGBAR_ENABLED=true
TELESCOPE_ENABLED=true
```

## 🤝 Contributing

### Development Guidelines
1. **Follow PSR-12** coding standards
2. **Write tests** for new features
3. **Update documentation** for significant changes
4. **Run code quality tools** before committing

### Code Quality Tools
```bash
# Fix code style automatically
./vendor/bin/duster fix

# Generate IDE helper files
php artisan ide-helper:generate

# Run static analysis
./vendor/bin/phpstan analyze
```

### Git Workflow
- **Feature branches** for new development
- **Pull requests** required for main branch
- **Automated testing** runs on all PRs
- **Code review** required before merging

## 📚 Documentation

- [`docs/TESTING_SUITE.md`](docs/TESTING_SUITE.md) - Comprehensive testing guide
- [`docs/PERFORMANCE_MONITORING.md`](docs/PERFORMANCE_MONITORING.md) - Performance optimization
- [`CLAUDE.md`](CLAUDE.md) - Development commands and guidelines
- [`SECURITY.md`](SECURITY.md) - Security policies and procedures

## 🔗 Links

- **Live Site**: [jordanpartridge.us](https://jordanpartridge.us)
- **Admin Panel**: [jordanpartridge.us/admin](https://jordanpartridge.us/admin)
- **GitHub**: [github.com/jordanpartridge/jordanpartridge.us](https://github.com/jordanpartridge/jordanpartridge.us)
- **Forge Deployment**: [Dashboard](https://forge.laravel.com/servers/820904/sites/2398933)

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Built with ❤️ by Jordan Partridge**  
*Laravel Developer | Cycling Enthusiast | Fat Bike Corps*