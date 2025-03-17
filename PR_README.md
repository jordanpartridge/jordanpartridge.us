# Enhanced LinkedIn Meta Tags

This PR enhances the LinkedIn sharing experience by adding comprehensive meta tags specifically for LinkedIn, along with improvements to Open Graph tags that LinkedIn falls back to.

## Changes Made

1. Added essential LinkedIn-specific meta tags:
   - `linkedin:image` - Ensures LinkedIn displays the correct image when content is shared
   - `linkedin:image:alt` - Improves accessibility for shared content
   - `linkedin:author` - Added specifically for article pages

2. Enhanced Open Graph tags that LinkedIn uses as fallbacks:
   - Added `og:image:alt` for better accessibility
   - Ensured complete Open Graph tag coverage

3. Added default structured data (JSON-LD) for both articles and regular pages:
   - This improves how content appears when shared professionally

4. Created a test page at `/test-linkedin-meta` (available in local environment) to verify the meta tags

## How to Test

1. Run the project locally
2. Visit http://localhost:8000/test-linkedin-meta (in local environment)
3. View the page source to verify all meta tags are present
4. Use LinkedIn Post Inspector (https://www.linkedin.com/post-inspector/) to validate how the content will appear when shared

## Key Benefits

- More consistent and professional display of shared content on LinkedIn
- Better accessibility through alt text on images
- Improved structured data for professional content
- Consistent fallback implementation for when LinkedIn-specific tags aren't recognized

## Notes

This implementation follows LinkedIn's best practices as of 2025 and ensures compatibility with other social platforms by maintaining strong Open Graph tag support.
