@extends('auth.layouts.app')
@section('title', __('auth.forgot_password.main_title'))
@section('content')
    <div class="auth-content my-auto">
        <div class="text-center">
            <h5 class="mb-0">{{ __('auth.forgot_password.main_title') }}</h5>
            <p class="text-muted mt-2">{{ __('auth.forgot_password.title') }}</p>
        </div>
        @if (session('status'))
            <div class="alert alert-success text-center my-4" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form autocomplete="off" class="mt-4 pt-2 pristine-valid" action="{{ route('password.email') }}" method="post" id="pristine-valid">
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
            <div class="mb-3">
                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">{{ __('auth.forgot_password.send_pwd_reset') }}</button>
            </div>
        </form>

        <div class="mt-5 text-center">
            {{-- <p class="text-muted mb-0">{{ __('auth.forgot_password.no_account') }}<a href="{{ route('register') }}" class="text-primary fw-semibold"> {{ __('auth.login.register_membership') }} </a> </p> --}}
            <p class="text-muted mb-0"><a href="{{ route('login') }}" class="text-primary fw-semibold">{{ __('auth.sign_in') }}</a> </p>
        </div>
        <div class="mt-5 text-center">

        </div>
    </div>
@endsection
