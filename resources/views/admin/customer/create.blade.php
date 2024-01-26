@extends('layouts.admin')
@section('title', 'Customer')

@push('css')

@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title"> Customers</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/customers') }}"> Customers</a></li>
        <li class="active">Add</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="add-form" class="form-horizontal" method="post" action="{{url('admin/customers')}}" enctype="multipart/form-data">
          @csrf
          <div class="card">
              <div class="card-header card-header-part">
                  <div class="row">
                      <div class="col-md-8">
                          <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Add Customer</h3>
                      </div>
                      <div class="col-md-4 text-right">
                          <a href="{{url('admin/customers')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i>  All Customers</a>
                      </div>
                      <div class="clearfix"></div>
                  </div>
              </div>
              <div class="card-body card-form">
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Customer Name: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="customer_name" value="{{old('customer_name')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Customer Phone: </label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="customer_phone" value="{{old('customer_phone')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Customer Email: </label>
                    <div class="col-sm-7">
                      <input type="email" class="form-control" name="customer_email" value="{{old('customer_email')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Customer Address: </label>
                    <div class="col-sm-7">
                      <textarea type="text" class="form-control" name="customer_address">{{old('customer_address')}}</textarea>
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Customer Note: </label>
                    <div class="col-sm-7">
                      <textarea type="text" class="form-control" name="customer_note">{{old('customer_note')}}</textarea>
                    </div>
                </div>
              </div>
              <div class="card-footer card-footer-button text-center">
                  <button type="submit" class="btn btn-submit btn-secondary waves-effect">Add</a>
              </div>
          </div>
        </form>
    </div>
</div>
@endsection
@push('js')

@endpush
