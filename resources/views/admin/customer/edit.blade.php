@extends('layouts.admin')
@section('title', 'Customer')

@push('css')

@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title"> Customer</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/customers/'.$customer->id.'/edit') }}"> Customer</a></li>
        <li class="active">Edit</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="update-form" class="form-horizontal" method="post" action="{{url('admin/customers/'.$customer->id)}}">
          @csrf
          @method('PUT')
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Customer Information</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/customers')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Customers</a>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div>
                </div>
                @if(auth()->user()->role_id > 2)
                <div class="form-group row custom-form-group">
                    <div class="col-sm-12 text-center text-secondary"><br>
                        <strong style="color: #cab611;">Warning! If you didn't add this record, you can not update unless you are a memeber of the authority!</strong>
                    </div>
                </div>
                @endif
                <div class="card-body card-form">
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Customer Name: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                        <input type="hidden" name="id" value="{{$customer->id}}">
                        <input type="text" class="form-control" name="customer_name" value="{{$customer->customer_name}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Customer Phone: </label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="customer_phone" value="{{$customer->customer_phone}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Customer Email: </label>
                        <div class="col-sm-7">
                        <input type="email" class="form-control" name="customer_email" value="{{$customer->customer_email}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Customer Address: </label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="customer_address">{{$customer->customer_address}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Customer Note: </label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" name="customer_note">{{$customer->customer_note}}</textarea>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->role_id <= 2 || $customer->user_id == auth()->user()->id)
                <div class="card-footer card-footer-button text-center">
                    <button type="submit" class="btn btn-submit btn-secondary waves-effect">UPDATE</button>
                </div>
                @else
                <div class="card-footer card-footer-button text-center">
                    <a href="{{url('admin/customers')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> View All</a>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')

@endpush

