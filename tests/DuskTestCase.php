<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (!isset($_ENV['CI'])) {
            // Only start ChromeDriver if we're not in a CI environment
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions())->addArguments([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-gpu',
            '--headless=new',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--disable-extensions',
            '--disable-software-rasterizer',
            '--disable-setuid-sandbox',
            '--enable-file-cookies',
            '--ignore-certificate-errors',
            '--proxy-server=\'direct://\'',
            '--proxy-bypass-list=*',
        ]);

        // Set page load timeout to prevent long-running operations from timing out
        $capabilities = DesiredCapabilities::chrome()
            ->setCapability(ChromeOptions::CAPABILITY, $options);

        // Use the environment variable for driver URL or default to localhost:9515
        $driverUrl = $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515';

        // Create with extended timeout configurations
        return RemoteWebDriver::create(
            $driverUrl,
            $capabilities,
            120000, // Connection timeout in milliseconds
            120000  // Request timeout in milliseconds
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     */
    protected function hasHeadlessDisabled(): bool
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Determine if the browser window should start maximized.
     */
    protected function shouldStartMaximized(): bool
    {
        return isset($_SERVER['DUSK_START_MAXIMIZED']) ||
               isset($_ENV['DUSK_START_MAXIMIZED']);
    }
}
