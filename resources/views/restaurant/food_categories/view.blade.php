@extends('layouts.app')
@section('title', __('system.food_categories.view.title'))

@push('page_css')
    @if ($restaurant->cover_image_url != null)
        <style>
            .profile-user {
                background-image: url("{{ $restaurant->cover_image_url }}");

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
                            @if ($restaurant->logo_url != null)
                                <div class="avatar-xxl me-3 ">

                                    <img data-src="{{ $restaurant->logo_url }}" alt="" class="img-fluid rounded-circle d-block img-thumbnail lazyload">
                                </div>
                            @else
                                <div class="avatar-xxl me-3 d-last-end ">
                                    <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3">{{ $restaurant->logo_name }}</h1>
                                </div>
                            @endif


                        </div>
                        <div class="flex-grow-1">
                            <div>
                                <h5 class="font-size-16 mb-1">{{ $restaurant->name }}</h5>
                                <p class="text-muted font-size-13 mb-2 pb-2">{{ $restaurant->type }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="d-flex align-items-start justify-content-end gap-2 mb-2">
                        <div>
                            {{ Form::open(['route' => ['restaurant.default.restaurant', ['restaurant' => $restaurant->id]], 'method' => 'put', 'autocomplete' => 'off']) }}
                            <a href="{{ route('restaurant.restaurants.index') }}" role="button" class="btn btn-outline-primary "><i class="me-1"></i> Back</a>
                            <input type="hidden" name='back' value="{{ request()->fullurl() }}">
                            <button type="submit" class="btn btn-primary {{ auth()->user()->restaurant_id == $restaurant->id ? 'disabled' : '' }}"><i class="me-1"></i> Set As Default</button>
                            {{ Form::close() }}
                        </div>
                        <div>
                            {{ Form::open(['route' => ['restaurant.restaurants.destroy', ['restaurant' => $restaurant->id]], 'autocomplete' => 'off', 'class' => 'data-confirm', 'data-confirm-message' => 'Are you sure you want to destroy <b class="text-danger" >' . $restaurant->name . '</b> restaurant?', 'data-confirm-title' => 'Delete', 'id' => 'delete-form_' . $restaurant->id, 'method' => 'delete']) }}
                            {{ Form::close() }}
                            <div class="dropdown">
                                <button class="btn btn-link font-size-16 shadow-none text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-horizontal-rounded font-size-20"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('restaurant.restaurants.edit', ['restaurant' => $restaurant->id, 'back' => request()->fullurl()]) }}">{{ __('system.crud.edit') }}</a></li>
                                    <li><a class="dropdown-item" role="button" data-delete='#delete-form_{{ $restaurant->id }}'>{{ __('system.crud.delete') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-transparent shadow-none">
                <div class="card-body">
                    <ul class="nav nav-tabs-custom card-header-tabs border-top mt-2" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link px-3 active" data-bs-toggle="tab" href="#Details" role="tab">Details</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-8">
            <div class="tab-content">
                <div class="tab-pane active" id="Details" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">About</h5>
                        </div>

                        <div class="card-body">
                            <div>
                                <div class="pb-3">
                                    <h5 class="font-size-15">Contact Details:</h5>
                                    <div class="text-muted">
                                        <dl class="row mb-0">
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Restaurant Name</dt>
                                                <dd class="col-sm-8">{{ $restaurant->name }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Restaurant Type</dt>
                                                <dd class="col-sm-8">{{ $restaurant->type }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Phone Number</dt>
                                                <dd class="col-sm-8">{{ $restaurant->phone_number ?? '-' }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Email</dt>
                                                <dd class="col-sm-8">{{ $restaurant->contact_email ?? '-' }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Language</dt>
                                                <dd class="col-sm-8">{{ $restaurant->language_string ?? 'English' }}</dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>

                                <div class="pt-3">
                                    <h5 class="font-size-15">Location :</h5>
                                    <div class="text-muted">
                                        <dl class="row mb-0">
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">City</dt>
                                                <dd class="col-sm-8">{{ $restaurant->city ?? '-' }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">State</dt>
                                                <dd class="col-sm-8">{{ $restaurant->state ?? '-' }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Country</dt>
                                                <dd class="col-sm-8">{{ $restaurant->country ?? '-' }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Address</dt>
                                                <dd class="col-sm-8">{{ $restaurant->address ?? '-' }}</dd>
                                            </div>

                                        </dl>
                                    </div>
                                </div>

                                <div class="pt-3">
                                    <h5 class="font-size-15">Created By:</h5>
                                    <div class="text-muted">
                                        <dl class="row mb-0">
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Name</dt>
                                                <dd class="col-sm-8">{{ $restaurant->created_user->name }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Email</dt>
                                                <dd class="col-sm-8">{{ $restaurant->created_user->email ?? '-' }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Phone</dt>
                                                <dd class="col-sm-8">{{ $restaurant->created_user->phone_number ?? '-' }}</dd>
                                            </div>
                                            <div class="col-md-6">
                                                <dt class="col-sm-4">Date & Time</dt>
                                                <dd class="col-sm-8">{{ $restaurant->created_at }}</dd>
                                            </div>

                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

        </div>


        <div class="col-xl-4 col-lg-4">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Staffs</h5>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <table class="table align-middle table-nowrap">
                            <tbody>
                                @if ($restaurant->users)
                                    @foreach ($restaurant->users as $user)
                                        <tr>
                                            <td style="width: 50px;">
                                                @if ($user->profile_url != null)
                                                    <img data-src="{{ $user->profile_url }}" alt="" class="avatar-sm rounded-circle me-2 lazyload">
                                                @else
                                                    <div class="avatar-sm d-inline-block align-middle me-2">
                                                        <div class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold">
                                                            {{ $user->logo_name }}
                                                        </div>
                                                    </div>
                                                @endif

                                            </td>
                                            <td>
                                                <h5 class="font-size-14 m-0"><a href="javascript: void(0);" class="text-dark">{{ $user->name }}</a></h5>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $user->email }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $user->phone_number }}
                                            </td>
                                        </tr>
                                    @endforeach

                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>

    </div>



@endsection
