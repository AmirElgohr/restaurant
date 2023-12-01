@extends('layouts.app')
@section('title', __('system.food_categories.update.menu', ['food_category' => $foodCategory->category_name]))
@section('content')
    <div class="row">

        <div class="col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-mb-8 col-xl-8">
                            <h4 class="card-title">{{ __('system.food_categories.update.menu', ['food_category' => $foodCategory->category_name]) }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item "><a href="{{ route('restaurant.food_categories.index') }}">{{ __('system.food_categories.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.food_categories.update.menu', ['food_category' => $foodCategory->category_name]) }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 text-end">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            {{ Form::model($foodCategory, ['route' => ['restaurant.food_categories.update', $foodCategory->id], 'method' => 'put', 'files' => true, 'id' => 'pristine-valid']) }}
                            @if (request()->query->has('back'))
                                <input type="hidden" name="back" value="{{ request()->query->get('back') }}">
                            @endif

                            @include('restaurant.food_categories.fields', ['edit' => true])
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                                    <a href="{{ route('restaurant.food_categories.index') }}"class="btn btn-secondary">{{ __('system.crud.back') }}</a>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
