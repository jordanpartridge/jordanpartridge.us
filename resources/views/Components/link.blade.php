<a class="navbar-item {{ url($attributes->get('href')) == url()->current() ? 'is-active' : '' }}" {{$attributes->merge()}}>{{$slot}}</a>
