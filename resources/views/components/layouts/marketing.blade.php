@props([
    'metaTitle' => config('app.name'),
    'metaDescription' => 'Software engineer, cycling enthusiast, and adventure seeker.',
    'metaImage' => asset('images/og-image.jpg'),
    'metaType' => 'website',
    'metaUrl' => url()->current(),
    'metaJsonLd' => null,
    'publishedTime' => null,
    'authorName' => null,
    'authorTwitter' => null,
    'categories' => null,
])

<x-layouts.main>
    <x-slot name="head">
        <x-seo
            :title="$metaTitle"
            :description="$metaDescription"
            :image="$metaImage"
            :type="$metaType"
            :url="$metaUrl"
            :jsonLd="$metaJsonLd"
            :twitterCardType="'summary_large_image'"
            :publishedTime="$publishedTime"
            :authorName="$authorName"
            :authorTwitter="$authorTwitter"
            :categories="$categories"
        />
    </x-slot>

    <x-ui.marketing.header />

    {{ $slot }}

</x-layouts.main>
