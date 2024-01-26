@extends('layouts.admin')
@section('title', 'Dashboard')


@push('css')
    <style>
        .card .card-header.dashboad-card-header {
            padding: 10px 20px;
            border: none;
            background: #2b4049;
        }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Dasboard</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin') }}">Dashboard</a></li>
        <li class="active">Home</li>
    </ol>
@endcomponent

<div class="row">
    <div class="col-sm-3 col-md-3 col-xl-3">
        <div class="mini-stat clearfix bx-shadow bg-white">
            <span class="mini-stat-icon bg-primary"><i class="md md-book"></i></span>
            <div class="mini-stat-info text-right text-dark mini_stat_info">
                <span class="counter text-dark">{{ $allSales }}</span>
                Sales Today
            </div>
        </div>
    </div>
    <div class="col-sm-3 col-md-3 col-xl-3">
        <div class="mini-stat clearfix bx-shadow bg-white">
            <span class="mini-stat-icon bg-primary"><i class="md md-wallet-membership"></i></span>
            <div class="mini-stat-info text-right text-dark mini_stat_info">
                <span class="counter text-dark">{{ $allPurchases }}</span>
                Purchases Today
            </div>
        </div>
    </div>
    <div class="col-sm-3 col-md-3 col-xl-3">
        <div class="mini-stat clearfix bx-shadow bg-white">
            <span class="mini-stat-icon bg-primary"><i class="md md-pages"></i></span>
            <div class="mini-stat-info text-right text-dark mini_stat_info">
                <span class="counter text-dark">{{ $allRefunds }}</span>
                Refunds Today
            </div>
        </div>
    </div>
    <div class="col-sm-3 col-md-3 col-xl-3">
        <div class="mini-stat clearfix bx-shadow bg-white">
            <span class="mini-stat-icon bg-primary"><i class="md md-view-stream"></i></span>
            <div class="mini-stat-info text-right text-dark mini_stat_info">
                <span class="counter text-dark">{{ $allExpenses }}</span>
                Expenses Today
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8 col-md-8 col-xl-8">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-xl-6">
                    <div class="mini-stat clearfix bx-shadow bg-white">
                        <span class="mini-stat-icon bg-primary"><i class="md md-view-stream"></i></span>
                        <div class="mini-stat-info text-right text-dark mini_stat_info">
                            <span class="counter text-dark">{{ $allPurchaseExpense }}</span>
                            Today's Purchases and Expenses
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-xl-6">
                    <div class="mini-stat clearfix bx-shadow bg-white">
                        <span class="mini-stat-icon bg-primary"><i class="md md-view-stream"></i></span>
                        <div class="mini-stat-info text-right text-dark mini_stat_info">
                            <span class="counter text-dark">{{ $allUsers }}</span>
                            Users
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-xl-6">
                    <div class="mini-stat clearfix bx-shadow bg-white">
                        <span class="mini-stat-icon bg-primary"><i class="md md-view-stream"></i></span>
                        <div class="mini-stat-info text-right text-dark mini_stat_info">
                            <span class="counter text-dark">{{ $allStaffs }}</span>
                            Staffs
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-xl-6">
                    <div class="mini-stat clearfix bx-shadow bg-white">
                        <span class="mini-stat-icon bg-primary"><i class="md md-view-stream"></i></span>
                        <div class="mini-stat-info text-right text-dark mini_stat_info">
                            <span class="counter text-dark">{{ $allSuppliers }}</span>
                            Suppliers
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-xl-12">
                    {!! $chartBarMonth->container() !!}
                </div>
                <div class="col-sm-12 col-md-12 col-xl-12">
                    {!! $chartBarYear->container() !!}
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-xl-4">
            <div class="row">
                {{-- Last 7 Days Report --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header dashboad-card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="card-title" style="color: #fff; font-size: 16px;"><i class="fa fa-gg-circle"></i> Last 7 Days Report</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <th> Sales</th>
                                        <th>{{ $sevenSales }}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchases</th>
                                        <th>{{ $sevenPurchases }}</th>
                                    </tr>
                                    <tr>
                                        <th> Refunds</th>
                                        <th>{{ $sevenRefunds }}</th>
                                    </tr>
                                    <tr>
                                        <th> Expenses</th>
                                        <th>{{ $sevenExpenses }}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchases and Expenses</th>
                                        <th>{{ $sevenPurchaseExpense  }}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchases and Expenses with Refunds</th>
                                        <th>{{ $sevenPurchaseExpense + $sevenRefunds}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Last 30 Days Report --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header dashboad-card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="card-title" style="color: #fff; font-size: 16px;"><i class="fa fa-gg-circle"></i> Last 30 Days Report</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <th> Sales</th>
                                        <th>{{ $monthSales }}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchases</th>
                                        <th>{{ $monthPurchases }}</th>
                                    </tr>
                                    <tr>
                                        <th> Refunds</th>
                                        <th>{{ $monthRefunds }}</th>
                                    </tr>
                                    <tr>
                                        <th> Expenses</th>
                                        <th>{{ $monthExpenses }}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchases and Expenses</th>
                                        <th>{{ $monthPurchaseExpense }}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchases and Expenses with Refunds</th>
                                        <th>{{ $monthPurchaseExpense + $monthRefunds}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                 {{-- Last 365 Days Report --}}
                 <div class="col-12">
                    <div class="card">
                        <div class="card-header dashboad-card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="card-title" style="color: #fff; font-size: 16px;"><i class="fa fa-gg-circle"></i> Last 365 Days Report</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <th> Sales</th>
                                        <th>{{ $yearSales }}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchases</th>
                                        <th>{{ $yearPurchases }}</th>
                                    </tr>
                                    <tr>
                                        <th> Refunds</th>
                                        <th>{{ $yearRefunds }}</th>
                                    </tr>
                                    <tr>
                                        <th> Expenses</th>
                                        <th>{{ $yearExpenses }}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchases and Expenses</th>
                                        <th>{{ $yearPurchaseExpense }}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchases and Expenses with Refunds</th>
                                        <th>{{ $allYearPurchaseExpRefund }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>    

{!! $chartBarMonth->script() !!}
{!! $chartBarYear->script() !!}
@endsection

@push('js')
<script src="{{asset('contents/admin')}}/assets/js/Chart.min.js" charset="utf-8"></script>
@endpush