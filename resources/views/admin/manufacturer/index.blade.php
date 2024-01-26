@extends('layouts.admin')
@section('title', 'Manufacturer')

@push('css')
    <style>
        table thead th:nth-child(4) { width: 14%;  }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Manufacturers</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/manufacturers') }}">Manufacturers</a></li>
        <li class="active">Index</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> All Manufacturer Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        @if(Auth::user()->role_id <= 2)
                        <button  class="btn-delete btn btn-md btn-danger card-top-button mr-2" data-url="{{ route('admin.manufacturer.destroy') }}" disabled><i class="fa fa-trash"></i> Delete</button>
                        @endif 
                        <a href="{{ route('admin.manufacturers.create') }}" class="btn btn-md btn-secondary card-top-button"><i class="fa fa-plus-circle"></i> Add Manufacturer</a>
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
                                        <th>Manufacturer Name</th>
                                        <th>Manufacturer Note</th>
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
                        {{-- <a href="#" class="btn btn-secondary" onclick="javascript:window.print();">PRINT</a> --}}
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
    <script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.manufacturers.index') }}",
            },
            columns: [
                { data: 'sl', name: 'sl' },
                // { data: 'id', name: 'id' },
                { data: 'name', name: 'name', orderable: false },
                { data: 'note', name: 'note', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });
    });
    </script>
@endpush




