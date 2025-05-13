<?php

namespace Tests\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Mockery;

class AssetHelper
{
    /**
     * Skip test if assets are required but not built, or mock Vite if specified
     */
    public static function handleMissingAssets(bool $mockVite = true)
    {
        $manifestPath = public_path('build/manifest.json');

        if (!File::exists($manifestPath)) {
            if ($mockVite) {
                // Create empty manifest file for Vite helper with proper structure
                File::makeDirectory(dirname($manifestPath), 0755, true, true);
                $manifest = [
                    'resources/css/app.css' => [
                        'file' => 'assets/app-mock.css',
                        'src'  => 'resources/css/app.css'
                    ],
                    'resources/js/app.js' => [
                        'file' => 'assets/app-mock.js',
                        'src'  => 'resources/js/app.js'
                    ]
                ];
                File::put($manifestPath, json_encode($manifest));

                // Create mock asset files
                $assetPath = public_path('build/assets');
                File::makeDirectory($assetPath, 0755, true, true);
                File::put("{$assetPath}/app-mock.css", '/* Mock CSS */');
                File::put("{$assetPath}/app-mock.js", '/* Mock JS */');

                // Mock global Vite helper
                App::bind('Illuminate\Foundation\Vite', function () {
                    $mock = Mockery::mock('Illuminate\Foundation\Vite');
                    $mock->shouldReceive('asset')->andReturn('/build/assets/app-mock.js');
                    $mock->shouldReceive('reactRefresh')->andReturn('');
                    $mock->shouldReceive('__invoke')->andReturn('<link rel="stylesheet" href="/build/assets/app-mock.css"><script src="/build/assets/app-mock.js"></script>');
                    return $mock;
                });
            } else {
                test()->markTestSkipped('This test requires frontend assets to be built (npm run build)');
            }
        }
    }
}
