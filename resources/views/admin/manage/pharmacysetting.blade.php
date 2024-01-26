@extends('layouts.admin')
@section('title', 'Settings')


@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/dropify.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Dasboard</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/pharmacy/settings') }}">Settings</a></li>
        <li class="active">Pharmacy</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" method="post" action="{{url('admin/pharmacy/settings/update')}}" enctype="multipart/form-data">
          @csrf
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Pharmacy Settings</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/web/settings')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> Web Settings</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="card-body card-form">
                    <div class="form-group row custom-form-group {{$errors->has('ph_name')? ' has-error':''}}">
                        <label class="col-sm-3 control-label"> Pharmacy Name: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <input type="text" class="form-control" name="ph_name" value="{{$data->ph_name}}">
                            @if ($errors->has('ph_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ph_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row custom-form-group {{$errors->has('ph_phone')? ' has-error':''}}">
                        <label class="col-sm-3 control-label"> Phone: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="ph_phone" value="{{$data->ph_phone}}">
                            @if ($errors->has('ph_phone'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ph_phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row custom-form-group {{$errors->has('ph_email')? ' has-error':''}}">
                        <label class="col-sm-3 control-label"> Email: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="email" class="form-control" name="ph_email" value="{{$data->ph_email}}">
                            @if ($errors->has('ph_email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ph_email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row custom-form-group {{$errors->has('ph_address')? ' has-error':''}}">
                        <label class="col-sm-3 control-label"> Address: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="ph_address">{{$data->ph_address}}</textarea>
                            @if ($errors->has('ph_address'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ph_address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> About: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="ph_about">{{$data->ph_about}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group custom-form-group-photo {{$errors->has('ph_logo')? ' has-error':''}}">
                        <label class="col-sm-3 col-form-label"> Logo: </label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control dropify" data-default-file="{{Storage::disk('public')->url('avatar.png')}}" name="ph_logo" value="{{old('ph_logo')}}">
                            @if ($errors->has('ph_logo'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ph_logo') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            @if($data->ph_logo != '')
                                <img class="table-img-100" src="{{Storage::disk('public')->url('settings/'.$data->ph_logo)}}" alt="Photo">
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
    <script src="{{asset('contents/admin')}}/assets/js/dropify.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/dropify.js"></script>
@endpush