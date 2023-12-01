<!DOCTYPE html>
<html lang="">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>@yield('title', 'Menu') | {{ config('app.name') }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/theme5/css/styles.css') }}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ asset(config('app.favicon_icon')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">
    <style>
        .fa-1-75x {
            font-size: 1.75em;
        }

        img.mfp-img {
            width: 100%;
        }
    </style>
    @stack('page_css')
</head>

<body>
    <header>
        <div class="container">
            <nav class="navbar navbar-light">
                @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])

                <a class="navbar-brand d-flex align-items-center" href="{{ route('restaurant.menu', ['restaurant' => $restaurant->id] + $append) }}">
                    <img data-src="{{ asset($restaurant->logo_url ?? config('app.dark_sm_logo')) }}" alt="" height="60" class="d-inline-block align-text-top me-1 img-content lazyload">
                    {{-- <span class="d-none d-sm-flex">{{ $restaurant->name }}</span> --}}
                </a>
                <div class="d-flex">
                    @isset($food_category)
                        <div class="d-flex d-none d-lg-flex">
                            <div class="input-group bg-white input-search-group">
                                <input type="text" class="form-control search-text border-0" placeholder="Search ..." aria-label="Search Result">

                                <button class="btn  search search-btn search_icon" type="button"><i class="fa fa-search align-middle"></i></button>
                            </div>
                        </div>
                        <div class=" d-inline-block d-lg-none">
                            <button type="button" class="btn header-item" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="fa fa-search fa-1-75x line-height-0 "></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">

                                <div class="p-3 app-search">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control search-text" placeholder="Search ..." aria-label="Search Result">

                                            <button class="btn btn-dark search search-btn search_icon" type="button"><i class="fa fa-search align-middle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endisset
                    <div class=" d-inline-block dropdown dropstart justify-content-center mt-lg-2">
                        <button type="button" class="btn header-item" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            {{-- <span class="fa-thin fa-language "></span> --}}
                            <i class="fa fa-language fa-2x text-dark line-height-0 "></i>
                        </button>
                        {{-- @if (!isset($languages))
                            
                        @endif --}}
                        @php($languages_array = getAllLanguages(true))
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            @foreach ($languages_array as $key => $language)
                                @if (App::currentLocale() == $key)
                                    <button class="dropdown-item  disabled" href="#">{{ $language }}</button>
                                @else
                                    {{ Form::open(['route' => ['restaurant.default.language', ['language' => $key]], 'method' => 'put', 'autocomplete' => 'off', 'class' => 'd-none', 'id' => 'user_set_default_language' . $key]) }}
                                    <input type="hidden" name='back' value="{{ request()->fullurl() }}">
                                    {{ Form::close() }}
                                    <button class="dropdown-item" onclick="event.preventDefault(); document.getElementById('user_set_default_language{{ $key }}').submit();">{{ $language }}</button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </nav>

        </div>
    </header>
    <section>
        <div class="container">
            @yield('content')
        </div>
    </section>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

    <script>
        lazyload();
        $(document).find('.popup-slider').each(function() {
            var that = $(this);
            var items = that.data('items');
            $(this).magnificPopup({
                items: items,
                closeBtnInside: false,

                gallery: {
                    enabled: true
                },
                type: 'image' // this is a default type
            });
        })
    </script>
    @stack('page_script')

</body>

</html>
