@extends('layouts.admin')
@section('title', 'Refunds')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">

    <style>
        table tbody td:nth-child(2) { width: 20%;  }
        table tbody td:nth-child(3) { width: 7%;  }
        table tbody td:nth-child(4) { width: 9%;  }
        table tbody td:nth-child(5) { width: 9%;  }
        table tbody td:nth-child(5) { width: 10%;  }
        .cursor-not {
            cursor: not-allowed;
        }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Refunds</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/refunds') }}">Refunds</a></li>
        <li class="active">Edit</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" method="post" action="{{url('admin/refunds/'.$refund->id)}}">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Refund</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/refunds')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Refunds</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="card-body card-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label"> Refund Date: </label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="id" value="{{ $refund->id }}">
                                    <input type="text" name="refund_date" class="form-control" id="datepicker_one" value="{{$refund->refund_date}}" required>
                                    @error('refund_date')
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
                                    <select class="select2 form-control" style="width:300px;" required disabled>
                                        <option value="{{$refund->customer->id}}">{{$refund->customer->customer_name}}</option>
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
                                <label class="col-sm-3 control-label">Refund Total: <span class="req_star">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control cursor-not refund_total" name="refund_total" value="{{$refund->refund_total}}" required readOnly>
                                    @error('refund_total')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label">Refund Note: </label>
                                <div class="col-sm-7">
                                    <textarea type="text" class="form-control" name="refund_note">{{ $refund->refund_note }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row custom-form-group">
                                <label class="col-sm-3 control-label"> Payment Type:<span class="req_star">*</span></label>
                                <div class="col-sm-7">
                                    <select class="select2_payment form-control" style="width:500px;" name="payment_type">
                                        <option value="Cash" @if($refund->payment_type == 'Cash') selected @endif>Cash</option>
                                        <option value="bKash" @if($refund->payment_type == 'bKash') selected @endif>bKash</option>
                                        <option value="Bank" @if($refund->payment_type == 'Bank') selected @endif>Bank</option>
                                        <option value="Cash + bKash" @if($refund->payment_type == 'Cash + bKash') selected @endif>Cash + bKash</option>
                                        <option value="Cash + Bank" @if($refund->payment_type == 'Cash + Bank') selected @endif>Cash + Bank</option>
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
                        <div class="col-4 offset-4" align="center"">
                            <button type="button" class="btn btn-warning btn-sm btn_editRefund" required>
                                <span style="padding: 1em; font-weight: 700; font-size: 1.4em;">Click To Activate Refund Process</span>
                            </button>
                            <br><br><br>
                        </div>
                    </div>
                    {{-- click to activate ends --}}

                    {{-- invoice details starts --}}
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-striped invoice-custom-table mb-0" id="myRefund">
                                <thead>
                                    <tr>
                                        <th style="display: none;"></th>
                                        <th>Name</th>
                                        <th>Unit</th>
                                        <th>Sold Qty</th>
                                        <th>Sale Price</th>
                                        <th>Total</th>
                                        <th>Total</th>
                                        <th>Refund Qty</th>
                                        <th>Refund Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($refundDetails as $key=>$p)
                                    <tr>
                                        <td style="display: none;">
                                            <input type="hidden" class="medicine_id" name="medicine_id[]" value="{{ $p->medicine_id }}" required readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control medicine_name" name="medicine_name[]" value="{{ $p->medicine_name }}" style="width:300px;" required readOnly>
                                        </td>
                                        <td><input type="text" class="form-control unit" style="width:140px;" value="Pcs"  required readOnly></td>
                                        
                                        <td><input type="number" min="1" max="10000000" class="form-control sold_qty" name="sold_qty[]" style="width:100px;" value="{{ $p->sold_qty }}" required readonly></td>

                                        <td><input type="text" class="form-control sell_price" name="sell_price[]" value="{{ $p->sell_price }}" required readOnly></td>

                                        <td><input type="text" class="form-control sell_price" name="sold_qty_sell_price[]" value="{{ $p->sold_qty * $p->sell_price }}" required readOnly></td>


                                        <td><input type="text" class="form-control" value="{{ $p->total_price }}" required readonly></td>

                                        <td><input type="number" min="0" max="10000000" class="form-control refund_qty" name="refund_qty[]" style="width:100px;" value="{{ $p->refund_qty }}" required></td>

                                        <td><input type="text" class="form-control total_price" name="total_price[]" value="{{ $p->total_price }}" required readonly></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br><br><br>

                    {{-- refund details ends --}}
                    {{-- boxes --}}
                    <div class="card-body">
                        <div class="col-md-10 offset-md-1">
                            <div class="form-group row">
                                <label for=""><strong>Total</strong></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control cursor-not" id="total" value="{{ $refund->refund_total }}" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for=""><strong>Cut</strong></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="refund_cut" id="cut" value="{{ $refund->refund_cut }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for=""><strong>Net Total</strong></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control cursor-not" name="refund_nettotal" id="netPaid" value="{{ $refund->refund_nettotal }}" readonly required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for=""><strong>Refund Amount</strong></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span>Tk</span>
                                    </div>
                                    <input type="text" class="form-control" name="refund_paid" id="paid" value="{{ $refund->refund_paid }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- boxes --}}
                </div>
                <div class="card-footer card-footer-button text-center">
                    <button type="submit" class="btn btn-submit btn-secondary waves-effect">Update</a>
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
            });

            $('.select2_payment').select2({
                width: "100%",
                placeholder: 'Choose Any',
            });

        });
    </script>

    <script>
        $(function() {
            $(document).on("click", ".btn_editRefund", function() {
                var html = '';

                $("#myRefund").append(html);

                $(this).attr('disabled', 'disabled');
                $("#activate_div_remove").attr('style', 'display: none;');


                $("#myRefund").delegate(".refund_qty", "keyup change", function(){
                    var quantity = $(this);
                    var tr = $(this).parent().parent();
                    if((quantity.val()-0) > (tr.find(".sold_qty").val()-0)){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning!',
                            text: "Returned Quantity Can not be bigger than sold quantity!",
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


                function calculate(paid, cut){
                    var refund_total = 0;
                    var paid = paid;
                    var cut = cut;

                    $(".total_price").each(function() {
                        refund_total = refund_total + ($(this).val()*1);
                    })
                    

                    if(cut > refund_total){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning!',
                            text: "Calculation Mismatch!",
                            timer: 3000,
                        });
                        cut = 0;
                    }


                    $(".refund_total").val(refund_total);
                    $("#total").val(refund_total);
                    $("#cut").val(cut);

                    netPaid = $("#total").val() - cut;
                    $("#netPaid").val(netPaid);

                }

                $("#paid").keyup(function(){
                    var paid = $(this).val();
                    var cut = $("#cut").val();
                    calculate(paid, cut);
                    var netPaid = $(this).val() - $("#cut").val();

                });

                $("#cut").keyup(function(){
                    var cut = $(this).val();
                    var paid = $("#paid").val();
                    calculate(paid, cut);
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







