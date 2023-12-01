<div class="item @if($key > 3) hide @endif itemImgSwiper">
    <div class="item-img wrapper swiper-wrapper">
        @if(!empty($product->gallery_images))
            @foreach($product->gallery_images as $image)
                <img src="{{ getFileUrl($image) }}" alt="" class="slide swiper-slide"/>
            @endforeach
        @endif
    </div>
    @if(!empty($product->price) || !empty($product->name))
        <div class="item-desc">
            <p class="price">{{ $product->price }} <span>{{config('app.currency_symbol')}}</span></p>
            <p>{{ $product->name }}</p>
        </div>
    @endif
</div>
