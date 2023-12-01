<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width , initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="project"/>
    <!-- Link style CSS -->
    <link rel="stylesheet" href="{{asset('menu/css/index.css')}}"/>
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="{{asset('menu/css/swiper-bundle.min.css')}}"/>

    <link rel="shortcut icon" href="{{ asset(config('app.favicon_icon')) }}">

    @include('menu.partials._style')
</head>
<body>
<main>
    <!-- start left-side -->
    <div class="left-side">
        @if(!empty($menu_title['en']) && !empty($menu_title['en']))
            <div class="left-side-title">
                <span>{{$menu_title['en']}}</span>
                <span>{{$menu_title['ar']}}</span>
            </div>
        @endif
        <div class="left-side-content">
            <div class="items">
                @foreach($foods as $key => $product)
                    @include('menu.partials._product', ['product' => $product, 'key' => $key])
                @endforeach
            </div>
        </div>
    </div>
    <!-- end left-side -->
    <!-- start center-side -->
    <div class="center-side">
        <div class="story-img">
            @if(!empty($script_code))
                {!! $script_code !!}
            @endif

            {{--            <iframe--}}
            {{--                width="100%"--}}
            {{--                height="100%"--}}
            {{--                src="https://www.youtube.com/embed/U9aGbgygaUE?autoplay=1&loop=1&rel=0&showinfo=0&mute=1&color=white&playlist=U9aGbgygaUE"--}}
            {{--                allow="autoplay"--}}
            {{--                frameborder="0"--}}
            {{--                controls="false"--}}
            {{--                loop="true"--}}
            {{--            ></iframe>--}}
        </div>
        <div class="cone-desc">
            <div>
                <p>شاركنا لحظاتك</p>
                <div id="time"></div>
                <p>Share your moments with us</p>
                <hr/>
                <p>
                    <svg
                            fill="#fff"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512"
                    >
                        <path
                                d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"
                        />
                    </svg>
                    {{ $insta }}
                </p>
            </div>
        </div>
    </div>
    <!-- end center-side -->

    <!-- start right-side -->
    <div class="right-side">
        <div class="logo">
            {{--            <img src="{{asset('menu/imges/logo.png')}}" alt=""/>--}}
            <img src="{{ asset($logo) }}" alt=""/>
        </div>
        <div class="video-elem">
            <iframe
                    width="100%"
                    src="{{ $intro_video_url }}"
                    allow="autoplay"
                    frameborder="0"
                    controls="false"
                    loop="true"
            >
            </iframe>
        </div>
        <div class="time-elem">
            <div class="date">
                <p>{{__('days.'.strtolower(date('D')), [], 'ar')}} {{date('d')}} {{__('months.'.strtolower(date('M')), [], 'ar')}} {{date('Y')}}</p>
                <div class="time">
                    <span class="hour"><span class="am-bm" id="time12">{{date("A", time())}}</span><span
                                id="timeHour">{{date("h", time())}}</span></span>
                    <span class="space" id="timeSpace">:</span>
                    <span class="munites" id="timeMinute">{{date("i", time())}}</span>
                </div>
            </div>
            <div class="logo-wasla">
                <img style="width: 100%" src="{{ asset(config('app.ligth_sm_logo')) }}" alt=""/>
            </div>
        </div>
    </div>
    <!-- end right-side -->
</main>
<script src="{{asset('menu/js/script.js')}}"></script>
<!-- Swiper JS -->
<script src="{{asset('menu/js/swiper-bundle.min.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>

<!-- Initialize Swiper -->
<script>

    var timeHour = document.getElementById("timeHour");
    var timeMinute = document.getElementById("timeMinute");
    var time12 = document.getElementById("time12");
    var timeSpace = document.getElementById("timeSpace");


    function refreshTime() {
        // var dateStringHour = new Date().toLocaleTimeString("en-US", {timeZone: "UTC", hour: '2-digit'});
        // timeHour.innerHTML = dateStringHour.replace(", ", " - ").replace("AM", "").replace("PM", "");
        timeHour.innerHTML = moment().format("hh");

        // var dateStringMinute = new Date().toLocaleTimeString("en-US", {timeZone: "UTC", minute:'2-digit'});
        // timeMinute.innerHTML = dateStringMinute.replace(", ", " - ").replace("AM", "").replace("PM", "");
        timeMinute.innerHTML = moment().format("mm");

        time12 = moment().format("A")

        if (timeSpace.style.visibility === "hidden") {
            timeSpace.style.visibility = "visible";
        } else {
            timeSpace.style.visibility = "hidden";
        }
    }

    setInterval(refreshTime, 700);

    let items = document.querySelectorAll(
        "main .left-side .left-side-content .items .item"
    );

    function hideAll() {
        // hide all items
        for (let i = 0; i < items.length; i++) {
            items[i].classList.add("hide");
        }
    }

    // hideAll();

    let count = 0;
    setInterval(() => {
        if(count*4 >= items.length){
            count=0
        }
        if (count === 0) {
            hideAll();
            // show count item
            for (let i = 0; i < 4; i++) {
                items[i].classList.remove("hide");
            }
            count = 1;
            return;
        }
        if (count === 1) {
            hideAll();
            if (items.length < 8) {
                // show count item
                for (let i = 4; i < items.length; i++) {
                    items[i].classList.remove("hide");
                }
                count = 0;
                return;
            } else {
                // show count item
                for (let i = 4; i < 8; i++) {
                    items[i].classList.remove("hide");
                }
                count = 2;
                return;
            }
        }
        if (count === 2) {
            hideAll();
            if (items.length < 12) {
                // show count item
                for (let i = 8; i < items.length; i++) {
                    items[i].classList.remove("hide");
                }
                count = 0;
                return;
            } else {
                // show count item
                for (let i = 8; i < 12; i++) {
                    items[i].classList.remove("hide");
                }
                count = 3;
                return;
            }
        }
        if (count === 3) {
            hideAll();
            if (items.length < 16) {
                // show count item
                for (let i = 12; i < items.length; i++) {
                    items[i].classList.remove("hide");
                }
                count = 0;
                return;
            } else {
                // show count item
                for (let i = 12; i < 16; i++) {
                    items[i].classList.remove("hide");
                }
                count = 4;
                return;
            }
        }
        if (count === 4) {
            hideAll();
            if (items.length < 20) {
                // show count item
                for (let i = 16; i < items.length; i++) {
                    items[i].classList.remove("hide");
                }
                count = 0;
                return;
            } else {
                // show count item
                for (let i = 16; i < 20; i++) {
                    items[i].classList.remove("hide");
                }
                count = 5;
                return;
            }
        }
        if (count === 5) {
            hideAll();
            if (items.length < 24) {
                // show count item
                for (let i = 20; i < items.length; i++) {
                    items[i].classList.remove("hide");
                }
                count = 0;
                return;
            } else {
                // show count item
                for (let i = 20; i < 24; i++) {
                    items[i].classList.remove("hide");
                }
                count = 6;
                return;
            }
        }
    }, 5000);

    var swiper = new Swiper(".itemImgSwiper", {
        slidesPerView: 1,
        loop: true,
        spaceBetween: 0,
        effect: "fade",
        autoplay: {
            delay: 2000,
            disableOnInteraction: true,
        },
    });
</script>
</body>
</html>
