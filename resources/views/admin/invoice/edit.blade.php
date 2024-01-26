@extends('layouts.admin')
@section('title', 'Invoice')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">

    <style>
        table tbody td:nth-child(1) { width: 20%;  }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Invoices</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/invoices') }}">Invoices</a></li>
        <li class="active">Update</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" method="post" action="{{url('admin/invoices/'.$invoice->id)}}">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Invoice</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/invoices')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Invoices</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="card-body card-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label"> Invoice Date: </label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="id" value="{{ $invoice->id }}">
                                    <input type="text" name="invoice_date" class="form-control" id="datepicker_one" value="{{$invoice->invoice_date}}">
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
                                <label class="col-sm-3 control-label"> Customer:<span class="req_star">*</span></label>
                                <div class="col-sm-7">
                                    <select class="select2 form-control" style="width:300px;" name="customer_id" id="customer_id_select2">
                                        <option value="{{$invoice->customer->id}}">{{$invoice->customer->customer_name}}</option>
                                    </select>
                                    <small style="color: #777;">Type phone or name</small>
                                    @error('customer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label">Invoice Total: <span class="req_star">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="invoice_total" id="invoice_total" value="{{$invoice->invoice_total}}" required readOnly>
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
                                <label class="col-sm-3 control-label">Invioce Note: </label>
                                <div class="col-sm-7">
                                    <textarea type="text" class="form-control" name="invoice_note">{{ $invoice->invoice_note }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label"> Payment Type:<span class="req_star">*</span></label>
                                <div class="col-sm-7">
                                    <select class="select2_payment form-control" style="width:500px;" name="payment_type">
                                        <option value="Cash" @if($invoice->payment_type == 'Cash') selected @endif>Cash</option>
                                        <option value="bKash" @if($invoice->payment_type == 'bKash') selected @endif>bKash</option>
                                        <option value="Bank" @if($invoice->payment_type == 'Bank') selected @endif>Bank</option>
                                        <option value="Cash + bKash" @if($invoice->payment_type == 'Cash + bKash') selected @endif>Cash + bKash</option>
                                        <option value="Cash + Bank" @if($invoice->payment_type == 'Cash + Bank') selected @endif>Cash + Bank</option>
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
                            <button type="button" class="btn btn-warning btn-sm btn_activateEditInvoice" required>
                                <span style="padding: 1em; font-weight: 700; font-size: 1.4em;">Click To Activate Edit Process</span>
                            </button>
                            <br><br><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <p class="bg-info text-center text-white"><span style="padding: 0.5em;; font-weight: 700; font-size: 1.4em;">If stock is less than quantity, you are not allowed to edit that record! Try to refund!</span></p>
                        </div>
                    </div>
                    {{-- click to activate ends --}}
                    {{-- invoice details starts --}}
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-striped invoice-custom-table mb-0" id="myInvoice">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Unit</th>
                                        <th>Stock</th>
                                        <th>Sale Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->invoiceDetails as $key=>$p)
                                    <tr>
                                        <td>
                                            <select class="form-control medicine_id" name="medicine_id[]" style="width:300px;"  required>
                                                <option value="{{ $p->medicine_id }}">{{$p->medicine_name}}</option>
                                            </select>
                                        </td>
                                        
                                        <td><input type="text" class="form-control unit" style="width:140px;" value="Pcs"  required readOnly></td>
                                        
                                        <td><input type="text" class="form-control stock" style="width:100px;" value="{{ $data[$key] }}" required readOnly></td>

                                        <td><input type="text" class="form-control sell_price" name="sell_price[]" style="width:140px;" value="{{ $p->sell_price }}" required readOnly></td>
                                       
                                        <td><input type="number" min="1" max="10000000" class="form-control qty" name="qty[]" style="width:100px;" value="{{ $p->qty }}" required @if($data[$key] < $p->qty) readonly @endif></td>
                                        
                                        <td><input type="text" class="form-control total_price" style="width:150px;" name="total_price[]" value="{{ $p->total_price }}" autocomplete="on" required readonly></td>
                                    </tr>
                                    @endforeach
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
                                    <input type="text" class="form-control invoice_total" id="total" value="{{ $invoice->invoice_total }}" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Discount </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control invoice_discount" name="invoice_discount" id="discount" value="{{ $invoice->invoice_discount }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Last Received Amount</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $invoice->invoice_received}}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Receive Total (add current with the last payment and type total)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="invoice_received" id="paid" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Net Total </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control invoice_nettotal" name="invoice_nettotal" id="netPaid" value="{{ $invoice->invoice_nettotal }}" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Due </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="invoice_due" id="due" value="{{ $invoice->invoice_due }}" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- boxes --}}
                </div>
                <div class="card-footer card-footer-button text-center">
                    <button type="submit" name="save_invoice" class="btn btn-submit btn-secondary waves-effect">Update</a>
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

    {{-- invoice --}}
    <script>
        $(function() {
            $(document).on("click", ".btn_activateEditInvoice", function() {

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
                
                var deleterow = (function() {
                    var executed = false;
                    return function() {
                        if (!executed) {
                            executed = true;
                            var $tbody = $("#myInvoice tbody")
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

               

                $("#myInvoice").delegate(".qty", "keyup change", function(){
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


                function calculate(paid, discount){
                    var invoice_total = 0;
                    var paid = paid;
                    var discount = $("#discount").val();

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

@endpush










{{-- $("#myInvoice").delegate(".qty", "keyup change", function(){
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
});  --}}


{{-- 
    
if(prev > current) {
    tr.find(".total_price").val(quantity.val() * tr.find(".sell_price").val());
    calculate(0,0);
}else if(prev < current) {
    Swal.fire({
        icon: 'warning',
        title: 'Warning!',
        text: "You cannot sell this medicine!",
        timer: 4000,
    });
    quantity.val(prev);
}else {
    Swal.fire({
        icon: 'warning',
        title: 'Warning!',
        text: "Stock Out!",
        timer: 4000,
    });
    quantity.val(1);
    tr.find(".total_price").val(quantity.val() * tr.find(".sell_price").val());
    calculate(0,0);
}    
    
--}}