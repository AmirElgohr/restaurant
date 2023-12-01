@extends('frontend.master')
@section('content')
    <section class="container">
        <div class="lg:flex items-center justify-between pt-14 pb-8 text-center lg:text-left">
            <h3 class="text-2xl font-bold mb-5 lg:mb-0 dark:text-white">{{ $food_category->local_lang_name }}</h3>
            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-end gap-5">

                {!! Form::select('categories', $categoires, $food_category->id, [
                    'class' => 'text-white bg-neutral dark:bg-[#2c333f] text-sm font-semibold py-3.5 px-4 rounded-lg border border-neutral dark:border-secondary outline-none',
                    'id' => 'category',
                ]) !!}

                <div class="relative sm:w-[350px]">
                    <button class="absolute left-3 top-1/2 -translate-y-1/2">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-secondary dark:text-white">
                            <g clip-path="url(#clip0_75_257)">
                                <path
                                    d="M15.8667 15.8668C16.2127 15.5208 16.7737 15.5207 17.1197 15.8667L18.3729 17.1194C18.7192 17.4655 18.7192 18.0269 18.373 18.3731C18.0268 18.7193 17.4654 18.7193 17.1193 18.373L15.8666 17.1198C15.5206 16.7738 15.5207 16.2128 15.8667 15.8668Z"
                                    fill="currentColor" />
                                <path
                                    d="M8.9748 1C13.3769 1 16.9496 4.57271 16.9496 8.9748C16.9496 13.3769 13.3769 16.9496 8.9748 16.9496C4.57271 16.9496 1 13.3769 1 8.9748C1 4.57271 4.57271 1 8.9748 1ZM8.9748 15.1774C12.4013 15.1774 15.1774 12.4013 15.1774 8.9748C15.1774 5.54741 12.4013 2.77218 8.9748 2.77218C5.54741 2.77218 2.77218 5.54741 2.77218 8.9748C2.77218 12.4013 5.54741 15.1774 8.9748 15.1774Z"
                                    fill="currentColor" />
                            </g>
                            <defs>
                                <clipPath id="clip0_75_257">
                                    <rect width="20" height="20" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                    <input type="text" placeholder="Search Item" id="search_text"
                        class="w-full outline-none border-2 border-neutral/30 dark:border-secondary/50 rounded-lg py-3 pl-10 pr-4 placeholder:text-sm placeholder:text-secondary dark:placeholder:text-white dark:text-white font-semibold dark:bg-white/10">
                </div>
            </div>
        </div>
        <div class="pb-12 md:pb-32 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 xl:gap-8">
            @include('frontend.food_list')
            <p class="font-bold dark:text-white name truncate not_found custome" style="display:none"> {{ __('system.messages.food_not_found') }}</p>
        </div>
    </section>
@endsection
@push('page_js')
    <script>
        @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])

        var categoriesRoute = "{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => '#ID#']) . '?' . http_build_query($append) }}"

        $(document).on("change", "#category", function() {
            categoriesRoute = categoriesRoute.replace("#ID#", $(this).val())
            document.location.href = categoriesRoute

        })
        $(document).ready(function() {
            function serch(search) {
                search = search.toLowerCase();
                $(".food-item").show();
                $(document).find(".not_found").hide();
                if (search == '') {
                    $(".food-item").show();
                    $('.not_found.custome').hide();
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
                        $(document).find(".not_found.custome").show();
                    } else {
                        $(document).find(".not_found").hide();
                    }

                }
            }


            $(document).on('keyup', '#search_text', function() {
                var search = $(this).val();
                serch(search)
            })
        })
    </script>
@endpush
