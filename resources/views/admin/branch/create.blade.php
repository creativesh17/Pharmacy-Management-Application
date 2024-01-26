@extends('layouts.admin')
@section('title', 'Branch')

@push('css')
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('contents/admin')}}/assets/css/bootstrap-datepicker.min.css">

@endpush
@section('content')
@component('admin.includes.breadcrumb')
    <h4 class="pull-left page-title">Branches</h4>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/branches') }}">Branches</a></li>
        <li class="active">Create</li>
    </ol>
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <form id="add-form" class="form-horizontal" method="post" action="{{url('admin/branches')}}">
          @csrf
            <div class="card">
               <div class="card-header card-header-part">
                   <div class="row">
                        <div class="col-md-8">
                          <h3 class="card-title card-top-title"><i class="fa fa-gg-circle"></i> Add Branch </h3>
                      </div>
                      <div class="col-md-4 text-right">
                          <a href="{{url('admin/branches')}}" class="btn btn-md btn-secondary waves-effect card-top-button"><i class="fa fa-th"></i> All Branch</a>
                      </div>
                      <div class="clearfix"></div>
                  </div>
              </div>
              <div class="card-body card-form">
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Title: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="branch_title" value="{{old('branch_title')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Branch Start Date: </label>
                    <div class="col-sm-7">
                        <input type="text" name="branch_start_date" class="form-control" id="datepicker_one" value="{{old('branch_start_date')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Code: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="branch_code" value="{{old('branch_code')}}">
                    </div>
                </div>


                {{-- <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Supervisor: <span class="req_star">*</span></label>
                    <div class="col-sm-4">
                        <select class="select2 form-control" name="user_id" id="select2select">
                            <option></option>
                            @foreach($allUser as $user)
                                @if($user->role_id == 3)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div> --}}

                {{-- correct one jQuery UI --}}
                {{-- <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Supervisor: <span class="req_star">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control"  id='autosearchajax' >
                        <input type="hidden" class="form-control" name="user_id" id='select_list' readonly>
                    </div>
                </div> --}}


                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Dispenser:<span class="req_star">*</span></label>
                    <div class="col-sm-4">
                        <select class="select2 form-control" style="width:500px;" name="user_id"></select>
                    </div>
                </div>




                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Phone: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="branch_phone" value="{{old('branch_phone')}}">
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Address: <span class="req_star">*</span></label>
                    <div class="col-sm-7">
                        <textarea type="text" class="form-control" name="branch_address">{{old('branch_address')}}</textarea>
                    </div>
                </div>
                <div class="form-group row custom-form-group">
                    <label class="col-sm-3 control-label"> Note: </label>
                    <div class="col-sm-7">
                      <textarea type="text" class="form-control" name="branch_note">{{old('branch_note')}}</textarea>
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
    <script src="{{asset('contents/admin')}}/assets/js/select2.min.js"></script>
    {{-- <script src="{{asset('contents/admin')}}/assets/js/jquery-ui.min.js"></script> --}}
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap-datepicker.min.js"></script>

    {{-- correct one jQuery UI --}}
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){
    
            $( "#autosearchajax" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url:"{{route('admin.autosearch')}}",
                        type: 'post',
                        dataType: "json",
                        data: {
                        _token: CSRF_TOKEN,
                        search: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                select: function (event, ui) {
                // Set selection
                $('#autosearchajax').val(ui.item.label); // display the selected text
                $('#select_list').val(ui.item.value); // save selected id to input
                return false;
                }
            });
        });
    </script>




    
    <script>
        $(function() {
            $('.select2').select2({
                width: "100%",
                placeholder: 'Choose Any',
                ajax: {
                    url: '/admin/autosearch',
                    dataType: 'JSON',
                    processResults: function (data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    }, 
                    cache: true
                }
            });
        });
    </script>
@endpush