<img
    src="https://maps.googleapis.com/maps/api/staticmap?size=9000x9000&maptype=roadmap&path=enc:{{$ride->polyline}}&key={{config('services.google_maps.key')}}"
    alt="Route Map"
    class="absolute inset-0 w-full h-full object-cover transform  transition-transform duration-700 ease-in-out">
