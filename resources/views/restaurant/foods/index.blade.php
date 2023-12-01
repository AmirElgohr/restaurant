@extends('layouts.app')
@section('title', __('system.foods.title'))
@push('page_css')
    <style>
        .data-description {
            text-overflow: clip;
            max-height: 40px;
            min-height: 40px;
            overflow: hidden;
        }
    </style>
@endpush
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">{{ __('system.foods.menu') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.foods.menu') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->hasRole('restaurant'))
                            <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                                <a href="{{ route('restaurant.foods.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i>{{ __('system.crud.add_new') }}</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div id="restaurants_list" class="dataTables_wrapper dt-bootstrap4 no-footer table_filter">

                            <input type="hidden" name="current_url" value="{{ request()->fullurl() }}" id="current_page_url">

                            <div class="row">
                                <div class="col-sm-12 col-md-6">

                                    <div class=" w-50 category-select-drop-container">
                                        <div class="">

                                            {{ Form::select('food_category_id', App\Http\Controllers\Restaurant\FoodCategoryController::getCurrentRestaurantAllFoodCategories(), request()->query('food_category_id'), [
                                                'class' => 'form-select filter-on-change choice-picker',
                                                'id' => 'restaurant_type',
                                                'data-remove_attr' => 'data-type',
                                                'required' => true,
                                                'data-pristine-required-message' => __('validation.custom.select_required', ['attribute' => 'food category']),
                                            ]) }}

                                        </div>
                                    </div>

                                </div>

                            </div>

                            <p class="text-danger mt-3">{{ request()->query->has('food_category_id') ? __('system.fields.food_drag_and_drop_message') : __('system.fields.select_cataegory_and_food_drag_and_drop_message') }}</p>

                            <div id="data-preview" class='overflow-hidden'>
                                @include('restaurant.foods.table')
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
