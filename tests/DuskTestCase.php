<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
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
        // Only start ChromeDriver locally, not in CI where we use Selenium service
        if (! static::runningInSail() && ! static::runningInCI()) {
            // Check if ChromeDriver is already running before attempting to start
            if (! static::isChromeDriverRunning()) {
                static::startChromeDriver();
            }
        }
    }

    /**
     * Determine if we're running in CI environment.
     */
    protected static function runningInCI(): bool
    {
        return isset($_ENV['CI']) || isset($_ENV['GITHUB_ACTIONS']) || isset($_ENV['DUSK_DRIVER_URL']);
    }

    /**
     * Check if ChromeDriver is already running on the expected port.
     */
    protected static function isChromeDriverRunning(): bool
    {
        $driverUrl = $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515';
        $port = parse_url($driverUrl, PHP_URL_PORT) ?? 9515;

        // Check if port is listening
        $connection = @fsockopen('localhost', $port, $errno, $errstr, 1);
        if ($connection) {
            fclose($connection);
            return true;
        }

        return false;
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions())->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            // Add additional options to help with macOS security issues
            '--no-sandbox',
            '--disable-dev-shm-usage',
            // Add debugging options for non-headless mode
            $this->hasHeadlessDisabled() ? '--disable-web-security' : '',
            $this->hasHeadlessDisabled() ? '--disable-features=VizDisplayCompositor' : '',
        ])->filter()->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        // Try to use a different port
        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
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
