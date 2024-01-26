@extends('layouts.admin')
@section('title', 'Branch')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-toggle.min.css">
    <style>
        table thead th:nth-child(1) { width: 6%;  }
        table thead th:nth-child(2) { width: 16%; }
        table thead th:nth-child(3) { width: 6%; }
        table thead th:nth-child(6) { width: 15%; }
        table thead th:nth-child(7) { width: 12%; }
    </style>
@endpush

@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Branches</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/branches') }}">Branches</a></li>
        <li class="active">Index</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> All Branches Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <button  class="btn-delete btn btn-md btn-danger card-top-button mr-2" data-url="{{ route('admin.branch.destroy') }}" disabled><i class="fa fa-trash"></i> Delete</button>
                        <a href="{{ route('admin.branches.create') }}" class="btn btn-md btn-secondary card-top-button"><i class="fa fa-plus-circle"></i> Add Branch</a>
                    </div>
                </div>
            </div>
         
            <div class="row">
                <div class="col-12"> 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped custom-table mb-0" id="dataTable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Title</th>
                                        <th>Code</th>
                                        <th>Dispenser</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>
                                            <div class="custom-control custom-checkbox d-inline">
                                                <input type="checkbox" class="check-all custom-control-input" id="horizontalCheckbox" autocomplete="off">
                                                <label class="custom-control-label" for="horizontalCheckbox">Action</label>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
         
            <div class="card-footer card-footer-part">
                <div class="row">
                    <div class="col-5">
                        
                    </div>
                    <div class="col-6">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap-toggle.min.js"></script>
    <script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.branches.index') }}",
            },
            columns: [
                { data: 'sl', name: 'sl' }, // { data: 'id', name: 'id' },
                { data: 'title', name: 'title', orderable: false },
                { data: 'code', name: 'code', orderable: false },
                { data: 'dispenser', name: 'dispenser', orderable: false },
                { data: 'phone', name: 'phone', orderable: false },
                { data: 'address', name: 'address', orderable: false },
                {   
                    data: 'branch_status',
                    render: function ( data, type, row ) {
                        if ( type === 'display' ) {
                            if(data) {
                                return '<input type="checkbox" data-id="' + row.id + '" data-url={{ url("admin/changebranchstatus") }}  class="editor-active" data-width="115" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="Inactive" checked>';

                            }else {
                                return '<input type="checkbox" data-id="' + row.id + '" data-url={{ url("admin/changebranchstatus") }} class="editor-active" data-width="115" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="Inactive">';
                            }
                        }
                        return data;
                    },
                    
                    name: 'status', orderable: false, searchable: false
                },

                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],

            rowCallback: function ( row, data ) {
                $('input.editor-active', row).bootstrapToggle();
            },
        });
    });
    </script>
@endpush





























