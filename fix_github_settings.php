<?php

// Update the github-arsenal.blade.php file to use the GitHubSettings correctly
$file_path = 'resources/views/components/software-development/github-arsenal.blade.php';
$content = file_get_contents($file_path);

// Replace the incorrect static method call with a proper way to access settings
$content = str_replace(
    "\$username = \\App\\Settings\\GitHubSettings::get('username') ?? 'jordanpartridge';",
    "\$settings = app(\\App\\Settings\\GitHubSettings::class);\n\$username = \$settings->username ?? 'jordanpartridge';",
    $content
);

file_put_contents($file_path, $content);
echo "Updated GitHubSettings usage in github-arsenal.blade.php.\n";

// Check if we should add a static get method to the GitHubSettings class for backward compatibility
$settings_file = 'app/Settings/GitHubSettings.php';
$settings_content = file_get_contents($settings_file);

// Only add the method if it doesn't already exist
if (strpos($settings_content, 'public static function get') === false) {
    // Find the proper position to insert the method (before the last closing brace)
    $pos = strrpos($settings_content, '}');
    $new_method = <<<'EOD'

    /**
     * Get a setting value by key
     * This is a convenience method for backward compatibility
     */
    public static function get(string $key, $default = null)
    {
        $settings = app(self::class);
        return $settings->$key ?? $default;
    }

EOD;

    // Insert the new method
    $settings_content = substr_replace($settings_content, $new_method, $pos, 0);
    file_put_contents($settings_file, $settings_content);
    echo "Added a static get() method to GitHubSettings for backward compatibility.\n";
}
