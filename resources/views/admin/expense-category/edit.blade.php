@extends('layouts.admin')
@section('title', 'Expense Category')

@push('css')

@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title"> Expense Categories</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/expensecategories') }}"> Expense Categories</a></li>
        <li class="active">Edit</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="update-form" class="form-horizontal" method="post" action="{{url('admin/expensecategories/'.$expensecategory->id)}}">
          @csrf
          @method('PUT')
          <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Update Expense Category </h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin/expensecategories')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i>  All Expense Categories</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="card-body card-form">
              <div class="form-group row custom-form-group">
                  <label class="col-sm-3 control-label"> Name: <span class="req_star">*</span></label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" name="expcate_name" value="{{$expensecategory->expcate_name}}">
                  </div>
              </div>
              <div class="form-group row custom-form-group">
                  <label class="col-sm-3 control-label"> Remarks: </label>
                  <div class="col-sm-7">
                    <textarea type="text" class="form-control" name="expcate_remarks">{{$expensecategory->expcate_remarks}}</textarea>
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

@endpush
