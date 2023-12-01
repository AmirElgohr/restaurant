     @php($languages_array = getAllLanguages(true))
     @extends('layouts.app', ['languages_array' => $languages_array])
     @section('title', __('system.environment.menu'))
     @section('content')
         <div class="row">

             <div class="col-xl-12 col-sm-12">
                 <div class="card">
                     <div class="card-header">

                         <div class="row">
                             <div class="col-md-6 col-xl-6">
                                 <h4 class="card-title">{{ __('system.environment.menu') }}</h4>
                                 <div class="page-title-box pb-0 d-sm-flex">
                                     <div class="page-title-right">
                                         <ol class="breadcrumb m-0">
                                             <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                             <li class="breadcrumb-item active">{{ __('system.environment.menu') }}</li>
                                         </ol>
                                     </div>
                                 </div>
                             </div>

                             <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">

                             </div>
                         </div>
                     </div>
                     <div class="card-body">
                         <form autocomplete="off" novalidate="" action="{{ route('restaurant.environment.setting.updateAdmin') }}" id="pristine-valid" method="post" enctype="multipart/form-data">
                             @method('put')
                             @csrf

                             @include('admin.settings.fields')
                             <div class="row">
                                 <div class="col-12 mt-3">

                                     <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                                 </div>
                             </div>


                         </form>
                     </div>
                     <!-- end card -->
                 </div>
             </div>
         </div>
     @endsection
