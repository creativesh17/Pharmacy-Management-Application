@extends('layouts.admin')
@section('title', 'Branch')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">

@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Branches</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/branches') }}">Branches</a></li>
        <li class="active">Update</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="update-form" class="form-horizontal" method="post" action="{{url('admin/branches/'.$branch->id)}}">
          @csrf
          @method('PUT')
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Branch </h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/branches')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Branch</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="card-body card-form">
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Title: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="branch_title" value="{{$branch->branch_title}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Branch Start Date: </label>
                        <div class="col-sm-7">
                            <input type="text" name="branch_start_date" class="form-control" id="datepicker_one" value="{{$branch->branch_start_date}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Code: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="branch_code" value="{{$branch->branch_code}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Supervisor: <span class="req_star">*</span></label>
                        <div class="col-sm-4">
                            <select class="select2 form-control" name="user_id" id="select2select">
                                <option></option>
                                @foreach($allUser as $user)
                                    @if($user->role_id == 3 && $user->branch == '' || $user->branch != '')
                                        <option value="{{$user->id}}" {{ ($branch->user_id == $user->id) ? 'selected':'' }}>{{$user->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Phone: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="branch_phone" value="{{$branch->branch_phone}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Address: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="branch_address">{{$branch->branch_address}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Note: </label>
                        <div class="col-sm-7">
                        <textarea type="text" class="form-control" name="branch_note">{{$branch->branch_note}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer card-footer-button text-center">
                    <button type="submit" class="btn btn-submit btn-secondary waves-effect">Update</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap-datepicker.min.js"></script>
@endpush


{{-- {{ $errors->has('photo') ? ' has-error' : '' }} --}}
{{-- @if($errors->has('photo'))
    <span class="invalid-feedback" role="alert">
        <strong>{{$errors->first('photo')}}</strong>
    </span>
@endif --}}