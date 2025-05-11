<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('app.name') }} Blog</title>
        <link>{{ config('app.url') }}/blog</link>
        <description>Latest articles from Jordan Partridge's blog</description>
        <language>en-us</language>
        <pubDate>{{ now()->toRssString() }}</pubDate>
        <lastBuildDate>{{ $posts->first() ? $posts->first()->created_at->toRssString() : now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ config('app.url') }}/feed.xml" rel="self" type="application/rss+xml" />

        @foreach ($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ config('app.url') }}/blog/{{ $post->slug }}</link>
            <guid>{{ config('app.url') }}/blog/{{ $post->slug }}</guid>
            <pubDate>{{ $post->created_at->toRssString() }}</pubDate>
            <description>{{ htmlspecialchars($post->excerpt ?? substr(strip_tags($post->body), 0, 160) . (strlen(strip_tags($post->body)) > 160 ? '...' : '')) }}</description>
            <content:encoded><![CDATA[{!! $post->body !!}]]></content:encoded>
            @if ($post->categories->count() > 0)
                @foreach ($post->categories as $category)
                <category>{{ $category->name }}</category>
                @endforeach
            @endif
            <author>{{ $post->user->email ?? 'jordan@partridge.rocks' }} ({{ $post->user->name ?? 'Jordan Partridge' }})</author>
        </item>
        @endforeach
    </channel>
</rss>