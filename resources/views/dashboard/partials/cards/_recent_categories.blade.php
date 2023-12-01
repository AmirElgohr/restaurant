<div class="card">

    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">{{ __('system.dashboard.recent_categories') }}</h4>

    </div><!-- end card header -->

    <div class="card-body px-0 pb-0 pt-2">
        <div class="table-responsive px-3" data-simplebar="init" style="height: 455px;">

            <table class="table align-middle table-nowrap">
                <tbody>
                @foreach ($categories as $foodCategory)
                    <tr>
                        <td style="width: 50px;">
                            <div class="avatar-md me-4">

                                @if ($foodCategory->category_image_url != null)
                                    <img data-src="{{ $foodCategory->category_image_url }}" alt=""
                                         class="avatar-md rounded-circle me-2 image-object-cover lazyload">
                                @else
                                    <div class="avatar-md d-inline-block align-middle me-2">
                                        <div
                                            class="avatar-title bg-soft-primary text-primary font-size-24 m-0 rounded-circle font-weight-bold">
                                            {{ $foodCategory->category_image_name }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td style="max-width:{{ $width }}" class="">
                            <div class="text-dark">
                                <h5 class="font-size-15 text-truncate">
                                    <a>{{ $foodCategory->local_lang_name }}</a></h5>
                                {{-- <span class="text-muted">{{ $user->email }}</span> --}}
                            </div>
                        </td>


                        <td>
                            <div class="text-end">

                                <span class="text-muted">{{ $foodCategory->created_at }}</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

    </div>
    <!-- end card body -->
</div>
<!-- end card -->
