<div class="row">


    <div class=" col-md-6">
        @php($lbl_category_name = __('system.fields.language_name'))

        <div class="mb-3 form-group @error('name') has-danger @enderror ">
            <label class="form-label" for="name">{{ $lbl_category_name }} <span class="text-danger">*</span></label>
            {!! Form::text('name', null, [
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => $lbl_category_name,
                'required' => 'true',
                'maxlength' => 255,
                'minlength' => 2,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_category_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_category_name)]),
            ]) !!}
            @error('name')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
