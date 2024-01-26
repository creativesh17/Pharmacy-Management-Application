@extends('layouts.admin')
@section('title', 'Branchwise')

@push('css')
     <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Branchwise</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/branchwise') }}">Branchwise</a></li>
        <li class="active">Search</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Search For Branchwise Dashboard Report </h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> Main </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="row">
                <div class="card-body">
                    <form class="form-horizontal" method="get" action="{{url('admin/branchwise/search')}}">
                        <div class="row">
                            <div class="col-sm-12">
                                <center> <h4>Branch Dashboard</h4></center>
                            </div>
                        </div>

                        <div class="form-group row custom-form-group">
                            <label class="col-sm-3">Branch Code:</label>
                            <div class="col-sm-7">
                                <select class="select2 form-control" name="branch_code" required>
                                    <option></option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->branch_code}}">{{$branch->branch_code}}</option>
                                    @endforeach
                                    @error('branch_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <center><button type="submit" class="btn btn-success">Search</button></center>
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
    <script>
        $(function() {
            $(".select2").select2({
                width: "100%",
                placeholder: "Choose Any",
            });
        });
    </script>
@endpush






