@extends('layouts.admin')
@section('title', 'Stock Out')

@push('css')
    <style>
        table thead th:nth-child(1) { width: 15%; }
        table thead th:nth-child(2) { width: 10%; }
        table thead th:nth-child(3) { width: 30%; }
    </style>
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Stock Out</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/stockout') }}">Stock Out</a></li>
        <li class="active">index</li>
    </ol>
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-part">
                <div class="row">
                    <div class="col-md-8">
                        @if(auth()->user()->role_id <= 2)
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Stock Report {{ request('branch') }}</h3>
                        @else
                            <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Stock Report {{ $branch->branch_code }}</h3>
                        @endif
                    </div>
                </div>
            </div>



            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.stock.out.search') }}" method="get">
                            <div class="row">
                                @if (auth()->user()->role_id <= 2)
                                    <div class="col-sm-2">
                                        <select class="select2 form-control" name="branch">
                                            @foreach($branches as $bran)
                                                <option value="{{$bran->branch_code}}" @if($bran->id == $branch->id) selected @endif>{{$bran->branch_code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-primary" name="action" value="search">Search</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary" name="action" value="pdf">Download PDF or Print</button>
                                    </div>
                                @else
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary" name="action" value="pdf">Download PDF or Print</button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>














         
            <div class="row">
                <div class="col-12"> 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped custom-table mb-0" id="dataTable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Branch</th>
                                        <th class="text-center">Medicine</th>
                                        <th class="text-center">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paginator as $key=>$data)
                                    <tr>
                                        <td class="text-center">{{ $paginator->firstItem() + $key }}</td>
                                        <td class="text-center">{{ $branch->branch_code }}</td>
                                        <td class="text-center">{{ $data['medicine_name'] }}</td>
                                        <td class="text-center">{{ $data['stock'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
         
            <div class="card-footer card-footer-part">
                <div class="row">
                    <div class="col-5">
                        
                    </div>
                    <div class="col-6">
                        @if(auth()->user()->role_id <= 2)
                            {{ $paginator->appends(['branch' => request('branch'), 'action' => 'search'])->links() }}    
                        @else
                            {{ $paginator->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- </div> --}}

@endsection

@push('js')
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>
    <script>
        $(function() {
            $(".select2").select2({
                width: "100%",
                placeholder: "Choose Branch",
            });
        });
    </script>
@endpush






























