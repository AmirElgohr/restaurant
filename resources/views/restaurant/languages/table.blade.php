<div class="row">
    <div class="col-sm-12">
        <table class="table align-middle datatable dt-responsive table-check nowrap dataTable no-footer  table-bordered" '' id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
                <tr role="row">
                    <th scope="col" width="50px">

                        <div class="d-flex justify-content-between">
                            @sortablelink('id', __('system.crud.id'), [], ['class' => 'w-100 text-gray'])
                        </div>

                    </th>
                    <th scope="col" width="30%">
                        <div class="d-flex justify-content-between">
                            @sortablelink('name', __('system.fields.language_name'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th scope="col" width="30%">
                        <div class="d-flex justify-content-between">
                            @sortablelink('created_at', __('system.fields.created_at'), [], ['class' => 'w-100 text-gray'])
                        </div>
                    </th>
                    <th style="width: 80px; min-width: 80px;">{{ __('system.crud.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($languages ?? [] as $language)
                    <tr>
                        <th scope="row" class="sorting_1">
                            {{ $language->id }}
                        </th>
                        <td>




                            <a class="text-body">{{ $language->name }}</a>
                        </td>
                        <td>
                            {{ $language->created_at }}
                        </td>
                        <td>
                            {{ Form::open(['route' => ['restaurant.languages.destroy', ['language' => $language->id]], 'class' => 'data-confirm', 'data-confirm-message' => __('system.languages.are_you_sure', ['name' => $language->name]), 'data-confirm-title' => __('system.crud.delete'), 'id' => 'delete-form_' . $language->id, 'method' => 'delete', 'autocomplete' => 'off']) }}
                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a role="button" href="{{ route('restaurant.languages.edit', ['language' => $language->id]) }}" class="btn btn-dark">{{ __('system.languages_data.edit.menu') }}</a>

                                @if (!(strtolower($language->name) == 'english' || $language->store_location_name == config('app.app_locale')))
                                    <button type="submit" class="btn btn-danger">{{ __('system.crud.delete') }}</button>
                                @endif
                            </div>
                            {{ Form::close() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            {{ __('system.crud.data_not_found', ['table' => __('system.languages.title')]) }}
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>


    </div>
</div>
<div class="row">
    {{ $languages->links() }}
</div>
