<?php

namespace App\Services\Gmail;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Log;

class EmailContentService
{
    private HTMLPurifier $purifier;
    private array $config;

    public function __construct()
    {
        $this->config = config('gmail-integration.security', []);
        $this->initializePurifier();
    }

    /**
     * Process and sanitize email content for safe display
     */
    public function processEmailContent(array $emailData): array
    {
        $processed = [
            'subject'         => $this->sanitizeText($emailData['subject'] ?? ''),
            'from'            => $this->sanitizeText($emailData['from'] ?? ''),
            'date'            => $emailData['date'] ?? '',
            'snippet'         => $this->sanitizeText($emailData['snippet'] ?? ''),
            'body_html'       => '',
            'body_text'       => '',
            'has_attachments' => $emailData['has_attachments'] ?? false,
            'attachments'     => $emailData['attachments'] ?? [],
        ];

        // Process HTML content if available
        if (!empty($emailData['body_html'])) {
            $processed['body_html'] = $this->sanitizeHtml($emailData['body_html']);
        }

        // Process plain text content
        if (!empty($emailData['body_text'])) {
            $processed['body_text'] = $this->sanitizeText($emailData['body_text']);
        }

        // If no HTML content, use text content
        if (empty($processed['body_html']) && !empty($processed['body_text'])) {
            $processed['body_html'] = $this->convertTextToHtml($processed['body_text']);
        }

        return $processed;
    }

    /**
     * Sanitize HTML content for email display
     */
    public function sanitizeHtml(string $html): string
    {
        if (!$this->config['security']['sanitize_html'] ?? true) {
            return $html;
        }

        // Check for size limits
        $maxSize = $this->config['security']['max_email_size'] ?? 1048576; // 1MB
        if (strlen($html) > $maxSize) {
            $html = substr($html, 0, $maxSize) . '... [Content truncated for safety]';
        }

        // Use simplified rendering mode for reliability
        if ($this->config['security']['marketing_email_mode'] ?? true) {
            return $this->simplifiedHtmlSanitization($html);
        }

        // Fallback to HTMLPurifier with error handling
        try {
            // Pre-process HTML to handle common email quirks
            $html = $this->preprocessEmailHtml($html);

            // Sanitize with HTMLPurifier
            $sanitized = $this->purifier->purify($html);

            // Post-process for better email display
            return $this->postprocessEmailHtml($sanitized);
        } catch (\Exception $e) {
            Log::warning('HTMLPurifier failed, using simplified sanitization', [
                'error' => $e->getMessage()
            ]);
            return $this->simplifiedHtmlSanitization($html);
        }
    }

