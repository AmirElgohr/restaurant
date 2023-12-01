@extends('layouts.app')
@section('title', __('system.restaurants.update.menu', ['restaurant' => strtolower($restaurant->name)]))
@section('content')
    <div class="row">

        <div class="col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">{{ __('system.restaurants.update.menu', ['restaurant' => strtolower($restaurant->name)]) }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item "><a href="{{ route('restaurant.restaurants.index') }}">{{ __('system.restaurants.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.restaurants.update.menu', ['restaurant' => strtolower($restaurant->name)]) }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{ Form::model($restaurant, ['route' => ['restaurant.restaurants.update', $restaurant->id], 'method' => 'put', 'files' => true, 'id' => 'pristine-valid']) }}
                    @if (request()->query->has('back'))
                        <input type="hidden" name="back" value="{{ request()->query->get('back') }}">
                    @endif
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                    @include('restaurant.restaurants.fields')
                    <div class="row">
                        <div class="col-12 mt-3">
                            <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                            <a href="{{ request()->query->get('back', null) ?? route('restaurant.restaurants.index') }}"class="btn btn-secondary">{{ __('system.crud.back') }}</a>
                        </div>
                    </div>

                    {{ Form::close() }}


                </div>

            </div>
        </div>
    </div>
@endsection
