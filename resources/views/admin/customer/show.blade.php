@extends('layouts.admin')
@section('title', 'Customers')


@push('css')
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Customers</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/customers') }}">Customers</a></li>
        <li class="active">View</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8 d-print-none">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> View Customer Information</h3>
                    </div>
                    <div class="col-md-4 d-print-none text-right">
                        <a href="{{url('admin/customers')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-plus-circle"></i> All Customers</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <table class="table table-bordered table-striped table-hover custom-view-table">
                            <tr>
                                <td>Name</td>
                                <td>:</td>
                                <td>{{$customer->customer_name}}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>:</td>
                                <td>{{$customer->customer_phone}}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td>{{$customer->customer_email}}</td>
                            </tr>
                            <tr>
                                <td>Added By</td>
                                <td>:</td>
                                <td>{{$customer->user->name ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>:</td>
                                <td>{{$customer->customer_address}}</td>
                            </tr>
                            <tr>
                                <td>Note</td>
                                <td>:</td>
                                <td>{{$customer->customer_note}}</td>
                            </tr>
                            <tr>
                                <td>Added Time</td>
                                <td>:</td>
                                <td>{{$customer->created_at->format('d-m-Y | h:i:s a')}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
            <div class="card-footer card-footer-part d-print-none">
                <a href="#" class="btn btn-secondary waves-effect" onclick="javascript:window.print();">PRINT</a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    
@endpush

