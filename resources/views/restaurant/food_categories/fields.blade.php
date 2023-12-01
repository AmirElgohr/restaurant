<div class="row">
    <div class="col-md-12  form-group">
        @php($lbl_category_image = __('system.fields.category_image'))
        <div class="d-flex align-items-center">
            <input type="file" name="category_image" id="category_image" class="d-none my-preview" accept="image/*" data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_category_image)]) }}"
                data-preview='.preview-image'>
            <label for="category_image" class="mb-0">
                <div for="profile-image" class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                    {{ $lbl_category_image }}
                </div>
            </label>
            <div class='mx-3 '>
                @if (isset($foodCategory) && $foodCategory->category_image_url != null)
                    <img data-src="{{ $foodCategory->category_image_url }}" alt="" class="avatar-xl rounded-circle img-thumbnail preview-image lazyload">
                @else
                    <div class="preview-image-default">
                        <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3 ">{{ $restaurant->category_image_name ?? 'C' }}</h1>
                    </div>
                    <img class="avatar-xl rounded-circle img-thumbnail preview-image" style="display: none;" />
                @endif
            </div>
        </div>
        @error('category_image')
            <div class="pristine-error text-help">{{ $message }}</div>
        @enderror
    </div>
    <div class=" col-md-6">
        @php($lbl_category_name = __('system.fields.category_name'))

        <div class="mb-3 form-group @error('category_name') has-danger @enderror  @error('restaurant_id') has-danger @enderror">
            <label class="form-label" for="name">{{ $lbl_category_name }} <span class="text-danger">*</span></label>
            {!! Form::text('category_name', null, [
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => $lbl_category_name,
                'required' => 'true',
                'maxlength' => 150,
                'minlength' => 2,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_category_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_category_name)]),
            ]) !!}


            @error('category_name')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
            @error('restaurant_id')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror

        </div>
    </div>

    @foreach (getAllCurrentRestaruentLanguages() as $key => $lang)
        <input type="hidden" name="restaurant_ids[{{ $key }}]" value="{{ auth()->user()->restaurant_id }}">
        <div class=" col-md-6">
            @php($lbl_category_name = __('system.fields.category_name') . ' ' . $lang)

            <div class="mb-3 form-group @error('lang_category_name.' . $key) has-danger @enderror @error('restaurant_ids.' . $key) has-danger @enderror">
                <label class="form-label" for="name">{{ $lbl_category_name }} <span class="text-danger">*</span></label>
                {!! Form::text("lang_category_name[$key]", null, [
                    'class' => 'form-control',
                    'id' => 'name',
                    'placeholder' => $lbl_category_name,
                    'required' => 'true',
                    'maxlength' => 150,
                    'minlength' => 2,
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_category_name)]),
                    'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_category_name)]),
                ]) !!}


                @error('lang_category_name.' . $key)
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
                @error('restaurant_ids.' . $key)
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror

            </div>
        </div>
    @endforeach



    <input type="hidden" name="restaurant_id" value="{{ auth()->user()->restaurant_id }}">
    @if (isset($edit))
        <input type="hidden" name="action" value="edit">
    @endif
</div>
