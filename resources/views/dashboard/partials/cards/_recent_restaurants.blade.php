<div class="card">

    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">{{ __('system.dashboard.recent_restaurants') }}</h4>

    </div><!-- end card header -->

    <div class="card-body px-0 pb-0 pt-2">
        <div class="card-body px-0 pt-2">
            <div class="table-responsive px-3" data-simplebar style="height: 425px;">
                <table class="table align-middle table-nowrap">
                    <tbody>
                    @foreach ($restaurants as $restaurant)
                        <tr>
                            <td style="width: 50px;">
                                <div class="avatar-md me-4">

                                    @if ($restaurant->logo_url != null)
                                        <img data-src="{{ $restaurant->logo }}" alt=""
                                             class="avatar-md rounded-circle me-2 lazyload">
                                    @else
                                        <div class="avatar-md d-inline-block align-middle me-2">
                                            <div
                                                class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold">
                                                {{ $restaurant->logo_name }}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </td>

                            <td>
                                <div>
                                    <h5 class="font-size-15"><a
                                            class="text-dark">{{ $restaurant->name }}</a></h5>
                                    <span class="text-muted">{{ $restaurant->phone_number }}</span>
                                </div>
                            </td>

                            <td>
                                <div class="text-end">

                                    <span class="text-muted">{{ $restaurant->created_at }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!-- end card body -->


</div>
<!-- end card -->
