@extends('frontend.master')
@section('content')
    <section class="container">
        <div class="flex justify-end pt-14 pb-8">
            <div class="relative w-[350px]">
                <button class="absolute left-3 top-1/2 -translate-y-1/2">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="text-secondary dark:text-white">
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
        @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])
        <div class="grid sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-x-5 gap-y-9 mt-8 mb-12 lg:mb-20 lg:mt-12"
            id="categories">
            @foreach ($food_categories as $category)
                <div class="text-center">
                    <a
                        href="{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => $category->id] + $append) }}">
                        <img src="{{ $category->category_image_url }}" alt=""
                            class="w-full rounded-xl h-36 object-cover" />
                    </a>
                    <a href="{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => $category->id] + $append) }}"
                        class="mt-3 inline-block font-bold line-clamp-1 dark:text-white">{{ $category->local_lang_name }}</a>
                </div>
            @endforeach

        </div>


        <div class="pb-12 md:pb-32 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 xl:gap-8 ">
            @php($foods = $restaurant->foods)
            @include('frontend.food_list', ['style' => 'display:none;'])
            <p class="font-bold dark:text-white name truncate not_found"
                style="{{ count($food_categories) > 0 ? 'display:none;' : '' }}">
                {{ __('system.messages.food_not_found') }}</p>
        </div>

    </section>
@endsection
@push('page_js')
    <script>
        $(document).ready(function() {
            function serch(search) {

                search = search.toLowerCase();
                $(document).find(".not_found").hide();

                if (search == '') {
                    $(".food-item").hide();
                    $('.not_found').hide();
                    $(document).find('#categories').show();
                } else {
                    {{-- $(".food-item").show(); --}}
                    $(document).find('#categories').hide();
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

                        if (val1.search(search) == -1 && val2.search(search) == -1 && val3.search(search) ==
                            -1) {
                            $(this).hide();
                            x++;
                        } else {
                            $(this).show();
                        }



                    });
                    if (x == $(document).find('.food-item').length) {
                        $(document).find(".not_found").show();
                    } else {
                        $(document).find(".not_found").hide();
                    }

                }
                // get a new date (locale machine date time)
                var date = new Date();
                // get the date as a string
                var n = date.toDateString();
                // get the time as a string
                var time = date.toLocaleTimeString();


            }


            $(document).on('keyup', '#search_text', function() {
                var search = $(this).val();
                serch(search)
            })
        })
    </script>
@endpush
