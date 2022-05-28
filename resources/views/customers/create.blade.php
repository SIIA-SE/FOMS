@extends('layouts.app')

@section('content')

<div class="card card-default">
    <div class="card-header">
        <b>{{ isset($customer) ? 'Edit Customer' : 'Add New Customer'}}</b>
    </div>
    <div class="card-body">
        <!--@if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li class="text-danger">{{$error}}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


        @endif-->
        <form action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf 
            
            @if(isset($institute))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-5">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ isset($customer) ? $customer->first_name : old('firstname') }}">
                        @error('firstname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ isset($customer) ? $customer->last_name : old('lastname') }}">
                        @error('lastname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control @error('gender') is-invalid @enderror" name="gender" value="{{ isset($customer) ? $customer->gender : old('gender') }}">
                        <option selected disabled>Select...</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="nic_no">NIC No.</label>
                        <input type="text" class="form-control @error('nic_no') is-invalid @enderror" name="nic_no" value="{{ isset($customer) ? $customer->nic_no : old('nic_no') }}">
                        @error('nic_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ isset($customer) ? $customer->address : old('address') }}">
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="contact_no">Contact No.</label>
                        <input type="text" class="form-control @error('contact_no') is-invalid @enderror" name="contact_no" value="{{ isset($customer) ? $customer->contact_no : old('contact_no') }}">
                        @error('contact_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ isset($customer) ? $customer->email : old('email') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="province">Province</label>
                        <select class="form-control @error('province') is-invalid @enderror" name="province" id="province" value="{{ isset($customer) ? $customer->province : ''}}">
                            <option @if(old('province')=='') selected disabled @endif>Select...</option>
                            @foreach(App\Province::all() as $province)
                                <option value="{{$province->id}}" {{ old('province') == $province->id ? "selected" : ""}}>{{$province->name}}</option>
                            @endforeach
                        </select>
                        @error('province')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="district">District</label>
                        <select class="form-control @error('district') is-invalid @enderror" name="district" id="district" value="{{ isset($customer) ? $customer->district : ''}}">
                        @if(old('district'))
                                @php
                                    $district = \App\District::find(old('district'));
                                @endphp
                                <option value="{{ old('district') }}" selected>{{ $district->name}}</option>
                            @else
                                <option value="" selected disabled>Select...</option>
                            @endif
                        
                        
                        </select>
                        @error('district')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="dsdivision">DS Division</label>
                        <select class="form-control @error('dsdivision') is-invalid @enderror" name="dsdivision" value="{{ isset($customer) ? $customer->ds_division : ''}}">
                        @if(old('dsdivision'))
                                @php
                                    $dsdivision = \App\DSDivision::find(old('dsdivision'));
                                @endphp
                                <option value="{{ old('dsdivision') }}" selected>{{ $dsdivision->name}}</option>
                            @else
                                <option value="" selected disabled>Select...</option>
                            @endif
                        
                        </select>
                        @error('dsdivision')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="gndivision">GN Division</label>
                        <select class="form-control @error('gndivision') is-invalid @enderror" name="gndivision" value="{{ isset($customer) ? $customer->gn_division : ''}}">
                        @if(old('gndivision'))
                                @php
                                    $gndivision = \App\GNDivision::find(old('gndivision'));
                                @endphp
                                <option value="{{ old('gndivision') }}" selected>{{ $gndivision->name}}</option>
                            @else
                                <option value="" selected disabled>Select...</option>
                            @endif
                        
                        
                        </select>
                        @error('gndivision')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <p></p>
            <div class="form-group">
                <a class="btn btn-dark" href="{{route('customers.index')}}">Go back</a>
                <button class="btn btn-success">{{ isset($customer) ? 'Update Details' : 'Add Customer'}}</button>
            </div>

            
        </form>
    </div>


</div>

@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function ()
    {
        jQuery('select[name="province"]').on('change',function(){
            var provinceID = jQuery(this).val();
               if(provinceID)
               {
                    jQuery.ajax({
                     url : "{{url('/getDistrictsList')}}?province_id=" + provinceID,
                     type : "GET",
                     dataType : "json",
                     success:function(data){    
                        jQuery('select[name="district"]').empty();
                        $('select[name="district"]').append('<option selected disabled>Select...</option>');
                        jQuery.each(data, function(key,value){
                        $('select[name="district"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                    });
               }
               else
               {
                  $('select[name="district"]').empty();
               }
        });

        jQuery('select[name="district"]').on('change',function(){
            var districtID = jQuery(this).val();
               if(districtID)
               {
                    jQuery.ajax({
                     url : "{{url('/getDSDivisionsList')}}?district_id=" + districtID,
                     type : "GET",
                     dataType : "json",
                     success:function(data){    
                        jQuery('select[name="dsdivision"]').empty();
                        $('select[name="dsdivision"]').append('<option selected disabled>Select...</option>');
                        jQuery.each(data, function(key,value){
                        $('select[name="dsdivision"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                    });
               }
               else
               {
                  $('select[name="dsdivision"]').empty();
               }
        });

        jQuery('select[name="dsdivision"]').on('change',function(){
            var dsdivisionID = jQuery(this).val();
               if(dsdivisionID)
               {
                    jQuery.ajax({
                     url : "{{url('/getGNDivisionsList')}}?dsdivision_id=" + dsdivisionID,
                     type : "GET",
                     dataType : "json",
                     success:function(data){    
                        jQuery('select[name="gndivision"]').empty();
                        $('select[name="gndivision"]').append('<option selected disabled>Select...</option>');
                        jQuery.each(data, function(key,value){
                        $('select[name="gndivision"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                    });
               }
               else
               {
                  $('select[name="gndivision"]').empty();
               }
        });
    });
</script>
@endsection