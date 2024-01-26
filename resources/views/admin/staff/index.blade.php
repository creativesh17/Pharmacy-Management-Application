@extends('layouts.admin')
@section('title', 'Staffs')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-toggle.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Staffs</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/staffs') }}">Staffs</a></li>
        <li class="active">Index</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> All Staffs Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <button  class="btn-delete btn btn-md btn-danger card-top-button mr-2" data-url="{{ route('admin.staff.destroy') }}" disabled><i class="fa fa-trash"></i> Delete</button>
                        <a href="{{ route('admin.staffs.create') }}" class="btn btn-md btn-secondary card-top-button"><i class="fa fa-plus-circle"></i> Add staff</a>
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
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Branch</th>
                                        <th>Photo</th>
                                        <th>Status</th>
                                        <th>
                                            <div class="custom-control custom-checkbox d-inline">
                                                <input type="checkbox" class="check-all custom-control-input" id="horizontalCheckbox">
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
{{-- </div> --}}

@endsection

@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap-toggle.min.js"></script>
    <script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.staffs.index') }}",
            },
            columns: [
                { data: 'sl', name: 'sl' },
                { data: 'name', name: 'name', orderable: false },
                { data: 'phone', name: 'phone', orderable: false },
                { data: 'email', name: 'email', orderable: false },
                { data: 'branch', name: 'branch', orderable: false },
                { data: 'photo', name: 'photo', orderable: false, searchable: false  },
                {   
                    data: 'status',
                    render: function ( data, type, row ) {
                        if ( type === 'display' ) {
                            if(data) {
                                return '<input type="checkbox" data-id="' + row.id + '" data-url={{ url("admin/changestaffstatus") }}  class="editor-active" data-width="115" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="Inactive" checked>';

                            }else {
                                return '<input type="checkbox" data-id="' + row.id + '" data-url={{ url("admin/changestaffstatus") }} class="editor-active" data-width="115" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="Inactive">';
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



