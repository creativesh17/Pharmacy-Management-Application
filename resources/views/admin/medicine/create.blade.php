@extends('layouts.admin')
@section('title', 'Medicines')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <style>
        select {
            z-index: 100;
        }
    </style>
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Medicines</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/medicines') }}">Medicines</a></li>
        <li class="active">Create</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="add-form" class="form-horizontal" method="post" action="{{url('admin/medicines')}}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header card-header-part">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Add Medicine</h3>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{url('admin/csv/import')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> Import Medicines</a>
                            <a href="{{url('admin/medicines')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Medicines</a>
                        </div>
                    </div>
                </div>
                <div class="card-body card-form">
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Name: <span class="req_star">*</span></label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" name="med_name" value="{{old('med_name')}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Generic Name: </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="generic_name" value="{{old('generic_name')}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Medicine Category: <span class="req_star">*</span></label>
                        <div class="col-sm-4">
                            <select class="select2 form-control" name="medicinecategory_id">
                                <option></option>
                                @foreach($medCategories as $medCategory)
                                    <option value="{{$medCategory->id}}">{{$medCategory->cate_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label"> Manufacturer: <span class="req_star">*</span></label>
                        <div class="col-sm-4">
                            <select class="select2 form-control" name="manufacturer_id">
                                <option></option>
                                @foreach($manufacturers as $manufacturer)
                                    <option value="{{$manufacturer->id}}">{{$manufacturer->manu_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Selling Price: </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="sell_price" value="{{old('sell_price')}}">
                        </div>
                    </div>
                    <div class="form-group row custom-form-group">
                        <label class="col-sm-3 control-label">Note: </label>
                        <div class="col-sm-7">
                        <textarea type="text" class="form-control" name="note">{{old('note')}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer card-footer-button text-center">
                    <button type="submit" class="btn btn-submit btn-secondary waves-effect">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>

    <script>
        $(function() {
            $(".select2").select2({
                width: "100%",
                placeholder: "Choose Any",
            });
        });
    </script>
@endpush