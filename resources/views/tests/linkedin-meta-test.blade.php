<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <x-seo
        title="Testing LinkedIn Meta Tags"
        description="This is a test page to verify LinkedIn meta tags implementation"
        image="{{ asset('images/og-image.jpg') }}"
        type="article"
        :publishedTime="now()"
        authorName="Test Author"
        :categories="['Test', 'Meta Tags', 'LinkedIn']"
    />
</head>
<body>
    <h1>LinkedIn Meta Tags Test Page</h1>
    <p>This page is used to test the implementation of LinkedIn meta tags.</p>

    <h2>Meta Tags Added:</h2>
    <ul>
        <li>linkedin:image</li>
        <li>linkedin:image:alt</li>
        <li>linkedin:author (for articles)</li>
        <li>og:image:alt</li>
        <li>Enhanced JSON-LD for both articles and regular pages</li>
    </ul>

    <p>Use the <a href="https://www.linkedin.com/post-inspector/" target="_blank">LinkedIn Post Inspector</a> to validate these tags.</p>
</body>
</html>
