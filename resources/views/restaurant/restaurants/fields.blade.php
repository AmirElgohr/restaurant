<div class="row align-items-stretch">
    <div class="col-md-4 form-group align-self-center">
        @php($lbl_logo = __('system.fields.logo'))
        <div class="d-flex  align-items-center ">
            <input type="file" name="logo" id="logo" class="d-none my-preview" accept="image/*"
                   data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_logo)]) }}"
                   data-preview='.preview-image'>
            <label for="logo" class="mb-0">
                <div for="profile-image" class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                    {{ $lbl_logo }}
                </div>
            </label>
            <div class='mx-3 '>
                @if (isset($restaurant) && $restaurant->logo_url != null)
                    <img data-src="{{ $restaurant->logo_url }}" alt=""
                         class="avatar-xl rounded-circle img-thumbnail preview-image lazyload">
                @else
                    <div class="preview-image-default">
                        <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3 ">{{ $restaurant->logo_name ?? 'R' }}</h1>
                    </div>
                    <img class="avatar-xl rounded-circle img-thumbnail preview-image" style="display: none;"/>
                @endif
            </div>
        </div>
        @error('logo')
        <div class="pristine-error text-help">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row mt-3">

    <div class="col-md-4">
        @php($lbl_first_name = __('system.fields.first_name'))

        <div class="mb-3 form-group @error('first_name') has-danger @enderror">
            <label class="form-label" for="first_name">{{ $lbl_first_name }} <span class="text-danger">*</span></label>
            {!! Form::text('first_name', isset($user) ? $user->first_name : '', [
                'class' => 'form-control',
                'id' => 'first_name',
                'placeholder' => $lbl_first_name,
                'required' => 'true',
                'maxlength' => 255,
                'minlength' => 2,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_first_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_first_name)]),
            ]) !!}
            @error('first_name')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>


    <div class="col-md-4">
        @php($lbl_last_name = __('system.fields.last_name'))

        <div class="mb-3 form-group @error('last_name') has-danger @enderror">
            <label class="form-label" for="last_name">{{ $lbl_last_name }} <span class="text-danger">*</span></label>
            {!! Form::text('last_name', isset($user) ? $user->last_name : '', [
                'class' => 'form-control',
                'id' => 'last_name',
                'placeholder' => $lbl_last_name,
                'required' => 'true',
                'maxlength' => 255,
                'minlength' => 2,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_last_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_last_name)]),
            ]) !!}
            @error('last_name')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>


    <div class="col-md-4">
        @php($lbl_email = __('system.fields.email'))


        <div class="mb-3 form-group @error('email') has-danger @enderror">
            <label class="form-label" for="email">{{ $lbl_email }} <span class="text-danger">*</span></label>

            {!! Form::email('email', isset($user) ? $user->email : '', [
                'class' => 'form-control',
                'id' => 'email',
                'placeholder' => $lbl_email,
                'required' => 'true',
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_email)]),
                'data-pristine-email-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_email)]),
            ]) !!}

            @error('email')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        @php($lbl_phone_number = __('system.fields.phone_number'))

        <div class="mb-3 form-group @error('phone_number') has-danger @enderror">
            <label class="form-label" for="pristine-phone-valid">{{ $lbl_phone_number }} <span
                        class="text-danger">*</span></label>

            {!! Form::text('phone_number', isset($user) ? $user->phone_number : '', [
                'class' => 'form-control',
                'id' => 'pristine-phone-valid',
                'placeholder' => $lbl_phone_number,
                'maxlength' => 15,
                'required' => true,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_phone_number)]),
            ]) !!}

            @error('phone_number')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        @php($lbl_restaurant_name = __('system.fields.restaurant_name'))

        <div class="mb-3 form-group @error('name') has-danger @enderror">
            <label class="form-label" for="name">{{ $lbl_restaurant_name }} <span class="text-danger">*</span></label>
            {!! Form::text('name', null, [
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => $lbl_restaurant_name,
                'required' => 'true',
                'maxlength' => 255,
                'minlength' => 2,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_restaurant_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_restaurant_name)]),
            ]) !!}
            @error('name')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        @php($lbl_restaurant_type = __('system.fields.restaurant_type'))
        <div class="mb-3 form-group @error('type') has-danger @enderror">
            <label class="form-label" for="restaurant">{{ $lbl_restaurant_type }} <span
                        class="text-danger">*</span></label>
            {{ Form::select('type', App\Models\Restaurant::restaurant_type_dropdown(), null, [
                'class' => 'form-control form-select',
                'id' => 'restaurant_type',
                'required' => true,
                'data-pristine-required-message' => __('validation.custom.select_required', ['attribute' => strtolower($lbl_restaurant_type)]),
            ]) }}
            @error('type')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @if(empty($user))
        <div class="col-md-4">
            <div class="mb-3 form-group">
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
        </div>

        <div class="col-md-4">
            <div class="mb-3 form-group">
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
        </div>
    @else
        <div class="col-md-4">
            <div class="mb-3 form-group">
                <div
                        class="form-floating form-floating-custom auth-pass-inputgroup @error('password') has-danger @enderror pristine-password-valid">

                    @php($lbl_password = __('system.fields.password'))
                    <input type="password" class="form-control pe-5 " name="password"
                           placeholder="{{ $lbl_password }}"  maxlength="16"
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
        </div>

        <div class="col-md-4">
            <div class="mb-3 form-group">
                <div
                        class="form-floating form-floating-custom auth-pass-inputgroup @error('password') has-danger @enderror pristine-password-valid">

                    @php($lbl_password_confirmation = __('system.fields.password_confirmation'))
                    <input type="password" class="form-control pe-5 "
                           name="password_confirmation"
                           placeholder="{{ $lbl_password_confirmation }}" maxlength="16"
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
        </div>
    @endif

</div>
