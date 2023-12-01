@extends('frontend.master')
@section('content')
    <div class="container">
        <div class="flex justify-center md:justify-end pt-14">
            <div class="relative sm:w-[350px]">
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
                <input type="text" placeholder="Search Item"
                    class="w-full outline-none border-2 border-neutral/30 dark:border-secondary/50 rounded-lg py-3 pl-10 pr-4 placeholder:text-sm placeholder:text-secondary dark:placeholder:text-white dark:text-white font-semibold dark:bg-white/10 search-text">
            </div>
        </div>
        <div class="overflow-x-auto mb-4 scrollbar-thin scrollbar-thumb-primary scrollbar-track-[#d1d7e7]">
            <div class="flex w-max justify-between gap-4 py-8 lg:py-12 font-semibold dark:text-white" id="myTab"
                data-tabs-toggle="#myTabContent" role="tablist">
                @foreach ($food_categories as $key => $category)
                    <div class="text-center w-40 xl:w-auto {{ $key == 0 ? 'actives' : '' }} cursor-pointer "
                        id="profile{{ $category->id }}-tab" data-tabs-target="#profile{{ $category->id }}" type="button"
                        role="tab" aria-controls="profile{{ $category->id }}">
                        <img src="{{ $category->category_image_url }}" alt=""
                            class="mx-auto w-24 h-24 rounded-full border border-2  border-transparent " />
                        <a href="javascript:" class="mt-3 inline-block line-clamp-3">{{ $category->local_lang_name }}</a>
                    </div>
                @endforeach

            </div>
        </div>
        <div id="myTabContent">
            @foreach ($food_categories as $key => $category)
                <div class="pb-12 md:pb-32 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 xl:gap-8  {{ $key == 0 ? 'active' : 'hidden' }} tab-pane"
                    id="profile{{ $category->id }}" role="tabpanel" aria-labelledby="profile{{ $category->id }}-tab">
                    @include('frontend.food_list', ['foods' => $category->foods])
                    {{-- <p class="font-bold dark:text-white name truncate not_found custome" style="display:none"> {{ __('system.messages.food_not_found') }}</p> --}}
                    <p class="font-bold dark:text-white name truncate not_found"
                        style="{{ count($category->foods) > 0 ? 'display:none;' : '' }}">
                        {{ __('system.messages.food_not_found') }}</p>
                </div>
            @endforeach

        </div>
    </div>
@endsection
@push('page_js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '[role=tab]', function() {
                var _target = $(this).data('tabs-target')
                $(document).find("#myTabContent .active").addClass('hidden')
                $(document).find("#myTabContent .active").removeClass('active')
                $(document).find(_target).removeClass('hidden')
                $(document).find(_target).addClass('active')
                $(document).find("#myTab .actives").removeClass('actives')
                $(this).addClass('actives')
            })


            function serch(search) {
                search = search.toLowerCase();

                if (search == '') {

                    $(".food-item").show();
                    $(document).find(".not_found.custome").hide();
                } else {
                    $(".food-item").show();
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

                                if (val1.search(search) == -1 && val2.search(search) == -1 && val3
                                    .search(search) == -1) {
                                    $(this).hide();
                                    x++;
                                }
                            }

                        });
                        if (x == $(this).find('.food-item').length) {
                            $(this).find(".not_found").show();
                        } else {
                            $(this).find(".not_found").hide();
                        }

                    });

                }
            }

            $(document).on('keyup', '.search-text', function() {
                var search = $(this).val();
                serch(search)
            })
        })
    </script>
@endpush
