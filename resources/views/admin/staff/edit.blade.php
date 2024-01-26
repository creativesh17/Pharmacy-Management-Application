@extends('layouts.admin')
@section('title', 'Staffs')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/dropify.min.css">
    {{-- <style>
        .disable-manual{
            cursor: not-allowed;
        }
    </style> --}}
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Staffs</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/staffs') }}">Staffs</a></li>
        <li class="active">Edit</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="update-form" class="form-horizontal" method="post" action="{{url('admin/staffs/'.$staff->id)}}" enctype="multipart/form-data">
          @csrf
          @method('PUT')
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Staff Information</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/staffs')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Staffs</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="card-body card-form">
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Name: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="id" value="{{$staff->id}}">
                            <input type="text" class="form-control" name="name" value="{{$staff->name}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Joining Date: <span class="req_star">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" name="joining_date" class="form-control" id="datepicker_one" value="{{$staff->joining_date}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group ">
                        <label class="col-sm-3 control-label">Phone: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="phone" value="{{$staff->phone}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Email: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="email" class="form-control" name="email" value="{{$staff->email}}">
                        </div>
                    </div>
                    @if(auth()->user()->role_id <= 2)
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Branch: <span class="req_star">*</span></label>
                        <div class="col-sm-4">
                            <select class="select2 form-control" name="branch_id">
                                <option></option>
                                @foreach($allBranch as $branch)
                                    <option value="{{$branch->id}}" @if($branch->id == $staff->branch_id) selected @endif>{{$branch->branch_code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="form-group row custom-form-group d-none">
                        <label class="col-sm-3 control-label">Branch: <span class="req_star">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="branch_id" value="{{auth()->user()->branch->branch_code}}" readonly>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">National ID: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" name="nid" value="{{$staff->nid}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Current Address: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="cadd">{{$staff->current_address}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Permanent Address: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="padd">{{$staff->permanent_address}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Note: </label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="note">{{$staff->note}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group custom-form-group-photo">
                        <label class="col-sm-3 col-form-label">Photo: </label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control dropify" data-default-file="{{Storage::disk('public')->url('avatar.png')}}" name="photo" value="{{old('photo')}}">
                        </div>
                        <div class="col-sm-6">
                            @if($staff->photo != '')
                                <img class="table-img-100" src="{{Storage::disk('public')->url('staffs/'.$staff->photo)}}" alt="Photo">
                            @else
                                <img class="table-img-100" src="{{Storage::disk('public')->url('avatar.png')}}" alt="Photo">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer card-footer-button text-center">
                    <button type="submit" class="btn btn-submit btn-secondary waves-effect">UPDATE</a>
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


{{-- {{ $errors->has('photo') ? ' has-error' : '' }} --}}
{{-- @if($errors->has('photo'))
    <span class="invalid-feedback" role="alert">
        <strong>{{$errors->first('photo')}}</strong>
    </span>
@endif --}}