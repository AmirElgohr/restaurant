@extends('layouts.app')
@section('title', __('system.themes.menu'))

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="tab-content">

                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-md-6 col-xl-6">
                                <h4 class="card-title">{{ __('system.themes.menu') }}</h4>
                                <div class="page-title-box pb-0 d-sm-flex">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                            <li class="breadcrumb-item active">{{ __('system.themes.menu') }}</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">

                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="row">

                            <!-- end card body -->
                            <div class="col-md-12  px-4 ">
                                <div class="row">
                                    @foreach ($themes as $theme)
                                        <div class="col-xl-3 col-sm-6">
                                            <div class="card  ">
                                                <div
                                                    class="border   @if (strtolower($theme['name']) == strtolower($restaurant->theme ?? current($themes)['name'])) border-success @else border-secondary @endif rounded-3">
                                                    <div class="row g-0 align-items-center">
                                                        <div class="col-md-12">
                                                            <div class="card-body">
                                                                <div class="row ">
                                                                    <div class="col-md-6">
                                                                        <h5 class="card-title">{{ $theme['name'] }}</h5>
                                                                    </div>
                                                                    <div class="col-md-6 text-end">
                                                                        @if (strtolower($theme['name']) == strtolower($restaurant->theme ?? current($themes)['name']))
                                                                            <span
                                                                                class="btn btn-sm btn-success disabled mb-md-2">{{ __('system.crud.default') }}</span>
                                                                            <a type="button" target="_blank"
                                                                                class="btn btn-sm btn-secondary mb-md-2"
                                                                                href="{{ route('restaurant.menu', ['restaurant' => $restaurant->id]) }}">{{ __('system.crud.preview') }}</a>
                                                                        @else
                                                                            {!! Form::open(['method' => 'put', 'route' => ['restaurant.themes.update']]) !!}
                                                                            <input type="hidden" name="theme"
                                                                                value="{{ $theme['name'] }}">
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-primary mb-md-2">{{ __('system.crud.active') }}</button>
                                                                            <a type="button" target="_blank"
                                                                                class="btn btn-sm btn-secondary mb-md-2"
                                                                                href="{{ route('restaurant.menu', ['restaurant' => $restaurant->id, 'restaurant_view' => strtolower($theme['name'])]) }}">{{ __('system.crud.preview') }}</a>
                                                                            {!! Form::close() !!}
                                                                        @endif
                                                                    </div>
                                                                </div>



                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <img class="card-img img-fluid lazyload"
                                                                data-src="{{ asset($theme['image']) }}" alt="Card image">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card -->
                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>


                    </div>

                </div>
                <!-- end card -->

            </div>
            <!-- end tab content -->
        </div>
        <!-- end col -->

    </div>
    <!-- end row -->
@endsection
