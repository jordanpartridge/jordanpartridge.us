<?php

namespace Tests;

use Exception;
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
        if (! static::runningInSail()) {
            // ChromeDriver will be started when needed in ensureChromeDriverIsRunning()
            echo "🔧 Dusk environment prepared\n";
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        // Ensure ChromeDriver is running before trying to connect
        $this->ensureChromeDriverIsRunning();

        $options = (new ChromeOptions())->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--disable-web-security',
            '--disable-features=VizDisplayCompositor',
        ])->filter()->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    /**
     * Ensure ChromeDriver is running and accessible.
     */
    protected function ensureChromeDriverIsRunning(): void
    {
        static $chromeDriverStarted = false;

        if ($chromeDriverStarted) {
            return;
        }

        echo "🔧 Ensuring ChromeDriver is running...\n";

        // Set DISPLAY for Linux CI
        if (! isset($_ENV['DISPLAY']) && PHP_OS_FAMILY === 'Linux') {
            $_ENV['DISPLAY'] = ':99';
            echo "📺 Set DISPLAY to :99 for Linux CI\n";
        }

        // Test if ChromeDriver is already running
        $connection = @fsockopen('localhost', 9515, $errno, $errstr, 2);
        if ($connection) {
            fclose($connection);
            echo "✅ ChromeDriver already running on port 9515\n";
            $chromeDriverStarted = true;
            return;
        }

        echo "⚠️ ChromeDriver not found on port 9515, starting it...\n";

        if (! static::runningInSail()) {
            try {
                static::startChromeDriver(['--verbose']);
                echo "✅ ChromeDriver started via Dusk\n";

                // Give it time to start
                sleep(3);

                // Verify it's responding
                $connection = @fsockopen('localhost', 9515, $errno, $errstr, 5);
                if ($connection) {
                    fclose($connection);
                    echo "✅ ChromeDriver is now responding\n";
                    $chromeDriverStarted = true;
                } else {
                    throw new Exception("ChromeDriver failed to respond after startup: $errno - $errstr");
                }

            } catch (Exception $e) {
                echo "❌ Failed to start ChromeDriver: " . $e->getMessage() . "\n";
                echo "🔍 Debugging info:\n";
                echo "- PHP_OS_FAMILY: " . PHP_OS_FAMILY . "\n";
                echo "- DISPLAY: " . ($_ENV['DISPLAY'] ?? 'not set') . "\n";
                echo "- ChromeDriver binary exists: " . (file_exists(base_path('vendor/laravel/dusk/bin/chromedriver-linux')) ? 'yes' : 'no') . "\n";
                throw $e;
            }
        }
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
