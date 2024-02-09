@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{ route('customers.index', ['id' => $customer_inst->id]) }}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  @if($staffRole == 'manager' || $staffRole == 'frontdeskuser')
  <a href="{{ route('customers.index', ['id' => $customer_inst->id]) }}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i> Customers</a>
  @endif
  <a href="{{ route('branches.index', ['id' => $customer_inst->id]) }}" class="list-group-item list-group-item-action {{ Route::is('branches.index') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Branches</a>

</div>

<br />

<div class="list-group">
  @foreach(Auth::user()->institutes as $userInstitute)
    @if($userInstitute->id == $customer_inst->id || $staffRole == 'manager' || $staffRole == 'sys_admin')
      
        <a href="{{route('add-staff.index', $customer_inst->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-plus-fill"></i> Staff Requests <span class="badge badge-danger">@if(count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) > 0) {{ count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) }} @endif</span></a>
        <a id="staffList" href="{{route('staff-list.index', $customer_inst->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-lines-fill"></i> Staff List</a>
    @endif
  @endforeach
  @if($staffRole == 'manager')
    <a id="generateReports" href="{{route('get-data.index', $customer_inst->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-arrow-down-square-fill"></i> Download Data</a>
  @endif
</div>

<br />
@endsection

@section('content')
<div class="row border-bottom border-dark">
  <div class="col-auto"><h4 >{{ $customer_inst->name }}</h4></div>
  <div class="col-auto"><span class="badge badge-secondary">{{ $customer_inst->code }}</span></div>
  <div class="col"><a href="{{route('institutes.index')}}" class="float-right btn btn-sm btn-danger"><i class="bi bi-box-arrow-left"></i> Exit</a></div>
</div>
<br />
<div id="customerForm" class="card card-default">
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
        <form action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store', ['institute_id' => $customer_inst->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf 
            
            @if(isset($customer))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-12">
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
            </div>
            <div class="row">
                <div class="col-5">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ isset($customer) ? $customer->first_name : old('firstname') }}">
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
                        @if(isset($customer->gender))
                            <option value="Male" @if($customer->gender == 'Male') selected @endif>Male</option>
                            <option value="Female" @if($customer->gender == 'Female') selected @endif>Female</option>
                            <option value="Other" @if($customer->gender == 'Other') selected @endif>Other</option>
                        @endif
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
                <div class="col-12">
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
                            <option @if(old('province')=='') disabled selected @endif>Select...</option>
                            @foreach(App\Province::all() as $province)
                                @if(isset($customer->province) && $customer->province == $province->id)
                                    @php $select= "selected"; @endphp
                                @else 
                                    @php $select=""; @endphp
                                @endif
                                    <option value="{{$province->id}}" @if(old('province') == $province->id) selected @else @php echo $select; @endphp @endif> {{$province->name}}</option>
                                
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
                        <option @if(old('district')=='') disabled @endif>Select...</option>
                        @if(old('district'))
                                @php
                                    $district = \App\District::find(old('district'));
                                @endphp
                                <option value="{{ old('district') }}" selected>{{ $district->name}}</option>
                            @elseif(isset($customer->district))
                                <option value="{{ $customer->district }}" selected>{{ \App\District::find($customer->district)->name }}</option>
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
                            @elseif(isset($customer->ds_division))
                                <option value="{{ $customer->ds_division }}" selected>{{ \App\DSDivision::find($customer->ds_division)->name }}</option>
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
                            @elseif(isset($customer->gn_division))
                                <option value= "{{ $customer->gn_division }}" selected>{{ \App\GNDivision::find($customer->gn_division)->name }}</option>
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
            <div class="buttons form-group">
                <a class="btn btn-dark mr-1" href="{{route('customers.index', ['id' => $customer_inst->id])}}"><i class="bi bi-chevron-left"></i>Go back</a>
                <button id="saveButton" class="btn btn-success mr-1"><i class="bi bi-plus-circle"></i> {{ isset($customer) ? 'Update Details' : 'Save Data'}}</button>
                <button type="button" id="addVisitButton" href="#" class="btn btn-primary mr-1" data-toggle="modal" data-target="#addVisit" disabled><i class="bi bi-person-plus"></i> Create Visit</button>
            </div>
        </form>
    </div>
</div>


@if ( $errors->get('branch') || $errors->get('purpose'))
    <script type="text/javascript">
        $( document ).ready(function() {
                $('#addVisit').modal('show');
        });
    </script>
