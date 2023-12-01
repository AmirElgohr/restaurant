@extends('layouts.app')
@section('title', __('system.food_categories.create.menu'))
@section('content')
    <div class="row">

        <div class="col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-mb-8 col-xl-8">
                            <h4 class="card-title">{{ __('system.food_categories.create.menu') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item "><a href="{{ route('restaurant.food_categories.index') }}">{{ __('system.food_categories.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.food_categories.create.menu') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form autocomplete="off" novalidate="" action="{{ route('restaurant.food_categories.store') }}" id="pristine-valid" method="post" enctype="multipart/form-data">
                                @csrf
                                @include('restaurant.food_categories.fields')
                                <div class="row">
                                    <div class="col-12 mt-3">
                                        <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                                        <a href="{{ route('restaurant.food_categories.index') }}"class="btn btn-secondary">{{ __('system.crud.cancel') }}</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
                <!-- end card -->
            </div>
        </div>
    </div>
@endsection
