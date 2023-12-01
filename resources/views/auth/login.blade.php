@extends('auth.layouts.app')
@section('title', __('auth.login.main_title'))
@section('content')
    <div class="auth-content my-auto">
        <div class="text-center">
            <h5 class="mb-0">{{ __('auth.login.main_title') }}</h5>
            <p class="text-muted mt-2">{{ __('auth.login.title') }}</p>
        </div>
        <form autocomplete="off" class="mt-4 pt-2 pristine-valid" action="{{ url('/login') }}" method="post" id="pristine-valid">
            @csrf
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
                    <input type="password" class="form-control pe-5 " name="password" placeholder="{{ $lbl_password }}" required maxlength="16"
                        data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_password)]) }}" data-pristine-password-message="{{ __('validation.password.invalid') }}">

                    <button type="button" class="btn btn-link  position-absolute h-100 end-0 top-0" id="password-addon">
                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                    </button>
                    <label for="input-password">{{ $lbl_password }}</label>
                    <div class="form-floating-icon">
                        <i data-feather="lock"></i>
                    </div>
                </div>
                @error('password')
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="form-check font-size-15">
                        <input class="form-check-input" type="checkbox" id="remember-check" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label font-size-13" for="remember-check">
                            {{ __('auth.remember_me') }}
                        </label>
                    </div>
                </div>

            </div>
            <div class="mb-3">
                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">{{ __('auth.sign_in') }}</button>
            </div>
        </form>


        <div class="mt-5 text-center">
            {{-- <p class="text-muted mb-0">{{ __('auth.login.no_account') }}<a href="{{ route('register') }}" class="text-primary fw-semibold"> {{ __('auth.login.register_membership') }} </a> </p> --}}
            <p class="text-muted mb-0"><a href="{{ route('password.request') }}" class="text-primary fw-semibold">{{ __('auth.login.forgot_password') }}</a> </p>
        </div>
        <div class="mt-5 text-center">

        </div>
    </div>
@endsection
