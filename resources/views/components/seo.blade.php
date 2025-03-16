@props([
    'title' => config('app.name'),
    'description' => 'Software engineer, cycling enthusiast, and adventure seeker.',
    'image' => asset('images/og-image.jpg'),
    'type' => 'website',
    'url' => url()->current(),
    'jsonLd' => null,
    'twitterCardType' => 'summary_large_image',
    'publishedTime' => null,
    'authorName' => null,
    'authorTwitter' => null,
    'categories' => [],
])

<!-- Primary Meta Tags -->
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}">
<meta name="description" content="{{ $description }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
@if ($type === 'article')
<meta property="article:published_time" content="{{ $publishedTime ?? now()->toIso8601String() }}">
<meta property="article:author" content="{{ $authorName ?? 'Jordan Partridge' }}">
@if (!empty($categories))
@foreach ($categories as $category)
<meta property="article:tag" content="{{ $category }}">
@endforeach
@endif
@endif

<!-- Twitter -->
<meta name="twitter:card" content="{{ $twitterCardType }}">
<meta name="twitter:url" content="{{ $url }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
<meta name="twitter:site" content="@jordanpartridge">
@if (!empty($authorTwitter))
<meta name="twitter:creator" content="{{ $authorTwitter }}">
@endif

<!-- Canonical URL -->
<link rel="canonical" href="{{ $url }}" />

<!-- Feeds -->
<link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} RSS Feed" href="{{ url('/feed.xml') }}" />

<!-- Structured Data / JSON-LD -->
@if ($jsonLd)
<script type="application/ld+json">
    {!! json_encode($jsonLd) !!}
</script>
@endif
