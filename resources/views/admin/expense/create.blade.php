@extends('layouts.admin')
@section('title', 'Expense')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Expenses</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/expenses') }}">Expenses</a></li>
        <li class="active">Create</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="add-form" class="form-horizontal" method="post" action="{{url('admin/expenses')}}" enctype="multipart/form-data">
          @csrf
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Add Expense</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/expenses')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Expenses</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="card-body card-form">
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Expense Details: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="expense_details" value="{{old('expense_details')}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Expense Category: <span class="req_star">*</span></label>
                        <div class="col-sm-4">
                            <select class="select2 form-control" name="expensecategory_id">
                                <option></option>
                                @foreach($allExpcate as $expCate)
                                    <option value="{{$expCate->id}}">{{$expCate->expcate_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Expense Amount: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="expense_amount" value="{{old('expense_amount')}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Expense Date: </label>
                        <div class="col-sm-4">
                            <input type="text" name="expense_date" class="form-control" id="datepicker_one" value="{{old('expense_date')}}">
                        </div>
                    </div>
                </div>
                <div class="card-footer card-footer-button text-center">
                    <button type="submit" class="btn btn-submit btn-secondary waves-effect"> Add </a>
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
            $(".select2").select2({
                width: "100%",
                placeholder: "Choose Any",
            });
        });
    </script>
@endpush