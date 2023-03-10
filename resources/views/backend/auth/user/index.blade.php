@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-7">
                    <h4 class="card-title mb-0">
                        {{ __('labels.backend.access.users.management') }}
                        <small class="text-muted">{{ __('labels.backend.access.users.active') }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-5">
                    <select name="roles" id="roles" class="form-control d-inline w-75">

                        <option value="">{{ __('labels.backend.access.users.select_role') }}</option>

                        @foreach($roles as $role)

                            <option value="{{ $role->id }}">{{ $role->name }}</option>

                        @endforeach

                    </select>
                    @include('backend.auth.user.includes.header-buttons')
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4">
                <div class="col">

                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>@lang('labels.general.id')</th>
                                <th>@lang('labels.backend.access.users.table.user_name')</th>
                                <th>@lang('labels.backend.access.users.table.email')</th>
                                <th>@lang('labels.backend.access.users.table.confirmed')</th>
                                <th>@lang('labels.backend.access.users.table.roles')</th>
                                <th>@lang('labels.backend.access.users.table.other_permissions')</th>
                                <th>@lang('labels.backend.access.users.table.last_updated')</th>
                                <th>@lang('labels.general.actions')</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div><!--col-->
            </div><!--row-->

        </div><!--card-body-->
    </div><!--card-->
@endsection


@push('after-scripts')
    <script>

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var route = '{{route('admin.auth.user.getData')}}';

            var myTable = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    'colvis'
                ],
                ajax: {
                    url: route,
                    type: 'post',
                    data: function (d) {
                        d.role = $('#roles').val();
                    }
                },
                columns: [
                    {data: "id", name: 'id'},
                    {data: "username", name: "username"},
                    {data: "email", name: "email"},
                    {data: "confirmed_label", name: "confirmed_label"},
                    {data: "roles_label", name: "roles.name"},
                    {data: "permissions_label", name: "permissions.name"},
                    {data: "last_updated", name: "last_updated"},
                    {data: "actions", name: "actions", "searchable": false}
                ],
                order: [ 0, 'desc' ],


                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
                language: {
                    url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons: {
                        colvis: '{{trans("datatable.colvis")}}',
                        pdf: '{{trans("datatable.pdf")}}',
                        csv: '{{trans("datatable.csv")}}',
                    }
                }
            });


            $(document).on('change', '#roles', function (e) {
                myTable.draw();
                e.preventDefault();
            });
        });

    </script>

@endpush
