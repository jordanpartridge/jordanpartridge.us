<?php

namespace Database\Seeders;

use App\Models\PromptTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PromptTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding prompt templates...');

        // Import LinkedIn post templates
        $this->seedTemplatesFromDirectory('social_post/linkedin');

        // Import Twitter post templates
        $this->seedTemplatesFromDirectory('social_post/twitter');

        // Import summary templates
        $this->seedTemplatesFromDirectory('summary');

        // Import SEO metadata templates
        $this->seedTemplatesFromDirectory('seo_metadata');

        $this->command->info('Prompt templates seeded successfully!');
    }

    /**
     * Seed templates from a specific directory.
     *
     * @param string $path
     * @return void
     */
    private function seedTemplatesFromDirectory(string $path): void
    {
        $directoryPath = database_path("ai/templates/{$path}");

        if (!File::isDirectory($directoryPath)) {
            $this->command->warn("Directory not found: {$directoryPath}");
            return;
        }

        $files = File::files($directoryPath);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $templateData = json_decode(file_get_contents($file->getPathname()), true);

            if (!$templateData) {
                $this->command->warn("Invalid JSON in file: {$file->getPathname()}");
                continue;
            }

            // Check if template with the same key and platform already exists
            $existingTemplate = PromptTemplate::where('key', $templateData['key'])
                ->where('platform', $templateData['platform'] ?? null)
                ->first();

            if ($existingTemplate) {
                // Update existing template
                $existingTemplate->update([
                    'name'          => $templateData['name'],
                    'system_prompt' => $templateData['system_prompt'],
                    'user_prompt'   => $templateData['user_prompt'],
                    'content_type'  => $templateData['content_type'],
                    'variables'     => $templateData['variables'] ?? null,
                    'parameters'    => $templateData['parameters'] ?? null,
                    'description'   => $templateData['description'] ?? null,
                    'is_active'     => true,
                ]);

                // Create a new version if the content has changed
                if ($existingTemplate->system_prompt !== $templateData['system_prompt'] ||
                    $existingTemplate->user_prompt !== $templateData['user_prompt']) {
                    $existingTemplate->createVersion();
                }

                $this->command->info("Updated template: {$templateData['name']}");
            } else {
                // Create new template
                PromptTemplate::create([
                    'name'          => $templateData['name'],
                    'key'           => $templateData['key'],
                    'system_prompt' => $templateData['system_prompt'],
                    'user_prompt'   => $templateData['user_prompt'],
                    'content_type'  => $templateData['content_type'],
                    'platform'      => $templateData['platform'] ?? null,
                    'variables'     => $templateData['variables'] ?? null,
                    'parameters'    => $templateData['parameters'] ?? null,
                    'description'   => $templateData['description'] ?? null,
                    'is_active'     => true,
                    'version'       => 1,
                ]);

                $this->command->info("Created template: {$templateData['name']}");
            }
        }
    }
}
