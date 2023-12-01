@extends('layouts.app')
@section('title', __('system.dashboard.menu'))
@section('content')
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">{{ __('system.dashboard.menu') }}</h4>


            </div>
        </div>
    </div>
    <div class="row">
        @if($user->hasRole('admin'))
            <div class="col-xl-3 col-md-6">
                <div class="card card-h-100">
                    <div class="card-body">
                        <a href="{{ route('restaurant.restaurants.index') }}">
                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">
                                <span
                                    class="text-muted mb-3 lh-1 d-block text-truncate">{{ __('system.dashboard.total_restaurants') }}</span>
                                    <h4 class="mb-3">
                                        <span class="counter-value" data-target="{{ $users_count }}">0</span>
                                    </h4>
                                </div>

                            </div>
                        </a>
                    </div>
                </div>
            </div>


        @elseif($user->hasRole('restaurant'))

        @endif


        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <a href="{{ route('restaurant.food_categories.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span
                                    class="text-muted mb-3 lh-1 d-block text-truncate">{{ __('system.dashboard.total_categories') }}</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $categories_count }}">0</span>
                                </h4>

                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card card-h-100">

                <div class="card-body">
                    <a href="{{ route('restaurant.foods.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span
                                    class="text-muted mb-3 lh-1 d-block text-truncate">{{ __('system.dashboard.total_foods') }}</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $foods_count }}">0</span>
                                </h4>

                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        @php($class = 'col-xl-6')
        @php($width = '250px')

        @if($user->hasRole('admin'))
            @php($class = 'col-xl-6')
            @php($width = '300px')

            <div class="col-xl-6">
                @include('dashboard.partials.cards._recent_restaurants')
            </div>

{{--            <div class="{{ $class }}">--}}
{{--                @include('dashboard.partials.cards._recent_users')--}}
{{--            </div>--}}
        @elseif($user->hasRole('restaurant'))
        @endif


        <div class="{{ $class }}">
            @include('dashboard.partials.cards._recent_categories')
        </div>

        <div class="{{ $class }}">
            @include('dashboard.partials.cards._recent_foods')
        </div>

    </div>
@endsection
