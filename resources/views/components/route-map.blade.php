<img src="https://maps.googleapis.com/maps/api/staticmap?size=9000x9000&maptype=roadmap&path=enc:{{$ride->polyline}}&key={{config('services.google_maps.key')}}" alt="Static Map" class="w-48 h-auto rounded-md"  x-show="layout === 'list'">
