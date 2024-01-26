@extends('layouts.admin')
@section('title', 'Invoice')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">

    <style>
        table tbody td:nth-child(1) { width: 20%;  }
    </style>
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <div class="d-print-none">
        <h4 class="pull-left page-title">Invoices</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="{{ url('admin/invoices') }}">Invoices</a></li>
            <li class="active">View</li>
        </ol>
    </div>
@endcomponent
<div class="row" id="invoice">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-right">
                            <img src="{{ asset('storage/settings/'.$settings->ph_logo) }}" alt="moltran">
                        </h4>
                    </div>
                    <div class="pull-right">
                        <h4>Invoice # <br>
                            <strong>{{ $invoice->invoice_date }}-{{ $invoice->id }}</strong>
                        </h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">

                        <div class="pull-left m-t-30">
                            <address>
                                  <strong>{{ $settings->ph_name }}</strong><br>
                                  <strong>Code: {{ $invoice->branch->branch_code }}</strong><br>
                                  {{ $settings->ph_address }}<br>
                                  <abbr>Phone: </abbr> {{ $invoice->branch->branch_phone }}
                                  </address>
                        </div>
                        <div class="pull-right m-t-30">
                            <p class="m-t-10"><strong>Customer Name: </strong> {{ $invoice->customer->customer_name }}</p>
                            <p><strong>Invoice Date: </strong> {{Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y')}}</p>
                            <p class="m-t-10"><strong>Payment Type: </strong> <span class="badge badge-pink">{{ $invoice->payment_type }}</span></p>
                            <p class="m-t-10"><strong>Invoice ID: </strong> #{{ $invoice->id }}</p>
                        </div>
                    </div>
                </div>
                <div class="m-h-50"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table m-t-30">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Medicine</th>
                                        <th>Unit Cost</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->invoiceDetails as $key=>$data)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{ $data->medicine_name }}</td>
                                        <td>{{ $data->sell_price }}</td>
                                        <td>{{ $data->qty }}</td>
                                        <td>{{ $data->total_price }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><br>
                <div class="row" style="border-radius: 0px; margin-right: 4em;">
                    <div class="col-md-3 offset-md-9">
                        <p class="text-right"><b>Total: </b> {{ $invoice->invoice_total }}</p>
                        <p class="text-right">Discout: {{  $invoice->invoice_discount }}</p>
                        <p class="text-right"><b>Net Total: </b> {{ $invoice->invoice_nettotal }}</p>
                        <p class="text-right">Due: {{ $invoice->invoice_due }}</p>
                        <hr>
                        <h3 class="text-right">Received: {{ $invoice->invoice_received }} Taka</h3>
                        <div style="margin-top: 10em;">
                            <p class="text-center">{{ $invoice->user->name}}</p>
                            <hr>
                            <strong style="margin-left: 7em;">Authorized By</strong>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-print-none">
                    <div class="pull-right">
                        <a href="javascript:window.print()" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></a>
                        <a href="{{ url('admin/invoices') }}" class="btn btn-primary waves-effect waves-light">Back</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>
@endpush








