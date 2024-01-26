@extends('layouts.admin')
@section('title', 'Recycle Bin')

@push('css')
    
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Recycle Bin</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/recycle') }}">Recycle Bin</a></li>
        <li class="active">Index</li>
    </ol>
@endcomponent

<div class="row">
    <div class="col-12">
        <!--Widget-4 -->
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-users"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allUser < 10)
                                <span>0</span>{{$allUser}}
                            @else
                                {{$allUser}}
                            @endif
                        </span>
                        Total {{ Str::plural('User', $allUser) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/users') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed User</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-dashcube"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allBranch < 10)
                                <span>0</span>{{$allBranch}}
                            @else
                                {{$allBranch}}
                            @endif
                        </span>
                        Total {{ Str::plural('Branch', $allBranch) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/branches') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Branch</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-street-view"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allSupplier < 10)
                                <span>0</span>{{$allSupplier}}
                            @else
                                {{$allSupplier}}
                            @endif
                        </span>
                        Total {{ Str::plural('Supplier', $allSupplier) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/suppliers') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Suppliers</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-archive"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allMedCategory < 10)
                                <span>0</span>{{$allMedCategory}}
                            @else
                                {{$allMedCategory}}
                            @endif
                        </span>
                        Total {{ Str::plural('Medicine Category', $allMedCategory) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/medcategories') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Medicine Categories</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-medkit"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allMedicine < 10)
                                <span>0</span>{{$allMedicine}}
                            @else
                                {{$allMedicine}}
                            @endif
                        </span>
                        Total {{ Str::plural('Medicine', $allMedicine) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/medicines') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Medicines</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-folder"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allPurchase < 10)
                                <span>0</span>{{$allPurchase}}
                            @else
                                {{$allPurchase}}
                            @endif
                        </span>
                        Total {{ Str::plural('Purchase', $allPurchase) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/purchases') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Purchases</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-circle"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allCustomer < 10)
                                <span>0</span>{{$allCustomer}}
                            @else
                                {{$allCustomer}}
                            @endif
                        </span>
                        Total {{ Str::plural('Customer', $allCustomer) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/customers') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Customers</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-file-o"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allInvoice < 10)
                                <span>0</span>{{$allInvoice}}
                            @else
                                {{$allInvoice}}
                            @endif
                        </span>
                        Total {{ Str::plural('Invoice', $allInvoice) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/invoices') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Invoices</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-building-o"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allRefund < 10)
                                <span>0</span>{{$allRefund}}
                            @else
                                {{$allRefund}}
                            @endif
                        </span>
                        Total {{ Str::plural('Refund', $allRefund) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url("admin/recycle/refunds") }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Refunds</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-user-plus"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allStaff < 10)
                                <span>0</span>{{$allStaff}}
                            @else
                                {{$allStaff}}
                            @endif
                        </span>
                        Total {{ Str::plural('Staff', $allStaff) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/staffs') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Staffs</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-cube"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allExpCategory < 10)
                                <span>0</span>{{$allExpCategory}}
                            @else
                                {{$allExpCategory}}
                            @endif
                        </span>
                        Total Expense {{ Str::plural('Category', $allExpCategory) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/expensecategories') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Expense Categories</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-adjust"></i></span>
                    <div class="mini-stat-info text-right text-dark mb-3">
                        <span class="counter text-dark">
                            @if ($allExpense < 10)
                                <span>0</span>{{$allExpense}}
                            @else
                                {{$allExpense}}
                            @endif
                        </span>
                        Total {{ Str::plural('Expense', $allExpense) }}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="stats text-center">
                                    <a href="{{ url('admin/recycle/expenses') }}"><i class="fa fa-share-square fa-lg"></i>View All Trashed Expenses</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
      
        </div> <!-- End row-->
    </div>
</div>
{{-- </div> --}}

@endsection

@push('js')
    
   
@endpush


