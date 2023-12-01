<div class="row">
    <div class="col-sm-12">
        <table class="table align-middle datatable dt-responsive table-check nowrap dataTable no-footer  table-bordered" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
                <tr role="row">
                    <th scope="col" style="width: 50px;">


                        <div class="d-flex justify-content-between">
                            @sortablelink('id', __('system.crud.id'), [], ['class' => 'w-100 text-gray'])
                        </div>

                    </th>
                    <th scope="col" style="width: 258px;">
                        <div class="d-flex justify-content-between">
                            @sortablelink('first_name', __('system.fields.name'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th scope="col" style="width: 266px;">
                        <div class="d-flex justify-content-between">
                            @sortablelink('email', __('system.fields.email'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th scope="col" style="width: 327px;">
                        @if (request()->query('user_list') == 'not_assigned' && isAdmin())
                            @php($resturants = ['' => __('system.users.select_restaurant')] + (new App\Repositories\Restaurant\RestaurantRepository())->getAllRestaurantsWithIdAndName())
                            {{ __('system.users.set_a_defualt_restaruant') }}
                        @else
                            <div class="d-flex justify-content-between">
                                @sortablelink('phone_number', __('system.fields.phone_number'), [], ['class' => 'w-100 text-gray'])
                            </div>
                        @endif
                    </th>
                    @if (request()->query('user_list', 'current') == 'all')
                        <th scope="col" style="width: 327px;">

                            <div class="d-flex justify-content-between">
                                {{ __('system.fields.restaurant') }}
                            </div>

                        </th>
                    @endif
                    <th scope="col" style="width: 336px;">
                        <div class="d-flex justify-content-between">
                            @sortablelink('created_at', __('system.fields.created_at'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th style="width: 80px; min-width: 80px;">{{ __('system.crud.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <th scope="row" class="sorting_1">
                            {{ $user->id }}
                        </th>
                        <td>

                            @if ($user->profile_url != null)
                                <img data-src="{{ $user->profile_url }}" alt="" class="avatar-sm rounded-circle me-2 image-object-cover lazyload">
                            @else
                                <div class="avatar-sm d-inline-block align-middle me-2">
                                    <div class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold">
                                        {{ $user->logo_name }}
                                    </div>
                                </div>
                            @endif


                            <span class="text-body">{{ $user->name }}</span>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if (request()->query('user_list') == 'not_assigned' && isAdmin())
                                {{ Form::open(['route' => ['restaurant.user.assign.restaurant', ['user' => $user->id]], 'autocomplete' => 'off', 'method' => 'put', 'data-confirm-message' => __('system.users.are_you_sure_update', ['name' => $user->name]), 'data-confirm-title' => __('system.users.set_a_defualt_restaruant')]) }}
                                {{ Form::select('assign_restaurant', $resturants, request()->query('food_category_id'), [
                                    'class' => 'form-select choice-picker assign_restaurant',
                                    'id' => 'restaurant_type' . $user->id,
                                    'data-remove_attr' => 'data-type',
                                    'required' => true,
                                    'data-pristine-required-message' => __('validation.custom.select_required', ['attribute' => 'food category']),
                                ]) }}
                                {{ Form::close() }}
                            @else
                                {{ $user->phone_number }}
                            @endif
                        </td>
                        @if (request()->query('user_list', 'current') == 'all')
                            <td>

                                {{ implode(',', $user->restaurants->pluck('name')->toarray() ?? []) }}

                            </td>
                        @endif
                        <td>

                            {{ $user->created_at }}

                        </td>

                        <td>
                            @if (auth()->user()->user_type == App\Models\User::USER_TYPE_ADMIN)
                                {{ Form::open(['route' => ['restaurant.users.destroy', ['user' => $user->id]], 'autocomplete' => 'off', 'class' => 'data-confirm', 'data-confirm-message' => __('system.users.are_you_sure', ['name' => $user->name]), 'data-confirm-title' => __('system.crud.delete'), 'id' => 'delete-form_' . $user->id, 'method' => 'delete']) }}
                            @endif

                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a role="button" href="{{ route('restaurant.users.edit', ['user' => $user->id]) }}" class="btn btn-success">{{ __('system.crud.edit') }}</a>
                                @if (auth()->user()->user_type == App\Models\User::USER_TYPE_ADMIN)
                                    <button type="submit" class="btn btn-danger">{{ __('system.crud.delete') }}</button>
                                @endif

                            </div>
                            @if (auth()->user()->user_type == App\Models\User::USER_TYPE_ADMIN)
                                {{ Form::close() }}
                            @endif


                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Admin data not found!
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>


    </div>
</div>
<div class="row">
    {{ $users->links() }}
</div>
@push('page_scripts')
    <script>
        $(document).on('change', '.assign_restaurant', function(e) {
            if ($(this).val() != '') {
                var that = $(this).parents('form')
                var title = that.data('confirm-message');

                title = title.replace('#resturant#', $(this).find(":selected").text())
                console.log(title);
                alertify.confirm(
                    title,
                    function() {
                        that.submit();
                    },
                    function() {
                        alertify.error('{{ __('system.messages.operation_canceled') }}');
                    }
                ).set({
                    title: that.data('confirm-title')
                }).set({
                    labels: {
                        ok: '{{ __('system.crud.confirmed') }}',
                        cancel: '{{ __('system.crud.cancel') }}'
                    }
                });
            }
        })
    </script>
@endpush
