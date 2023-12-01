   <img class="section-to-print" src='data:image/png;base64,{!! $image !!}'>
   <div class="row">
       <div class="col-md-6">
           <a href="data:image/png;base64,{!! $image !!}" class="btn btn-primary w-100 my-2" download target="_blank">{{ __('system.crud.download') }}</a>
       </div>
       <div class="col-md-6">
           <button class="btn btn-primary w-100 my-2" onclick=" window.print();" target="_blank">{{ __('system.crud.print') }}</button>
       </div>
   </div>
