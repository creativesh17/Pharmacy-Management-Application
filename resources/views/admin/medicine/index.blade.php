@extends('layouts.admin')
@section('title', 'Medicine')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-toggle.min.css">
    <style>
        table thead th:nth-child(3) { width: 14%;  }
        table thead th:nth-child(4) { width: 10%;  }
        table thead th:nth-child(5) { width: 16%;  }
        table thead th:nth-child(6) { width: 10%;  }
        table thead th:nth-child(7) { width: 10%;  }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Medicine</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/medicines') }}">Medicine</a></li>
        <li class="active">Index</li>
    </ol>
@endcomponent
@if(Session::has('errors'))
<div class="alert alert-danger alert-dismissible alerterror" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>
        @foreach (session('errors') as $error)
            {{ $error }}
        @endforeach
    </strong>
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> All Medicine Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        @if(Auth::user()->role_id <= 2)
                        <button  class="btn-delete btn btn-md btn-danger card-top-button mr-2" data-url="{{ route('admin.medicine.destroy') }}" disabled><i class="fa fa-trash"></i> Delete</button>
                        @endif
                        <a href="{{url('admin/csv/import')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> Import Medicines</a>
                        <a href="{{ route('admin.medicines.create') }}" class="btn btn-md btn-secondary card-top-button"><i class="fa fa-plus-circle"></i> Add Medicine</a>
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
                                        <th>Generic Name</th>
                                        <th>Category</th>
                                        <th>Manufacturer</th>
                                        <th>Selling Price</th>
                                        <th>Note</th>
                                        <th>Status</th>
                                        <th>
                                            <div class="custom-control custom-checkbox d-inline">
                                                @if(Auth::user()->role_id <= 2)
                                                <input type="checkbox" class="check-all custom-control-input" id="horizontalCheckbox" autocomplete="off">
                                                <label class="custom-control-label" for="horizontalCheckbox">Action</label>
                                                @else
                                                <span class="action-class">Action</span>
                                                @endif
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
                        {{-- {{ $all->links() }} --}}
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
                    url: "{{ route('admin.medicines.index') }}",
                },
                columns: [
                    { data: 'sl', name: 'sl' },
                    // { data: 'id', name: 'id' },
                    { data: 'name', name: 'name', orderable: false },
                    { data: 'generic_name', name: 'generic name', orderable: false },
                    { data: 'category', name: 'category', orderable: false },
                    { data: 'manufacturer', name: 'manufacturer', orderable: false },
                    { data: 'sell_price', name: 'selling price' },
                    { data: 'note', name: 'note', orderable: false, searchable: false  },
                    {   
                        data: 'med_status',
                        render: function ( data, type, row ) {
                            if ( type === 'display' ) {
                                if(data) {
                                    return '<input type="checkbox" data-id="' + row.id + '" data-url={{ url("admin/changemedicinestatus") }}  class="editor-active" data-width="115" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="Inactive" checked>';
    
                                }else {
                                    return '<input type="checkbox" data-id="' + row.id + '" data-url={{ url("admin/changemedicinestatus") }} class="editor-active" data-width="115" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="Inactive">';
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






























