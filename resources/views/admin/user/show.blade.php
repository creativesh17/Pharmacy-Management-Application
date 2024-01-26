@extends('layouts.admin')
@section('title', 'Users')


@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-toggle.min.css">    
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Users</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/users') }}">Users</a></li>
        <li class="active">View</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part d-print-none">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> View User Information</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin/users')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-plus-circle"></i> All Users</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2" style="margin-right: 10px;">
                        <div>
                            @if($user->photo != '')
                                <img class="table-img-200" src="{{Storage::disk('public')->url('users/'.$user->photo)}}" alt="photo"/>
                            @else
                                <img class="table-img-200" src="{{Storage::disk('public')->url('avatar.png')}}" alt="photo" />
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8" style="margin-left: 10px;">
                        <table class="table table-bordered table-striped table-hover custom-view-table">
                            <tr>
                                <td>Name</td>
                                <td>:</td>
                                <td>{{$user->name}}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>:</td>
                                <td>{{$user->phone}}</td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td>:</td>
                                <td>{{$user->username}}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td>{{$user->email}}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if ($user->status == 1)
                                        <span class="badge bg-info status-color-active">Active</span>
                                    @else
                                        <span class="badge bg-danger status-color-inactive">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>User Role</td>
                                <td>:</td>
                                <td>{{$user->role->role_name}}</td>
                            </tr>
                            <tr>
                                <td>National ID</td>
                                <td>:</td>
                                <td>{{$user->nid}}</td>
                            </tr>
                            <tr>
                                <td>Current Address</td>
                                <td>:</td>
                                <td>{{$user->current_address}}</td>
                            </tr>
                            <tr>
                                <td>Permanent Address</td>
                                <td>:</td>
                                <td>{{$user->permanent_address}}</td>
                            </tr>
                            <tr>
                                <td>Registration Time</td>
                                <td>:</td>
                                <td>{{$user->created_at->format('d-m-Y | h:i:s a')}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
            <div class="card-footer card-footer-part d-print-none">
                 <a href="#" class="btn btn-primary" onclick="javascript:window.print();">PRINT</a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap-toggle.min.js"></script>
@endpush


