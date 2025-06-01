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
     * Track if essential data has been seeded
     */
    protected static bool $dataSeedComplete = false;

    /**
     * Reset static tracking variables between test classes.
     */
    public static function tearDownAfterClass(): void
    {
        static::$dataSeedComplete = false;
        parent::tearDownAfterClass();
    }

    /**
     * Setup the test environment with optimized database setup.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Seed essential data only once per test session for better performance
        $this->seedEssentialDataOnce();
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
     * Seed essential data only once per test session for better performance.
     */
    protected function seedEssentialDataOnce(): void
    {
        if (static::$dataSeedComplete) {
            return;
        }

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

        static::$dataSeedComplete = true;
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
            // Disable web security and display compositor for stable testing
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

        if (env('CI_DEBUG', false)) {
            echo "🔧 Ensuring ChromeDriver is running...\n";
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
                "ChromeDriver binary not found in any expected location. Run 'php artisan dusk:chrome-driver' to install it for your platform."
            );
        }

        // Set DISPLAY for Linux CI
        if (! isset($_ENV['DISPLAY']) && PHP_OS_FAMILY === 'Linux') {
            $_ENV['DISPLAY'] = ':99';
            if (env('CI_DEBUG', false)) {
                echo "📺 Set DISPLAY to :99 for Linux CI\n";
            }
        }

        // Test if ChromeDriver is already running
        $connection = @fsockopen('localhost', 9515, $errno, $errstr, 2);
        if ($connection) {
            fclose($connection);
            if (env('CI_DEBUG', false)) {
                echo "✅ ChromeDriver already running on port 9515\n";
            }
            $chromeDriverStarted = true;
            return;
        }

        if (env('CI_DEBUG', false)) {
            echo "⚠️ ChromeDriver not found on port 9515, starting it...\n";
        }

        if (! static::runningInSail()) {
            try {
                static::startChromeDriver(['--verbose']);
                if (env('CI_DEBUG', false)) {
                    echo "✅ ChromeDriver started via Dusk\n";
                }

                // Give it time to start
                sleep(3);

                // Verify it's responding
                $connection = @fsockopen('localhost', 9515, $errno, $errstr, 5);
                if ($connection) {
                    fclose($connection);
                    if (env('CI_DEBUG', false)) {
                        echo "✅ ChromeDriver is now responding\n";
                    }
                    $chromeDriverStarted = true;
                } else {
                    throw new Exception("ChromeDriver failed to respond after startup: $errno - $errstr");
                }

            } catch (Exception $e) {
                if (env('CI_DEBUG', false)) {
                    echo "❌ Failed to start ChromeDriver: " . $e->getMessage() . "\n";
                    echo "🔍 Debugging info:\n";
                    echo "- PHP_OS_FAMILY: " . PHP_OS_FAMILY . "\n";
                    echo "- DISPLAY: " . ($_ENV['DISPLAY'] ?? 'not set') . "\n";
                    echo "- ChromeDriver binary exists: " . (file_exists(base_path('vendor/laravel/dusk/bin/chromedriver-linux')) ? 'yes' : 'no') . "\n";
                }
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
