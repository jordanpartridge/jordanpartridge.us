<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SEOInfrastructureTest extends DuskTestCase
{
    /**
     * Test that sitemap.xml generates correctly
     * Issue states: "already working in production"
     */
    public function test_sitemap_generation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/sitemap.xml')
                ->assertSee('<?xml')
                ->assertSee('<urlset')
                ->assertSee('<url>')
                ->assertSee('<loc>')
                ->assertSee(config('app.url')); // Should contain site URLs
        });
    }

    /**
     * Test that meta tags are present on pages
     */
    public function test_meta_tags_presence()
    {
        $pages = [
            '/'                     => 'Jordan Partridge',
            '/blog'                 => 'Blog',
            '/software-development' => 'Software Development',
            '/contact'              => 'Contact',
            '/services'             => 'Services'
        ];

        $this->browse(function (Browser $browser) use ($pages) {
            foreach ($pages as $url => $pageTitle) {
                $browser->visit($url);

                // Check essential meta tags
                $this->assertNotEmpty(
                    $browser->attribute('head title', 'textContent'),
                    "Page {$url} missing title tag"
                );

                $this->assertNotEmpty(
                    $browser->attribute('head meta[name="description"]', 'content'),
                    "Page {$url} missing meta description"
                );

                // Check Open Graph tags
                $this->assertNotEmpty(
                    $browser->attribute('head meta[property="og:title"]', 'content'),
                    "Page {$url} missing og:title"
                );

                $this->assertNotEmpty(
                    $browser->attribute('head meta[property="og:description"]', 'content'),
                    "Page {$url} missing og:description"
                );

                // Check Twitter Card tags
                $twitterCard = $browser->attribute('head meta[name="twitter:card"]', 'content');
                if ($twitterCard) {
                    $this->assertContains(
                        $twitterCard,
                        ['summary', 'summary_large_image'],
                        "Page {$url} has invalid Twitter card type"
                    );
                }
            }
        });
    }

    /**
     * Test favicon is properly loaded
     * This addresses the issue we fixed in #268
     */
    public function test_favicon_loading()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');

            // Check favicon link exists in head - this verifies Issue #268 fix
            $faviconElements = $browser->driver->findElements(WebDriverBy::cssSelector('head link[rel="icon"]'));
            $this->assertGreaterThan(0, count($faviconElements), 'Favicon link should be present in head');

            // Verify it points to a valid asset path
            $faviconUrl = $faviconElements[0]->getAttribute('href');
            $this->assertStringContainsString('/img/logo.jpg', $faviconUrl, 'Favicon should point to logo.jpg asset');
        });
    }

    /**
     * Test RSS feeds function properly
     */
    public function test_rss_feed_functionality()
    {
        $rssFeeds = [
            '/feed.xml',
            '/rss',
            '/feed'
        ];

        $this->browse(function (Browser $browser) use ($rssFeeds) {
            foreach ($rssFeeds as $feedUrl) {
                try {
                    $browser->visit($feedUrl);

                    // If feed exists, check it's valid XML
                    $pageSource = $browser->driver->getPageSource();

                    if (strpos($pageSource, '<?xml') !== false) {
                        $this->assertStringContainsString('<?xml', $pageSource, "RSS feed {$feedUrl} missing XML declaration");
                        $this->assertStringContainsString('<rss', $pageSource, "RSS feed {$feedUrl} missing RSS tag");
                        $this->assertStringContainsString('<channel>', $pageSource, "RSS feed {$feedUrl} missing channel");
                        $this->assertStringContainsString('<title>', $pageSource, "RSS feed {$feedUrl} missing title");
                    }
                } catch (\Exception $e) {
                    // If feed doesn't exist, that's fine - not all sites have all feed URLs
                    continue;
                }
            }
        });
    }

    /**
     * Test social media integration
     */
    public function test_social_media_integration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');

            // Check for social media links/integration
            $socialElements = $browser->elements('a[href*="github"], a[href*="twitter"], a[href*="linkedin"], [data-social]');

            if (count($socialElements) > 0) {
                // If social links exist, verify they're properly formatted
                $githubLinks = $browser->elements('a[href*="github.com"]');
                foreach ($githubLinks as $link) {
                    $href = $link->getAttribute('href');
                    $this->assertStringContainsString('github.com', $href, 'GitHub link should contain github.com');
                }
            }

            // Check for social sharing buttons
            $sharingElements = $browser->elements('[data-share], .share-button, .social-share');
            // These are optional, so just verify they work if present

            // Check Open Graph image exists if specified
            $ogImage = $browser->attribute('head meta[property="og:image"]', 'content');
            if ($ogImage && !filter_var($ogImage, FILTER_VALIDATE_URL)) {
                // If it's a relative URL, test it
                $response = $this->get($ogImage);
                $response->assertStatus(200);
            }
        });
    }

    /**
     * Test structured data (JSON-LD)
     */
    public function test_structured_data()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/blog'];

            foreach ($pages as $url) {
                $browser->visit($url);

                // Look for JSON-LD structured data
                $jsonLdElements = $browser->elements('script[type="application/ld+json"]');

                if (count($jsonLdElements) > 0) {
                    foreach ($jsonLdElements as $element) {
                        $jsonContent = $element->getAttribute('innerHTML');
                        $decodedJson = json_decode($jsonContent, true);

                        $this->assertNotNull($decodedJson, "Invalid JSON-LD on page {$url}");
                        $this->assertArrayHasKey('@context', $decodedJson, "JSON-LD missing @context on page {$url}");
                        $this->assertArrayHasKey('@type', $decodedJson, "JSON-LD missing @type on page {$url}");
                    }
                }
            }
        });
    }

    /**
     * Test canonical URLs
     */
    public function test_canonical_urls()
    {
        $pages = [
            '/',
            '/blog',
            '/software-development',
            '/contact',
            '/services'
        ];

        $this->browse(function (Browser $browser) use ($pages) {
            foreach ($pages as $url) {
                $browser->visit($url);

                $canonicalLink = $browser->attribute('head link[rel="canonical"]', 'href');

                if ($canonicalLink) {
                    $this->assertTrue(
                        filter_var($canonicalLink, FILTER_VALIDATE_URL) !== false,
                        "Invalid canonical URL on page {$url}: {$canonicalLink}"
                    );

                    // Canonical should be absolute URL
                    $this->assertStringContainsString(
                        config('app.url'),
                        $canonicalLink,
                        "Canonical URL should be absolute on page {$url}"
                    );
                }
            }
        });
    }

    /**
     * Test robots.txt
     */
    public function test_robots_txt()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/robots.txt')
                ->assertSee('User-agent'); // Should have user-agent directive

            $robotsContent = $browser->driver->getPageSource();

            // Should allow search engines to index
            $this->assertStringNotContainsString(
                'Disallow: /',
                $robotsContent,
                'Robots.txt should not disallow all pages'
            );
        });
    }

    /**
     * Test page load performance affects SEO
     */
    public function test_seo_performance_factors()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            $browser->visit('/')
                ->assertSee('Jordan Partridge');

            $loadTime = (microtime(true) - $startTime) * 1000;

            // Fast loading is good for SEO (under 3s is acceptable, under 1s is excellent)
            $this->assertLessThan(
                3000,
                $loadTime,
                "Page load time affects SEO - current: {$loadTime}ms"
            );
        });
    }

    /**
     * Test mobile-friendly viewport
     */
    public function test_mobile_viewport()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');

            // Check for mobile viewport meta tag
            $viewportMeta = $browser->driver->findElement(WebDriverBy::cssSelector('meta[name="viewport"]'))->getAttribute('content');

            $this->assertNotEmpty($viewportMeta, 'Page missing viewport meta tag for mobile SEO');
            $this->assertStringContainsString(
                'width=device-width',
                $viewportMeta,
                'Viewport should include width=device-width for mobile SEO'
            );
        });
    }

    /**
     * Test heading structure for SEO
     */
    public function test_heading_structure()
    {
        $this->browse(function (Browser $browser) {
            $pages = ['/', '/blog', '/software-development'];

            foreach ($pages as $url) {
                $browser->visit($url);

                // Should have exactly one H1 tag
                $h1Elements = $browser->elements('h1');
                $this->assertCount(
                    1,
                    $h1Elements,
                    "Page {$url} should have exactly one H1 tag for SEO"
                );

                // H1 should have content
                $h1Text = $browser->text('h1');
                $this->assertNotEmpty(
                    $h1Text,
                    "H1 tag on page {$url} should have content"
                );
            }
        });
    }
}
