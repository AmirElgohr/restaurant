@extends('layouts.app')
@section('title', __('system.restaurants.title'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">{{ __('system.restaurants.menu') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.restaurants.menu') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                            <a href="{{ route('restaurant.restaurants.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> {{ __('system.crud.add_new') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="mb-4">
                        <div id="restaurants_list" class="dataTables_wrapper dt-bootstrap4 no-footer table_filter">
                            @include('common.filter_ui')
                            <div id="data-preview" class='overflow-hidden'>
                                @include('restaurant.restaurants.table')
                            </div>

                        </div>
                        <!-- end table -->
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>
@endsection
