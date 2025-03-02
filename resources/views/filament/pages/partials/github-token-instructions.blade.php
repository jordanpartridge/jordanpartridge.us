<div class="prose dark:prose-invert max-w-none">
    <h3>How to Generate a GitHub Token</h3>
    
    <ol class="list-decimal pl-5">
        <li>Go to <a href="https://github.com/settings/tokens" target="_blank" class="text-primary-500 hover:underline">GitHub Token Settings</a></li>
        <li>Click "Generate new token" and select "Generate new token (classic)"</li>
        <li>Give your token a descriptive name (e.g., "Website GitHub Integration")</li>
        <li>For scopes, select:
            <ul class="list-disc pl-5">
                <li>repo (Full control of private repositories)</li>
                <li>read:org (Read organization data)</li>
                <li>read:user (Read user data)</li>
            </ul>
        </li>
        <li>Click "Generate token"</li>
        <li>Copy the generated token and paste it in the field above</li>
        <li>Note: This token will only be shown once on GitHub</li>
    </ol>
    
    <div class="bg-amber-50 dark:bg-amber-900/50 p-4 rounded-lg mt-4">
        <h4 class="text-amber-800 dark:text-amber-400 font-medium">Important Notes</h4>
        <ul class="list-disc pl-5 text-amber-700 dark:text-amber-300">
            <li>Your token is saved securely in your .env file</li>
            <li>The token is required to sync repositories from GitHub</li>
            <li>If you don't have a token, you won't be able to sync repositories</li>
            <li>After saving, you'll need to refresh the page to see changes</li>
        </ul>
    </div>
</div>