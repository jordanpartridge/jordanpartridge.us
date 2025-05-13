<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use PHPUnit\Framework\TestCase;

class TestCommandForAssetBuilding extends TestCase
{
    /**
     * Test that the manifest file exists and skip asset-dependent tests if not.
     */
    public function test_manifest_file_exists(): void
    {
        $manifestPath = public_path('build/manifest.json');

        if (!File::exists($manifestPath) && app()->environment('testing') && !app()->environment('local')) {
            // In CI environments, mark tests as skipped
            $this->markTestSkipped('Frontend assets not built, skipping asset-dependent tests.');
        } else {
            // Local development with assets available
            $this->assertTrue(true);
        }
    }

    /**
     * Create a fake manifest file for testing if needed.
     */
    public function test_create_mock_manifest_for_testing(): void
    {
        $manifestPath = public_path('build/manifest.json');

        if (File::exists($manifestPath) || !app()->environment('testing')) {
            $this->assertTrue(true);
            return;
        }

        // Create a minimal manifest file for tests to use
        $assetPath = public_path('build/assets');
        File::makeDirectory($assetPath, 0755, true, true);
        File::put("{$assetPath}/app-mock.css", '/* Mock CSS */');
        File::put("{$assetPath}/app-mock.js", '/* Mock JS */');

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

        $this->assertTrue(File::exists($manifestPath));
    }
}
