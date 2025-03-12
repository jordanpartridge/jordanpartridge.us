<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Seo extends Component
{
    public string $title;
    public string $description;
    public ?string $image;
    public ?string $type;
    public string $url;
    public ?string $twitterCardType;
    public ?array $jsonLd;

    public function __construct(
        ?string $title = null,
        ?string $description = null,
        ?string $image = null,
        ?string $type = 'website',
        ?string $url = null,
        ?string $twitterCardType = 'summary_large_image',
        ?array $jsonLd = null
    ) {
        $this->title = $title ?? config('app.name');
        $this->description = $description ?? 'Personal website and blog by Jordan Partridge - Software developer, cyclist, and technology enthusiast.';
        $this->image = $image ?? asset('images/social-card.jpg');
        $this->type = $type;
        $this->url = $url ?? request()->url();
        $this->twitterCardType = $twitterCardType;
        $this->jsonLd = $jsonLd;
    }

    public function render(): View
    {
        return view('components.seo');
    }
}
