@extends('layouts.admin')
@section('title', 'Users')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/dropify.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Users</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/users') }}">Users</a></li>
        <li class="active">Create</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="add-form" class="form-horizontal" method="post" action="{{url('admin/users')}}" enctype="multipart/form-data">
          @csrf
          <div class="card">
              <div class="card-header card-header-part">
                  <div class="row">
                      <div class="col-md-8">
                          <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> User Registration</h3>
                      </div>
                      <div class="col-md-4 text-right">
                          <a href="{{url('admin/users')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Users</a>
                      </div>
                      <div class="clearfix"></div>
                  </div>
              </div>
              <div class="card-body card-form">
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">Name: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="name" value="{{old('name')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">Phone: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="phone" value="{{old('phone')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">Username: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="username" value="{{old('username')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">Email: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="email" class="form-control" name="email" value="{{old('email')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">Password: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="password" class="form-control" name="password" value="">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">Confirm-Password: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="password" class="form-control" name="password_confirmation" value="">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">User Role: <span class="req_star">*</span></label>
                    <div class="col-sm-4">
                        <select class="select2 form-control" name="role">
                            <option></option>
                            @foreach($allRole as $urole)
                                <option value="{{$urole->id}}">{{$urole->role_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">National ID: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="nid" value="{{old('nid')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">Current Address: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                        <textarea type="text" class="form-control" name="cadd">{{old('cadd')}}</textarea>
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">Permanent Address: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                        <textarea type="text" class="form-control" name="padd">{{old('padd')}}</textarea>
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label">Note: </label>
                    <div class="col-sm-7">
                      <textarea type="text" class="form-control" name="note">{{old('note')}}</textarea>
                    </div>
                </div>
                <div class="form-group row custom-form-group custom-form-group-photo">
                    <label class="col-sm-3 col-form-label">Photo: </label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control dropify" data-default-file="{{Storage::disk('public')->url('avatar.png')}}" name="photo" value="{{old('photo')}}">
                    </div>
                </div>
              </div>
              <div class="card-footer card-footer-button text-center">
                  <button type="submit" class="btn btn-submit btn-secondary waves-effect">REGISTRATION</a>
              </div>
          </div>
        </form>
    </div>
</div>
@endsection
@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/dropify.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/dropify.js"></script>

    <script>
        $(function() {
            $(".select2").select2({
                width: "100%",
                placeholder: "Choose Any",
            });
        });
    </script>
@endpush