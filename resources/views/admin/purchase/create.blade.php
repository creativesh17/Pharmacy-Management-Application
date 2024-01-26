@extends('layouts.admin')
@section('title', 'Purchases')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">

    <style>
        table tbody td:nth-child(1) { width: 20%;  }
        table tbody td:nth-child(3) { width: 5%;  }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Purchases</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/purchases') }}">Purchases</a></li>
        <li class="active">Create</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" method="post" action="{{url('admin/purchases')}}">
            @csrf
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Add Purchase</h3>
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
                                    <input type="text" name="purchase_invoice_id" class="form-control" value="{{old('purchase_invoice_id')}}">
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
                                    <input type="text" name="purchase_date" class="form-control" id="datepicker_one" value="{{old('purchase_date')}}">
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
                                    <select class="select2 form-control" style="width:500px;" name="supplier_id"></select>
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
                                    <input type="text" class="form-control" name="purchase_total" id="purchase_total" value="{{old('purchase_total')}}" required readOnly>
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
                                    <textarea type="text" class="form-control" name="purchase_note">{{old('purchase_note')}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label"> Payment Type:<span class="req_star">*</span></label>
                                <div class="col-sm-7">
                                    <select class="select2_payment form-control" style="width:500px;" name="payment_type">
                                        <option value="Cash">Cash</option>
                                        <option value="bKash">bKash</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Cash + bKash">Cash + bKash</option>
                                        <option value="Cash + Bank">Cash + Bank</option>
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
                    {{-- purchase details starts --}}
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-striped purchase-custom-table mb-0" id="myPurchase">
                                <thead>
                                    <tr>
                                        <th style="display: none;"></th>
                                        <th>Name</th>
                                        <th>DARR No</th>
                                        <th>Expiry Date</th>
                                        <th>Sale Price</th>
                                        <th>Stock</th>
                                        <th>Purchase Price</th>
                                        <th>Total</th>
                                        <th>
                                            <button type="button" name="addPurchase" class="btn btn-success btn-sm btn_addPurchase" required>
                                                <span><i class="fa fa-plus"></i></span>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

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
                                    <input type="text" class="form-control purchase_total" name="purchase_total" id="total" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Discount</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="purchase_discount" id="discount" required>
                                    @error('purchase_discount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Paid</label>
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
                                    <input type="text" class="form-control cursor-not" name="purchase_nettotal" id="netPaid" readonly required>
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
                                    <input type="text" class="form-control" name="purchase_due" id="due" required readonly>
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
                    <button type="submit" name="save_purchase" class="btn btn-submit btn-secondary waves-effect">Submit</a>
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


            $('.select2_payment').select2({
                width: "100%",
                placeholder: 'Choose Any',
            });

        });
    </script>

    {{-- purchase --}}
    <script>
        $(function() {
            $(document).on("click", ".btn_addPurchase", function() {
                var html = '';
                html += '<tr>';
                // html += '<td><input type="hidden" class="form-control medicine_id" name="medicine_id[]" readonly></td>';
                html += '<td><select class="form-control medicine_id" name="medicine_id[]" style="width:300px;" required></select></td>';
                html += '<td><input type="text" class="form-control darr_no" name="darr_no[]" required></td>';
                html += '<td><input type="text" class="form-control expiry_date" name="expiry_date[]" style="width:150px;" required></td>';
                html += '<td><input type="text" class="form-control sell_price" name="sell_price[]" style="width:140px;" required></td>';
                html += '<td><input type="number" min="1" max="10000000" class="form-control stock" name="stock[]" style="width:100px;" required></td>';
                html += '<td><input type="text" class="form-control medicine_price" name="medicine_price[]" style="width:140px;" required></td>';
                html += '<td><input type="text" class="form-control total_price" style="width:150px;" name="total_price[]" readonly></td>';

                html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-remove"></i></button></td>'

                $("#myPurchase").append(html);

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

                



                $(document).on('click', '.btn-remove', function() {
                    $(this).closest('tr').remove();
                    calclulate(0, 0);
                    $("#paid").val(0);
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
}

$("#paid").keyup(function() {
    var paid = $(this).val();
    calculate(paid);
}); --}}
