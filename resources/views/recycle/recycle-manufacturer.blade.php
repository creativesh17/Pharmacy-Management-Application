@extends('layouts.admin')
@section('title', 'Trashed Manufacturer')

@push('css')

@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Trashed Manufacturers</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{url('admin/recycle/manufacturers')}}">Trashed Manufacturers</a></li>
        <li class="active">Index</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> All Trashed Manufacturers</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <button  class="btn-delete btn btn-md btn-danger card-top-button mr-2" data-url="{{ route('admin.manufacturer.forcedelete') }}" disabled><i class="fa fa-trash"></i> Delete</button>
                        <button  class="btn-restore btn btn-md btn-success card-top-button mr-2" data-url="{{ route('admin.manufacturer.restore') }}" disabled><i class="fa fa-trash"></i> Restore</button>
                        <a href="{{ route('admin.manufacturers.index') }}" class="btn btn-md btn-secondary card-top-button"><i class="fa fa-plus-circle"></i> All Manufacturers</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped custom-table mb-0" id="dataTable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Manufacturer Name</th>
                                        <th>Manufacturer Note</th>
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

@endsection

@push('js')
    <script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.recycle.manufacturers') }}",
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

