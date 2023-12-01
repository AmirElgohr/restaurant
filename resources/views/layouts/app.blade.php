<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>@yield('title', 'Home') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    {{-- <link rel="shortcut icon" href="{{ asset(config('app.dark_sm_logo')) }}"> --}}
    <link rel="shortcut icon" href="{{ asset(config('app.favicon_icon')) }}">

    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />


    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/libs/alertifyjs/build/css/alertify.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />



    <link href="{{ asset('assets/libs/alertifyjs/build/css/alertify.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/alertifyjs/build/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .text-gray {
            color: #495057
        }

        table.dataTable>thead div .sorting:before,
        table.dataTable>thead div .sorting:after,
        table.dataTable>thead div .sorting_asc:before,
        table.dataTable>thead div .sorting_asc:after,
        table.dataTable>thead div .sorting_desc:before,
        table.dataTable>thead div .sorting_desc:after,
        table.dataTable>thead div .sorting_asc_disabled:before,
        table.dataTable>thead div .sorting_asc_disabled:after,
        table.dataTable>thead div .sorting_desc_disabled:before,
        table.dataTable>thead div .sorting_desc_disabled:after {
            position: absolute;
            bottom: 0px;
            display: block;
            opacity: .3;
        }

        table.dataTable>thead .sorting_asc:before,
        table.dataTable>thead .sorting_desc:after {
            opacity: 1 !important;
        }

        .choices__inner {
            display: inline-block;
            vertical-align: top;
            width: 100%;
            background-color: transparent !important;
            padding: 4.8px 7.5px 3.75px !important;
            border: 1px solid #ced4da !important;
            border-radius: 4.5px;
            font-size: 12px;
            min-height: 19px;
            overflow: hidden;
        }

        .has-success .form-select .has-success .choices__inner {
            border: 1px solid #34c38f !important;
        }

        .has-danger .form-select,
        .has-danger .choices__inner {
            border: 1px solid #ef6767 !important;
        }



        .choices__input {
            background: transparent !important;
        }

        .choices__input::placeholder {
            color: #adb5bd;
        }

        .pristine-error {
            font-size: 13px !important;
        }

        #data-preview>.row>.col-sm-12 {
            min-height: calc(100vh - 375px);
            overflow-x: auto;
        }

        .dropdown-icon-item img {
            height: 36px !important
        }

        .bg-light-gray {
            background-color: rgb(199 199 199 / 25%);
        }


        @media only screen and (min-width: 320px) and (max-width: 700px) {
            .navbar-brand-box {
                display: none;
            }

            .add-new-btn-parent,
            .dataTables_filter,
            .dataTables_length {
                text-align: left !important;
            }

            .add-new-btn-parent {
                margin-top: 8px !important;
            }

            .dataTables_length label {
                display: inline-flex !important;
                align-items: center;
            }

            .dataTables_length label select {
                margin-left: 5px !important;
                margin-right: 5px;

            }

            .category-select-drop-container {
                margin-bottom: 5px !important;
            }

            .form-select,
            .form-control,
            .dataTables_filter label,
            .category-select-drop-container {
                display: block !important;
                margin-left: 0px !important;
                width: 100% !important;
            }
        }

        img[class*="avatar"],
        .header-profile-user {
            object-fit: contain !important;
        }

        img.image-object-cover {
            object-fit: cover !important;
        }

        .btn-success {
            background-color: #DBFFF1 !important;
            color: green !important;
        }

        .btn-danger {
            background-color: #FFEBF1 !important;
            color: red !important;
        }

        a[type='button'] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;

        }

        .ajs-content {
            word-break: break-all;
        }

        body[data-layout-mode=dark] .table .text-gray {
            color: #858d98;
        }

        .ui-sortable-handle .grid-move-icon {
            right: 5%;
            top: 2%;
            position: absolute;
            z-index: 9;
            /* fallback if grab cursor is unsupported */
            cursor: grab;
            cursor: -moz-grab;
            cursor: -webkit-grab;
        }

        .ui-sortable-handle.selected .grid-move-icon {
            cursor: move;
            cursor: -moz-move;
            cursor: -webkit-move;
        }

        #search-model .dash-text {
            width: 150px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        @media only screen and (min-width: 768px) and (max-width: 1200px) {
            #search-model .dash-text {
                width: 100px;
            }
        }
    </style>
    <style>
        @media only screen and (min-width: 380px) and (max-width: 600px) {
            .navbar-header .dropdown button {
                width: 45px;
                padding: 5px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .country-select .flag {
            background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/img/flags.png");
        }

        .iti {
            width: 100%;
        }

        .rounded-circle {
            border: 1px solid var(--bs-body-color)
        }

        [data-layout-mode="dark"] .rounded-circle {
            border: 1px solid var(--bs-table-striped-bg)
        }

        [data-layout-mode="dark"] kbd {
            background: #4e4e4e;
        }
    </style>
    @stack('third_party_stylesheets')

    @stack('page_css')
</head>

@php($theme = Cookie::get('resto_defult_theme', 'light'))

<body data-sidebar="{{ $theme }}" data-layout-mode="{{ $theme }}" data-topbar="{{ $theme }}">

    <div id="layout-wrapper">


        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">

                    <div class="navbar-brand-box">
                        <a href="{{ route('home') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <img class="lazyload" data-src="{{ asset(config('app.favicon_icon')) }}" alt="" height="30">
                            </span>
                            <span class="logo-lg">
                                <img class="lazyload" data-src="{{ asset(config('app.ligth_sm_logo')) }}" alt="" height="60">
                                {{-- <span class="logo-txt font-size-13">{{ config('app.name') }}</span> --}}
                            </span>
                        </a>

                        <a href="{{ route('home') }}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img class="lazyload" data-src="{{ asset(config('app.favicon_icon')) }}" alt="" height="30">
                            </span>
                            <span class="logo-lg">
                                <img class="lazyload" data-src="{{ asset(config('app.dark_sm_logo')) }}" alt="" height="60">
                                {{-- <span class="logo-txt font-size-13">{{ config('app.name') }}</span> --}}
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    @if(auth()->user()->hasRole('restaurant'))
                        <button type="button" class="btn btn-sm px-3 font-size-16 header-item font-weight-bold d-none d-sm-inline-block">
                            {{ auth()->user()->restaurant->name }}
                        </button>
                    @endif


                </div>

                <div class="d-flex">


                    <div class="dropdown ">
                        <div class="dropdown d-none d-sm-inline-block">
                            <button type="button" class="btn header-item" id="mode-setting-btn">
                                <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                                <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                            </button>
                        </div>
                        <div class="dropdown d-none ">
                            <button type="button" class="btn header-item" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">
                                <i class="fa fa-search font-size-18"></i>
                            </button>
                        </div>
                        <div class="dropdown d-none ">
                            <button type="button" class="btn header-item" data-bs-toggle="modal" data-bs-target=".bs-example1-modal-xl">
                                <i class="fa fa-keyboard font-size-18"></i>
                            </button>
                        </div>
                        <div class="dropdown d-none ms-1">
                            <button type="button" class="btn header-item select_store_top" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <h1 class="font-size-18 px-2 pt-2 header-item d-inline-block h-auto"><span class="fas fa-store-alt font-size-18"></span></h1>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                                <div class="p-2">

                                    @php($restaurants = App\Http\Controllers\HomeController::getCurrentUsersAllRestaurants())
                                    @foreach ($restaurants as $restaurant)
                                        @if (auth()->user()->restaurant_id != $restaurant->id)
                                            {{ Form::open(['route' => ['restaurant.default.restaurant', ['restaurant' => $restaurant->id]], 'method' => 'put', 'autocomplete' => 'off', 'class' => 'd-none', 'id' => 'restaurant_default_restaurant' . $restaurant->id]) }}
                                            <input type="hidden" name='back' value="{{ request()->fullurl() }}">
                                            {{ Form::close() }}
                                        @endif
                                        <a class="dropdown-icon-item  @if (auth()->user()->restaurant_id == $restaurant->id) bg-light-gray  disabled @endif"
                                            @if (auth()->user()->restaurant_id != $restaurant->id) role="button"
                                             onclick="event.preventDefault(); document.getElementById('restaurant_default_restaurant{{ $restaurant->id }}').submit();" @endif
                                            title="Set as Default">
                                            <div class="row g-0">

                                                <div class="col-3">

                                                    @if ($restaurant->logo_url != null)
                                                        <img data-src="{{ $restaurant->logo_url }}" alt="" class="rounded-circle header-profile-user lazyload">
                                                    @else
                                                        <h1 class="rounded-circle header-profile-user font-size-18 px-2 pt-2 text-white d-inline-block font-bold bg-primary">{{ $restaurant->logo_name }}</h1>
                                                    @endif


                                                </div>
                                                <div class="col-9  text-start overflow-hidden">
                                                    <span>{{ $restaurant->name }}</span>
                                                </div>


                                            </div>
                                        </a>
                                    @endforeach


                                </div>
                            </div>
                        </div>
                        <div class="dropdown d-none ms-1">
                            <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <h1 class="font-size-18 px-2 pt-2 header-item d-inline-block h-auto"><span class="fas fa-language font-size-18"></span></h1>
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                <div class="p-2">
                                    @if (!isset($languages_array))
                                        @php($languages_array = getAllLanguages(true))
                                    @endif
                                    @foreach ($languages_array as $key => $language)
                                        <a class="dropdown-icon-item  @if (App::currentLocale() == $key) bg-light-gray  disabled @endif"
                                            @if (App::currentLocale() != $key) role="button"
                                             onclick="event.preventDefault(); document.getElementById('user_set_default_language{{ $key }}').submit();" @endif
                                            title="Set as Default">
                                            <div class="row g-0">
                                                <div class="col-12  text-start overflow-hidden">
                                                    <h6 class="px-2">{{ $language }}</h6>
                                                </div>


                                            </div>
                                        </a>
                                        @if (App::currentLocale() != $key)
                                            {{ Form::open(['route' => ['restaurant.default.language', ['language' => $key]], 'method' => 'put', 'autocomplete' => 'off', 'class' => 'd-none', 'id' => 'user_set_default_language' . $key]) }}
                                            <input type="hidden" name='back' value="{{ request()->fullurl() }}">
                                            {{ Form::close() }}
                                        @endif
                                    @endforeach


                                </div>
                            </div>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item bg-soft-light " id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                @if (auth()->user()->profile_url != null)
                                    <img data-src="{{ auth()->user()->profile_url }}" alt="" class="rounded-circle header-profile-user image-object-cover lazyload">
                                @else
                                    <h1 class="rounded-circle header-profile-user font-size-18 px-2 pt-2 text-white d-inline-block font-bold">{{ auth()->user()->logo_name }}</h1>
                                @endif

                                <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ auth()->user()->name }}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">

                                <a class="dropdown-item" href="{{ route('restaurant.profile') }}"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> {{ __('system.profile.menu') }}</a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" role="button" onclick="event.preventDefault(); document.getElementById('logout-form').click();"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i>
                                    {{ __('auth.sign_out') }}</a>
                                <form autocomplete="off" action="{{ route('logout') }}" method="POST" class="d-none data-confirm" data-confirm-message="{{ __('system.fields.logout') }}"
                                    data-confirm-title=" {{ __('auth.sign_out') }}">
                                    <button id="logout-form" type="submit"></button>
                                    @csrf
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
        </header>


        <div class="vertical-menu">

            <div data-simplebar class="h-100">


                <div id="sidebar-menu">


                    @include('layouts.sidebar')

                </div>

            </div>
        </div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">


                    @yield('content')



                </div>
            </div>



            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-0">©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> {{ __('auth.copyright') }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                <a href="{{ route('home') }}">{{ config('app.name') }}</a> | {{ __('auth.all_rights_reserved') }}
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <div class="modal fade bs-example-modal-xl" id="search-model" data-bs-focus="false" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog">

            <div class="modal-dialog modal-xl ">
                <div class="modal-content">
                    <div class="modal-header">
                        <form class="app-search d-block w-100 mt-3" id="global-search-form" autocomplete="off">
                            @csrf
                            <div class="position-relative">
                                <input type="text" class="form-control global-search-input" id="global-search-input" name='search' tabindex="2" placeholder="{{ __('system.crud.search') }}" autofocus>
                                <button class="btn btn-primary global-search-btn" type="button"><i class="bx bx-search-alt align-middle"></i></button>
                            </div>
                        </form>
                        <button type="button" class="btn-close position-absolute" style="right: 12px;top:12px;z-index:999" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body search_content">
                        <h6>{{ __('system.fields.enter_more_char') }}</h6>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div class="modal fade bs-example1-modal-xl" id="search-model" data-bs-focus="false" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog">

            <div class="modal-dialog modal-xl modal-fullscreen-lg-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel">{{ __('system.dashboard.shortcuts') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table_overflow_box">
                            <table style="margin-bottom: 0em;">
                                <thead>
                                    <tr class="mb-3">
                                        <th width="200px">{{ __('system.dashboard.windows') }}</th>
                                        <th width="200px">{{ __('system.dashboard.mac') }}</th>
                                        <th>{{ __('system.dashboard.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>

                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>h</kbd>
                                                    +
                                                    <kbd>down</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>h</kbd>
                                                    +
                                                    <kbd>down</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.change_defualt_restaurant') }}</td>
                                    </tr>
                                    <tr>

                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>h</kbd>
                                                    +
                                                    <kbd>up</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>h</kbd>
                                                    +
                                                    <kbd>up</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.previews_change_defualt_restaurant') }}</td>
                                    </tr>
                                    <tr>

                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>esc</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>esc</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.cancel') }}</td>
                                    </tr>
                                    <tr>

                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>h</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>h</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.open_restaurant_drop_down') }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>-</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>-</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.change_sidebar_small') }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>+</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>+</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.change_sidebar_large') }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>m</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>m</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.change_app_mode') }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>home</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>home</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_dashboard') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>shift</kbd>
                                                    +
                                                    <kbd>f</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>shift</kbd>
                                                    +
                                                    <kbd>f</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.global_search') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>u</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>u</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_users') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>p</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>p</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_profile') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>r</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>r</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_restaurant') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>c</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>c</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_categories') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>f</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>f</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_foods') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>l</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>l</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_language') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>q</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>q</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_qr') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>t</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>t</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_themes') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>ctrl</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>s</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <kbd>
                                                    <kbd>command</kbd>
                                                    +
                                                    <kbd>alt</kbd>
                                                    +
                                                    <kbd>s</kbd>

                                                </kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.redirect_settings') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p><kbd><kbd>ctrl</kbd> + <kbd>right</kbd></kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p><kbd><kbd>command</kbd> + <kbd>right</kbd></kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.forward') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p><kbd><kbd>ctrl</kbd> + <kbd>left</kbd></kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p><kbd><kbd>command</kbd> + <kbd>left</kbd></kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.back') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p><kbd><kbd>ctrl</kbd> + <kbd>f</kbd></kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p><kbd><kbd>command</kbd> + <kbd>f</kbd></kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.page_in_find') }}</td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <p><kbd><kbd>ctrl</kbd> + <kbd>A</kbd></kbd>
                                            </p>
                                        </td>
                                        <td>
                                            <p><kbd><kbd>command</kbd> + <kbd>A</kbd></kbd>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top">{{ __('system.dashboard.add_page_item') }}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div class="d-none">
            <span id="layout-vertical"></span>
            <span id="layout-horizontal"></span>
            <span id="layout-mode-light"></span>
            <span id="layout-mode-dark"></span>
            <span id="layout-width-fuild"></span>
            <span id="layout-width-boxed"></span>
            <span id="layout-position-fixed"></span>
            <span id="layout-position-scrollable"></span>
            <span id="topbar-color-light"></span>
            <span id="topbar-color-dark"></span>
            <span id="sidebar-size-default"></span>
            <span id="sidebar-size-compact"></span>
            <span id="sidebar-size-small"></span>
            <span id="sidebar-color-light"></span>
            <span id="sidebar-color-dark"></span>
            <span id="sidebar-color-brand"></span>
            <span id="layout-direction-ltr"></span>
            <span id="layout-direction-rtl"></span>
        </div>
    </div>



    <div class="rightbar-overlay"></div>
    <script>
        const themeRoute = '{{ route('theme.mode') }}';

        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
    </script>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- lazyload --}}
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>



    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>



    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>



    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

    <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>

    <script src="{{ asset('assets/libs/pristinejs/pristine.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ asset('assets/libs/alertifyjs/build/alertify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/notification.init.js') }}"></script>
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/libs/imask/imask.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-mask.init.js') }}"></script>
    <script>
        const body = document.querySelector('body');
        lazyload();
        @if (session()->has('Success'))
            alertify.success('{{ session('Success') }}');
        @endif
        @if (session()->has('Error'))
            alertify.error('{{ session('Error') }}');
        @endif

        $(function() {
            $(document).on('keypress', '.start_no_space', function(e) {
                if (e.which == 32) {
                    return false;
                }
            });
        });
        $(document).on('click', '[data-delete]', function() {
            var ele_sele = $(this).data('delete')
            $(document).find(ele_sele).trigger('submit');
        })
        $(document).on('submit', '.data-confirm', function(e) {
            let that = $(this);
            e.preventDefault();
            alertify.confirm(
                that.data('confirm-message'),
                function() {
                    e.currentTarget.submit();
                },
                function() {
                    alertify.error('{{ __('system.messages.operation_canceled') }}');
                }
            ).set({
                title: that.data('confirm-title')
            }).set({
                labels: {
                    ok: '{{ __('system.crud.confirmed') }}',
                    cancel: '{{ __('system.crud.cancel') }}'
                }
            });
        })
    </script>
    <script>
        function previewSelectedFile(input) {
            var previewattr = $(input).data('preview');
            var preview = $(document).find(previewattr)
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    preview.attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
                preview.show();
                $(document).find(previewattr + "-default").hide();
            }
        }
        $(document).on('change', '.my-preview', function() {
            previewSelectedFile(this);
        });
    </script>
    <script>
        function objectToQueryString(obj) {
            var str = [];
            for (var p in obj)
                if (obj.hasOwnProperty(p)) {
                    if (obj[p])
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                }
            return str.join("&");
        }

        function filter(key, that) {
            var url = '{{ request()->url() }}'
            var query = {!! json_encode(request()->query()) !!}

            var value = that.val();
            if (value != null) {
                query[key] = value;
            }
            document.location.href = url + "?" + objectToQueryString(query);

        }

        $(document).on('keypress', '.filter-on-enter', function(e) {
            if (e.which == 13) { //Enter key pressed
                var that = $(this)
                filter('filter', that)
            }
        });
        $(document).on('change', '.filter-on-change', function(e) {

            var that = $(this)

            $name = 'par_page';
            if (that)
                $name = that.attr('name');
            filter($name, that)

        });
        $(document).on('click', '.filter-on-click', function(e) {

            var that = $(this)
            $name = that.attr('name');
            filter($name, that)

        });
    </script>
    <script>
        var currentSRequest = null;

        function globalSearch() {
            var data = $(document).find("#global-search-form").serialize()
            if (currentSRequest != null) {
                currentSRequest.abort();
            }
            currentSRequest = $.ajax({
                url: "{{ route('restaurant.global.search') }}",
                type: 'post',
                data: data,
                success: function(data) {
                    if (data) {
                        $(document).find('.search_content').html(data)
                    }
                },
            });
        }
        $(document).on('submit', "#global-search-form", function(e) {
            e.preventDefault();
            globalSearch();
        })
        $(document).on('click', ".global-search-btn", function(e) {
            globalSearch();
        })
        $(document).on('keyup', '.global-search-input', function(e) {
            globalSearch()
        })
    </script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.6.3/mousetrap.min.js"></script>
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('search-model'))
        Mousetrap.bind(['command+alt+h+down', 'ctrl+alt+s+down'], function() {
            var store = $(document).find('.select_store_top');
            var stores = store.parent().find('.dropdown-menu .dropdown-icon-item')
            var x = 0;
            stores.each(function() {
                if (x == 1) {
                    $(this).click();
                    x = 0;
                }
                if ($(this).hasClass('disabled')) {
                    x++;
                }
            })
            return false;
        });
        Mousetrap.bind(['command+alt+h+up', 'ctrl+alt+s+up'], function() {
            var store = $(document).find('.select_store_top');
            var stores = store.parent().find('.dropdown-menu .dropdown-icon-item')
            var x = '';
            stores.each(function() {

                if ($(this).hasClass('disabled') && x) {
                    x.click();
                }
                x = $(this);
            })
            return false;
        });
        Mousetrap.bind(['esc'], function() {
            var store = $(document).find('.select_store_top');
            store.removeClass('show');
            myModal.hide();
            store.parent().find('.dropdown-menu').removeClass('show');
        })
        Mousetrap.bind(['command+alt+h', 'ctrl+alt+h'], function() {
            var store = $(document).find('.select_store_top');
            store.addClass('show');
            store.parent().find('.dropdown-menu').addClass('show');
            return false;
        });

        Mousetrap.bind(['command++', 'ctrl++'], function() {
            $(document).find('body').attr('data-sidebar-size', 'lg');
            return false;
        });
        Mousetrap.bind(['command+-', 'ctrl+-'], function() {
            $(document).find('body').attr('data-sidebar-size', 'sm');
            return false;
        });


        Mousetrap.bind(['command+m', 'ctrl+m'], function() {

            var theme = $(document).find('#mode-setting-btn');
            if (theme.length > 0)
                theme[0].click();
            return false;
        });
        Mousetrap.bind(['command+alt+home', 'ctrl+alt+home'], function() {
            document.location.href = '{{ route('home') }}'
            return false;
        });
        Mousetrap.bind(['command+alt+p', 'ctrl+alt+p'], function() {
            document.location.href = '{{ route('restaurant.profile') }}'
            return false;
        });

        Mousetrap.bind(['command+alt+r', 'ctrl+alt+r'], function() {
            document.location.href = '{{ route('restaurant.restaurants.index') }}'
            return false;
        });

        Mousetrap.bind(['command+alt+c', 'ctrl+alt+c'], function() {
            document.location.href = '{{ route('restaurant.food_categories.index') }}'
            return false;
        });

        Mousetrap.bind(['command+f', 'ctrl+f'], function() {
            $(document).find('#search').focus();
            return false;
        });

        {{--Mousetrap.bind(['command+alt+l', 'ctrl+alt+l'], function() {--}}
        {{--    document.location.href = '{{ route('restaurant.languages.index') }}'--}}
        {{--    return false;--}}
        {{--});--}}
        {{--Mousetrap.bind(['command+alt+q', 'ctrl+alt+q'], function() {--}}
        {{--    document.location.href = '{{ route('restaurant.create.QR') }}'--}}
        {{--    return false;--}}
        {{--});--}}
        {{--Mousetrap.bind(['command+alt+t', 'ctrl+alt+t'], function() {--}}
        {{--    document.location.href = '{{ route('restaurant.themes.index') }}'--}}
        {{--    return false;--}}
        {{--});--}}
        Mousetrap.bind(['command+alt+s', 'ctrl+alt+s'], function() {
            document.location.href = '{{ route('restaurant.environment.setting') }}'
            return false;
        });
        Mousetrap.bind(['command+right', 'ctrl+right'], function() {
            window.history.go(+1)
            return false;
        });
        Mousetrap.bind(['command+left', 'ctrl+left'], function() {
            window.history.go(-1)
            return false;
        });

        Mousetrap.bind(['command+alt+f', 'ctrl+alt+f'], function() {
            document.location.href = '{{ route('restaurant.foods.index') }}'
            return false;
        });


        Mousetrap.bind(['command+a', 'ctrl+a'], function() {
            var add_btn = $(document).find('.add-new-btn-parent .btn-primary');
            if (add_btn.length > 0) {
                add_btn[0].click();
            }
            return false;
        });






        Mousetrap.bind(['command+alt+u', 'ctrl+alt+u'], function() {
            document.location.href = '{{ route('restaurant.users.index') }}'
            return false;
        });

        Mousetrap.bind(['command+shift+f', 'ctrl+shift+f'], function() {
            myModal.toggle()


            return false;
        });
        var searchModel = document.getElementById('search-model')
        searchModel.addEventListener('show.bs.modal', function(event) {
            setTimeout(() => {
                $(document).find('#global-search-input').focus()
            }, 500);
        })
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js" integrity="sha512-+gShyB8GWoOiXNwOlBaYXdLTiZt10Iy6xjACGadpqMs20aJOoh+PJt3bwUVA6Cefe7yF7vblX6QwyXZiVwTWGg==" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script>
        var input = document.querySelector("#pristine-phone-valid");
        if (input) {
            iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                hiddenInput: "phone_number",
                geoIpLookup: function(callback) {
                    $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "us";
                        callback(countryCode);
                    });
                },
                utilsScript: "{{ asset('assets/js/utils.js') }}" // just for formatting/placeholders etc
            });
            $(input).on('blur', function() {
                var number = iti.getNumber();
                $(document).find("[name=phone_number]:last-child").val(number);
            })
        }

        $(document).on("click", "#password-confirmation-addon", function () {
            var passEle = $(this).parent("div").find("input");
            var type = passEle.attr("type");
            passEle.attr(
                "type",
                "password" === type ? (type = "text") : (type = "password")
            );
        });
    </script>
    @stack('third_party_scripts')

    @stack('page_scripts')
</body>

</html>
