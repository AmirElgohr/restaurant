@extends('layouts.app')

@section('content')
    <div class="row">


        <div class="col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">{{ __('system.profile.edit.menu') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('restaurant.profile') }}">{{ __('system.profile.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.profile.edit.menu') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <form autocomplete="off" novalidate="" action="{{ route('restaurant.profile.update') }}" id="pristine-valid" method="post" enctype="multipart/form-data">

                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <div class=" d-flex  align-items-center ">
                                        <div class='mx-3 '>
                                            @php($lbl_profile_image = __('system.fields.profile_image'))

                                            @if ($user->profile_url != null)
                                                <img data-src="{{ $user->profile_url }}" alt="" class="avatar-xl rounded-circle img-thumbnail preview-image lazyload">
                                            @else
                                                <div class="preview-image-default">
                                                    <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3 ">{{ $user->logo_name }}</h1>
                                                </div>
                                                <img class="avatar-xl rounded-circle img-thumbnail preview-image" style="display: none;" />
                                            @endif
                                        </div>

                                        <input type="file" name="profile_image" id="profile_image" class="d-none my-preview" accept="image/*"
                                            data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_profile_image)]) }}" data-preview='.preview-image'>
                                        <label for="profile_image" class="mb-0">
                                            <div for="profile-image" class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                                                {{ $lbl_profile_image }}
                                            </div>
                                        </label>

                                    </div>
                                </div>
                                @error('profile_image')
                                    <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mt-3">

                                <div class="col-md-4 ">
                                    @php($lbl_first_name = __('system.fields.first_name'))
                                    <div class="mb-3 form-group @error('first_name') has-danger @enderror">
                                        <label class="form-label" for="first_name">{{ $lbl_first_name }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control start_no_space" id="first_name" placeholder="{{ $lbl_first_name }}" required="" value="{{ old('first_name', $user->first_name) }}" maxlength="50"
                                            data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_first_name)]) }}" name="first_name" pattern="/^[a-zA-Z]+[a-zA-Z]+$/"
                                            data-pristine-pattern-message="{{ __('validation.custom.invalid', ['attribute' => strtolower($lbl_first_name)]) }}">
                                        @error('first_name')
                                            <div class="pristine-error text-help">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    @php($lbl_last_name = __('system.fields.last_name'))

                                    <div class="mb-3 form-group @error('last_name') has-danger @enderror">
                                        <label class="form-label" for="last_name">{{ $lbl_last_name }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control start_no_space" id="last_name" placeholder="{{ $lbl_last_name }}" required="" value="{{ old('last_name', $user->last_name) }}" maxlength="50"
                                            name="last_name" data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_last_name)]) }}" pattern="/^[a-zA-Z]+[a-zA-Z]+$/"
                                            data-pristine-pattern-message="{{ __('validation.custom.invalid', ['attribute' => strtolower($lbl_last_name)]) }}">
                                        @error('last_name')
                                            <div class="pristine-error text-help">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    @php($lbl_phone_number = __('system.fields.phone_number'))

                                    <div class="mb-3 form-group @error('phone_number') has-danger @enderror">
                                        <label class="form-label" for="pristine-phone-valid">{{ $lbl_phone_number }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="pristine-phone-valid" placeholder="{{ $lbl_phone_number }}" required maxlength="15" value="{{ old('phone_number', $user->phone_number) }}" maxlength="50"
                                            name="phone_number" data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_phone_number)]) }}">
                                        @error('phone_number')
                                            <div class="pristine-error text-help">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 d-none">
                                <h5 class="font-size-14 mb-3">{{ __('system.fields.address_details') }}</h5>
                                <div class="col-md-4">
                                    @php($lbl_city = __('system.fields.city'))

                                    <div class="mb-3 form-group @error('city') has-danger @enderror">
                                        <label class="form-label" for="input-city">{{ $lbl_city }}</label>
                                        <input type="text" name="city" class="form-control" id="input-city" placeholder="{{ $lbl_city }}" value="{{ old('city', $user->city) }}">

                                    </div>
                                    @error('city')
                                        <div class="pristine-error text-help">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    @php($lbl_state = __('system.fields.state'))

                                    <div class="mb-3 form-group @error('state') has-danger @enderror">
                                        <label class="form-label" for="input-state">{{ $lbl_state }}</label>
                                        <input type="text" name="state" class="form-control" id="input-state" placeholder="{{ $lbl_state }}" value="{{ old('state', $user->state) }}">

                                    </div>
                                    @error('state')
                                        <div class="pristine-error text-help">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    @php($lbl_country = __('system.fields.country'))

                                    <div class="mb-3 form-group @error('country') has-danger @enderror">
                                        <label class="form-label" for="input-country">{{ $lbl_country }}</label>
                                        <input type="text" name="country" class="form-control" id="input-country" placeholder="{{ $lbl_country }}" value="{{ old('country', $user->country) }}">

                                    </div>
                                    @error('country')
                                        <div class="pristine-error text-help">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    @php($lbl_zip = __('system.fields.zip'))
                                    <div class="mb-3 form-group @error('zip') has-danger @enderror">
                                        <label class="form-label" for="input-zip">{{ $lbl_zip }}</label>
                                        <input type="text" name="zip" class="form-control pristine-custom-pattern" id="input-zip" placeholder="{{ $lbl_zip }}" maxlength="8" value="{{ old('zip', $user->zip) }}"
                                            custom-pattern="^([0-9a-zA-Z]{4,8}|.{0})$" maxlength="8" data-pristine-pattern-message="{{ __('validation.custom.invalid', ['attribute' => strtolower($lbl_zip)]) }}">

                                    </div>
                                    @error('zip')
                                        <div class="pristine-error text-help">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8">
                                    @php($lbl_address = __('system.fields.address'))

                                    <div class="mb-3 form-group @error('address') has-danger @enderror">
                                        <label class="form-label" for="input-address">{{ $lbl_address }}</label>
                                        <textarea name="address" class="form-control" id="input-address" cols="30" placeholder="{{ $lbl_address }}" rows="2">{{ old('address', $user->address) }}</textarea>

                                    </div>
                                    @error('address')
                                        <div class="pristine-error text-help">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                                    <a href="{{ route('restaurant.profile') }}"class="btn btn-outline-primary">{{ __('system.crud.back') }}</a>
                                </div>
                            </div>

                        </form>
                    </div>


                </div>

            </div>
        </div>
    </div>
@endsection
