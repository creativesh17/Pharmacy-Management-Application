@extends('layouts.admin')
@section('title', 'Staffs')


@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-toggle.min.css">    
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Staffs</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/staffs') }}">Staffs</a></li>
        <li class="active">View</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> View Staff Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin/staffs')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-plus-circle"></i> All Staffs</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div>
                            @if($staff->photo != '')
                                <img class="table-img-200" src="{{Storage::disk('public')->url('staffs/'.$staff->photo)}}" alt="photo"/>
                            @else
                                <img class="table-img-200" src="{{Storage::disk('public')->url('avatar.png')}}" alt="photo" />
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered table-striped table-hover custom-view-table">
                            <tr>
                                <td>Name</td>
                                <td>:</td>
                                <td>{{$staff->name}}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>:</td>
                                <td>{{$staff->phone}}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td>{{$staff->email}}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if ($staff->status == 1)
                                        <span class="badge bg-info status-color-active">Active</span>
                                    @else
                                        <span class="badge bg-danger status-color-inactive">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Branch</td>
                                <td>:</td>
                                <td>{{$staff->branch->branch_code}}</td>
                            </tr>
                            <tr>
                                <td>National ID</td>
                                <td>:</td>
                                <td>{{$staff->nid}}</td>
                            </tr>
                            <tr>
                                <td>Current Address</td>
                                <td>:</td>
                                <td>{{$staff->current_address}}</td>
                            </tr>
                            <tr>
                                <td>Permanent Address</td>
                                <td>:</td>
                                <td>{{$staff->permanent_address}}</td>
                            </tr>
                            <tr>
                                <td>Joining Date</td>
                                <td>:</td>
                                <td>{{$staff->joining_date}}</td>
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


