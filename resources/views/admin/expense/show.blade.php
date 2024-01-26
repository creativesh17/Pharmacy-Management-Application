@extends('layouts.admin')
@section('title', 'Expense')


@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-toggle.min.css">    
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Expenses</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/expenses') }}">Expenses</a></li>
        <li class="active">View</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> View Expense Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin/expenses')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-plus-circle"></i> All Expenses</a>
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
                                <td>Expense Details</td>
                                <td>:</td>
                                <td>{{$expense->expense_details}}</td>
                            </tr>
                            <tr>
                                <td>Expense Category</td>
                                <td>:</td>
                                <td>{{$expense->expensecategory->expcate_name}}</td>
                            </tr>
                            <tr>
                                <td>Expense Date</td>
                                <td>:</td>
                                <td>{{$expense->expense_date}}</td>
                            </tr>
                            <tr>
                                <td>Expense Amount</td>
                                <td>:</td>
                                <td>{{$expense->expense_amount}}</td>
                            </tr>
                            <tr>
                                <td>Branch</td>
                                <td>:</td>
                                <td>{{$expense->branch->branch_code}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
            <div class="card-footer card-footer-part">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap-toggle.min.js"></script>
@endpush

