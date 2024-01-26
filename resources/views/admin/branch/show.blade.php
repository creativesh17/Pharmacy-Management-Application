@extends('layouts.admin')
@section('title', 'Branch')


@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-toggle.min.css">    
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Branches</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/branches') }}">Branches</a></li>
        <li class="active">View</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> View Branch Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin/branches')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-plus-circle"></i> All Branch</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8 m-auto">
                        <table class="table table-bordered table-striped table-hover custom-view-table">
                            <tr>
                                <td>Title</td>
                                <td>:</td>
                                <td>{{$branch->branch_title}}</td>
                            </tr>
                            <tr>
                                <td>Start Date</td>
                                <td>:</td>
                                <td>{{$branch->created_at->format('Y-m-d')}}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>:</td>
                                <td>{{$branch->branch_phone}}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>:</td>
                                <td>{{$branch->branch_address}}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if ($branch->branch_status == 1)
                                        <span class="badge bg-info status-color-active">Active</span>
                                    @else
                                        <span class="badge bg-danger status-color-inactive">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Note</td>
                                <td>:</td>
                                <td>{{$branch->branch_note}}</td>
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

