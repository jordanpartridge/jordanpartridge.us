<section class="hero is-palette-dark is-medium">
    <!-- Hero head: will stick at the top -->
    <div class="hero-head">
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <figure class="image is-32x32 m-1 container">
                        <img class="is-rounded" src="/img/hero.gif"/>
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
            <div class="container">
                <p class="buttons">
                    <x-link class="button" href="https://github.com/jordanpartridge">
                        <span class="icon">
                        <i class="fab fa-github"></i>
                         </span>
                        <span>jordanpartridge</span></x-link>
                    <a class="button" href="https://www.linkedin.com/in/jordan-partridge-8284897/">
                    <span class="icon">
                      <i class="fab fa-linkedin"></i>
                    </span>
                        <span>Connect with me</span>
                    </a>
                    <a class="button" href="https://www.strava.com/athletes/2645359">
                    <span class="icon">
                      <i class="fab fa-strava"></i>
                    </span>
                        <span>Check out all my bike joy</span>
                    </a>
                </p>
            </div>
        </nav>
    </div>
</section>
