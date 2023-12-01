@push('page_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">
    <style>
        img.mfp-img {
            width: 100%;
        }
    </style>
@endpush
{{-- <div class=""> --}}
@if (count($foods) > 0)
    @foreach ($foods as $food)
        <div class="bg-white dark:bg-secondary/50 rounded-xl shadow-shadowitem hover:shadow-shadowdark transition food-item" style="{{ $style ?? '' }}">
            <a href="javascript:"><img src="{{ $food->food_image_url }}" alt="" class="w-full rounded-t-xl h-56 object-cover popup-slider" data-items='{!! json_encode($food->gallery_images_slider_data) !!}' /></a>
            <div class="p-4">
                <p class="font-bold dark:text-white name truncate">{{ $food->local_lang_name }}</p>
                <p class="text-neutral font-semibold my-3 text-sm line-clamp-3 dark:text-[#B4C1E0] description truncate  !block">
                    {{ $food->local_lang_description }}</p>
                <div class="text-primary font-bold text-sm dark:text-white amount"> {{ $food->usd_price }}</div>
            </div>
        </div>
    @endforeach
@else
    <p class="font-bold dark:text-white name truncate not_found"> {{ __('system.messages.food_not_found') }}</p>
@endif
{{-- </div> --}}
@push('page_js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script>
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
@endpush