@endif


<form action="{{ route('visits.store', ['institute_id' => $customer_inst->id]) }}" method="POST" id="addVisitForm">
  @csrf
  <div class="modal fade" id="addVisit" tabindex="-1" role="dialog" aria-labelledby="addVisitLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addVisitLabel">Add New Visit to </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="branch">Select Branch</label>
                <select class="form-control @error('branch') is-invalid @enderror" name="branch" value="{{ old('branch') }}">
                <option selected disabled>Select...</option>
                @foreach($customer_inst->branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
                </select>
                @error('branch')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="purpose">Purpose of Visit</label>
                <textarea class="form-control @error('purpose') is-invalid @enderror" name="purpose" rows="3"></textarea>
                @error('purpose')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="remarks">Remarks</label><small class="d-inline-block form-text text-muted ml-1">(Optional)</small>
                <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks" rows="3"></textarea>
                @error('remarks')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="custId" name="custId" value="">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Create</button>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function ()
    {
        function blink_text() {
            jQuery('#test').fadeOut(500);
            jQuery('#test').fadeIn(500);
        }
        setInterval(blink_text, 1000);

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

        jQuery('input[name="nic_no"]').on('input',function(){
            var customer_nic = $(this).val();
               if(customer_nic)
               {
                jQuery.ajax({
                    url : "{{ url('/getCustomer') }}" +  "/" + "{{ $customer_inst->id }}" + "/" + customer_nic,
                    type : "GET",
                    dataType : "json",
                    success:function(data){    
                        if(!jQuery.isEmptyObject(data)){
                            //console.log(data);
                            jQuery('input[name="firstname"]').val(data[0].first_name);
                            jQuery('input[name="firstname"]').attr('readonly', true);
                            jQuery('input[name="lastname"]').val(data[0].last_name);
                            jQuery('input[name="lastname"]').attr('readonly', true);
                            jQuery('select[name="gender"]').val(data[0].gender).change();
                            jQuery('select[name="gender"]').attr('disabled', true);
                            jQuery('input[name="address"]').val(data[0].address);
                            jQuery('input[name="address"]').attr('readonly', true);
                            jQuery('input[name="contact_no"]').val(data[0].contact_no);
                            jQuery('input[name="contact_no"]').attr('readonly', true);
                            jQuery('input[name="email"]').val(data[0].email);
                            jQuery('input[name="email"]').attr('readonly', true);
                            jQuery('select[name="province"]').val(data[0].province).change();
                            jQuery('select[name="province"]').attr('disabled', true);
                            jQuery('#addVisitButton').attr('disabled', false);
                            jQuery('#saveButton').attr('disabled', true);
                            jQuery("#addVisitLabel").append(data[0].first_name);
                            jQuery("#custId").val(data[0].id);
                            jQuery(".buttons").append('<a id="editCustomerButton" href="/customers/' + data[0].id + '/edit" class="btn btn-info mr-1"><i class="bi bi-pencil-square"></i> Edit Details</a>');


                            jQuery.ajax({
                            url : "{{url('/getDistrictsList')}}?province_id=" + data[0].province,
                            type : "GET",
                            dataType : "json",
                            success:function(data2){    
                                jQuery('select[name="district"]').empty();
                                jQuery.each(data2, function(key,value){
                                    if(key == data[0].district){
                                        $('select[name="district"]').append('<option selected disabled value="'+ key +'">'+ value +'</option>');
                                        jQuery('select[name="district"]').attr('disabled', true);
                                    }
                                });
                            }
                            });

                            jQuery.ajax({
                            url : "{{url('/getDSDivisionsList')}}?district_id=" + data[0].district,
                            type : "GET",
                            dataType : "json",
                            success:function(data2){    
                                jQuery('select[name="dsdivision"]').empty();
                                jQuery.each(data2, function(key,value){
                                    if(key == data[0].ds_division){
                                        $('select[name="dsdivision"]').append('<option selected disabled value="'+ key +'">'+ value +'</option>');
                                        jQuery('select[name="dsdivision"]').attr('disabled', true);
                                    }
                                
                                });
                            }
                            });

                            jQuery.ajax({
                            url : "{{url('/getGNDivisionsList')}}?dsdivision_id=" + data[0].ds_division,
                            type : "GET",
                            dataType : "json",
                            success:function(data2){    
                                jQuery('select[name="gndivision"]').empty();
                                jQuery.each(data2, function(key,value){
                                    if(key == data[0].gn_division){
                                        $('select[name="gndivision"]').append('<option value="'+ key +'">'+ value +'</option>');
                                        jQuery('select[name="gndivision"]').attr('disabled', true);
                                    }
                                });
                            }
                            });

                            jQuery.ajax({
                            url : "{{url('/getVisit')}}" + "/" + data[0].id,
                            type : "GET",
                            dataType : "json",
                            success:function(data2){    
                                if(!jQuery.isEmptyObject(data2)){
                                    console.log(data2);
                                    jQuery('#customerForm').after('<div id="visitCard" class="card border-primary mt-3"><div  id="visitHeader" class="card-header bg-primary"><b>Visit of </b></div><div class="card-body"><div class="row"><div class="col-4"><p><b>Visited Branch:</b></p><p><b>Visit Created:</b></p><p><b>Status:</b></p></div><div class="col-8"><p name="visited_branch"></p><p name="visited_date"></p><p name="visit_status"></p></div></div></div></div>');
                                    jQuery("#visitHeader").append('<b>' + data[0].first_name + ' </b><span id="test" class="badge badge-success">ACTIVE</span>');
                                    jQuery('p[name="visited_branch"]').text(data2['branch'].name);
                                    jQuery('p[name="visited_date"]').text(data2['visit_time']);
                                    jQuery('p[name="visit_status"]').text(data2['visit'].status);
                                    jQuery('#addVisitButton').attr('disabled', true);
                                }
                                
                            }
                            });
                            

                        }else{
                            jQuery('input[name="firstname"]').attr('readonly', false);
                            jQuery('input[name="firstname"]').val('');
                            jQuery('input[name="lastname"]').attr('readonly', false);
                            jQuery('input[name="lastname"]').val('');
                            jQuery('select[name="gender"]').attr('disabled', false);
                            jQuery('select[name="gender"]').prop('selectedIndex',0);
                            jQuery('input[name="address"]').attr('readonly', false);
                            jQuery('input[name="address"]').val('');
                            jQuery('input[name="contact_no"]').attr('readonly', false);
                            jQuery('input[name="contact_no"]').val('');
                            jQuery('input[name="email"]').attr('readonly', false);
                            jQuery('input[name="email"]').val('');
                            jQuery('select[name="province"]').attr('disabled', false);
                            jQuery('select[name="province"]').prop('selectedIndex',0);
                            jQuery('select[name="district"]').attr('disabled', false);
                            jQuery('select[name="district"]').empty();
                            jQuery('select[name="dsdivision"]').attr('disabled', false);
                            jQuery('select[name="dsdivision"]').empty();
                            jQuery('select[name="gndivision"]').attr('disabled', false);
                            jQuery('select[name="gndivision"]').empty();
                            jQuery('#addVisitButton').attr('disabled', true);
                            jQuery('#saveButton').attr('disabled', false);
                            jQuery("#custId").val('');
                            jQuery('#visitCard').remove();
                            jQuery("#editCustomerButton").remove();
                        }
                    }
                });
            }else{
                jQuery('input[name="firstname"]').attr('readonly', false);
                jQuery('input[name="firstname"]').val('');
                jQuery('input[name="lastname"]').attr('readonly', false);
                jQuery('input[name="lastname"]').val('');
                jQuery('select[name="gender"]').attr('disabled', false);
                jQuery('select[name="gender"]').prop('selectedIndex',0);
                jQuery('input[name="address"]').attr('readonly', false);
                jQuery('input[name="address"]').val('');
                jQuery('input[name="contact_no"]').attr('readonly', false);
                jQuery('input[name="contact_no"]').val('');
                jQuery('input[name="email"]').attr('readonly', false);
                jQuery('input[name="email"]').val('');
                jQuery('select[name="province"]').attr('disabled', false);
                jQuery('select[name="province"]').prop('selectedIndex',0);
                jQuery('select[name="district"]').attr('disabled', false);
                jQuery('select[name="district"]').empty();
                jQuery('select[name="dsdivision"]').attr('disabled', false);
                jQuery('select[name="dsdivision"]').empty();
                jQuery('select[name="gndivision"]').attr('disabled', false);
                jQuery('select[name="gndivision"]').empty();
                jQuery('#addVisitButton').attr('disabled', true);
                jQuery('#saveButton').attr('disabled', false);
                jQuery("#custId").val('');
                jQuery('#visitCard').remove();
            }
            
        });
    });
</script>
@endsection