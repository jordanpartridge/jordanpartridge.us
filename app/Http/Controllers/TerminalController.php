<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class TerminalController extends Controller
{
    /**
     * Execute a terminal command
     */
    public function execute(Request $request)
    {
        $command = $request->input('command');
        
        if (empty($command)) {
            return response()->json([
                'type' => 'error',
                'content' => 'No command provided'
            ]);
        }

        try {
            // Parse the command
            $parsed = $this->parseCommand($command);
            
            if (!$parsed) {
                return response()->json([
                    'type' => 'error',
                    'content' => "Command not found: {$command}"
                ]);
            }

            // Execute the command
            $output = $this->executeArtisanCommand($parsed['command'], $parsed['parameters']);
            
            return response()->json([
                'type' => 'output',
                'content' => $output,
                'command' => $command
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'content' => 'Command execution failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Parse terminal command into artisan command and parameters
     */
    private function parseCommand(string $command): ?array
    {
        // Handle built-in commands
        $builtInCommands = [
            'help' => ['command' => 'help', 'parameters' => []],
            'clear' => ['command' => 'clear', 'parameters' => []],
            'cls' => ['command' => 'clear', 'parameters' => []],
            'ls' => ['command' => 'list:sections', 'parameters' => []],
        ];

        if (isset($builtInCommands[$command])) {
            return $builtInCommands[$command];
        }

        // Handle artisan commands
        if (preg_match('/^php artisan (.+)$/', $command, $matches)) {
            $artisanCommand = $matches[1];
            
            // Map frontend artisan commands to our terminal commands
            $commandMap = [
                'about' => 'about',
                'show:skills' => 'show:skills',
                'show:projects' => 'show:projects',
                'make:contact' => 'make:contact',
                'quote:website' => 'quote:website',
                'consult:schedule' => 'consult:schedule',
                'make:coffee' => 'make:coffee'
            ];

            // Parse command with options
            $parts = explode(' ', $artisanCommand);
            $baseCommand = $parts[0];

            if (isset($commandMap[$baseCommand])) {
                return [
                    'command' => $commandMap[$baseCommand],
                    'parameters' => $this->parseParameters(array_slice($parts, 1))
                ];
            }
        }

        // Handle composer commands
        if (preg_match('/^composer require (.+)$/', $command, $matches)) {
            $package = $matches[1];
            return [
                'command' => 'composer:require',
                'parameters' => ['package' => $package]
            ];
        }

        // Handle cd commands
        if (preg_match('/^cd (.+)$/', $command, $matches)) {
            return [
                'command' => 'cd',
                'parameters' => ['path' => $matches[1]]
            ];
        }

        return null;
    }

    /**
     * Parse command parameters
     */
    private function parseParameters(array $parts): array
    {
        $parameters = ['--format' => 'terminal']; // Always use terminal format
        
        foreach ($parts as $part) {
            if (strpos($part, '--') === 0) {
                if (strpos($part, '=') !== false) {
                    [$key, $value] = explode('=', $part, 2);
                    $parameters[$key] = $value;
                } else {
                    $parameters[$part] = true;
                }
            }
        }

        return $parameters;
    }

    /**
     * Execute artisan command and capture output
     */
    private function executeArtisanCommand(string $command, array $parameters): string
    {
        // Handle special built-in commands
        if ($command === 'clear') {
            return 'Terminal cleared.';
        }

        if ($command === 'help') {
            return $this->getHelpText();
        }

        if ($command === 'list:sections') {
            return "Available sections:\nintro\nskills\nprojects\ncontact";
        }

        if ($command === 'cd') {
            return "Changed directory to: ~/{$parameters['path']}";
        }

        // Create buffered output to capture command output
        $output = new BufferedOutput();
        
        try {
            // Execute the artisan command
            Artisan::call($command, $parameters, $output);
            
            $result = $output->fetch();
            
            // If no output, return success message
            if (empty(trim($result))) {
                return "Command executed successfully.";
            }
            
            return $result;
            
        } catch (\Exception $e) {
            return "Error executing command: " . $e->getMessage();
        }
    }

    /**
     * Get help text for available commands
     */
    private function getHelpText(): string
    {
        return "Available commands:

Core Commands:
  help                          Show this help message
  clear, cls                    Clear the terminal
  ls                           List available sections
  cd <path>                    Change directory

Portfolio Commands:
  php artisan about            Learn about Jordan Partridge
  php artisan show:skills      Display technical skills
  php artisan show:projects    Browse project portfolio
  php artisan make:contact     Get in touch

Service Commands:
  php artisan quote:website    Get project estimate
  php artisan consult:schedule Schedule consultation

Fun Commands:
  php artisan make:coffee      Brew coffee for coding
  composer require <package>   Install packages

Tips:
  - Use tab for autocomplete
  - Use arrow keys for command history
  - Try 'composer require jordanpartridge/expertise'
  - Commands support options: --help, --format=json";
    }
}