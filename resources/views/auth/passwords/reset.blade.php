@extends('auth.layouts.app')
@section('title', __('auth.reset_password.main_title'))
@section('content')
    <div class="auth-content my-auto">
        <div class="text-center">
            <h5 class="mb-0">{{ __('auth.reset_password.main_title') }}</h5>
            <p class="text-muted mt-2">{{ __('auth.reset_password.title') }}</p>
        </div>
        <form autocomplete="off" class="mt-4 pt-2 pristine-valid" action="{{ route('password.update') }}" method="post" id="pristine-valid">
            @csrf
            @php
                if (!isset($token)) {
                    $token = \Request::route('token');
                }
            @endphp
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-4 form-group">
                <div class="form-floating form-floating-custom @error('email') has-danger @enderror">
                    @php($lbl_email = __('system.fields.email'))
                    <input type="email" class="form-control" id="input-username" placeholder="{{ $lbl_email }}" name="email" value="{{ old('email') }}" required
                        data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_email)]) }}" data-pristine-email-message="{{ __('validation.custom.invalid', ['attribute' => strtolower($lbl_email)]) }}">
                    <label for="input-username">{{ $lbl_email }}</label>
                    <div class="form-floating-icon">
                        <i data-feather="users"></i>
                    </div>
                </div>
                @error('email')
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4 form-group">
                <div class="form-floating form-floating-custom auth-pass-inputgroup @error('password') has-danger @enderror">

                    @php($lbl_password = __('system.fields.password'))
                    <input type="password" class="form-control pe-5 " id="pristine-password-valid" name="password" placeholder="{{ $lbl_password }}" required maxlength="16"
                        data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_password)]) }}" data-pristine-password-message="{{ __('validation.password.invalid') }}">
                    <button type="button" class="btn btn-link  position-absolute h-100 end-0 top-0" id="password-addon">
                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                    </button>
                    <label for="pristine-password-valid">{{ $lbl_password }}</label>
                    <div class="form-floating-icon">
                        <i data-feather="lock"></i>
                    </div>
                </div>
                @error('password')
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4 form-group">
                <div class="form-floating form-floating-custom auth-pass-inputgroup @error('password_confirmation') has-danger @enderror">
                    @php($lbl_c_password = __('system.fields.password_confirmation'))

                    <input type="password" class="form-control pe-5 " id="pristine-password_confirmation-valid" data-pristine-password-metch="pristine-password-valid" name="password_confirmation" placeholder="{{ $lbl_c_password }}" required
                        data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_c_password)]) }}" data-pristine-password_confirmation-message="{{ __('validation.password.passwordconfirmation') }}">


                    <label for="pristine-password_confirmation-valid">{{ $lbl_c_password }}</label>
                    <div class="form-floating-icon">
                        <i data-feather="lock"></i>
                    </div>
                </div>
                @error('password_confirmation')
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">{{ __('auth.reset_password.reset_pwd_btn') }}</button>
            </div>
        </form>

        <div class="mt-5 text-center">
        </div>
    </div>
@endsection
