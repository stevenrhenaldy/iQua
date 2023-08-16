@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>Devices Type Management</h2>
                        <div class="row">
                            <div class="col-md-12 my-1">
                                <a href="{{ route('admin.device_type.create') }}" class="btn text-center btn-primary float-end">Add
                                    New
                                    Device Type</a>
                            </div>
                            <div class="col-12">

                                <table id="device_table" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">

        $(function () {
            $("#device_table").dataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });
    </script>
@endsection
