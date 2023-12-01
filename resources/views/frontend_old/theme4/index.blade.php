@extends('frontend.theme2.master')
@section('title', __('system.theme.menu'))
@push('page_css')
    <link href="{{ asset('assets/theme4/style.css') }}" rel="stylesheet" type="text/css" />
    <style>
        @media only screen and (min-width: 320px) and (max-width: 700px) {

            .restaurent-product-title-box,
            .restaurent-product-rating {
                display: block !important;
            }
        }
    </style>
@endpush
@section('content')
    <div class="box-search form-group mb-4">
        <input type="text" class="form-control search-text" placeholder="{{ __('system.crud.search') }}">
        <span class="search_icon d-flex justify-content-center align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18px" height="18px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </span>
    </div>
    {{-- <h2 class="main_title">{{ __('system.theme.menu') }}</h2> --}}
    <div class="menu_section">
        <ul class="nav nav-tab flex-wrap-ul mb-0" id="myTab" role="tablist">
            @foreach ($food_categories as $key => $category)
                <li>
                    <a class="@if ($key == 0) active @endif text-center" id="home-tab" data-toggle="tab" href="#home{{ $category->id }}" role="tab" aria-controls="home" aria-selected="true">

                        @if ($category->category_image_url != null)
                            <div class="icon text-custom-white bg-light-green"><img src="{{ $category->category_image_url }}" alt="categories" class="rounded-circle"></div>
                        @else
                            <div class="avatar-lg d-inline-block align-middle me-2">
                                <div class="avatar-title  text-danger font-size-24 m-0 rounded-circle font-weight-bold">
                                    {{ $category->category_image_name }}
                                </div>
                            </div>
                        @endif


                        <span class="text-light-black cat-name">{{ $category->local_lang_name }}</span>
                    </a>
                </li>
            @endforeach

        </ul>
        <div class="tab-content" id="myTabContent">

            @foreach ($food_categories as $key => $category)
                <div class="menu_list tab-pane  fade  @if ($key == 0) show active @endif mt-4" id="home{{ $category->id }}" role="tabpanel" aria-labelledby="home-tab">

                    @forelse ($category->foods as $food)
                        <div class=" restaurent-product-list food-item">
                            <div class="restaurent-product-detail ">
                                <div class="restaurent-product-img position-relative popup-slider " role="button" data-items='{!! json_encode($food->gallery_images_slider_data) !!}' style="max-height:150px;">
                                    <img data-src="{{ $food->food_image_url }}" alt="#" class="img-fluid position-absolute lazyload">
                                </div>
                                <div class="restaurent-product-left">
                                    <div class="restaurent-product-title-box ">
                                        <h5 class='name text-danger name'> {{ $food->local_lang_name }}</h5>
                                        <div class="restaurent-product-rating">
                                            <div class="ratings">
                                                <h5 class="amount text-danger">{{ $food->usd_price }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="restaurent-product-caption-box mt-2">
                                        <span class="text-light-white description">
                                            {{ $food->local_lang_description }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                            {{-- <div class="item_img"><img src="{{ $food->food_image_url }}" alt=""></div>
                            <div class="item_detail">
                                <h3 class='name'>{{ $food->local_lang_name }}</h3>
                                <p class="description">{{ $food->local_lang_description }}</p>
                            </div>
                            <div class="star_icon amount">
                                {{ $food->usd_price }}
                            </div> --}}
                        </div>
                    @empty

                        <div class=" restaurent-product-list food-item text-center not_found">
                            {{ __('system.messages.food_not_found') }}
                        </div>
                    @endforelse
                    @if (count($category->foods) > 0)
                        <div class=" restaurent-product-list food-item text-center not_found custome" style="display: none">
                            {{ __('system.messages.food_not_found') }}
                        </div>
                    @endif

                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('page_script')
    <script>
        $(document).ready(function() {
            function serch(search) {
                search = search.toLowerCase();
                $(".food-item").show();
                if (search == '') {

                    $(".food-item").show();
                    $(document).find(".not_found.custome").hide();
                } else {
                    search = search.replace('\\', '\\\\');
                    $(document).find('.tab-pane').each(function() {
                        var x = 0;
                        $(this).find('.food-item').each(function() {
                            if (!$(this).hasClass('not_found')) {
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
                                    x++;
                                }
                            }


                        });
                        if (x == $(this).find('.food-item').length - 1) {
                            $(this).find(".not_found").show();
                        } else {
                            $(this).find(".not_found").hide();
                        }

                    });


                }
            }
            $(document).on('click', '.search_icon', function() {
                var search = $(document).find('.search-text').val();
                serch(search)
            })

            $(document).on('keyup', '.search-text', function() {
                var search = $(this).val();
                serch(search)
            })
        })
    </script>
@endpush
