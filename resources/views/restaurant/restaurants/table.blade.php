<div class="row">
    <div class="col-sm-12">
        <table class="table align-middle datatable dt-responsive table-check nowrap dataTable no-footer  table-bordered" '' id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
                <tr role="row">
                    <th scope="col" style="width: 50px;">

                        <div class="d-flex justify-content-between">
                            @sortablelink('id', __('system.crud.id'), [], ['class' => 'w-100 text-gray'])
                        </div>

                    </th>
                    <th scope="col" style="width: 258px;">
                        <div class="d-flex justify-content-between">
                            @sortablelink('user_name', __('system.fields.user_name'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>

                    <th scope="col" style="width: 258px;">
                        <div class="d-flex justify-content-between">
                            @sortablelink('name', __('system.fields.restaurant_name'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th scope="col" style="width: 266px;">
                        <div class="d-flex justify-content-between">
                            @sortablelink('type', __('system.fields.restaurant_type'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th scope="col" style="width: 266px;">
                        <div class="d-flex justify-content-between">
                            @sortablelink('contact_email', __('system.fields.email'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th scope="col" style="width: 327px;">
                        <div class="d-flex justify-content-between">
                            @sortablelink('phone_number', __('system.fields.phone_number'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th scope="col" style="width: 336px;">
                        <div class="d-flex justify-content-between">
                            @sortablelink('created_at', __('system.fields.created_at'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th style="width: 80px; min-width: 80px;">{{ __('system.crud.action') }}</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($restaurants ?? [] as $restaurant)
                    <tr>
                        <th scope="row" class="sorting_1">
                            {{ $restaurant->adminUser()->id }}
                        </th>
                        <th scope="row" class="sorting_1">
                            {{ $restaurant->adminUser()->name }}
                        </th>
                        <td>
                            <a href="#" target="_blank">
                                <a href="{{config('app.url') . '?menu=' . $restaurant->uuid}}" target="_blank">


                                @if ($restaurant->logo != null)
                                    <img data-src="{{ $restaurant->logo_url }}" alt="" class="avatar-lg rounded-circle me-2 lazyload">
                                @else
                                    <div class="avatar-lg d-inline-block align-middle me-2">
                                        <div class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold">
                                            {{ $restaurant->logo_name }}
                                        </div>
                                    </div>
                                @endif

                                <span>{{ $restaurant->name }}</span> <i class=" fas fa-external-link-alt ms-2 " aria-hidden="true"></i>
                            </a>
                        </td>
                        <td>{{ $restaurant->type ?? '-' }}</td>
                        <td>{{ $restaurant->adminUser()->email ?? '-' }}</td>
                        <td>{{ $restaurant->adminUser()->phone_number ?? '-' }}</td>
                        <td>
                            {{ $restaurant->created_at }}
                        </td>
                        <td>
                            @if (auth()->user()->user_type == App\Models\User::USER_TYPE_ADMIN)
                                {{ Form::open(['route' => ['restaurant.restaurants.destroy', ['restaurant' => $restaurant->id]], 'class' => 'data-confirm', 'data-confirm-message' => __('system.restaurants.are_you_sure', ['name' => $restaurant->name]), 'data-confirm-title' => __('system.crud.delete'), 'id' => 'delete-form_' . $restaurant->id, 'method' => 'delete', 'autocomplete' => 'off']) }}
                            @endif

                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a role="button" href="{{ route('restaurant.restaurants.show', ['restaurant' => $restaurant->id]) }}" class="btn btn-secondary">{{ __('system.crud.detail') }}</a>
                                <a role="button" href="{{ route('restaurant.restaurants.edit', ['restaurant' => $restaurant->id]) }}" class="btn btn-success">{{ __('system.crud.edit') }}</a>
                                @if (auth()->user()->restaurant_id != $restaurant->id)
                                    @if (auth()->user()->user_type == App\Models\User::USER_TYPE_ADMIN)
                                        <button type="submit" class="btn btn-danger">{{ __('system.crud.delete') }}</button>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-danger disabled" disabled>{{ __('system.crud.default') }}</button>
                                @endif
                            </div>
                            @if (auth()->user()->user_type == App\Models\User::USER_TYPE_ADMIN)
                                {{ Form::close() }}
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            {{ __('system.crud.data_not_found', ['table' => __('system.restaurants.title')]) }}
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>


    </div>
</div>
<div class="row">
    {{ $restaurants->links() }}
</div>
