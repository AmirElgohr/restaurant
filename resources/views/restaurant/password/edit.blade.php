@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-mb-6">
                            <h4 class="card-title">{{ __('system.profile.menu') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('restaurant.profile') }}">{{ __('system.profile.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.password.menu') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <form autocomplete="off" novalidate="" action="{{ route('restaurant.password.update') }}" id="pristine-valid" method="post" enctype="multipart/form-data" class="submit-with-confirm">
                                <div class="row">
                                    @csrf
                                    @method('put')
                                    <div class="col-md-12">
                                        <div class="mb-3 form-group @error('current_password') has-danger @enderror">
                                            @php($lbl_current_password = __('system.fields.current_password'))

                                            <label class="form-label" for="current_password">{{ $lbl_current_password }} <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="current_password" placeholder="{{ $lbl_current_password }}" required="" maxlength="18"
                                                data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_current_password)]) }}" name="current_password">
                                            @error('current_password')
                                                <div class="pristine-error text-help">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @php($lbl_new_password = __('system.fields.new_password'))

                                        <div class="mb-3 form-group @error('password') has-danger @enderror">
                                            <label class="form-label" for="pristine-password-valid">{{ $lbl_new_password }} <span class="text-danger">*</span></label>
                                            <input type="password" name="password" id="pristine-password-valid" class="form-control"placeholder="{{ $lbl_new_password }}" required maxlength="16"
                                                data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_new_password)]) }}" data-pristine-password-message="{{ __('validation.password.invalid') }}">
                                            @error('password')
                                                <div class="pristine-error text-help">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @php($lbl_password_confirmation = __('system.fields.password_confirmation'))
                                        <div class="mb-3 form-group @error('password_confirmation') has-danger @enderror">
                                            <label class="form-label" for="pristine-password_confirmation-valid">{{ $lbl_password_confirmation }} <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="pristine-password_confirmation-valid" placeholder="{{ $lbl_password_confirmation }}" maxlength="50" name="password_confirmation" required
                                                data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_password_confirmation)]) }}"
                                                data-pristine-password_confirmation-message="{{ __('validation.password.passwordconfirmation') }}" data-pristine-password-metch="pristine-password-valid">
                                            @error('password_confirmation')
                                                <div class="pristine-error text-help">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-3">
                                        <button class="btn btn-primary" type="submit">{{ __('system.password.menu') }}</button>
                                        <a href="{{ route('restaurant.profile') }}"class="btn btn-outline-primary">{{ __('system.crud.back') }}</a>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>

                <!-- end card -->
            </div>
        </div>
    @endsection
