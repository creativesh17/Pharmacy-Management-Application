@extends('layouts.admin')
@section('title', 'Suppliers')

@push('css')

@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Suppliers</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/suppliers') }}">Suppliers</a></li>
        <li class="active">Edit</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="update-form" class="form-horizontal" method="post" action="{{url('admin/suppliers/'.$supplier->id)}}">
          @csrf
          @method('PUT')
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Supplier Information</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/suppliers')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Suppliers</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="card-body card-form">
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Name: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                        <input type="hidden" name="id" value="{{$supplier->id}}">
                        <input type="text" class="form-control" name="sup_name" value="{{$supplier->sup_name}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group ">
                        <label class="col-sm-3 control-label"> Phone: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="sup_phone" value="{{$supplier->sup_phone}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Email: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <input type="email" class="form-control" name="sup_email" value="{{$supplier->sup_email}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Address: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="sup_address">{{$supplier->sup_address}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Note: </label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="sup_note">{{$supplier->sup_note}}</textarea>
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

@endpush


{{-- {{ $errors->has('photo') ? ' has-error' : '' }} --}}
{{-- @if($errors->has('photo'))
    <span class="invalid-feedback" role="alert">
        <strong>{{$errors->first('photo')}}</strong>
    </span>
@endif --}}