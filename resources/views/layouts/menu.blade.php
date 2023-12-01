@if(auth()->user()->hasRole('admin'))
    @include('layouts.partials._admin_menu')
@elseif(auth()->user()->hasRole('restaurant'))
    @include('layouts.partials._restaurant_menu')
@endif
