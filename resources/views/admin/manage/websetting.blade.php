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
        <form class="form-horizontal" method="post" action="{{url('admin/web/settings/update')}}" enctype="multipart/form-data">
          @csrf
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Web Settings</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/pharmacy/settings')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> Pharmacy Settings</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="card-body card-form">
                    <div class="form-group row custom-form-group {{$errors->has('web_title')? ' has-error':''}}">
                        <label class="col-sm-3 control-label"> Website Title: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            {{-- <input type="hidden" name="id" value="{{$data->id}}"> --}}
                            <input type="text" class="form-control" name="web_title" value="{{$data->web_title}}">
                            @if ($errors->has('web_title'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('web_title') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row custom-form-group {{$errors->has('web_subtitle')? ' has-error':''}}">
                        <label class="col-sm-3 control-label"> Website Subtitle: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="web_subtitle" value="{{$data->web_subtitle}}">
                            @if ($errors->has('web_subtitle'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('web_subtitle') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row custom-form-group {{$errors->has('web_footer_text')? ' has-error':''}}">
                        <label class="col-sm-3 control-label"> Website Footer Text: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="web_footer_text" value="{{$data->web_footer_text}}">
                            @if ($errors->has('web_footer_text'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('web_footer_text') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row custom-form-group {{$errors->has('web_language')? ' has-error':''}}">
                        <label class="col-sm-3 control-label"> Website Language: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="web_language" value="{{$data->web_language}}">
                            @if ($errors->has('web_language'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('web_language') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row custom-form-group custom-form-group-photo {{$errors->has('web_favicon')? ' has-error':''}}">
                        <label class="col-sm-3 col-form-label"> Favicon: </label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control dropify" data-default-file="{{Storage::disk('public')->url('avatar.png')}}" name="web_favicon" value="{{old('web_favicon')}}">
                            @if ($errors->has('web_favicon'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('web_favicon') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            @if($data->web_favicon != '')
                                <img class="table-img-100" src="{{Storage::disk('public')->url('favicon/'.$data->web_favicon)}}" alt="Photo">
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