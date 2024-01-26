@extends('layouts.admin')
@section('title', 'Damaged')

@push('css')
     <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
     <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title"> Damaged </h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/damaged') }}"> Damaged </a></li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Add To The Damaged Medicine List </h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin/damaged/index')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Damaged </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="row">
                <div class="card-body">
                    <form class="form-horizontal" method="post" action="{{url('admin/damaged/create')}}">
                        @csrf
                        <div class="form-group row custom-form-group">
                            <label class="col-sm-4"> Medicine Name:</label>
                            <div class="col-sm-4">
                                <select class="form-control medicine_id" name="medicine_id" required>
                                    @error('medicine_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </select>
                            </div>
                        </div>

                        <div class="form-group row custom-form-group">
                            <label class="col-sm-4 control-label">DARR No: <span class="req_star">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="darr_no" value="{{old('darr_no')}}">
                                @error('darr_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row custom-form-group">
                            <label class="col-sm-4 control-label">Expiry Date: <span class="req_star">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="expiry_date" id="datepicker_one" value="{{old('expiry_date')}}">
                                @error('expiry_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row custom-form-group">
                            <label class="col-sm-4 control-label">Number of Pieces: <span class="req_star">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="pieces" value="{{old('pieces')}}">
                                @error('pieces')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <center><button type="submit" class="btn btn-success">Add</button></center>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('.medicine_id').select2({
            width: "100%",
            placeholder: 'Choose Any',
            ajax: {
                url: '/admin/damaged/medicineautosearch',
                dataType: 'JSON',
                processResults: function (data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.medicine_name,
                                id: item.medicine_id
                            }
                        })
                    };
                }, 
                cache: true
            }
        });
    </script>
@endpush






