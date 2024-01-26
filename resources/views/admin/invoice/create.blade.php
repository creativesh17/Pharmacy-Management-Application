@extends('layouts.admin')
@section('title', 'Invoices')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">

    <style>
        table tbody td:nth-child(1) { width: 20%;  }
        table tbody td:nth-child(3) { width: 5%;  }
        .cursor-not {
            cursor: not-allowed;
        }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Invoices</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/invoices') }}">Invoices</a></li>
        <li class="active">Create</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" method="post" action="{{url('admin/invoices')}}">
            @csrf
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Add Invoice</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/invoices')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Invoices</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>


                <div class="card-body" id="addCustomerId" style="display: none;">
                    <div class="row">
                        <div class="col-md-12" >
                            <div class="form-group row custom-form-group">
                                <div class="col-sm-12 float-left">
                                    <div class="col-sm-12">
                                        <div class="form-group row custom-form-group">
                                            <label class="col-sm-3 control-label"> Name: <span class="req_star">*</span></label>
                                            <div class="col-sm-7">
                                            <input type="text" class="form-control" name="customer_name" value="{{old('customer_name')}}">
                                            </div>
                                            @error('customer_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="col-2 float-right">
                                                <button type="button" class="btn btn-success btn-customer-old" onclick="oldCustomer();" style="display: none;">Old Customer</button>
                                            </div>
                                        </div>
                                        <div class="form-group row custom-form-group">
                                            <label class="col-sm-3 control-label"> Phone: </label>
                                            <div class="col-sm-7">
                                            <input type="text" class="form-control" name="customer_phone" value="{{old('customer_phone')}}">
                                            </div>
                                        </div>
                                        <div class="form-group row custom-form-group">
                                            <label class="col-sm-3 control-label"> Email: </label>
                                            <div class="col-sm-7">
                                            <input type="email" class="form-control" name="customer_email" value="{{old('customer_email')}}">
                                            </div>
                                        </div>
                                        <div class="form-group row custom-form-group">
                                            <label class="col-sm-3 control-label"> Address: </label>
                                            <div class="col-sm-7">
                                            <textarea type="text" class="form-control" name="customer_address">{{old('customer_address')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card-body card-form">
                    <div class="row">
                        <div class="col-md-6" id="customer_id_old">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-2 control-label"> Customer:<span class="req_star">*</span></label>
                                <div class="col-sm-6">
                                    <select class="select2 form-control" style="width:500px;" name="customer_id"></select>
                                    <small style="color: #777;">Type phone or name</small>
                                    @error('customer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-4 float-right">
                                    <button type="button" class="btn btn-success btn-customer" onclick="addCustomer();">New Customer</button>
                                </div>  
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-2 control-label"> Date: </label>
                                <div class="col-sm-6">
                                    <input type="text" name="invoice_date" class="form-control" id="datepicker_one" value="{{old('invoice_date')}}">
                                    @error('invoice_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-2 control-label">Invoice Total: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control cursor-not" name="invoice_total" id="invoice_total" value="{{old('invoice_total')}}" required readOnly>
                                    @error('invoice_total')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-2 control-label"> Payment Type:<span class="req_star">*</span></label>
                                <div class="col-sm-6">
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
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-2 control-label"> Note: </label>
                                <div class="col-sm-6">
                                    <textarea type="text" class="form-control" name="invoice_note">{{old('invoice_note')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    {{-- invoice details starts --}}
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-striped invoice-custom-table mb-0" id="myInvoice">
                                <thead>
                                    <tr>
                                        <th style="display: none;"></th>
                                        <th>Name</th>
                                        <th>Unit</th>
                                        <th>Stock</th>
                                        <th>Sale Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>
                                            <button type="button" name="addInvoice" class="btn btn-success btn-sm btn_addInvoice" required>
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
                    {{-- invoice details ends --}}
                    {{-- boxes --}}
                    <div class="card-body">
                        <div class="col-md-10 offset-md-1">
                            <div class="form-group row">
                                <label for="">Total</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control cursor-not invoice_total" name="invoice_total" id="total" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Discount</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="invoice_discount" id="discount" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Received</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="invoice_received" id="paid" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Net Total</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control cursor-not" name="invoice_nettotal" id="netPaid" readonly required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Due Amount</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control cursor-not" name="invoice_due" id="due" required readonly>
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
                    url: '/admin/invoice/customerautosearch',
                    dataType: 'JSON',
                    processResults: function (data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.customer_name,
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

    <script>
        $(function() {
            $(document).on("click", ".btn_addInvoice", function() {
                var html = '';
                html += '<tr>';
                html += '<td><select class="form-control medicine_id" name="medicine_id[]" style="width:300px;" required></select></td>';
                html += '<td><input type="text" class="form-control unit" style="width:140px;" value="Pcs"  required readOnly></td>';
                html += '<td><input type="text" class="form-control stock" style="width:100px;" value="" required readOnly></td>';
                html += '<td><input type="text" class="form-control sell_price" name="sell_price[]" style="width:140px;" value="" required readOnly></td>';
                html += '<td><input type="number" min="1" max="10000000" class="form-control qty" name="qty[]" style="width:100px;" required></td>';
                html += '<td><input type="text" class="form-control total_price" style="width:150px;" name="total_price[]" readonly></td>';

                html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-remove"></i></button></td>'

                $("#myInvoice").append(html);

                $('.medicine_id').select2({
                    width: "100%",
                    placeholder: 'Choose Any',
                    ajax: {
                        url: '/admin/invoice/medicineautosearch',
                        dataType: 'JSON',
                        processResults: function (data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.medicine_name,
                                        id: item.medicine_id
                                    }
                                })
                            };
                        }, 
                        cache: true
                    }
                });


                $('.medicine_id').on('change', function(e) {
                    var id = this.value;
                    console.log(id);
                    var tr = $(this).parent().parent();
                    $.ajax({
                        url: "/admin/invoice/medicineinfo",
                        method: "GET",
                        data: {id:id},
                        success: function(data){
                            console.log(data);
                            var len = data.length;
                            console.log(len);
                            
                            var stock = data[0];
                            tr.find(".stock").val(stock);

                            var sell_price = data[1];
                            tr.find(".sell_price").val(sell_price);

                            tr.find(".qty").val(1);
                            tr.find(".total_price").val(tr.find(".qty").val() * tr.find(".sell_price").val());
                            calculate(0,0);
                            
                        }
                    });
                });


                $("#myInvoice").delegate(".qty", "keyup change wheel mouseleave", function(){
                    var quantity = $(this);
                    var tr = $(this).parent().parent();
                    if((quantity.val()-0) > (tr.find(".stock").val()-0)){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning!',
                            text: "Stock Out!",
                            timer: 4000,
                        });
                        quantity.val(1);
                        tr.find(".total_price").val(quantity.val() * tr.find(".sell_price").val());
                        calculate(0,0);
                    }else {
                        tr.find(".total_price").val(quantity.val() * tr.find(".sell_price").val());
                        calculate(0,0);
                    }
                    
                });
                
                $.fn.disableSelection = function() {
                    return this.each(function() {
                        $(this).attr('unselectable', 'on')
                        .css({
                            '-moz-user-select':'none',
                            '-webkit-user-select':'none',
                            'user-select':'none',
                            '::selection':'none',
                        })
                        .each(function() {
                            this.onselectstart = function() { return false; };
                        });
                    });
                };

                



                $(document).on('click', '.btn-remove', function() {
                    $(this).closest('tr').remove();
                    calclulate(0, 0);
                    $("#paid").val(0);
                });


                function calculate(paid, discount){
                    var invoice_total = 0;
                    var paid = paid;
                    var discount = discount;

                    $(".total_price").each(function() {
                        invoice_total = invoice_total + ($(this).val()*1);
                    })

                    due = invoice_total - paid; 
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

                    $("#invoice_total").val(invoice_total);
                    $("#total").val(invoice_total);
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
                
            });
        });
    </script>

    <script>
        function addCustomer() {
            var btn = $('.btn-customer');
            // btn.attr("disabled", false).html("Old Customer");
            $('.btn-customer').attr('style', 'display: none;');
            $('.btn-customer-old').attr('style', 'display: block;');
            $('#customer_id_old').attr('style', 'display: none;');
            $("#addCustomerId").attr('style', 'display: block;');
        }
        function oldCustomer() {
            var btn = $('.btn-customer-old');
            $('.btn-customer-old').attr('style', 'display: none;');
            $('.btn-customer').attr('style', 'display: block;');
            $('.btn-customer').addClass('text-right');
            $('#customer_id_old').attr('style', 'display: block;');
            $("#addCustomerId").attr('style', 'display: none;');
        }
    </script>
@endpush




