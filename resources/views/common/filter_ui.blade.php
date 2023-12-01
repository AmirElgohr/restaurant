 {{-- @push('page_css')
     <style>
         .choices__list--dropdown.is-active {
             visibility: visible !important;
         }
     </style>
 @endpush --}}
 <input type="hidden" name="current_url" value="{{ request()->fullurl() }}" id="current_page_url">

 <div class="row">
     <div class="col-sm-12 col-md-6">
         @if (!(isset($par_page) && $par_page == 'hide'))
             <div class="dataTables_length"><label>{{ __('system.crud.show') }}

                     {!! Form::select('par_page', [10 => 10, 25 => 25, 50 => 50, 100 => 100], request()->query('par_page', 10), ['class' => 'custom-select custom-select-sm form-control form-control-sm form-select form-select-sm filter-on-change']) !!}
                     {{ strtolower(__('system.crud.entries')) }}</label>
             </div>
         @else
             @isset($food_categories)
                 <div class=" w-50 category-select-drop-container">
                     <div class="">

                         {{ Form::select('food_category_id', $food_categories, request()->query('food_category_id'), [
                             'class' => 'form-select filter-on-change choice-picker',
                             'id' => 'restaurant_type',
                             'data-remove_attr' => 'data-type',
                             'required' => true,
                             'data-pristine-required-message' => __('validation.custom.select_required', ['attribute' => 'food category']),
                         ]) }}

                     </div>
                 </div>
             @endisset
         @endif

     </div>
     <div class="col-sm-12 col-md-6">
         <div class="dataTables_filter">
             <label>{{ __('system.crud.search') }}:
                 <input type="search" id="search" class="form-control filter-on-enter" placeholder="{{ __('system.crud.search') }}" value="{{ request()->query('filter') }}" />

             </label>
         </div>
     </div>
 </div>
