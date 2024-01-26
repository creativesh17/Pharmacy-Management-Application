@extends('layouts.admin')
@section('title', 'Expense Report')

@push('css')
    <style>
        table thead th:nth-child(1) { width: 10%; }
        table thead th:nth-child(2) { width: 20%; }
        table thead th:nth-child(3) { width: 10%; }
        table thead th:nth-child(4) { width: 10%; }
        table thead th:nth-child(5) { width: 18%; }
    </style>
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Expense Reports</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/reports/expenses') }}">Expense Reports</a></li>
        <li class="active">index</li>
    </ol>
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Today's Expense Report</h3>
                    </div>
                    <div class="col-md-4 text-right">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.reports.expensesearch') }}" method="get">
                            {{-- @csrf --}}
                            <div class="row">
                                @if (auth()->user()->role_id <= 2)
                                <div class="col-sm-2">
                                    <select class="select2 form-control" name="branch">
                                        <option value="All">ALL</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->branch_code}}">{{$branch->branch_code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-calendar" style="padding-right: 0.7em;"></i> <span> From</span>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="from" id="datepicker_one">
                                        @error('from')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-calendar" style="padding-right: 0.7em;"></i> <span> To</span>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="to" id="datepicker_two">
                                        @error('to')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary" name="action" value="search">Search</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary" name="action" value="pdf">Download PDF or Print</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" style="background-color: #fff; margin-top: 20px;">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="text-center">
                            @if (auth()->user()->role_id <= 2)
                            <h4>{{ $setting->ph_name }}</h4>
                            <h4>{{ $setting->ph_address }}</h4>
                            <h4> Print Date: {{Carbon\Carbon::now()->format('d M, Y | h:i:s a') }}</h4>
                            @else
                            <h4>{{ $branch->branch_title }}</h4>
                            <h4>{{ $branch->branch_address }}</h4>
                            <h4> Print Date: {{Carbon\Carbon::now()->format('d M, Y | h:i:s a') }}</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
         
            <div class="row">
                <div class="col-12"> 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped custom-table mb-0" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Branch</th>
                                        <th class="text-right">Net Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($todayExpense as $key=>$data)
                                    <tr>
                                        <td>{{ $todayExpense->firstItem() + $key }}</td>
                                        <td>{{ $data->expense_date }}</td>   
                                        <td>{{ $data->branch->branch_code }}</td>   
                                        <td class="text-right">{{ $data->expense_amount }}</td>   
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        {{-- <td colspan="4"></td> --}}
                                        <td colspan="3" class="text-right" style="font-weight: 700; font-size: 20px;"> Net Total </td>
                                        <td class="text-right" style="font-weight: 700; font-size: 20px;"> {{ $todayExpenseTotal }} </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer card-footer-part" style="background-color: #fff">
                <div class="row" style="padding-bottom: 5em; padding-left: 2em;">
                    <div class="col-md-2 col-sm-2 offset-md-10 offset-sm-10">
                        @if (auth()->user()->role_id <= 2)
                            {{ $todayExpense->appends(['branch' => request('branch'), 'from' => request('from'), 'to' => request('to'), 'action' => 'search'])->links() }}
                        @else 
                            {{ $todayExpense->appends(['from' => request('from'), 'to' => request('to'), 'action' => 'search'])->links() }}
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-2 offset-md-10 offset-sm-10">
                       <div class="text-right" style="border-top: 1px solid #ddd;">
                           <h4 style="padding-right: 2em;">Signature</h4>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- </div> --}}

@endsection

@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function() {
            $(".select2").select2({
                width: "100%",
                placeholder: "Choose Branch",
            });
        });
    </script>
@endpush






























