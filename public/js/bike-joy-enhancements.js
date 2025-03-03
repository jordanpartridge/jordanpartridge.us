// Enhanced interactions for Bike Joy page

document.addEventListener('DOMContentLoaded', function() {
    // Animated stat counters
    function animateStats() {
        const statElements = document.querySelectorAll('.animate-stat');
        statElements.forEach(el => {
            const targetValue = parseFloat(el.getAttribute('data-value'));
            const suffix = el.getAttribute('data-suffix') || '';
            const duration = 2000; // Animation duration in milliseconds
            const frameDuration = 1000 / 60; // 60fps
            const totalFrames = Math.round(duration / frameDuration);
            let frame = 0;
            
            const counter = setInterval(() => {
                frame++;
                const progress = frame / totalFrames;
                const currentValue = Math.round(targetValue * progress * 10) / 10;
                
                el.textContent = currentValue.toFixed(1) + suffix;
                
                if (frame === totalFrames) {
                    clearInterval(counter);
                }
            }, frameDuration);
        });
    }
    
    // Initialize circular progress indicators
    function initProgressCircles() {
        const circles = document.querySelectorAll('.stat-circle');
        circles.forEach(circle => {
            const value = parseFloat(circle.getAttribute('data-value'));
            const max = parseFloat(circle.getAttribute('data-max')) || 100;
            const percent = (value / max) * 100;
            circle.style.setProperty('--percent', `${percent}%`);
        });
    }
    
    }
    
    // Simulate route paths on the route previews
    function initRoutePreviews() {
        document.querySelectorAll('.route-preview').forEach(preview => {
            const path = preview.querySelector('.route-path');
            const startMarker = preview.querySelector('.route-marker.start');
            const endMarker = preview.querySelector('.route-marker.end');
            
            // Random path generation for visual purposes
            const length = Math.random() * 40 + 60; // 60-100% width
            const height = Math.random() * 60 - 30; // -30 to +30px vertical variation
            const curvePoint = Math.random() * 0.6 + 0.2; // Curve point between 20-80% of path
            
            path.style.width = `${length}%`;
            path.style.top = `50%`;
            path.style.left = `10%`;
            path.style.height = `${6 + Math.random() * 4}px`; // 6-10px height
            path.style.transform = `translateY(-50%) perspective(400px) rotateX(${Math.random() * 30 - 15}deg)`;
            
            // Position markers
            startMarker.style.left = '10%';
            startMarker.style.top = '50%';
            
            endMarker.style.left = `${length + 10}%`;
            endMarker.style.top = '50%';
        });
    }
    
    ];
        
        const forecastContainer = document.querySelector('.weather-forecast-days');
        if (forecastContainer) {
            forecasts.forEach(forecast => {
                const dayEl = document.createElement('div');
                dayEl.className = 'weather-day';
                dayEl.innerHTML = `
                    <div class="flex items-center">
                        <span class="weather-icon">${forecast.icon}</span>
                        <span>${forecast.day}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-3">${forecast.temp}</span>
                        <span class="text-sm text-gray-400">${forecast.condition}</span>
                    </div>
                `;
                forecastContainer.appendChild(dayEl);
            });
        }
    }
    
    // Init all enhancements
    function initEnhancements() {
        animateStats();
        initProgressCircles();
        initRoutePreviews();
        
    }
    
    // Run initializations
    initEnhancements();
    
    // Add to window for Alpine.js
    window.bikeJoyEnhancements = {
        animateStats,
        initProgressCircles,
        initRoutePreviews,
        
    };
});