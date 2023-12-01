@extends('frontend.theme2.master')
@section('title', __('system.theme.menu'))
@push('page_css')
    <style>
        .menu_items a {
            height: 350px !important
        }

        @media (max-width: 768px) {
            .menu_items a {
                height: 200px !important
            }
        }
    </style>
@endpush
@section('content')

    {{-- <h2 class="main_title">{{ __('system.theme.menu') }}</h2> --}}
    <div class="row">
        @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])
        @foreach ($food_categories as $category)
            <div class="col-6 menu_items mb-3">
                <a href="{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => $category->id] + $append) }}">

                    @if ($category->category_image_url != null)
                        <span><img data-src="{{ $category->category_image_url }}" class="lazyload" alt=""></span>
                    @else
                        <span class="backgound-category">
                            {{ $category->local_lang_name }}
                        </span>
                    @endif
                    <h3>{{ $category->local_lang_name }}</h3>
                </a>
            </div>
        @endforeach
    </div>
@endsection
