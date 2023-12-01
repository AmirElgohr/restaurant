@extends('frontend.theme5.master')
@section('title', __('system.theme.menu'))
@section('content')
    @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])
    <div class="menu-list ">
        @foreach ($food_categories as $category)
            <a href="{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => $category->id] + $append) }}" class="menu-disc">
                <div class="disc-photo">
                    @if ($category->category_image_url != null)
                        <span><img data-src="{{ $category->category_image_url }}" class="lazyload" alt=""></span>
                    @else
                        <span class="backgound-category">
                            {{ $category->local_lang_name }}
                        </span>
                    @endif
                </div>
                <p>{{ $category->local_lang_name }}</p>
            </a>
        @endforeach
    </div>

    </div>
@endsection
