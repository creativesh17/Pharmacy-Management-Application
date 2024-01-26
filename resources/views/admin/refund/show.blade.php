@extends('layouts.admin')
@section('title', 'Refund')

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
        <h4 class="pull-left page-title">Refunds</h4>
        <ol class="breadcrumb pull-right">
            <li><a href="{{ url('admin/refunds') }}">Refunds</a></li>
            <li class="active">View</li>
        </ol>
    </div>
@endcomponent
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-right">
                            <img src="{{ asset('storage/settings/'.$settings->ph_logo) }}" alt="moltran">
                        </h4>
                    </div>
                    {{-- <div class="text-center">{{ $settings->ph_name }}</div> --}}
                    <div class="pull-right">
                        <h4>Refund Invoice # <br>
                            <strong>{{ $refund->refund_date }}-{{ $refund->id }}</strong>
                        </h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">

                        <div class="pull-left m-t-30">
                            <address>
                                  <strong>{{ $settings->ph_name }}</strong><br>
                                  <strong>Code: {{ $refund->branch->branch_code }}</strong><br>
                                  {{ $settings->ph_address }}<br>
                                  <abbr>Phone: </abbr> {{ $refund->branch->branch_phone }}
                                  </address>
                        </div>
                        <div class="pull-right m-t-30">
                            <p class="m-t-10"><strong>Customer Name: </strong> {{ $refund->customer->customer_name }}</p>
                            <p><strong>Refund Date: </strong> {{Carbon\Carbon::parse($refund->refund_date)->format('d M, Y')}}</p>
                            <p class="m-t-10"><strong>Payment Type: </strong> <span class="badge badge-pink">{{ $refund->payment_type }}</span></p>
                            <p class="m-t-10"><strong>Refund ID: </strong> #{{ $refund->id }}</p>
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
                                        <th>Refund Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($refund->refundDetails as $key=>$data)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{ $data->medicine_name }}</td>
                                        <td>{{ $data->sell_price }}</td>
                                        <td>{{ $data->refund_qty }}</td>
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
                        <p class="text-right"><b>Total: </b> {{ $refund->refund_total }}</p>
                        <p class="text-right">Cut: {{  $refund->refund_cut }}</p>
                        <p class="text-right"><b>Net Total: </b> {{ $refund->refund_nettotal }}</p>
                        <hr>
                        <h3 class="text-right">Paid: {{ $refund->refund_paid }} Taka</h3>
                        <div style="margin-top: 10em;">
                            <p style="margin-left: 4.5em;">{{ $refund->user->name}}</p>
                            <hr style="margin-right: 0em;">
                            <strong style="margin-left: 4.5em;">Authorized By</strong>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-print-none">
                    <div class="pull-right">
                        <a href="javascript:window.print()" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></a>
                        <a href="{{ url('admin/refunds') }}" class="btn btn-primary waves-effect waves-light">Back</a>
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








