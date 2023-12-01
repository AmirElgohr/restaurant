<div class="gallery_image_hiddens d-none">
    @foreach ($food->gallery_images_with_details ?? [] as $img)
        <input type="hidden" name="gallery_image[]" value="{{ $img['img'] }}" id="img_{{ $img['id'] }}">
    @endforeach
</div>

<div class="row">

    <div class="col-md-4  form-group">
        @php($lbl_food_image = __('system.fields.food_image'))
        <div class="d-flex align-items-center">
            <input type="file" name="food_image" id="food_image" class="d-none my-preview" accept="image/*" data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_food_image)]) }}" data-preview='.preview-image'>
            <label for="food_image" class="mb-0">
                <div for="profile-image" class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                    {{ $lbl_food_image }}
                </div>
            </label>
            <div class='mx-3 '>
                @if (isset($food) && $food->food_image_url != null)
                    <img data-src="{{ $food->food_image_url }}" alt="" class="avatar-xl rounded-circle img-thumbnail preview-image lazyload">
                @else
                    <div class="preview-image-default">
                        <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3 ">{{ $food->food_image_name ?? 'F' }}</h1>
                    </div>
                    <img class="avatar-xl rounded-circle img-thumbnail preview-image" style="display: none;" />
                @endif
            </div>
        </div>

        @error('food_image')
            <p>
            <div class="pristine-error text-help">{{ $message }}</div>
            </p>
        @enderror
        <input type="hidden" name="restaurant_id" value="{{ auth()->user()->restaurant_id }}">

    </div>

</div>

<div class="row mt-3">
    <div class="col-md-4">
        <div class="mb-3 form-group  @error('categories') has-danger @enderror   ">
            @php($lbl_food_category = __('system.fields.food_category'))
            <label class="form-label" for="input-language">{{ $lbl_food_category }} <span class="text-danger">*</span></label>
            @php($categories = App\Http\Controllers\Restaurant\FoodCategoryController::getCurrentRestaurantAllFoodCategories())
            <input type="text" name="categories_select" pristine-value-in="{{ implode(',', array_keys($categories)) }}" value="{{ old('categories_select', implode(',', $food->categories_ids ?? [])) }}"
                id="choices-multiple-remove-button-ref" required="true" class="pristine-in-validators d-none" data-pristine-required-message="{{ __('validation.custom.select_required', ['attribute' => strtolower($lbl_food_category)]) }}">
            {!! Form::select('categories[]', $categories, old('categories', $food->categories_ids ?? []), [
                'class' => 'form-control choice-picker-multiple w-100 ',
                'id' => 'choices-multiple-remove-button',
                'data-id' => 'choices-multiple-remove-button-ref',
                'multiple' => true,
                'placeholder' => $lbl_food_category,
                'data-remove' => 'false',
            ]) !!}
            @error('categories')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror

        </div>

    </div>

</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3 form-group @error('name') has-danger @enderror  @error('restaurant_id') has-danger @enderror">
            @php($lbl_food_name = __('system.fields.food_name'))

            <label class="form-label" for="name">{{ $lbl_food_name }} <span class="text-danger"></span></label>
            {!! Form::text('name', null, [
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => $lbl_food_name,
                'required' => false,
                'maxlength' => 150,
                'minlength' => 2,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_name)]),
            ]) !!}
            @error('name')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
            @error('restaurant_id')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        @php($lbl_food_description = __('system.fields.food_description'))

        <div class="mb-3 form-group @error('description') has-danger @enderror">
            <label class="form-label" for="input-address">{{ $lbl_food_description }} <span class="text-danger">*</span></label>
            {!! Form::textarea('description', null, [
                'class' => 'form-control',
                'id' => 'input-address',
                'placeholder' => $lbl_food_description,
                'minlength' => '5',
                'rows' => 2,
                'required' => true,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_description)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_description)]),
            ]) !!}
        </div>
        @error('description')
            <div class="pristine-error text-help">{{ $message }}</div>
        @enderror
    </div>