    /**
     * Sanitize plain text content
     */
    public function sanitizeText(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Convert plain text to HTML with proper formatting
     */
    public function convertTextToHtml(string $text): string
    {
        // Preserve line breaks
        $html = nl2br($this->sanitizeText($text));

        // Convert URLs to links
        $html = $this->convertUrlsToLinks($html);

        return $html;
    }

    /**
     * Get enhanced styling for email content
     */
    public function getEmailStyles(): string
    {
        return '
        <style>
            .email-content {
                max-width: 100%;
                word-wrap: break-word;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                line-height: 1.5;
                color: #374151;
            }

            .dark .email-content {
                color: #d1d5db;
            }

            .email-content img {
                max-width: 100%;
                height: auto;
                display: block;
                margin: 10px 0;
            }

            .email-content table {
                max-width: 100%;
                border-collapse: collapse;
                margin: 10px 0;
            }

            .email-content td, .email-content th {
                padding: 8px;
                border: 1px solid #e5e7eb;
                vertical-align: top;
            }

            .dark .email-content td, .dark .email-content th {
                border-color: #4b5563;
            }

            .email-content blockquote {
                margin: 16px 0;
                padding: 12px 16px;
                border-left: 4px solid #3b82f6;
                background: #f8fafc;
                font-style: italic;
            }

            .dark .email-content blockquote {
                background: #1f2937;
                border-left-color: #60a5fa;
            }

            .email-content code {
                background: #f1f5f9;
                padding: 2px 4px;
                border-radius: 4px;
                font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
                font-size: 0.875em;
            }

            .dark .email-content code {
                background: #374151;
            }

            .email-content pre {
                background: #f8fafc;
                padding: 16px;
                border-radius: 8px;
                overflow-x: auto;
                margin: 16px 0;
            }

            .dark .email-content pre {
                background: #1f2937;
            }

            .email-content a {
                color: #3b82f6;
                text-decoration: underline;
            }

            .dark .email-content a {
                color: #60a5fa;
            }

            .email-content a:hover {
                color: #1d4ed8;
            }

            .dark .email-content a:hover {
                color: #93c5fd;
            }

            /* Override any email styles that might break layout */
            .email-content * {
                max-width: 100% !important;
                position: static !important;
            }

            .email-content table {
                width: auto !important;
                max-width: 100% !important;
            }
        </style>';
    }

    /**
     * Initialize HTML Purifier with email-specific configuration
     */
    private function initializePurifier(): void
    {
        $config = HTMLPurifier_Config::createDefault();

        // Enable HTML5 elements commonly used in emails
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML.TidyLevel', 'medium');

        // Very permissive configuration for marketing emails
        $config->set('HTML.Allowed', 'p,br,strong,em,u,ol,ul,li,a[href|target|title|style],img[src|alt|width|height|style|title|border|align|hspace|vspace|class],h1,h2,h3,h4,h5,h6,blockquote,code,pre,table[width|height|border|cellpadding|cellspacing|align|style|class|bgcolor],tr[align|valign|style|height|bgcolor|class],td[width|height|align|valign|colspan|rowspan|style|bgcolor|class],th[width|height|align|valign|colspan|rowspan|style|bgcolor|class],tbody,thead,tfoot,div[style|align|class|id],span[style|class|id],font[face|size|color|style],b,i,center,hr[width|size|align|style],small,big');

        // Enable custom attributes commonly used in emails
        $config->set('Attr.EnableID', true);
        $config->set('Attr.AllowedFrameTargets', ['_blank', '_self', '_parent', '_top']);
        $config->set('HTML.AllowedAttributes', ['*.style', '*.class', '*.id', '*.align', '*.valign', '*.width', '*.height', '*.border', '*.cellpadding', '*.cellspacing', '*.bgcolor', '*.colspan', '*.rowspan', 'a.href', 'a.target', 'img.src', 'img.alt', 'font.face', 'font.size', 'font.color']);

        // Disable strict attribute enforcement
        $config->set('HTML.Strict', false);
        $config->set('Attr.DefaultInvalidValue', '');

        // Configure URI handling for links and images
        $config->set('URI.AllowedSchemes', ['http', 'https', 'mailto', 'data']);
        $config->set('URI.DisableExternalResources', false);

        // More lenient handling for marketing emails
        $config->set('HTML.TidyLevel', 'none'); // Minimal cleaning
        $config->set('Output.TidyFormat', false); // Don't reformat HTML
        $config->set('Core.RemoveInvalidImg', false); // Don't remove images with invalid src
        $config->set('Core.CollectErrors', false); // Don't collect errors
        $config->set('HTML.ForbiddenElements', []); // Don't forbid any elements
        $config->set('HTML.ForbiddenAttributes', []); // Don't forbid any attributes

        // Allow inline styles (common in email HTML)
        $config->set('CSS.AllowImportant', true);
        $config->set('CSS.AllowTricky', true);
        $config->set('CSS.Proprietary', true);

        // Allow common CSS properties used in emails
        $config->set('CSS.AllowedProperties', [
            'color', 'background-color', 'background', 'font-family', 'font-size',
            'font-weight', 'font-style', 'text-align', 'text-decoration',
            'margin', 'margin-top', 'margin-bottom', 'margin-left', 'margin-right',
            'padding', 'padding-top', 'padding-bottom', 'padding-left', 'padding-right',
            'border', 'border-top', 'border-bottom', 'border-left', 'border-right',
            'width', 'height', 'max-width', 'min-width', 'display', 'float',
            'clear', 'position', 'top', 'bottom', 'left', 'right',
            'line-height', 'vertical-align', 'text-indent'
        ]);

        // Set cache directory
        $config->set('Cache.SerializerPath', storage_path('framework/cache/htmlpurifier'));

        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Simplified HTML sanitization for reliable email rendering
     */
    private function simplifiedHtmlSanitization(string $html): string
    {
        // Remove dangerous event handlers
        $html = preg_replace('/on\w+\s*=\s*["\'][^"\']*["\']/', '', $html);

        // Fix images without proper src
        $html = preg_replace_callback('/<img([^>]*?)>/i', function ($matches) {
            $attrs = $matches[1];

            // Check if src exists and is valid
            if (!preg_match('/src\s*=\s*["\']([^"\']+)["\']/', $attrs, $srcMatch) || empty(trim($srcMatch[1]))) {
                // No valid src, add placeholder
                $attrs .= ' src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMSIgaGVpZ2h0PSIxIiB2aWV3Qm94PSIwIDAgMSAxIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiNGM0Y0RjYiLz48L3N2Zz4K"';
            }
            return '<img' . $attrs . '>';
        }, $html);

        // Allow common email HTML tags with basic filtering
        $allowedTags = '<p><br><strong><em><u><ol><ul><li><a><h1><h2><h3><h4><h5><h6><table><tr><td><th><tbody><thead><tfoot><div><span><font><b><i><center><hr><img>';
        $html = strip_tags($html, $allowedTags);

        return $html;
    }

    /**
     * Pre-process email HTML to handle common issues
     */
    private function preprocessEmailHtml(string $html): string
    {
        // Remove common email client specific tags that cause issues
        $html = preg_replace('/<o:p[^>]*>.*?<\/o:p>/is', '', $html); // Outlook tags
        $html = preg_replace('/<v:[^>]*>.*?<\/v:[^>]*>/is', '', $html); // VML tags

        // Fix images without src attributes (common in marketing emails)
        $html = preg_replace_callback(
            '/<img([^>]*?)>/i',
            function ($matches) {
                $attrs = $matches[1];

                // Check if src attribute exists and is valid
                if (preg_match('/src\s*=\s*["\']([^"\']*)["\']/', $attrs, $srcMatch)) {
                    $srcValue = trim($srcMatch[1]);
                    if (empty($srcValue)) {
                        // Empty src, replace with placeholder
                        $attrs = preg_replace('/src\s*=\s*["\'][^"\']*["\']/', 'src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMSIgaGVpZ2h0PSIxIiB2aWV3Qm94PSIwIDAgMSAxIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiNGM0Y0RjYiLz48L3N2Zz4K"', $attrs);
                    }
                } else {
                    // No src attribute, check for data-src or add placeholder
                    if (preg_match('/data-src\s*=\s*["\']([^"\']+)["\']/', $attrs, $dataSrcMatch)) {
                        $dataSrcValue = trim($dataSrcMatch[1]);
                        if (!empty($dataSrcValue)) {
                            $attrs .= ' src="' . htmlspecialchars($dataSrcValue) . '"';
                        }
                    } else {
                        // Add placeholder src
                        $attrs .= ' src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMSIgaGVpZ2h0PSIxIiB2aWV3Qm94PSIwIDAgMSAxIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiNGM0Y0RjYiLz48L3N2Zz4K"';
                    }
                }

                return '<img' . $attrs . '>';
            },
            $html
        );

        // Handle relative URLs in remaining images and links
        $html = preg_replace_callback('/src=(["\'])(?!https?:\/\/|data:|\/\/)([^"\']*)\1/i', function ($matches) {
            // For relative URLs, we'll add placeholder for security
            return 'src=' . $matches[1] . 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMSIgaGVpZ2h0PSIxIiB2aWV3Qm94PSIwIDAgMSAxIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiNGM0Y0RjYiLz48L3N2Zz4K' . $matches[1];
        }, $html);

        // Handle CSS that might break layout
        $html = preg_replace('/position:\s*fixed/i', 'position: static', $html);
        $html = preg_replace('/position:\s*absolute/i', 'position: static', $html);

        return $html;
    }

    /**
     * Post-process sanitized HTML for better email display
     */
    private function postprocessEmailHtml(string $html): string
    {
        // Wrap content in a container for better styling
        $html = '<div class="email-content">' . $html . '</div>';

        // Add target="_blank" to all links for security
        $html = preg_replace('/<a\s+(?![^>]*target=)[^>]*>/i', '<a target="_blank" $0', $html);
        $html = str_replace('<a target="_blank" <a ', '<a target="_blank" ', $html);

        return $html;
    }

    /**
     * Convert URLs in text to clickable links
     */
    private function convertUrlsToLinks(string $text): string
    {
        $pattern = '/https?:\/\/[^\s<>"{}|\\^`\[\]]+/i';
        return preg_replace($pattern, '<a href="$0" target="_blank" rel="noopener noreferrer">$0</a>', $text);
    }
}
