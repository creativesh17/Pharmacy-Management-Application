@extends('layouts.admin')
@section('title', 'Medicines')


@push('css')
       
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Medicines</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/medicines') }}">Medicines</a></li>
        <li class="active">View</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> View Medicine Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin/medicines')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-plus-circle"></i> All Medicines</a>
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
                                <td>Name</td>
                                <td>:</td>
                                <td>{{$medicine->med_name}}</td>
                            </tr>
                            <tr>
                                <td>Generic Name</td>
                                <td>:</td>
                                <td>{{$medicine->generic_name}}</td>
                            </tr>
                            <tr>
                                <td>Medicine Category</td>
                                <td>:</td>
                                <td>{{optional($medicine->category)->cate_name}}</td>
                            </tr>
                            <tr>
                                <td>Manufacturer</td>
                                <td>:</td>
                                <td>{{optional($medicine->manufacturer)->manu_name}}</td>
                            </tr>
                            <tr>
                                <td>Sale Price</td>
                                <td>:</td>
                                <td>{{optional($medicine)->sell_price}}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if ($medicine->med_status == 1)
                                        <span class="badge bg-info status-color-active">Active</span>
                                    @else
                                        <span class="badge bg-danger status-color-inactive">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Added Time</td>
                                <td>:</td>
                                <td>{{$medicine->created_at->format('d-m-Y | h:i:s a')}}</td>
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
    
@endpush

