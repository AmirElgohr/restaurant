<li>
    <a href="{{ route('home') }}" class="{{ Request::is('home') ? 'active' : '' }}">
        <i class="fas fa-home"></i>

        <span data-key="t-dashboard">{{ __('system.dashboard.menu') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('restaurant.users.index') }}" class="{{ Request::is('restaurant/users*') ? 'active' : '' }}">
        <i class="fas fa-users font-size-18"></i>
        <span data-key="t-{{ __('system.users.menu') }}">Admins</span>
    </a>
</li>

<li>
    <a href="{{ route('restaurant.restaurants.index') }}"
       class="{{ Request::is('restaurant/restaurants*') ? 'active' : '' }}">
        <i class="fas fa-store-alt font-size-18"></i>
        <span data-key="t-{{ __('system.restaurants.menu') }}">{{ __('system.restaurants.menu') }}</span>
    </a>
</li>


<li><a href="{{ route('restaurant.food_categories.index') }}"><i class="fas fa-list-alt font-size-18"></i> <span
            data-key="t-{{ __('system.food_categories.menu') }}">{{ __('system.food_categories.menu') }}</span></a></li>

<li><a href="{{ route('restaurant.foods.index') }}"> <i class="fas fa-hamburger font-size-18"></i> <span
            data-key="t-{{ __('system.foods.menu') }}">{{ __('system.foods.menu') }}</span></a></li>


{{--<li><a href="{{ route('restaurant.get.QR') }}"> <i class="fas fa-qrcode font-size-18"></i> <span--}}
{{--            data-key="t-{{ __('system.qr_code.menu') }}">{{ __('system.qr_code.menu') }}</span></a></li>--}}

<li><a href="{{ route('restaurant.environment.setting') }}"> <i class="fas fa-cog font-size-18"></i> <span
            data-key="t-{{ __('system.environment.menu') }}">{{ __('system.environment.menu') }}</span></a></li>
{{--<li><a href="{{ route('restaurant.themes.index') }}"> <i class="fas fa-paint-roller font-size-18"></i> <span data-key="t-{{ __('system.themes.menu') }}">{{ __('system.themes.menu') }}</span></a></li>--}}
{{--<li><a href="{{ route('restaurant.languages.index') }}"> <i class="fas  fa-language font-size-18"></i> <span data-key="t-{{ __('system.languages.menu') }}">{{ __('system.languages.menu') }}</span></a></li>--}}
