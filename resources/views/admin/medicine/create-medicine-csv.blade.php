@extends('layouts.admin')
@section('title', 'Medicines')

@push('css')

@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Medicines</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/medicines') }}">Medicines</a></li>
        <li class="active">Import</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Import medicines</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('admin.medicines.create') }}" class="btn btn-md btn-secondary card-top-button"><i class="fa fa-plus-circle"></i> Add Medicine</a>
                        <a href="{{url('admin/medicines')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Medicines</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="card-body card-form">
                <div class="form-group row custom-form-group">
                    <div class="col-sm-10">
                        <p>
                            1. &nbsp;The first line in downloaded csv file should remain as it is. Please do not change the order of columns.
                            <br>
                            2. &nbsp;The correct column order is (med_name, generic_name, sell_price) & you must follow this. &nbsp;If you want to add medicine category and manufacturer, you can add it later(not by file upload).
                            <br>
                            3. &nbsp;Please make sure the csv file is UTF-8 encoded and not saved with byte order mark (BOM).
                            <br>
                            4. &nbsp;Default category and manufacturer will be added if uploaded by csv file.
                            <br>
                            5. <strong>Success!</strong> and <strong>Error</strong> message can come together. &nbsp; It can mean that some rows were added and some were not due to some validation errors or no rows were added.
                            <br>
                        </p>
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <div class="col-sm-6">
                        <a href="{{ Storage::disk('public')->url('download/medicine.csv') }}" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download Sample File</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header card-header-part">
                <div class="col-md-8">
                    <h3 class="card-title card-top-title">Upload Medicines (CSV)</h3>
                </div>
            </div>
            <form action="{{ url('admin/csv/import') }}" class="form-vertical" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="upload_csv_file" class="col-sm-4 col-form-label">Upload CSV File <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="file" type="file" placeholder="Upload CSV File" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect">Upload</button>
                        </div>
                    </div>
                </div>
            </form>                
        </div>
    </div>
</div>
@endsection
@push('js')

@endpush