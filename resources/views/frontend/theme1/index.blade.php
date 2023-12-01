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
                    class="w-full outline-none border-2 border-neutral/30 dark:border-secondary/50 rounded-lg py-3 pl-10 pr-4 placeholder:text-sm placeholder:text-secondary dark:placeholder:text-white dark:text-white font-semibold dark:bg-white/10 search-text">
            </div>
        </div>
        @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])
            @foreach ($food_categories as $category)
                <div class="category">
                    <div class="lg:flex items-center justify-between pt-0 pb-8 text-center lg:text-left">
                        <h3 class="text-2xl font-bold mb-5 lg:mb-0 dark:text-white title">{{ $category->local_lang_name }}
                        </h3>
                    </div>
                    <div class="mb-10">
                        <div class="grid md:grid-cols-2 gap-5">
                            @foreach ($category->foods as $key => $food)
                                <div class="bg-white dark:bg-secondary/50 rounded-xl p-4 food-item">
                                    <a href="javascript:"
                                        class="font-bold text-secondary dark:text-white name">{{ $food->local_lang_name }}</a>
                                    <p class="text-neutral text-sm pt-3 mb-4 font-semibold line-clamp-3 description">
                                        {{ $food->local_lang_description }}</p>
                                    <button type="button"
                                        class="text-primary font-bold text-sm dark:text-white bg-primary/10 dark:bg-primary rounded-lg py-1.5 px-3 inline-block amount"><span>{{ $food->usd_price }}</span></button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            <p class="font-bold dark:text-white name truncate not_found" style="{{ count($category->foods) > 0 ? "display:none;" : "" }}"> {{ __('system.messages.food_not_found') }}</p>
    </section>
@endsection
@push('page_js')
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

                                if (val1.search(search) == -1 && val2.search(search) == -1 && val3
                                    .search(search) == -1) {
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
            $(document).on('keyup', '.search-text', function() {
                var search = $(this).val();
                serch(search)
            })
        })
    </script>
@endpush
