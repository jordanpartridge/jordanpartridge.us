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
        if (! static::runningInSail()) {
            echo "🔧 Starting ChromeDriver in CI environment...\n";

            // Set DISPLAY variable for headless Linux CI
            if (! isset($_ENV['DISPLAY']) && PHP_OS_FAMILY === 'Linux') {
                $_ENV['DISPLAY'] = ':99';
                echo "📺 Set DISPLAY environment variable to :99\n";
            }

            try {
                static::startChromeDriver(['--verbose', '--log-level=DEBUG']);
                echo "✅ ChromeDriver started successfully\n";

                // Give ChromeDriver a moment to start
                sleep(2);

                // Test connection
                $connection = @fsockopen('localhost', 9515, $errno, $errstr, 5);
                if ($connection) {
                    echo "✅ ChromeDriver is responding on localhost:9515\n";
                    fclose($connection);
                } else {
                    echo "❌ ChromeDriver not responding: $errno - $errstr\n";
                }

            } catch (\Exception $e) {
                echo "❌ ChromeDriver failed to start: " . $e->getMessage() . "\n";
                throw $e;
            }
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
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
