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

<!-- Twitter -->
<meta property="twitter:card" content="{{ $twitterCardType }}">
<meta property="twitter:url" content="{{ $url }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="{{ $image }}">

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
