<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset(config('app.favicon_icon')) }}">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap"
          rel="stylesheet"/>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    @stack('page_css')
</head>

<body dir="ltr" class="ltr overflow-x-hidden">

<div class="antialiased font-montserrat text-secondary text-base  bg-cover bg-no-repeat bg-center rounded-t-xl"
     style="background:url('{{ asset('assets/theme/images/gradient-bg.png') }}') fixed">
    <div class="dark:bg-secondary  bg-cover bg-no-repeat bg-center dark-bg pt-4" style="">

        <header class="border-b-2 border-neutral/10 dark:border-white/10 py-3.5 dark:bg-[#18202d]/30 dark:backdrop-blur-xl bg-[#ebecf3]/30 backdrop-blur-xl sticky top-0 z-50">
            <div class="container">
                <div class="flex items-center justify-between">
                </div>
            </div>
        </header>
        <div style="min-height:calc(100vh - 152px)">
            @yield('content')
        </div>
        <footer>
            <div class="container">
                <div
                        class="flex flex-col md:flex-row items-center justify-center md:justify-between text-neutral py-5 border-t-2 border-dashed border-secondary/10 dark:border-white/10 font-semibold text-sm lg:text-base dark:text-white">
                    <div>©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> {{ __('auth.copyright') }}
                        {{ config('app.name') }} | {{ __('auth.all_rights_reserved') }}
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

</body>

</html>
