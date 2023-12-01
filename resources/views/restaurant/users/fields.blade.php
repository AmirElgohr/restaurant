@push('page_css')
    <style>
        .form-floating-custom>.form-control,
        .form-floating-custom>.form-select {
            padding: .47rem .75rem !important;
        }
    </style>
@endpush
<div class="row">

    <div class="col-md-12  form-group">
        <div class="d-flex align-items-center">
            <div class='mx-3 '>
                @if (isset($user) && $user->profile_url != null)
                    <img data-src="{{ $user->profile_url }}" al" class="avatar-xl rounded-circle img-thumbnail preview-image lazyload">
                @else
                    <div class="preview-image-default">
                        <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3 ">{{ $user->logo_name ?? 'U' }}</h1>
                    </div>
                    <img class="avatar-xl rounded-circle img-thumbnail preview-image" style="display: none;" />
                @endif

            </div>
            @php($lbl_profile_image = __('system.fields.profile_image'))
            <input type="file" name="profile_image" id="profile_image" class="d-none my-preview" accept="image/*" data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_profile_image)]) }}"
                data-preview='.preview-image'>
            <label for="profile_image" class="mb-0">
                <div for="profile-image" class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                    {{ $lbl_profile_image }}
                </div>
            </label>
        </div>
        @error('profile_image')
            <div class="pristine-error text-help">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4 ">
        @php($lbl_first_name = __('system.fields.first_name'))
        <div class="mb-3 form-group @error('first_name') has-danger @enderror">
            <label class="form-label" for="first_name">{{ $lbl_first_name }} <span class="text-danger">*</span></label>
            {!! Form::text('first_name', null, [
                'class' => 'form-control start_no_space',
                'id' => 'first_name',
                'placeholder' => $lbl_first_name,
                'required' => 'true',
                'maxlength' => 50,
                'pattern' => "/^[a-zA-Z]+[a-zA-Z]+$/",
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_first_name)]),
                'data-pristine-pattern-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_first_name)]),
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

            {!! Form::text('last_name', null, [
                'class' => 'form-control start_no_space',
                'id' => 'last_name',
                'placeholder' => $lbl_last_name,
                'required' => 'true',
                'maxlength' => 50,
                'pattern' => "/^[a-zA-Z]+[a-zA-Z]+$/",
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_last_name)]),
                'data-pristine-pattern-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_last_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_last_name)]),
            ]) !!}
            @error('last_name')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
    @if (!isset($user))
        <div class="col-md-4">
            @php($lbl_email = __('system.fields.email'))
            <div class="mb-3 form-group @error('email') has-danger @enderror">
                <label class="form-label" for="email">{{ $lbl_email }} <span class="text-danger">*</span></label>


                {!! Form::text('email', null, [
                    'class' => 'form-control',
                    'id' => 'email',
                    'placeholder' => $lbl_email,
                    'required' => 'true',
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower(__('system.fields.email'))]),
                    'data-pristine-email-message' => __('validation.custom.invalid', ['attribute' => strtolower(__('system.fields.password'))]),
                ]) !!}
                @error('email')
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            @php($lbl_password = __('system.fields.password'))

            <div class="mb-3 form-group @error('password') has-danger @enderror">

                <label class="form-label" for="pristine-password-valid">{{ $lbl_password }} <span class="text-danger">*</span></label>
                <div class="form-floating-custom auth-pass-inputgroup ">
                    <input type="password" name="password" id="pristine-password-valid" class="form-control"placeholder="{{ $lbl_password }}" required maxlength="16"
                        data-pristine-required-message="{{ __('validation.required', ['attribute' => strtolower($lbl_password)]) }}" data-pristine-password-message="{{ __('validation.password.invalid') }}">
                    <button type="button" class="btn btn-link  position-absolute h-100 end-0 top-0" id="password-addon" style="padding: 4px 12px;">
                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                    </button>
                </div>
                @error('password')
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>

        </div>
    @endif
    <div class="col-md-4">
        @php($lbl_phone_number = __('system.fields.phone_number'))
        <div class="mb-3 form-group @error('phone_number') has-danger @enderror">
            <label class="form-label" for="pristine-phone-valid">{{ $lbl_phone_number }} <span class="text-danger">*</span></label>
            {!! Form::tel('phone_number', null, [
                'class' => 'form-control',
                'id' => 'pristine-phone-valid',
                'placeholder' => $lbl_phone_number,
                'required' => 'true',
                'maxlength' => 15,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_phone_number)]),
            ]) !!}
            @error('phone_number')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>

</div>
<div class="row d-none">
    <h5 class="font-size-14 my-3">{{ __('system.fields.address_details') }}</h5>
    <div class="col-md-4">
        @php($lbl_city = __('system.fields.city'))

        <div class="mb-3 form-group @error('city') has-danger @enderror">
            <label class="form-label" for="input-city">{{ $lbl_city }}</label>


            {!! Form::text('city', null, [
                'class' => 'form-control',
                'id' => 'input-city',
                'placeholder' => $lbl_city,
            ]) !!}


            @error('city')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>

    </div>
    <div class="col-md-4">
        @php($lbl_state = __('system.fields.state'))

        <div class="mb-3 form-group @error('state') has-danger @enderror">
            <label class="form-label" for="input-state">{{ $lbl_state }}</label>

            {!! Form::text('state', null, [
                'class' => 'form-control',
                'id' => 'input-state',
                'placeholder' => $lbl_state,
            ]) !!}
            @error('state')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>

    </div>
    <div class="col-md-4">
        @php($lbl_country = __('system.fields.country'))

        <div class="mb-3 form-group @error('country') has-danger @enderror">
            <label class="form-label" for="input-country">{{ $lbl_country }}</label>

            {!! Form::text('country', null, [
                'class' => 'form-control',
                'id' => 'input-country',
                'placeholder' => $lbl_country,
            ]) !!}
            @error('country')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>

    </div>
    <div class="col-md-4">
        @php($lbl_zip = __('system.fields.zip'))

        <div class="mb-3 form-group @error('zip') has-danger @enderror">
            <label class="form-label" for="input-zip">{{ $lbl_zip }}</label>

            {!! Form::text('zip', null, [
                'class' => 'form-control pristine-custom-pattern',
                'id' => 'input-zip',
                'placeholder' => $lbl_zip,
                'custom-pattern' => "^([0-9a-zA-z]{4,8}|.{0})$",
                'maxlength' => 8,
                'data-pristine-pattern-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_zip)]),
                'data-pristine-maxlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_zip)]),
            ]) !!}
            @error('zip')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>

    </div>
    <div class="col-md-8">
        @php($lbl_address = __('system.fields.address'))
        <div class="mb-3 form-group @error('address') has-danger @enderror">
            <label class="form-label" for="input-address">{{ $lbl_address }}</label>

            {!! Form::textarea('address', null, [
                'class' => 'form-control',
                'id' => 'input-address',
                'placeholder' => $lbl_address,
                'rows' => 2,
                'minlength' => 5,

                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_address)]),
            ]) !!}

        </div>
        @error('address')
            <div class="pristine-error text-help">{{ $message }}</div>
        @enderror
    </div>
</div>
