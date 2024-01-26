@extends('layouts.admin')
@section('title', 'Refunds')

@push('css')
    <style>
        table thead th:nth-child(2) { width: 5%; }
        table thead th:nth-child(3) { width: 3%; }
        table thead th:nth-child(4) { width: 10%; }
        table thead th:nth-child(5) { width: 7%; }
        /* table thead th:nth-child(6) { width: 10%; }
        table thead th:nth-child(10) { width: 8%; } */
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Refunds</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/refunds') }}">Refunds</a></li>
        <li class="active">index</li>
    </ol>
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> All Refunds Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <button  class="btn-delete btn btn-md btn-danger card-top-button mr-2" data-url="{{ route('admin.refund.destroy') }}" disabled><i class="fa fa-trash"></i> Delete</button>
                        @if(auth()->user()->role_id > 2)
                        <a href="{{ route('admin.refund.add') }}" class="btn btn-md btn-secondary card-top-button"><i class="fa fa-plus-circle"></i> Add Refund</a>
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
                                        <th>Cut</th>
                                        <th>Net Total</th>
                                        <th>Paid</th>
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

    <script>
        $(document).ready( function () {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.refunds.index') }}",
                },
                columns: [
                    { data: 'sl', name: 'sl'},
                    { data: 'refund_date', name: 'refund_date', orderable: false },
                    { data: 'branch', name: 'branch', orderable: false },
                    { data: 'customer', name: 'customer', orderable: false },
                    { data: 'payment type', name: 'payment type', orderable: false },
                    { data: 'refund_total', name: 'refund_total', orderable: false },
                    { data: 'refund_cut', name: 'refund_cut', orderable: false },
                    { data: 'refund_nettotal', name: 'refund_nettotal', orderable: false },
                    { data: 'refund_paid', name: 'refund_paid', orderable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endpush






























