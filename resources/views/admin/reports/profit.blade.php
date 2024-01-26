@extends('layouts.admin')
@section('title', 'Profit Statement')

@push('css')
    <style>
        .font-profit th {
            font-size: 16px !important;
        }
        .font-profit td {
            font-size: 16px !important;
        }
        .font-profit span {
            font-size: 16px !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title"> Profit Statement </h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/reports/profits') }}"> Profit Statement </a></li>
        <li class="active">index</li>
    </ol>
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        @if(auth()->user()->role_id <= 2)
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Profit Statement For {{ request('branch') }}</h3>
                        @else
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Profit Statement For {{ $branch->branch_code }}</h3>
                        @endif
                    </div>
                    <div class="col-md-4 text-right">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.reports.profitSearch') }}" method="get">
                            {{-- @csrf --}}
                            <div class="row">
                                @if (auth()->user()->role_id <= 2)
                                <div class="col-sm-2">
                                    @if(request('branch') == 'All')
                                        <select class="select2 form-control" name="branch">
                                            <option value="All" selected>ALL</option>
                                            @foreach($branches as $bran)
                                                <option value="{{$bran->branch_code}}">{{$bran->branch_code}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="select2 form-control" name="branch">
                                            <option value="All">ALL</option>
                                            @foreach($branches as $bran)
                                                <option value="{{$bran->branch_code}}" @if($bran->id == $branch->id) selected @endif>{{$bran->branch_code}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                @endif
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-calendar" style="padding-right: 0.7em;"></i> <span> From</span>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="from" id="datepicker_one" value="{{ request('from') }}">
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
                                        <input type="text" class="form-control" name="to" id="datepicker_two" value="{{ request('to') }}">
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
                            @if (request('branch') == 'All' && auth()->user()->role_id <= 2)
                            <h4>{{ $setting->ph_name }}</h4>
                            <h4>{{ $setting->ph_address }}</h4>
                            <h4> Print Date: {{Carbon\Carbon::now()->format('d M, Y | h:i:s a') }}</h4>
                            @else
                            <h4>{{ $branch->branch_title }}</h4>
                            <h4>{{ $branch->branch_address }}</h4>
                            <h4> Print Date: {{Carbon\Carbon::now()->format('d M, Y | h:i:s a') }}</h4>
                            <h4> Branch Operator : {{ $dispenser }}</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
         
            <div class="row">
                <div class="col-12"> 
                    <div class="card-body">
                        <div class="table-responsive font-profit">
                            <table class="table table-bordered table-striped custom-table mb-0" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">Purchases</th>
                                        <th class="text-center">Expenses</th>
                                        <th class="text-center">Refunds</th>
                                        <th class="text-center">Sales</th>
                                        <th class="text-center">Profit</th>
                                        <th class="text-center">Loss</th>
                                        <th class="text-center">Neutral</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                    <tr>
                                        <td class="text-center">{{ $purchaseTotal }}</td>   
                                        <td class="text-center">{{ $expenseTotal }}</td>   
                                        <td class="text-center">{{ $refundTotal }}</td>   
                                        <td class="text-center">{{ $saleTotal }}</td>
                                        @if($saleTotal != $allGoing)

                                            @if ($saleTotal > $allGoing)
                                            <td class="text-center"> <span class="badge badge-success"> {{ $saleTotal - $allGoing }} <span> </td>
                                            @else
                                            <td class="text-center"> 0.00 </td>
                                            @endif    

                                            @if ($saleTotal < $allGoing)
                                            <td class="text-center"> <span class="badge badge-danger">{{ $allGoing - $saleTotal }} </span> </td>
                                            @else
                                            <td class="text-center"> 0.00 </td>
                                            @endif      

                                            <td class="text-center"> 0.00 </td>
                                                

                                        @else
                                            <td class="text-center"> 0.00 </td>
                                            <td class="text-center"> 0.00 </td>
                                            <td class="text-center"> <span class="badge badge-info"> Neutral </span> </td>
                                        @endif


                                    </tr>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
         
            <div class="card-footer card-footer-part" style="background-color: #fff">
                <div class="row" style="padding-bottom: 5em; padding-left: 2em;">
                    <div class="col-md-2 col-sm-2 offset-md-10 offset-sm-10">

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






























