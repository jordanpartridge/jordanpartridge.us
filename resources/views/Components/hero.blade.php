<section class="hero is-palette-dark is-medium">
    <!-- Hero head: will stick at the top -->
    <div class="hero-head">
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <figure class="image is-32x32 m-1 container">
                        <img class="is-rounded" src="/img/hero.gif" />
                    </figure>
                    <span class="navbar-burger" data-target="navbarMenuHeroA">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </span>
                </div>
                <div id="navbarMenuHeroA" class="navbar-menu">
                    <div class="navbar-end">
                       <x-nav/>

                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Hero content: will be in the middle -->
    <div class="hero-body">
        <div class="container has-text-centered">
            {{ $slot}}
        </div>
    </div>

    <!-- Hero footer: will stick at the bottom -->
    <div class="hero-foot">
        <nav class="tabs">
{{--            <div class="container">--}}
{{--                <ul>--}}
{{--                    <li class="is-active"><a>Software Engineering</a></li>--}}
{{--                    <li><x-link href="/">Home</x-link></li>--}}
{{--                    <li><a>Grid</a></li>--}}
{{--                    <li><a>Elements</a></li>--}}
{{--                    <li><a>Components</a></li>--}}
{{--                    <li><a>Layout</a></li>--}}
{{--                </ul>--}}
{{--            </div>--}}
        </nav>
    </div>
</section>
