<!DOCTYPE html>
<html lang="">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    {{-- <meta name='viewport' content='width=device-width, initial-scale=1'> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>@yield('title', 'Menu') | {{ config('app.name') }}</title>
    <link href="{{ asset('assets/theme2/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/theme2/main-style.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset(config('app.favicon_icon')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">
    <style>
        img.mfp-img {
            width: 100%;
        }

        .dropdown-menu.show .dropdown-item {
            cursor: pointer !important;
        }

        .dropdown-menu.show .dropdown-item.disabled {
            cursor: default !important;
        }
    </style>
    @stack('page_css')
</head>

<body>
    <header>
        <div class="container">
            <div class="top_header d-flex justify-content-between align-items-center">
                <div class="logo d-flex">


                    <a href="{{ route('restaurant.menu', ['restaurant' => $restaurant->id] + $append) }}">
                        <img data-src="{{ asset($restaurant->logo_url ?? config('app.dark_sm_logo')) }}" alt="" height="60" class="d-inline lazyload">
                        {{-- <span class="pl-2 "> {{ $restaurant->name }}</span> --}}
                    </a>
                </div>
                <div class="dropdown dropleft bg-transparent">
                    <a role="button" class="d-flex justify-content-center align-items-center" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-language fa-2x line-height-0"></i>
                    </a>
                    @php($languages_array = getAllLanguages(true))

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @foreach ($languages_array as $key => $language)
                            @if (App::currentLocale() == $key)
                                <button class="dropdown-item  disabled" href="#">{{ $language }}</button>
                            @else
                                {{ Form::open(['route' => ['restaurant.default.language', ['language' => $key]], 'method' => 'put', 'class' => 'd-none', 'id' => 'user_set_default_language' . $key]) }}
                                <input type="hidden" name='back' value="{{ request()->fullurl() }}">
                                {{ Form::close() }}
                                <button class="dropdown-item" onclick="event.preventDefault(); document.getElementById('user_set_default_language{{ $key }}').submit();">{{ $language }}</button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            @yield('content')
        </div>
    </section>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    @stack('page_script')
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
</body>

</html>
