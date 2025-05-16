@props([
    'animation' => 'fade-in', // Options: fade-in, slide-up, zoom-in
    'delay' => 0, // Delay in ms before animation starts
    'duration' => 500, // Duration of animation in ms
    'triggerOnce' => true, // If true, animation triggers only once
])

<div
    x-data="{
        shown: false,
        animationClass: '{{ $animation }}',
        delayMs: {{ $delay }},
        durationMs: {{ $duration }},
        triggerOnce: {{ $triggerOnce ? 'true' : 'false' }}
    }"
    x-init="
        animClasses = {
            'fade-in': 'opacity-0 transition-opacity',
            'slide-up': 'opacity-0 translate-y-8 transition-all',
            'zoom-in': 'opacity-0 scale-95 transition-all',
        };
        initialClass = animClasses[animationClass];
        $el.classList.add(...initialClass.split(' '));

        $el.style.transitionDuration = durationMs + 'ms';

        setTimeout(() => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && (!triggerOnce || !shown)) {
                        shown = true;
                        if (animationClass === 'fade-in') {
                            $el.classList.remove('opacity-0');
                        } else if (animationClass === 'slide-up') {
                            $el.classList.remove('opacity-0', 'translate-y-8');
                        } else if (animationClass === 'zoom-in') {
                            $el.classList.remove('opacity-0', 'scale-95');
                        }
                    } else if (!entry.isIntersecting && !triggerOnce) {
                        shown = false;
                        if (animationClass === 'fade-in') {
                            $el.classList.add('opacity-0');
                        } else if (animationClass === 'slide-up') {
                            $el.classList.add('opacity-0', 'translate-y-8');
                        } else if (animationClass === 'zoom-in') {
                            $el.classList.add('opacity-0', 'scale-95');
                        }
                    }
                });
            }, { threshold: 0.1 });

            observer.observe($el);
        }, delayMs);
    "
    {{ $attributes }}
>
    {{ $slot }}
</div>