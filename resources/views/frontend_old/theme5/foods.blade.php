@extends('frontend.theme5.master')
@section('title', __('system.theme.menu'))
@section('content')
    @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])

    <div style="position: relative; padding: 20px 0" class="">
        <a href="{{ route('restaurant.menu', ['restaurant' => $restaurant->id] + $append) }}" style="position: absolute; left: 0px; top: 25px; color: black">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <h1 style="text-align: center; font-size: 28px; margin-bottom: 0px; font-weight: 600">{{ $food_category->local_lang_name }}</h1>
    </div>

    <div class="product">
        @forelse ($foods as $food)
            <div class="product-item food-item">
                <div class="product-img popup-slider " role="button" data-items='{!! json_encode($food->gallery_images_slider_data) !!}'>
                    <img data-src="{{ $food->food_image_url }}" class="lazyload">
                </div>
                <div class="product-text">
                    <div class="product-title">
                        <h2 class='name'>{{ $food->local_lang_name }}</h2>
                        <p class="description">{{ $food->local_lang_description }}</p>
                    </div>
                    <div class="prodcut-cart">
                        <p class="amount">{{ $food->usd_price }}</p>
                        <!-- <a href="javascript:;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                                                                <circle cx="9" cy="21" r="1"></circle>
                                                                <circle cx="20" cy="21" r="1"></circle>
                                                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                                            </svg>
                                                        </a> -->
                    </div>
                </div>
            </div>
        @empty
            <div class="product-item food-item text-center">
                {{ __('system.messages.food_not_found') }}
            </div>
        @endforelse
        @if (count($foods) > 0)
            <div class="product-item food-item text-center not_found" style="display: none">
                {{ __('system.messages.food_not_found') }}
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
                if (search == '') {

                    $(".food-item").show();
                    $(document).find('.not_found').hide();
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
                    console.log(x, $(document).find('.food-item').length)
                    if (x == $(document).find('.food-item').length) {
                        $(document).find(".not_found").show();
                    } else {
                        $(document).find(".not_found").hide();
                    }

                }
            }
            $(document).on('click', '.search_icon', function() {
                var search = $(this).parents('.input-group').find('.search-text').val();
                serch(search)
            })

            $(document).on('keyup', '.search-text', function() {

                var search = $(this).val();
                serch(search)
            })
        })
    </script>
@endpush
