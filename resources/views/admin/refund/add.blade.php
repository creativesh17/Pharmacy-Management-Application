@extends('layouts.admin')
@section('title', 'Refunds')

@push('css')

@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Refunds</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/refunds') }}">Refunds</a></li>
        <li class="active">Search</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Search Invoice For Refund</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('admin/refunds')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Refunds</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="row">
                <div class="card-body">
                    <form class="form-horizontal" method="get" action="{{url('admin/refund/create')}}">
                        <div class="row">
                            <div class="col-sm-12">
                                <center> <h4>Customer Return</h4></center>
                            </div>
                        </div>

                        <div class="form-group row custom-form-group">
                            <label class="col-sm-3">Invoice No:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="invoice_id" placeholder="Invoice No" required>
                                @error('invoice_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
    
@endpush






