@extends('layouts.app')
@section('title', __('system.foods.view.menu'))

@push('page_css')
    @if ($food->label_image_url != null)
        <style>
            .profile-user {
                background-image: url("{{ $food->label_image_url }}");

            }
        </style>
    @endif
    <style>
        .page-content {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        .d-last-end {
            justify-content: end;
            align-items: end;
            display: inline-flex;
        }
    </style>
@endpush
@section('content')


    <div class="row">
        <div class="col-xl-12">
            <div class="profile-user"></div>
        </div>
    </div>

    <div class="row">
        <div class="profile-content">
            <div class="row align-items-end">
                <div class="col-sm">
                    <div class="d-flex align-items-end mt-3 mt-sm-0">
                        <div class="flex-shrink-0">
                            @if ($food->food_image_url != null)
                                <div class="avatar-xxl me-3 ">

                                    <img data-src="{{ $food->food_image_url }}" alt="" class="img-fluid rounded-circle d-block img-thumbnail lazyload" style="height:110px;width: 110px;object-fit: cover;">
                                </div>
                            @else
                                <div class="avatar-xxl me-3 d-last-end ">
                                    <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3">{{ $food->food_image_name }}</h1>
                                </div>
                            @endif


                        </div>
                        <div class="flex-grow-1">
                            <div>
                                <h5 class="font-size-16 mb-1">{{ $food->name }}</h5>
                                <p class="text-muted font-size-13 mb-2 pb-2">{{ $food->type }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="d-flex align-items-start justify-content-end gap-2 mb-2">
                        <div>
                            <a href="{{ route('restaurant.foods.index') }}" role="button" class="btn btn-secondary "><i class="me-1"></i> Back</a>

                        </div>
                        <div>
                            {{ Form::open(['route' => ['restaurant.foods.destroy', ['food' => $food->id]], 'class' => 'data-confirm', 'data-confirm-message' => 'Are you sure you want to destroy <b class="text-danger" >' . $food->name . '</b> food?', 'data-confirm-title' => 'Delete', 'id' => 'delete-form_' . $food->id, 'method' => 'delete', 'autocomplete' => 'off']) }}
                            {{ Form::close() }}
                            <div class="dropdown">
                                <button class="btn btn-link font-size-16 shadow-none text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-horizontal-rounded font-size-20"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('restaurant.foods.edit', ['food' => $food->id, 'back' => request()->fullurl()]) }}">{{ __('system.crud.edit') }}</a></li>
                                    <li><a class="dropdown-item" role="button" data-delete='#delete-form_{{ $food->id }}'>{{ __('system.crud.delete') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('system.fields.about') }}</h5>
                </div>

                <div class="card-body">
                    <div>
                        <div class="pb-3">
                            <div class="text-muted">
                                <dl class="row mb-0">
                                    <div class="col-md-6">
                                        <dt class="col-sm-4">{{ __('system.fields.food_name') }}</dt>
                                        <dd class="col-sm-8">{{ $food->name }}</dd>
                                    </div>
                                    <div class="col-md-6">
                                        <dt class="col-sm-4">{{ __('system.fields.category_name') }}</dt>
                                        <dd class="col-sm-8">

                                            @foreach ($food->food_categories as $category)
                                                <li>{{ $category->category_name }}</li>
                                            @endforeach
                                        </dd>
                                    </div>
                                    <div class="col-md-6">
                                        <dt class="col-sm-4">{{ __('system.fields.food_price') }}</dt>
                                        <dd class="col-sm-8">{{ $food->usd_price ?? '-' }}</dd>
                                    </div>
                                    <div class="col-md-6">
                                        <dt class="col-sm-4">{{ __('system.fields.preparation_time') }}</dt>
                                        <dd class="col-sm-8">{{ $food->preparation_time ?? '-' }}</dd>
                                    </div>

                                </dl>
                            </div>
                        </div>

                        <div class="pt-3">
                            <h5 class="font-size-15">{{ __('system.fields.food_description') }}:</h5>
                            <div class="text-muted">
                                {{ $food->description }}
                            </div>
                        </div>


                    </div>
                </div>

            </div>

        </div>

    </div>
    <div class="row mt-3">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body row">

                    @foreach ($food->gallery_images_with_details as $img)
                        <div class="col-md-3" style="display: flex;justify-content: center;align-items: center;">
                            <div class="pt-3 w-100">
                                <img src="{{ $img['url'] }}" alt="" class="w-100" style="max-height:250px;object-fit: cover;">

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


@endsection