</div>
@foreach (getAllCurrentRestaruentLanguages() as $key => $lang)
    <div class="row">
        <input type="hidden" name="restaurant_ids[{{ $key }}]" value="{{ auth()->user()->restaurant_id }}">
        <div class="col-md-4">
            <div class="mb-3 form-group @error('name.' . $key) has-danger @enderror  @error('restaurant_ids.' . $key) has-danger @enderror">
                @php($lbl_food_name = __('system.fields.food_name') . ' ' . $lang)

                <label class="form-label" for="name.{{ $key }}">{{ $lbl_food_name }} <span class="text-danger">*</span></label>
                {!! Form::text("lang_name[$key]", null, [
                    'class' => 'form-control',
                    'id' => 'name.' . $key,
                    'placeholder' => $lbl_food_name,
                    'required' => 'true',
                    'maxlength' => 150,
                    'minlength' => 2,
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_name)]),
                    'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_name)]),
                ]) !!}
                @error('name.' . $key)
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
                @error('restaurant_ids.' . $key)
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            @php($lbl_food_description = __('system.fields.food_description') . ' ' . $lang)

            <div class="mb-3 form-group @error('description.' . $key) has-danger @enderror">
                <label class="form-label" for="input-address">{{ $lbl_food_description }} <span class="text-danger">*</span></label>
                {!! Form::textarea("lang_description[$key]", null, [
                    'class' => 'form-control',
                    'id' => 'input-address',
                    'placeholder' => $lbl_food_description,
                    'minlength' => '5',
                    'rows' => 2,
                    'required' => true,
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_description)]),
                    'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_description)]),
                ]) !!}
            </div>
            @error('description.' . $key)
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>

    </div>
@endforeach
<div class="row">
    <div class="col-md-4">
        <div class="mb-3 form-group @error('price') has-danger @enderror">
            @php($lbl_food_price = __('system.fields.food_price'))
            <label class="form-label" for="price-mask">{{ $lbl_food_price }} <span class="text-danger"></span></label>
            <div class="input-group">
                {!! Form::text('price', null, [
                    'class' => 'form-control price-mask',
                    'id' => 'price-mask',
                    'placeholder' => $lbl_food_price,
                    'max' => '10000',
                    'required' => false,
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_price)]),
                    'data-pristine-email-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_price)]),
                ]) !!}
                <div class="input-group-text">{{ config('app.currency_symbol') }}</div>
            </div>


            @error('price')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>

{{--    <div class="col-md-4">--}}
{{--        @php($lbl_preparation_time = __('system.fields.preparation_time'))--}}

{{--        <div class="mb-3 form-group @error('preparation_time') has-danger @enderror">--}}
{{--            <label class="form-label" for="preparation_time">{{ $lbl_preparation_time }} <span class="text-danger">*</span></label>--}}
{{--            {!! Form::text('preparation_time', isset($food) && $food->preparation_time ? str_replace([' hours ', 'minutes'], ['.', ''], $food->preparation_time) : null, [--}}
{{--                'class' => 'form-control',--}}
{{--                'id' => 'preparation_time',--}}
{{--                'placeholder' => $lbl_preparation_time,--}}
{{--                'required' => 'true',--}}
{{--                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_preparation_time)]),--}}
{{--            ]) !!}--}}
{{--            @error('preparation_time')--}}
{{--                <div class="pristine-error text-help">{{ $message }}</div>--}}
{{--            @enderror--}}
{{--        </div>--}}
{{--    </div>--}}


    <div class="col-md-4">
        <div class="row">

            <div class="col-mb-6">
                @php($lbl_is_available = __('system.fields.is_available'))
                <div class="mt-4 mt-md-0">
                    <label class="form-label" for="is_available">{{ $lbl_is_available }}</label>
                    <div class="form-check form-switch form-switch-md mb-3">
                        <input type="hidden" name="is_available" value="0">
                        {!! Form::checkbox('is_available', 1, null, [
                            'class' => 'form-check-input',
                            'id' => 'is_available',
                            'placeholder' => $lbl_is_available,
                        ]) !!}
                    </div>

                </div>
            </div>
        </div>

    </div>


</div>
