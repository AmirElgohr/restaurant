@extends('frontend.theme2.master')
@section('title', __('system.theme.menu'))
@push('page_css')
    <style>
        .item_img {
            width: 85px !important;
            max-width: auto !important;
            flex: none;
        }

        .star_icon {
            flex: none;
        }
    </style>
@endpush
@section('content')
    <div class="box-search form-group mb-4">
        <input type="text" class="form-control search-text" placeholder="{{ __('system.crud.search') }}" @if (count($foods) == 0) disabled="disabled" @endif>
        <span class="search_icon d-flex justify-content-center align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18px" height="18px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </span>
    </div>
    <div class="media">
        <div class="media-body">

            <h2 class="main_title">{{ $food_category->local_lang_name }}</h2>

        </div>
        @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])

        <a href="{{ route('restaurant.menu', ['restaurant' => $restaurant->id] + $append) }}"class="btn btn-danger">{{ __('system.crud.back') }}</a>
    </div>

    <div class="menu_list">
        @forelse ($foods as $food)
            <div class="item_menu food-item">
                <div class="item_img position-relative popup-slider " role="button" data-items='{!! json_encode($food->gallery_images_slider_data) !!}'><img src="{{ $food->food_image_url }}" class="position-absolute " alt=""></div>
                <div class="item_detail">
                    <h3 class='name'>{{ $food->local_lang_name }}</h3>
                    <p class="description">{{ $food->local_lang_description }}</p>
                </div>
                <div class="star_icon amount">
                    {{ $food->usd_price }}
                </div>
            </div>
        @empty
            <div class="item_menu food-item ">
                <p class="text-center"> {{ __('system.messages.food_not_found') }}</p>
            </div>
        @endforelse
        @if (count($foods) > 0)
            <div class="item_menu food-item not_found" style="display: none">
                <p class="text-center"> {{ __('system.messages.food_not_found') }}</p>
            </div>
        @endif
    </div>
@endsection
@push('page_script')
    <script>
        $(document).ready(function() {
            function serch(search) {
                search = search.toLowerCase();
                $(".food-item").show();
                $(document).find(".not_found").hide();
                if (search == '') {
                    $(".food-item").show();
                    $('.not_found').hide();
                } else {
                    search = search.replace('\\', '\\\\');
                    var x = 0;
                    $(document).find('.food-item').each(function() {
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



                    });
                    if (x == $(document).find('.food-item').length) {
                        $(document).find(".not_found").show();
                    } else {
                        $(document).find(".not_found").hide();
                    }

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
