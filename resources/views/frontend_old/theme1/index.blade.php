<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Menu | {{ config('app.name') }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link rel="shortcut icon" href="{{ asset(config('app.favicon_icon')) }}">
    <link href="{{ asset('assets/theme1/style.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <style>
        ::-webkit-scrollbar {
            width: 12px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {

            -webkit-box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.3);
        }

        ::-webkit-scrollbar-thumb {
            background-color: darkgrey;
            border-radius: 15px;

        }

        @media only screen and (min-width: 320px) and (max-width: 700px) {
            .img-content {
                max-width: 132px;
                object-fit: contain;
            }
        }

        input {

            border-radius: 0.25rem !important;
        }
    </style>
</head>

<body class="frontend">
    <div id="layout-wrapper">
        <header id="page-topbar" class="mb-5">

            <div class="container">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box w-auto">
                            <a href="{{ route('restaurant.menu', ['restaurant' => $restaurant->id]) }}" class="logo logo-dark">

                                <span class="logo-sm">
                                    <img data-src="{{ asset($restaurant->logo_url ?? config('app.dark_sm_logo')) }}" alt="" height="30" width="30" class="img-content lazyload">
                                    {{-- <span class="logo-txt font-size-13 logo-overlay ">{{ $restaurant->name }} --}}
                                </span>
                                </span>
                                <span class="logo-lg">
                                    <img data-src="{{ asset($restaurant->dark_logo_url ?? config('app.dark_sm_logo')) }}" alt="" height="60" class="img-content lazyload">
                                    {{-- <span class="logo-txt font-size-13 logo-overlay">{{ $restaurant->name }}</span> --}}
                                </span>
                            </a>
                        </div>
                        <!-- App Search-->
                        <form autocomplete="off" class="app-search d-none d-lg-block">
                            <div class="position-relative">
                                <input type="text" class="form-control search-text" placeholder="Search...">
                                <button class="btn btn-dark search search-btn" type="button"><i class="bx bx-search-alt align-middle"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex">
                        <div class="dropdown d-inline-block d-lg-none ms-2">
                            <button type="button" class="btn header-item" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <p class="rounded-circle pt-2 header-item d-inline-block h-auto"><span class="fas fa-search fa-2x"></span></p>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">

                                <form autocomplete="off" class="p-3 app-search">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control search-text" placeholder="Search ..." aria-label="Search Result">

                                            <button class="btn btn-dark search search-btn" type="button"><i class="bx bx-search-alt align-middle"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="dropdown d-inline-block ">
                            <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <p class="rounded-circle pt-2 header-item d-inline-block h-auto"><span class="fas fa-language fa-2x"></span></p>
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                <div class="p-2">

                                    @php($languages_array = getAllLanguages(true))
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
                    </div>
                </div>

            </div>
        </header>
    </div>
    <div id="main-content">
        <section class="container">
            <div class="page-content">
                <div id="menu">
                    @foreach ($food_categories as $category)
                        <div class="category div{{ $category->id }}">
                            <div class="row ">
                                <div class="col-12">
                                    <h1 class="py-3 text-uppercase text-big-shoulder-title title" data-id="{{ $category->id }}">{{ $category->local_lang_name }}</h1>
                                </div>
                            </div>

                            <div class="food-items">
                                @forelse ($category->foods as $food)
                                    <dl class="row mb-0 food-item">
                                        <dd class="col-9">
                                            <h5 class="font-size-18  mb-0 text-philosopher-title text-uppercase name">{{ $food->local_lang_name }}</h5>
                                            <p class="text-grate-vibes font-size-14  mt-2 description">{{ $food->local_lang_description }}</p>

                                        </dd>
                                        <dd class="col-3 font-size-18 text-grate-vibes px-1 text-end amount">{{ $food->usd_price }}</dd>

                                    </dl>
                                @empty
                                    <dl class="row mb-0 food-item">
                                        <dd class="col-12 text-grate-vibes font-size-14">
                                            {{ __('system.messages.food_not_found') }}


                                        </dd>


                                    </dl>
                                @endforelse

                            </div>
                        </div>
                    @endforeach
                    <p class="not_found" style="display: none"> {{ __('system.messages.food_not_found') }}</p>
                </div>
            </div>
        </section>
    </div>


    <script>
        $(document).ready(function() {
            function serch(search) {
                search = search.toLowerCase();
                $(".category,.food-item").show();
                if (search == '') {

                    $(".category,.food-item").show();
                    $(document).find(".not_found").hide();
                } else {
                    $(document).find(".not_found").hide();
                    search = search.replace('\\', '\\\\');
                    console.log(search);
                    var x = 0;
                    $(document).find('.category').each(function() {
                        var title = $(this).find('.title').html()
                        title = title.toLowerCase();
                        if (title.search(search) == -1) {
                            var hide = 0;

                            $(this).find('.food-item').each(function() {
                                var val1 = $(this).find('.name').html(),
                                    val2 = $(this).find('.amount').html();
                                val3 = $(this).find('.description').html();
                                if (val1)
                                    val1 = val1.toLowerCase();
                                else
                                    val1 = "";
                                if (val2)
                                    val2 = val2.toLowerCase();
                                else
                                    val2 = "";
                                if (val3)
                                    val3 = val3.toLowerCase();
                                else
                                    val3 = "";

                                if (val1.search(search) == -1 && val2.search(search) == -1 && val3.search(search) == -1) {
                                    $(this).hide();
                                    hide++;
                                }
                            });
                            if (hide == $(this).find('.food-item').length) {
                                $(this).hide();
                                x++;
                            }

                        }
                    })

                    if (x == $(document).find('.category').length) {
                        $(document).find(".not_found").show();
                    } else {
                        $(document).find(".not_found").hide();
                    }
                }
            }
            $(document).on('click', '.search', function() {
                var search = $(this).parents('form').find('.search-text').val();
                serch(search)
            })
            $(document).on('submit', '.app-search', function(e) {
                e.preventDefault();
                var search = $(this).find('.search-text').val();
                serch(search)
            })
            $(document).on('keyup', '.search-text', function() {
                var search = $(this).val();
                serch(search)
            })
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    <script>
        lazyload();
    </script>
</body>

</html>
