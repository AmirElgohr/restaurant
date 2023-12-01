@extends('layouts.app')
@section('title', __('system.users.title'))
@section('content')

    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">{{ __('system.users.title') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.users.menu') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">
                            <a href="{{ route('restaurant.users.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i>{{ __('system.crud.add_new') }}</a>

                        </div>
                    </div>


                </div>
                <div class="card-body">

                    <div class="mb-4">
                        <div id="users_list" class="dataTables_wrapper dt-bootstrap4 no-footer table_filter">
{{--                            <div class="row">--}}
{{--                                @if (auth()->user()->user_type == App\Models\User::USER_TYPE_ADMIN)--}}
{{--                                    <div class="col-xl-12 mb-2 ">--}}
{{--                                        <div class="btn-group">--}}
{{--                                            <button name='user_list' value="current"--}}
{{--                                                class="btn btn-sm  filter-on-click @if (request()->query('user_list', 'current') == 'current') btn-primary disabled--}}
{{--                     @else btn-outline-primary @endif">{{ __('system.users.this_resturant_users') }}</button>--}}
{{--                                            <button name='user_list' value="all"--}}
{{--                                                class="btn btn-sm filter-on-click @if (request()->query('user_list', 'current') == 'all') btn-primary disabled--}}
{{--                     @else btn-outline-primary @endif"">{{ __('system.users.system_all_user') }}</button>--}}
{{--                                            <button name='user_list' value="not_assigned"--}}
{{--                                                class="btn btn-sm filter-on-click @if (request()->query('user_list', 'current') == 'not_assigned') btn-primary disabled--}}
{{--                     @else btn-outline-primary @endif"">{{ __('system.users.unsigned_users') }}</button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </div>--}}
                            @include('common.filter_ui')
                            <div id="data-preview">
                                @include('restaurant.users.table')
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
