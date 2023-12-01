<div class="row">
    <div class="col-md-4">
        @php($lbl_restaurant_name = __('system.fields.restaurant_name'))

        <div class="mb-3 form-group @error('restaurant_name') has-danger @enderror">
            <label class="form-label" for="input-restaurant_name">{{ $lbl_restaurant_name }} <span
                    class="text-danger">*</span></label>
            {!! Form::text('restaurant_name', $row->name, [
                'class' => 'form-control',
                'id' => 'input-restaurant_name',
                'required' => 'true',
            ]) !!}

            @error('restaurant_name')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3 form-group @error('menu_title_en') has-danger @enderror">
            <label class="form-label" for="input-menu_title_en">Menu Title (EN)<span
                        class="text-danger"></span></label>
            {!! Form::text('menu_title_en', $row->menu_title_en, [
                'class' => 'form-control',
                'id' => 'input-menu_title_en',
            ]) !!}

            @error('menu_title_en')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3 form-group @error('menu_title_ar') has-danger @enderror">
            <label class="form-label" for="input-menu_title_ar">Menu Title (AR) <span
                        class="text-danger"></span></label>
            {!! Form::text('menu_title_ar', $row->menu_title_ar, [
                'class' => 'form-control',
                'id' => 'input-menu_title_ar',
            ]) !!}

            @error('menu_title_ar')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-4">
        @php($lbl_theme = __('system.fields.theme'))

        <div class="mb-3 form-group @error('theme') has-danger @enderror">
            <label class="form-label" for="input-theme">{{ $lbl_theme }} <span
                    class="text-danger">*</span></label>
            {!! Form::color('theme', $row->theme, [
                'class' => 'form-control',
                'id' => 'input-theme',
                'required' => 'true',
            ]) !!}

            @error('theme')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 form-group">
        @php($lbl_restaurant_logo = __('system.fields.restaurant_logo'))
        <label class="form-label d-block" for="app_name">{{ $lbl_restaurant_logo }} <span
                class="text-danger">*</span></label>
        <div class="d-flex align-items-center ">
            <div class='mx-3 ' style="width: 150px">
                <img data-src="{{ asset($row->logo) }}" alt="" class=" preview-image lazyload"
                     style="max-width:100%;">

            </div>

            <input type="file" name="restaurant_logo" id="restaurant_logo" class="d-none my-preview" accept="image/*"
                   data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_restaurant_logo)]) }}"
                   data-preview='.preview-image'>
            <label for="restaurant_logo" class="mb-0">
                <div for="profile-image" class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                    {{ $lbl_restaurant_logo }}
                </div>
            </label>

        </div>
        @error('restaurant_logo')
        <div class="pristine-error text-help px-3">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        @php($lbl_instagram_url = __('system.fields.instagram_url'))

        <div class="mb-3 form-group @error('instagram_url') has-danger @enderror">
            <label class="form-label" for="input-instagram_url">{{ $lbl_instagram_url }} <span
                        class="text-danger"></span></label>
            {!! Form::text('instagram_url', $row->instagram_url, [
                'class' => 'form-control',
                'id' => 'input-instagram_url',
                'required' => false,
            ]) !!}

            @error('instagram_url')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        @php($intro_video_url = __('system.fields.intro_video_url'))

        <div class="mb-3 form-group @error('intro_video_url') has-danger @enderror">
            <label class="form-label" for="input-intro_video_url">{{ $intro_video_url }} <span
                        class="text-danger"></span></label>
            {!! Form::text('intro_video_url', $row->intro_video_url, [
                'class' => 'form-control',
                'id' => 'input-intro_video_url',
                'required' => 'true',
            ]) !!}

            @error('intro_video_url')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        @php($lbl_script_code = __('system.fields.script_code'))

        <div class="mb-3 form-group @error('script_code') has-danger @enderror">
            <label class="form-label" for="input-script_code">{{ $lbl_script_code }} <span
                        class="text-danger"></span></label>
            {!! Form::textArea('script_code', $row->script_code, [
                'class' => 'form-control',
                'id' => 'input-code',
                'required' => false,
            ]) !!}

            @error('script_code')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
