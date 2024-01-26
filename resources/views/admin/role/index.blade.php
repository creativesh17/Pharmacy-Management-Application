@extends('layouts.admin')
@section('title', 'Role')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-toggle.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Roles</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/roles') }}">Roles</a></li>
        <li class="active">Index</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Role Information</h3>
                    </div>
                </div>
            </div>
         
            <div class="row">
                <div class="col-12"> 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped custom-table mb-0" id="dataTable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @foreach ($allRole as $key=>$role)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $role->role_name }}</td>
                                        <td>
                                            @if ($role->role_status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-pink">Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('admin.role.edit', $role->id)}}"><i class="fa fa-pencil-square fa-lg edit-icon"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
         
            <div class="card-footer card-footer-part">
                <div class="row">
                    <div class="col-5">
                        
                    </div>
                    <div class="col-6">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
