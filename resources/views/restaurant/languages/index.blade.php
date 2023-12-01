@extends('layouts.app')
@section('title', __('system.languages.title'))
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">{{ __('system.languages.menu') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a
                                                href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.languages.menu') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-6 text-end add-new-btn-parent">

                            <a href="{{ route('restaurant.languages.create') }}" class="btn btn-primary"><i
                                    class="bx bx-plus me-1"></i>{{ __('system.crud.add_new') }}</a>
                            {{-- <button href="{{ route('restaurant.languages.export.sample') }}" class="btn btn-info"
                                data-bs-toggle="modal" data-bs-target="#export"><i class="bx bx-upload"></i>
                                {{ __('system.crud.export_excel') }}</button>
                            <button href="{{ route('restaurant.languages.import.sample') }}" class="btn btn-info"
                                data-bs-toggle="modal" data-bs-target="#import"><i class="bx bx-upload"
                                    style="transform: rotate(180deg);"></i> {{ __('system.crud.import_excel') }}</button> --}}
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="export" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('system.crud.export_excel') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="mb-3">
                                        <label for="recipient-name" class="col-form-label">Recipient:</label>
                                        <input type="text" class="form-control" id="recipient-name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="message-text" class="col-form-label">Message:</label>
                                        <textarea class="form-control" id="message-text"></textarea>
                                    </div>
                                </form>
                                <a
                                    href="{{ route('restaurant.languages.export.sample') }}">{{ __('system.crud.download') }}</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal fade" id="import" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('system.crud.import_excel') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('restaurant.languages.import.sample') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="recipient-name"
                                            class="col-form-label">{{ __('system.crud.upload_excel') }}</label>
                                        <input type="file" class="form-control" id="import-file" name="import_file">
                                        @error('import_file')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('system.crud.submit') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div id="restaurants_list" class="dataTables_wrapper dt-bootstrap4 no-footer table_filter">
                            @include('common.filter_ui')
                            <div id="data-preview" class='overflow-hidden'>
                                @include('restaurant.languages.table')
                            </div>

                        </div>
                        <!-- end table -->
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>
@endsection
