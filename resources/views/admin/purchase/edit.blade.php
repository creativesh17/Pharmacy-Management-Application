@extends('layouts.admin')
@section('title', 'Purchases')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">

    <style>
        table tbody td:nth-child(1) { width: 20%;  }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Purchases</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/purchases') }}">Purchases</a></li>
        <li class="active">Update</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" method="post" action="{{url('admin/purchases/'.$purchase->id)}}">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Purchase</h3>
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
                                    <input type="hidden" name="id" value="{{ $purchase->id }}">
                                    <input type="text" name="purchase_invoice_id" class="form-control" value="{{$purchase->purchase_invoice_id}}">
                                    {{-- @error('purchase_invoice_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label"> Purchase Date: </label>
                                <div class="col-sm-7">
                                    <input type="text" name="purchase_date" class="form-control" id="datepicker_one" value="{{$purchase->purchase_date}}">
                                    @error('purchase_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label"> Supplier:<span class="req_star">*</span></label>
                                <div class="col-sm-7">
                                    <select class="select2 form-control" style="width:300px;" name="supplier_id" id="supplier_id_select2">
                                        <option value="{{$purchase->supplier->id}}">{{$purchase->supplier->sup_name}}</option>
                                    </select>
                                    <small style="color: #777;">Type phone or name</small>
                                    @error('supplier_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label">Purchase Total: <span class="req_star">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="purchase_total" id="purchase_total" value="{{$purchase->purchase_total}}" required readOnly>
                                    @error('purchase_total')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label">Purchase Note: </label>
                                <div class="col-sm-7">
                                    <textarea type="text" class="form-control" name="purchase_note">{{ $purchase->purchase_note }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label"> Payment Type:<span class="req_star">*</span></label>
                                <div class="col-sm-7">
                                    <select class="select2_payment form-control" style="width:500px;" name="payment_type">
                                        {{-- <option value="{{ $purchase->payment_type }}" selected>{{ $purchase->payment_type }}</option> --}}
                                        <option value="Cash" @if($purchase->payment_type == 'Cash') selected @endif>Cash</option>
                                        <option value="bKash" @if($purchase->payment_type == 'bKash') selected @endif>bKash</option>
                                        <option value="Bank" @if($purchase->payment_type == 'Bank') selected @endif>Bank</option>
                                        <option value="Cash + bKash" @if($purchase->payment_type == 'Cash + bKash') selected @endif>Cash + bKash</option>
                                        <option value="Cash + Bank" @if($purchase->payment_type == 'Cash + Bank') selected @endif>Cash + Bank</option>
                                    </select>
                                    @error('payment_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    {{-- click to activate starts --}}
                    <div class="row" id="activate_div_remove">
                        <div class="col-4 offset-4" align="center">
                            <button type="button" class="btn btn-warning btn-sm btn_activateEditPurchase" required>
                                <span style="padding: 1em; font-weight: 700; font-size: 1.4em;">Click To Activate Edit Process</span>
                            </button>
                            <br><br><br>
                        </div>
                    </div>
                    {{-- click to activate ends --}}
                    {{-- purchase details starts --}}
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-striped purchase-custom-table mb-0" id="myPurchase">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>DARR No</th>
                                        <th>Expiry Date</th>
                                        <th>Sale Price</th>
                                        <th>Stock</th>
                                        <th>Purchase Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->purchaseDetails as $p)
                                    <tr>
                                        {{-- <td style="display: none;"><input type="hidden" name="purchase_details_id[]" value="{{ $p->id }}"></td> --}}
                                        <td>
                                            <select class="form-control medicine_id" name="medicine_id[]" style="width:300px;"  required>
                                                <option value="{{ $p->medicine_id }}">{{$p->medicine_name}}</option>
                                            </select>
                                        </td>

                                        <td><input type="text" class="form-control darr_no" name="darr_no[]" value="{{ $p->darr_no }}" required></td>
                                        <td><input type="text" class="form-control expiry_date" name="expiry_date[]" style="width:150px;" value="{{ $p->expiry_date }}" required></td>

                                        <td><input type="text" class="form-control sell_price" name="sell_price[]" style="width:140px;" value="{{ $p->sell_price }}" required></td>

                                        <td><input type="number" min="1" max="10000000" class="form-control stock" name="stock[]" style="width:100px;" value="{{ $p->stock }}" required></td>

                                        <td><input type="text" class="form-control medicine_price" name="medicine_price[]" style="width:140px;" value="{{ $p->medicine_price }}" required></td>

                                        <td><input type="text" class="form-control total_price" style="width:150px;" name="total_price[]" value="{{ $p->total_price }}" readonly></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br><br><br>
                    {{-- purchase details ends --}}
                    {{-- boxes --}}
                    <div class="card-body">
                        <div class="col-md-10 offset-md-1">
                            <div class="form-group row">
                                <label for="">Total</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control purchase_total" name="purchase_total" id="total" value="{{ $purchase->purchase_total }}" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Discount</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="purchase_discount" id="discount" value="{{ $purchase->purchase_discount }}" required>
                                    @error('purchase_discount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Last Paid Amount</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $purchase->purchase_paid }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Pay Now (add current with the last payment and type total paid)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="purchase_paid" id="paid" required>
                                    @error('purchase_paid')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Net Total</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control cursor-not" name="purchase_nettotal" id="netPaid" value="{{ $purchase->purchase_nettotal }}" readonly required>
                                    @error('purchase_nettotal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Due Amount</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="purchase_due" id="due" value="{{ $purchase->purchase_due }}" required readonly>
                                    @error('purchase_due')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- boxes --}}
                </div>
                <div class="card-footer card-footer-button text-center">
                    <button type="submit" name="save_purchase" class="btn btn-submit btn-secondary waves-effect">Update</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function() {
            // $(document).on("click", "#supplier_id_select2", function() {
                $('.select2').select2({
                    width: "100%",
                    placeholder: 'Choose Any',
                    ajax: {
                        url: '/admin/purchase/supplierautosearch',
                        dataType: 'JSON',
                        processResults: function (data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.sup_name,
                                        id: item.id
                                    }
                                })
                            };
                        }, 
                        cache: true
                    }
                });
            // });

            // $(document).on("click", "#payment_type_select", function() {
                $('.select2_payment').select2({
                    width: "100%",
                    placeholder: 'Choose Any',
                });
            // });

        });
    </script>

    {{-- purchase --}}
    <script>
        $(function() {
            $(document).on("click", ".btn_activateEditPurchase", function() {

                var html = '';
                html += '<tr>';
                html += '<td><select class="form-control medicine_id" name="medicine_id[]" style="width:300px;" required></select></td>';
                html += '<td><input type="text" class="form-control darr_no" name="darr_no[]" required></td>';
                html += '<td><input type="text" class="form-control expiry_date" name="expiry_date[]" style="width:150px;" required></td>';
                html += '<td><input type="number" min="1" max="10000000" class="form-control stock" name="stock[]" style="width:100px;" required></td>';
                html += '<td><input type="text" class="form-control medicine_price" name="medicine_price[]" style="width:140px;" required></td>';
                html += '<td><input type="text" class="form-control total_price" style="width:150px;" name="total_price[]" readonly></td>';

                html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-remove"></i></button></td>'

                $("#myPurchase").append(html);
                
                var deleterow = (function() {
                    var executed = false;
                    return function() {
                        if (!executed) {
                            executed = true;
                            var $tbody = $("#myPurchase tbody")
                            var $last = $tbody.find('tr:last');
                            if($last.is(':last-child')){
                                $last.remove();
                            }
                        }
                    };
                })();
                deleterow(); 

                $(this).attr('disabled', 'disabled');
                $("#activate_div_remove").attr('style', 'display: none;');

                

                $('.medicine_id').select2({
                    width: "100%",
                    placeholder: 'Choose Any',
                    ajax: {
                        url: '/admin/purchase/medicineautosearch',
                        dataType: 'JSON',
                        processResults: function (data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.med_name,
                                        id: item.id
                                    }
                                })
                            };
                        }, 
                        cache: true
                    }
                });

                $("#myPurchase").delegate(".stock", "keyup change", function(){
                    var quantity = $(this);
                    var tr = $(this).parent().parent();
                 
                    tr.find(".total_price").val(quantity.val() * tr.find(".medicine_price").val());
                    calculate(0,0);
                   
                });


                function calculate(paid, discount){
                    var purchase_total = 0;
                    var paid = paid;
                    var discount = discount;

                    $(".total_price").each(function() {
                        purchase_total = purchase_total + ($(this).val()*1);
                    })

                    due = purchase_total - paid; 
                    due = due - discount;
                    

                    if(due < 0){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning!',
                            text: "Calculation Mismatch!",
                            timer: 3000,
                        });
                        due = 0;
                    }

                    $("#purchase_total").val(purchase_total);
                    $("#total").val(purchase_total);
                    $("#due").val(due);
                    $("#discount").val(discount);

                    netPaid = $("#total").val() - discount;
                    $("#netPaid").val(netPaid);
                }

                $("#paid").keyup(function(){
                    var paid = $(this).val();
                    var discount = $("#discount").val();
                    calculate(paid, discount);
                    var netPaid = $(this).val() - $("#discount").val();
                });

                $("#discount").keyup(function(){
                    var discount = $(this).val();
                    var paid = $("#paid").val();
                    calculate(paid, discount);
                    var netPaid = $("#paid").val() - $(this).val();
                });
                

                $('.expiry_date').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    todayHighlight: true
                });

                
            });
        });
    </script>

@endpush








{{-- 
$("#paid").keyup(function() {
    var paid = $(this).val();
    calculate(paid);
});

function calculate(paid) {
    var purchase_total = 0;
    var paid = paid;

    $(".total_price").each(function() {
        purchase_total = purchase_total + ($(this).val()*1);
    });

    due = purchase_total - paid;

    $("#purchase_total").val(purchase_total);
    $("#total").val(purchase_total);
    $("#due").val(due);
} --}}
