@extends('layouts.admin')
@section('title', 'Purchase')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">

    <style>
        table tbody td:nth-child(1) { width: 20%;  }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Purchases</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/purchases') }}">Purchases</a></li>
        <li class="active">View</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> View Purchase</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin/purchases')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Purchases</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="card-body card-form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row custom-form-group">
                            <label class="col-sm-3 control-label"> Purchase Invoice ID: </label>
                            <div class="col-sm-7">
                                <input type="text" name="purchase_date" class="form-control" value="{{$purchase->purchase_invoice_id}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row custom-form-group">
                            <label class="col-sm-3 control-label"> Purchase Date: </label>
                            <div class="col-sm-7">
                                <input type="text" name="purchase_date" class="form-control" value="{{$purchase->purchase_date}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row custom-form-group">
                            <label class="col-sm-3 control-label"> Added BY: </label>
                            <div class="col-sm-7">
                                <input type="text" name="purchase_date" class="form-control" value="{{$purchase->user->name}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row custom-form-group">
                            <label class="col-sm-3 control-label"> Supplier: </label>
                            <div class="col-sm-7">
                                <input type="text" name="supplier_id" class="form-control" value="{{$purchase->supplier->sup_name}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row custom-form-group">
                            <label class="col-sm-3 control-label">Purchase Total: </label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="purchase_total" id="purchase_total" value="{{$purchase->purchase_total}}" readOnly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row custom-form-group">
                            <label class="col-sm-3 control-label">Purchase Note: </label>
                            <div class="col-sm-7">
                                <textarea type="text" class="form-control" name="purchase_note" readonly>{{$purchase->purchase_note ?? ''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row custom-form-group">
                            <label class="col-sm-3 control-label"> Payment Type: </label>
                            <div class="col-sm-7">
                                <input type="text" name="payment_type" class="form-control" value="{{$purchase->payment_type}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                {{-- purchase details starts --}}
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-striped purchase-custom-table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>DARR No</th>
                                    <th>Expiry Date</th>
                                    <th>Sale Price</th>
                                    <th>Remaining Stock</th>
                                    <th>Original Stock</th>
                                    <th>Purchase Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase->purchaseDetails as $purchasedetails)
                                    <tr>
                                        <td>{{ $purchasedetails->medicine_name }}</td>
                                        <td>{{ $purchasedetails->darr_no }}</td>
                                        <td>{{ $purchasedetails->expiry_date }}</td>
                                        <td>{{ $purchasedetails->sell_price }}</td>
                                        <td>{{ $purchasedetails->stock }}</td>
                                        <td>{{ $purchasedetails->stock_original }}</td>
                                        <td>{{ $purchasedetails->medicine_price }}</td>
                                        <td>{{ $purchasedetails->total_price }}</td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td style="text-align: right;" colspan="6"></td>
                                        <td style="text-align: right;"><strong>Total</strong></td>
                                        <td><strong>{{ $purchase->purchase_total }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;" colspan="6"></td>
                                        <td style="text-align: right;"><strong>Discount</strong></td>
                                        <td><strong>{{ $purchase->purchase_discount }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;" colspan="6"></td>
                                        <td style="text-align: right;"><strong>Net Total</strong></td>
                                        <td><strong>{{ $purchase->purchase_nettotal }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;" colspan="6"></td>
                                        <td style="text-align: right;"><strong>Due</strong></td>
                                        <td><strong>{{ $purchase->purchase_due }}</strong></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br><br><br>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>
@endpush








