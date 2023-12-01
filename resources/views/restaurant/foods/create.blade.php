@extends('layouts.app')
@section('title', __('system.foods.create.menu'))
@section('content')
    <div class="col-xl-12 col-sm-12">
        <div class="card">
            <div class="card-header">

                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <h4 class="card-title">{{ __('system.foods.create.menu') }}</h4>
                        <div class="page-title-box pb-0 d-sm-flex">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                    <li class="breadcrumb-item "><a href="{{ route('restaurant.restaurants.index') }}">{{ __('system.foods.menu') }}</a></li>
                                    <li class="breadcrumb-item active">{{ __('system.foods.create.menu') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-body">
                @php
                    $uniqe = createQniqueSessionAndDestoryOld('unique');
                @endphp
                @include('restaurant.foods.multi_file', ['unique' => $uniqe, 'field_name' => 'gallery_image'])

                <form autocomplete="off" novalidate="" action="{{ route('restaurant.foods.store') }}" id="pristine-valid" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="folder" value="{{ $uniqe }}">
                    @include('restaurant.foods.fields')
                    <div class="row ">
                        <div class="col-md-12 mt-3">
                            <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                            <a href="{{ route('restaurant.foods.index') }}"class="btn btn-secondary">{{ __('system.crud.cancel') }}</a>
                        </div>
                    </div>

                </form>


            </div>
            <!-- end card -->
        </div>
    </div>
    </div>
@endsection
