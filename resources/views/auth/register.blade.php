\@extends('auth.layouts.app')
@section('title', __('auth.registration.main_title'))

@section('content')
    <div class="auth-content my-auto">
        <div class="text-center">
            <h5 class="mb-0">{{ __('auth.registration.main_title') }}</h5>
            <p class="text-muted mt-2">{{ __('auth.registration.title') }}</p>
        </div>
        <form autocomplete="off" class="mt-4 pt-2 pristine-valid" action="{{ route('register') }}" method="post"
              id="pristine-valid" novalidate>
            @csrf
            <div class="mb-4 form-group">
                <div class="form-floating form-floating-custom @error('first_name') has-danger @enderror">
                    @php($lbl_first_name = __('system.fields.first_name'))

                    <input type="text" class="form-control" id="input-first_name" placeholder="{{ $lbl_first_name }}"
                           name="first_name" value="{{ old('first_name') }}" required maxlength="50"
                           pattern="/^[a-zA-Z]+$/i"
                           data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_first_name)]) }}"
                           data-pristine-pattern-message="{{ __('validation.custom.invalid', ['attribute' => strtolower($lbl_first_name)]) }}">
                    <label for="input-first_name">{{ $lbl_first_name }}</label>
                    <div class="form-floating-icon">
                        <i data-feather="users"></i>
                    </div>
                </div>
                @error('first_name')
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-group">
                <div class="form-floating form-floating-custom @error('first_name') has-danger @enderror">
                    @php($lbl_last_name = __('system.fields.last_name'))

                    <input type="text" class="form-control" id="input-last_name" placeholder="{{ $lbl_last_name }}"
                           name="last_name" value="{{ old('last_name') }}" required maxlength="50"
                           pattern="/^[a-zA-Z]+$/i"
                           data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_last_name)]) }}"
                           data-pristine-pattern-message="{{ __('validation.custom.invalid', ['attribute' => strtolower($lbl_last_name)]) }}">
                    <label for="input-last_name">{{ $lbl_last_name }}</label>
                    <div class="form-floating-icon">
                        <i data-feather="users"></i>
                    </div>
                </div>
                @error('last_name')
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-group">
                <div class="form-floating form-floating-custom @error('email') has-danger @enderror">
                    @php($lbl_email = __('system.fields.email'))

                    <input type="email" class="form-control" id="input-username" placeholder="{{ $lbl_email }}"
                           name="email" value="{{ old('email') }}" required
                           data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_email)]) }}"
                           data-pristine-email-message="{{ __('validation.custom.invalid', ['attribute' => strtolower($lbl_email)]) }}">
                    <label for="input-username">{{ $lbl_email }}</label>
                    <div class="form-floating-icon">
                        <i data-feather="mail"></i>
                    </div>
                </div>
                @error('email')
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-group">
                <div class="form-floating form-floating-custom @error('phone_number') has-danger @enderror">
                    @php($lbl_phone_number = __('system.fields.phone_number'))
                    <input type="tel" class="form-control" id="pristine-phone-valid"
                           placeholder="{{ $lbl_phone_number }}" name="phone_number" value="{{ old('phone_number') }}"
                           required maxlength="15"
                           data-pristine-phone-message="{{ __('validation.custom.invalid', ['attribute' => strtolower($lbl_phone_number)]) }}"
                           data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_phone_number)]) }}">
                    <label for="pristine-phone-valid">{{ $lbl_phone_number }}</label>
                    <div class="form-floating-icon">
                        <i data-feather="phone"></i>
                    </div>
                </div>
                @error('phone_number')
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-group">
                <div class="form-floating form-floating-custom @error('restaurant_name') has-danger @enderror">
                    @php($lbl_restaurant_name = __('system.fields.restaurant_name'))
                    <input type="text" class="form-control" id="input-restaurant_name"
                           placeholder="{{ $lbl_restaurant_name }}" name="restaurant_name"
                           value="{{ old('restaurant_name') }}"
                           data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_restaurant_name)]) }}"
                           data-pristine-pattern-message="{{ __('validation.custom.invalid', ['attribute' => strtolower($lbl_restaurant_name)]) }}"
                           required maxlength="255" pattern="/^[a-z0-9 ]+$/i">
                    <label for="input-restaurant_name">{{ $lbl_restaurant_name }}</label>
                    <div class="form-floating-icon">
                        <i class="bx bx-heading font-size-22"></i>
                    </div>
                </div>
                @error('restaurant_name')
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4 form-group">
                <div class="form-floating form-floating-custom @error('restaurant_type') has-danger @enderror">
                    @php($lbl_restaurant_type = __('system.fields.restaurant_type'))

                    {{ Form::select('restaurant_type', App\Models\Restaurant::restaurant_type_dropdown(), null, ['class' => 'form-control form-select', 'id' => 'input-restaurant_type', 'required' => true, 'data-pristine-required-message' => __('validation.custom.select_required', ['attribute' => strtolower($lbl_restaurant_type)])]) }}
                    <label for="input-restaurant_type">{{ $lbl_restaurant_type }}</label>
                    <div class="form-floating-icon">
                        <i class="dripicons-cutlery font-size-22"></i>
                    </div>
                </div>
                @error('restaurant_type')
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-group">
                <div
                    class="form-floating form-floating-custom auth-pass-inputgroup @error('password') has-danger @enderror pristine-password-valid">

                    @php($lbl_password = __('system.fields.password'))
                    <input type="password" class="form-control pe-5 " id="pristine-password-valid" name="password"
                           placeholder="{{ $lbl_password }}" required maxlength="16"
                           data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_password)]) }}"
                           data-pristine-password-message="{{ __('validation.password.invalid') }}">

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
                <div
                    class="form-floating form-floating-custom auth-pass-inputgroup @error('password') has-danger @enderror pristine-password-valid">

                    @php($lbl_password_confirmation = __('system.fields.password_confirmation'))
                    <input type="password" class="form-control pe-5 " id="pristine-password-confirmation-valid"
                           name="password_confirmation"
                           placeholder="{{ $lbl_password_confirmation }}" required maxlength="16"
                           data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_password_confirmation)]) }}"
                           data-pristine-password-message="{{ __('validation.password.invalid') }}">

                    <button type="button" class="btn btn-link  position-absolute h-100 end-0 top-0"
                            id="password-confirmation-addon">
                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                    </button>
                    <label for="pristine-password-valid">{{ $lbl_password_confirmation }}</label>
                    <div class="form-floating-icon">
                        <i data-feather="lock"></i>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="form-check font-size-15  form-group">
                        <input class="form-control form-check-input p-2" type="checkbox" id="remember-check"
                               name="terms" value="1" required
                               data-pristine-required-message="{{ __('validation.accepted', ['attribute' => 'terms']) }}">
                        <label class="form-check-label font-size-13" for="remember-check">
                            &nbsp;&nbsp; {{ __('auth.registration.i_agree') }} <a
                                href="#">{{ __('auth.registration.terms') }}</a>
                        </label>
                    </div>
                </div>

            </div>
            <div class="mb-3">
                <button class="btn btn-primary w-100 waves-effect waves-light"
                        type="submit">{{ __('auth.sign_up') }}</button>
            </div>
        </form>

        <div class="mt-5 text-center">
            <p class="text-muted mb-0"><a href="{{ route('login') }}"
                                          class="text-primary fw-semibold">{{ __('auth.registration.have_membership') }}</a>
            </p>
        </div>
        <div class="mt-5 text-center">

        </div>
    </div>
@endsection

@push('third_party_scripts')
    <script src="{{ asset('assets/libs/imask/imask.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-mask.init.js') }}"></script>
@endpush
