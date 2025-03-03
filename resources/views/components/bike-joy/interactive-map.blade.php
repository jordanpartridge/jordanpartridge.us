<div
    x-data="{
        map: null,
        routes: [],
        markers: [],
        polylines: [],
        activeRouteId: null,
        mapLoaded: false,
        loadMap() {
            if (window.google && !this.mapLoaded) {
                this.initMap();
            } else if (!window.google) {
                // Load Google Maps API if not already loaded
                const script = document.createElement('script');
                script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config("services.google.maps_api_key") }}&callback=initGoogleMap';
                script.defer = true;
                window.initGoogleMap = () => {
                    this.initMap();
                };
                document.head.appendChild(script);
            }
        },
        initMap() {
            const defaultLocation = { lat: 43.618881, lng: -116.214607 }; // Boise, ID as default center
            this.map = new google.maps.Map(this.$refs.mapContainer, {
                center: defaultLocation,
                zoom: 10,
                styles: [
                    { featureType: "administrative", elementType: "geometry", stylers: [{ visibility: "off" }] },
                    { featureType: "poi", stylers: [{ visibility: "off" }] },
                    { featureType: "road", elementType: "labels.icon", stylers: [{ visibility: "off" }] },
                    { featureType: "transit", stylers: [{ visibility: "off" }] },
                    { featureType: "water", elementType: "geometry", stylers: [{ color: "#4B6455" }] },
                    { featureType: "landscape", elementType: "geometry", stylers: [{ color: "#E6E8E6" }] },
                ]
            });

            this.mapLoaded = true;
            this.loadRoutes();
        },
        loadRoutes() {
            // Simulate or use actual route data
            if (this.routes.length === 0) {
                // Fake routes for demonstration
                this.routes = [
                    {
                        id: 1,
                        name: 'Military Ridge Trail',
                        path: [
                            {lat: 43.618881, lng: -116.224607},
                            {lat: 43.620881, lng: -116.234607},
                            {lat: 43.625881, lng: -116.244607},
                            {lat: 43.630881, lng: -116.254607}
                        ],
                        color: '#4A6741',
                        distance: '5.2 miles',
                        elevation: '420 ft',
                        difficulty: 'Moderate'
                    },
                    {
                        id: 2,
                        name: 'Fat Tire Summit Route',
                        path: [
                            {lat: 43.615881, lng: -116.214607},
                            {lat: 43.610881, lng: -116.224607},
                            {lat: 43.605881, lng: -116.234607},
                            {lat: 43.600881, lng: -116.244607}
                        ],
                        color: '#8A9A80',
                        distance: '4.7 miles',
                        elevation: '650 ft',
                        difficulty: 'Hard'
                    }
                ];
            }

            // Clear existing markers and polylines
            this.clearMapObjects();

            // Add routes to map
            this.routes.forEach(route => {
                // Create polyline
                const polyline = new google.maps.Polyline({
                    path: route.path,
                    geodesic: true,
                    strokeColor: route.color,
                    strokeOpacity: 0.8,
                    strokeWeight: 4
                });

                // Add start marker
                const startMarker = new google.maps.Marker({
                    position: route.path[0],
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 7,
                        fillColor: '#4CAF50',
                        fillOpacity: 1,
                        strokeWeight: 2,
                        strokeColor: '#FFFFFF'
                    },
                    map: this.map,
                    title: `Start: ${route.name}`
                });

                // Add end marker
                const endMarker = new google.maps.Marker({
                    position: route.path[route.path.length - 1],
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 7,
                        fillColor: '#F44336',
                        fillOpacity: 1,
                        strokeWeight: 2,
                        strokeColor: '#FFFFFF'
                    },
                    map: this.map,
                    title: `End: ${route.name}`
                });

                // Store references
                this.polylines.push(polyline);
                this.markers.push(startMarker, endMarker);

                // Add click handler
                polyline.setMap(this.map);
                google.maps.event.addListener(polyline, 'click', () => {
                    this.selectRoute(route.id);
                });
            });

            // Fit bounds to show all routes
            this.fitMapToRoutes();
        },
        clearMapObjects() {
            // Clear polylines
            this.polylines.forEach(polyline => {
                polyline.setMap(null);
            });
            this.polylines = [];

            // Clear markers
            this.markers.forEach(marker => {
                marker.setMap(null);
            });
            this.markers = [];
        },
        selectRoute(routeId) {
            this.activeRouteId = routeId;
            const selectedRoute = this.routes.find(r => r.id === routeId);
            if (selectedRoute) {
                // Highlight selected route
                this.polylines.forEach((polyline, index) => {
                    if (index === routeId - 1) {
                        polyline.setOptions({strokeWeight: 6, strokeOpacity: 1.0});
                    } else {
                        polyline.setOptions({strokeWeight: 3, strokeOpacity: 0.6});
                    }
                });

                // Center map on route
                const bounds = new google.maps.LatLngBounds();
                selectedRoute.path.forEach(point => {
                    bounds.extend(new google.maps.LatLng(point.lat, point.lng));
                });
                this.map.fitBounds(bounds);
            }
        },
        fitMapToRoutes() {
            if (this.routes.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                this.routes.forEach(route => {
                    route.path.forEach(point => {
                        bounds.extend(new google.maps.LatLng(point.lat, point.lng));
                    });
                });
                this.map.fitBounds(bounds);
            }
        }
    }"
    x-init="loadMap"
    class="camo-border bg-gray-800 rounded-lg shadow-xl overflow-hidden"
>
    <div class="h-96 w-full" x-ref="mapContainer"></div>

    <div class="bg-gray-800 p-4">
        <h3 class="text-xl text-white military-font mb-3">DEPLOYMENT ROUTES</h3>
        <div class="space-y-2">
            <template x-for="route in routes" :key="route.id">
                <div
                    class="p-3 rounded-lg cursor-pointer transition-all duration-300 flex justify-between items-center"
                    :class="activeRouteId === route.id ? 'bg-green-800' : 'bg-gray-700 hover:bg-gray-600'"
                    @click="selectRoute(route.id)"
                >
                    <div>
                        <h4 class="text-white font-semibold" x-text="route.name"></h4>
                        <div class="flex space-x-4 text-gray-300 text-sm mt-1">
                            <span x-text="route.distance"></span>
                            <span x-text="route.elevation"></span>
                        </div>
                    </div>
                    <span
                        class="px-2 py-1 rounded text-xs font-bold"
                        :class="{
                            'bg-yellow-600 text-white': route.difficulty === 'Moderate',
                            'bg-red-600 text-white': route.difficulty === 'Hard',
                            'bg-green-600 text-white': route.difficulty === 'Easy'
                        }"
                        x-text="route.difficulty"
                    ></span>
                </div>
            </template>
        </div>
    </div>
</div>