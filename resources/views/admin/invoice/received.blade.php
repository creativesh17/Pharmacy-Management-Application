@extends('layouts.admin')
@section('title', 'Received')

@push('css')
    <style>
        table thead th:nth-child(2) { width: 7%; }
        table thead th:nth-child(3) { width: 6%; }
        table thead th:nth-child(4) { width: 10%; }
        table thead th:nth-child(5) { width: 6%; }
        table thead th:nth-child(6) { width: 10%; }
        table thead th:nth-child(10) { width: 8%; }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Received Sales</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/invoice/received') }}">Received Sales</a></li>
        <li class="active">received</li>
    </ol>
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> All Received Sales Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <button  class="btn-delete btn btn-md btn-danger card-top-button mr-2" data-url="{{ route('admin.invoice.destroy') }}" disabled><i class="fa fa-trash"></i> Delete</button>
                        @if(auth()->user()->role_id > 2)
                        <a href="{{ route('admin.invoices.create') }}" class="btn btn-md btn-secondary card-top-button"><i class="fa fa-plus-circle"></i> Add Invoice</a>
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
                                        <th>Date</th>
                                        <th>Branch</th>
                                        <th>Customer</th>
                                        <th>Type</th>
                                        <th>Total</th>
                                        <th>Discount</th>
                                        <th>Net Total</th>
                                        <th>Received</th>
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
                    url: "{{ route('admin.invoice.received') }}",
                },
                columns: [
                    { data: 'sl', name: 'sl'},
                    { data: 'invoice_date', name: 'invoice_date', orderable: false },
                    { data: 'branch', name: 'branch', orderable: false },
                    { data: 'customer', name: 'customer', orderable: false },
                    { data: 'payment type', name: 'payment type', orderable: false },
                    { data: 'invoice_total', name: 'invoice_total', orderable: false },
                    { data: 'invoice_discount', name: 'invoice_discount', orderable: false },
                    { data: 'invoice_nettotal', name: 'invoice_nettotal', orderable: false },
                    { data: 'invoice_received', name: 'invoice_received', orderable: false },
                    { data: 'invoice_due', name: 'invoice_due', orderable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endpush






























