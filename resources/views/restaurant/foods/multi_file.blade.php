 {{ Form::open(['route' => ['restaurant.foods.update-image'], 'method' => 'post', 'files' => true, 'class' => 'dropzone mb-3 ']) }}
 <input type="hidden" name="unique" value="{{ $unique }}" />
 <div class="fallback">
     <input name="file" type="file" multiple="multiple">
 </div>
 <div class="dz-message needsclick">
     <div class="mb-3">
         <i class="display-4 text-muted bx bx-cloud-upload"></i>
     </div>

     <h5>{{ __('system.multi_file.message') }}</h5>
 </div>
 {{ Form::close() }}
 @push('page_css')
     <link href="{{ asset('assets/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
     <style>
         .dropzone .dz-preview {
             padding: 5px;
         }

         .dropzone .dz-preview.dz-image-preview {
             background: #0000002e;
             border-radius: 26px;
         }

         .dropzone .dz-preview .dz-image {
             display: flex;
             justify-content: center;
             align-items: center;
         }



         .dropzone .dz-preview .dz-remove {
             opacity: 0;
             pointer-events: none;
         }

         .dz-image-preview:hover .dz-remove {
             opacity: 1;
             pointer-events: unset;
         }
     </style>
 @endpush
 @push('page_scripts')
     <script src="{{ asset('assets/libs/dropzone/min/dropzone.min.js') }}"></script>
     <script>
         Dropzone.autoDiscover = false;
         var old = @json($food->gallery_images_with_details ?? []);
         var myDropzone = new Dropzone(".dropzone", {
             acceptedFiles: ".jpeg,.jpg,.png,.gif",
             addRemoveLinks: true,
             thumbnailMethod: "contain",

             dictDefaultMessage: "{{ __('system.multi_file.drop_msg') }}",
             dictFallbackMessage: "{{ __('system.multi_file.browser_not_supported_drop') }}",
             dictFallbackText: "{{ __('system.multi_file.olden_days') }}",
             dictInvalidFileType: "{{ __('system.multi_file.invalid_file_type') }}",
             dictCancelUpload: "{{ __('system.multi_file.cancel_upload') }}",
             dictUploadCanceled: "{{ __('system.multi_file.upload_canceled') }}",
             dictCancelUploadConfirmation: "{{ __('system.multi_file.are_you_sure_you_cancel') }}",
             dictRemoveFile: "{{ __('system.multi_file.remove_file') }}",
             dictMaxFilesExceeded: "{{ __('system.multi_file.more_file_not_allowed') }}",



             removedfile: function(file) {
                 var fileName = $(file.previewElement).find('.dz-filename span').attr('data-dz-name');

                 $(document).find("#img_" + fileName).remove();
                 var _ref;
                 return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
             },
             init: function() {
                 let myDropzone = this;
                 @foreach ($food->gallery_images_with_details ?? [] as $img)
                     myDropzone.displayExistingFile({
                         name: "{{ $img['name'] }}",
                     }, "{{ $img['url'] }}", null, null, true);
                 @endforeach


             },
             reset() {
                 if ($(this.element).find('.dz-preview').length == 0)
                     return this.element.classList.remove("dz-started");
             },
             success: function(file, response) {
                 var name = response.data.name;
                 var id = response.data.id;
                 var upload_name = response.data.upload_name;
                 $(document).find('.gallery_image_hiddens').append(`
                 <input type="hidden" name="{{ $field_name }}[]" value="${upload_name}" id="img_${id}" /> 
                 `);
                 $(file.previewElement).find('.dz-filename span').attr('data-dz-name', id)
             }
         });
         $(document).ready(function() {
             $(document).find('.dz-preview').each(function(index, obj) {
                 $(this).find('.dz-filename span').attr('data-dz-name', old[index].id);
                 $(this).find('.dz-size span').html("~");

             })
         })
     </script>
 @endpush
