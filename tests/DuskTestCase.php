<?php

namespace Tests;

use App\Models\Category;
use App\Models\Post;
use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class DuskTestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Seed essential data for each test to avoid database contention
        $this->seedEssentialData();
    }

    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            // Note: Cannot check ChromeDriver binary in BeforeClass as Laravel app isn't fully initialized
            // This check will happen in ensureChromeDriverIsRunning() method instead

            // Check if ChromeDriver is already running before attempting to start
            if (! static::isChromeDriverRunning()) {
                static::startChromeDriver();
            }
        }
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
     * Seed essential data for each test to ensure clean state.
     */
    protected function seedEssentialData(): void
    {
        // Create essential roles for FilamentShield
        $roles = ['admin', 'editor', 'user', 'super_admin', 'panel_user'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create essential permissions for testing
        $permissions = ['manage users', 'edit articles', 'view articles'];
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Assign permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($permissions);
        }

        // Seed a few blog posts for testing if the Post model exists
        if (class_exists(Post::class)) {
            Post::factory()->count(3)->create([
                'status' => 'published'
            ]);
        }

        // Seed categories if they exist
        if (class_exists(Category::class)) {
            Category::factory()->count(2)->create();
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
            // Performance optimizations for CI
            '--disable-web-security',
            '--disable-features=VizDisplayCompositor',
            '--disable-background-timer-throttling',
            '--disable-renderer-backgrounding',
            '--disable-backgrounding-occluded-windows',
            '--disable-ipc-flooding-protection',
            '--disable-hang-monitor',
            '--disable-prompt-on-repost',
            '--disable-sync',
            '--force-color-profile=srgb',
            '--metrics-recording-only',
            '--no-first-run',
            '--safebrowsing-disable-auto-update',
            '--enable-automation',
            '--password-store=basic',
            '--use-mock-keychain',
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


        // Ensure ChromeDriver binary exists before attempting to start
        $chromedriverPaths = [
            base_path('vendor/laravel/dusk/bin/chromedriver-linux'),
            base_path('vendor/laravel/dusk/bin/chromedriver-mac-arm'),
            base_path('vendor/laravel/dusk/bin/chromedriver-mac-intel'),
            base_path('vendor/laravel/dusk/bin/chromedriver-win.exe'),
        ];

        $chromedriverExists = false;
        foreach ($chromedriverPaths as $path) {
            if (file_exists($path)) {
                $chromedriverExists = true;
                break;
            }
        }

        if (! $chromedriverExists) {
            throw new Exception(
                "ChromeDriver binary not found in any expected location. " .
                "Run 'php artisan dusk:chrome-driver' to install it for your platform."
            );
        }

        // Set DISPLAY for Linux CI
        if (! isset($_ENV['DISPLAY']) && PHP_OS_FAMILY === 'Linux') {
            $_ENV['DISPLAY'] = ':99';
        }

        // Test if ChromeDriver is already running
        $connection = @fsockopen('localhost', 9515, $errno, $errstr, 2);
        if ($connection) {
            fclose($connection);
            $chromeDriverStarted = true;
            return;
        }


        if (! static::runningInSail()) {
            try {
                static::startChromeDriver(['--verbose']);

                // Wait for ChromeDriver to respond with retry logic
                $maxRetries = 10;
                $retryDelay = 0.5; // 500ms
                $connected = false;

                for ($i = 0; $i < $maxRetries; $i++) {
                    usleep($retryDelay * 1000000); // Convert to microseconds
                    $connection = @fsockopen('localhost', 9515, $errno, $errstr, 1);
                    if ($connection) {
                        fclose($connection);
                        $connected = true;
                        break;
                    }
                }

                if ($connected) {
                    $chromeDriverStarted = true;
                } else {
                    throw new Exception("ChromeDriver failed to respond after startup: $errno - $errstr");
                }
            } catch (Exception $e) {
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
