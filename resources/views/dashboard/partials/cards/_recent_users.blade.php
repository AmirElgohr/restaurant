<div class="card">

    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">{{ __('system.dashboard.recent_users') }}</h4>

    </div><!-- end card header -->

    <div class="card-body px-0 pb-0 pt-2">
        <div class="table-responsive px-3" data-simplebar="init" style="height: 455px;">

            <table class="table align-middle table-nowrap">
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td style="width: 50px;">
                            <div class="avatar-md me-4">

                                @if ($user->profile_url != null)
                                    <img data-src="{{ $user->profile_url }}" alt=""
                                         class="avatar-md rounded-circle me-2 image-object-cover lazyload">
                                @else
                                    <div class="avatar-md d-inline-block align-middle me-2">
                                        <div
                                            class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold">
                                            {{ $user->logo_name }}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </td>

                        <td style="max-width:{{ $width }}">
                            <div class="">
                                <h5 class="font-size-15 text-truncate"><a
                                        class="text-dark">{{ $user->name }}</a></h5>
                                <span class="text-muted d-block text-truncate">{{ $user->email }}</span>
                            </div>
                        </td>


                        <td>
                            <div class="text-end">

                                <span class="text-muted">{{ $user->created_at }}</span>
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
