@extends('layouts.admin')
@section('title', 'Profile')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/dropify.min.css">
    <style>
        .nav.nav-tabs > li > a.active {
            background-color: #ddd !important;
            border-bottom: 2px solid #333333 !important;
            padding-left: 12px !important;
            padding-right: 12px !important;
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Profile</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/profile') }}">Profile</a></li>
        <li class="active">Index</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Profile </h3>
                    </div>
                </div>
            </div>
         
            <div class="row">
                <div class="col-12"> 
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#profile_with_icon_title" role="tab">PROFILE</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#change_password_with_icon_title" role="tab">PASSWORD</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="profile_with_icon_title">
                                <form id="update-form" class="form-horizontal" method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group row custom-form-group">
                                        <label class="col-sm-3 control-label"> Name: <span class="req_star">*</span></label>
                                        <div class="col-sm-7">
                                            <input type="text" name="name" id="name" class="form-control" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row custom-form-group">
                                        <label class="col-sm-3 control-label"> Email: <span class="req_star">*</span></label>
                                        <div class="col-sm-7">
                                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}">
                                        </div>
                                    </div>
                                    <div class="form-group row custom-form-group">
                                        <label class="col-sm-3 control-label">Note: </label>
                                        <div class="col-sm-7">
                                            <textarea class="form-control" rows="5" name="note">{{ Auth::user()->note }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row custom-form-group custom-form-group-photo">
                                        <label class="col-sm-3 col-form-label">Photo: </label>
                                        <div class="col-sm-3">
                                            <input type="file" class="form-control dropify" data-default-file="{{asset('storage/avatar.png')}}" name="photo" value="{{old('photo')}}">
                                        </div>
                                        <div class="col-sm-6">
                                            @if(Auth::user()->profile_photo != '')
                                                <img class="table-img-100" src="{{Storage::disk('public')->url('profile/'.Auth::user()->profile_photo)}}" alt="Photo">
                                            @else
                                                <img class="table-img-100" src="{{Storage::disk('public')->url('avatar.png')}}" alt="Photo">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer card-footer-button text-center">
                                        <button type="submit" class="btn btn-submit-extra btn-secondary "> UPDATE</a>
                                    </div>
                                </form>
                            </div>
                            
                            <div role="tabpanel" class="tab-pane fade in" id="change_password_with_icon_title">
                                <form id="update-form" method="POST" action="{{ route('admin.password.update') }}" class="form-horizontal">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group row custom-form-group">
                                        <label class="col-sm-3 control-label">Old Password: <span class="req_star">*</span></label>
                                        <div class="col-sm-7">
                                            <input type="password" class="form-control" name="old_password">
                                        </div>
                                    </div>
                                    <div class="form-group row custom-form-group">
                                        <label class="col-sm-3 control-label">Password: <span class="req_star">*</span></label>
                                        <div class="col-sm-7">
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group row custom-form-group">
                                        <label class="col-sm-3 control-label">Confirm-Password: <span class="req_star">*</span></label>
                                        <div class="col-sm-7">
                                            <input type="password" class="form-control" name="pass_confirm">
                                        </div>
                                    </div>
                                    <div class="card-footer card-footer-button text-center">
                                        <button type="submit" class="btn btn-submit-extra btn-secondary"> UPDATE</a>
                                    </div>
                                </form>
                            </div>
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
    <script src="{{asset('contents/admin')}}/assets/js/dropify.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/dropify.js"></script>
    
@endpush




























