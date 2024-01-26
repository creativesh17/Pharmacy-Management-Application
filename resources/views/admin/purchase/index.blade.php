@extends('layouts.admin')
@section('title', 'Purchase')

@push('css')
    {{-- <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-toggle.min.css"> --}}
    <style>
        table thead th:nth-child(1) { width: 7%; }
        table thead th:nth-child(3) { width: 6%; }
        table thead th:nth-child(4) { width: 6%; }
        table thead th:nth-child(5) { width: 10%; }
        table thead th:nth-child(6) { width: 7%; }
        table thead th:nth-child(8) { width: 7%; }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Purchases</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/purchases') }}">Purchases</a></li>
        <li class="active">Index</li>
    </ol>
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> All Purchase Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <button  class="btn-delete btn btn-md btn-danger card-top-button mr-2" data-url="{{ route('admin.purchase.destroy') }}" disabled><i class="fa fa-trash"></i> Delete</button>
                        @if(auth()->user()->role_id > 2)
                        <a href="{{ route('admin.purchases.create') }}" class="btn btn-md btn-secondary card-top-button"><i class="fa fa-plus-circle"></i> Add Purchase</a>
                        @endif
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
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Branch</th>
                                        <th>Supplier</th>
                                        <th>Type</th>
                                        <th>Total</th>
                                        <th>Discount</th>
                                        <th>Net Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
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
    {{-- <script src="{{asset('contents/admin')}}/assets/js/bootstrap-toggle.min.js"></script> --}}
    <script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.purchases.index') }}",
            },
            columns: [
                { data: 'sl', name: 'sl'},
                { data: 'purchase invoice id', name: 'purchase invoice id'},
                { data: 'purchase_date', name: 'purchase_date', orderable: false },
                { data: 'branch', name: 'branch', orderable: false },
                { data: 'supplier', name: 'supplier', orderable: false },
                { data: 'payment type', name: 'payment type', orderable: false },
                { data: 'purchase_total', name: 'purchase_total', orderable: false },
                { data: 'purchase_discount', name: 'purchase_discount', orderable: false },
                { data: 'purchase_nettotal', name: 'purchase_nettotal', orderable: false },
                { data: 'purchase_paid', name: 'purchase_paid', orderable: false },
                { data: 'purchase_due', name: 'purchase_due', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });
    });
    </script>
@endpush
































{{-- <tbody>
    @foreach($all as $data)
    <tr>
        <td>{{$data->name}}</td>
        <td>{{$data->phone}}</td>
        <td>{{$data->username}}</td>
        <td>{{$data->email}}</td>
        <td>{{$data->role->role_name}}</td>
        <td>
            @if($data->photo != '')
                <img class="table-image-40" src="{{asset('storage/users/'.$data->photo)}}" alt="user-photo"/>
            @else
                <img class="table-image-40" src="{{asset('storage')}}/avatar.png" alt="user-photo"/>
            @endif
        </td>
        <td>
            <a href="{{route('admin.users.show', $data->username)}}"><i class="fa fa-plus-square fa-lg view-icon"></i></a>

            <a href="{{route('admin.users.edit', $data->username)}}"><i class="fa fa-pencil-square fa-lg edit-icon"></i></a>

            <a href="#" onclick="deleteUser({{ $data->id }})" style="margin: 2px;">
                <i class="fa fa-trash fa-lg delete-icon"></i>
            </a>
            <form id="delete-form-{{ $data->id }}" action="{{ route('admin.users.destroy', $data->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </td>
    </tr>
    @endforeach
</tbody> --}}