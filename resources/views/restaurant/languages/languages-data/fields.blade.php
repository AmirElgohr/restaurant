<div class="row">
    <input type="hidden" name="file" value="{{ $file }}">
    @php($last = '')
    @foreach ($english as $key => $one)
        @if (strpos($key, '.') !== false)
            @php($c = substr($key, 0, strpos($key, '.')))
            @if ($last != $c)
                @if (is_numeric($last))
                    <hr class="mt-4">
                @endif
</div>
<div class="row ">
    @if (!is_numeric($c))
        <h5 class="font-size-14 my-4">{{ readableString($c) }}</h5>
    @endif
    @php($last = $c)
    @endif
    @endif
    <div class=" row mt-3 ">
        @php($lbl_category_name = $one)
        @if (is_array($one))
    </div>
    @continue
    @endif
    <div class="col-md-3">
        @php(preg_match_all('/:[a-zA-Z_]+[. ]{1}/i', $one, $m))
        @php($validation = [])
        @if (count($m[0]) > 0)
            @php($validation = ['data-in-metch' => implode(',', array_map('trimDotAndSpaces', $m[0]))])
            @php($validation['data-pristine-metch-message'] = __('validation.custom.required_array_keys', ['values' => implode(',', array_map('trimDotAndSpaces', $m[0]))]))
        @endif
        <label class="form-label" for="name" style="word-break: break-all;">{!! $lbl_category_name !!} <span class="text-danger">*</span></label>
    </div>
    <div class="col-md-9  form-group  ">
        @if (request()->query->get('file') == 'foods' && Str::contains($key, '.description'))
            {!! Form::textarea(
                getDotStringToInputString($key, 'other'),
                $other[$key] ?? $one,
                [
                    'class' => 'form-control',
                    'id' => 'name',
                    'placeholder' => $lbl_category_name,
                    'required' => 'true',
                    'rows' => '2',
                    'data-pristine-required-message' => __('validation.custom.required'),
                ] + $validation,
            ) !!}
        @else
            {!! Form::text(
                getDotStringToInputString($key, 'other'),
                $other[$key] ?? $one,
                [
                    'class' => 'form-control',
                    'id' => 'name',
                    'placeholder' => $lbl_category_name,
                    'required' => 'true',
            
                    'data-pristine-required-message' => __('validation.custom.required'),
                ] + $validation,
            ) !!}
        @endif


    </div>
</div>
@endforeach

</div>
