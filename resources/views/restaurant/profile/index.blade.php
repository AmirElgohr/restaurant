@extends('layouts.app')

@section('content')
    <div class="row">

        <div class="col-xl-12 col-sm-12">

            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">{{ __('system.profile.menu') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.profile.menu') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div>
                                    @if ($user->profile_url != null)
                                        <img data-src="{{ $user->profile_url }}" alt="" class="avatar-xl rounded-circle img-thumbnail lazyload">
                                    @else
                                        <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3">{{ $user->logo_name }}</h1>
                                    @endif
                                </div>
                                <div class="flex-1 ms-3">
                                    <h5 class="font-size-15 mb-1"><a href="#" class="text-dark text-break">{{ $user->name }}</a></h5>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6  p-0">
                            <div class="justify-content-md-end mt-3 ">
                                <a href="{{ route('restaurant.profile.edit') }}" role="button" class="btn btn-success waves-effect waves-light">{{ __('system.crud.edit') }}</a>
                                <a href="{{ route('restaurant.password.edit') }}" role="button" class="btn btn-success waves-effect waves-light">{{ __('system.password.menu') }}</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-3 pt-1">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4">{{ __('system.fields.first_name') }}</dt>
                                        <dd class="col-sm-8">{{ $user->first_name }}</dd>

                                        <dt class="col-sm-4">{{ __('system.fields.last_name') }}</dt>
                                        <dd class="col-sm-8">{{ $user->last_name }}</dd>

                                        <dt class="col-sm-4">{{ __('system.fields.phone_number') }}</dt>
                                        <dd class="col-sm-8">{{ $user->phone_number }}</dd>


                                    </dl>
                                </div>
                            </div>
                            <div class="col-md-6 d-none">
                                <div class="mt-3 pt-1">
                                    <dl class="row mb-0">

                                        <dt class="col-sm-4">{{ __('system.fields.country') }}</dt>
                                        <dd class="col-sm-8">{{ $user->country ?? '-' }}</dd>

                                        <dt class="col-sm-4">{{ __('system.fields.state') }}</dt>
                                        <dd class="col-sm-8">{{ $user->state ?? '-' }}</dd>
                                        <dt class="col-sm-4">{{ __('system.fields.city') }}</dt>
                                        <dd class="col-sm-8">{{ $user->state ?? '-' }}</dd>

                                        <dt class="col-sm-4 text-truncate">{{ __('system.fields.zip') }}</dt>
                                        <dd class="col-sm-8">{{ $user->zip ?? '-' }}</dd>

                                        <dt class="col-sm-4 text-truncate">{{ __('system.fields.address') }}</dt>
                                        <dd class="col-sm-8">{{ $user->address ?? '-' }}</dd>

                                    </dl>
                                </div>
                            </div>
                        </div>



                    </div>


                </div>
            </div>
        </div>
    @endsection
