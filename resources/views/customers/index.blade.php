@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{route('institutes.show', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  <a href="{{route('customers.index', ['id' => $institute->id])}}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i> Customers</a>
  <a href="{{ route('branches.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('branches.index') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Branches</a>
</div>

<br />

<div class="list-group">
  <a href="{{route('add-staff.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-plus-fill"></i> Staff Requests <span class="badge badge-danger">4</span></a>
</div>

<br />
@endsection

@section('content')
<div class="row border-bottom">
  <div class="col-auto"><h4 >{{ $institute->name }}</h4></div>
  <div class="col-auto"><span class="badge badge-secondary">{{ $institute->code }}</span></div>
</div>
<br />
<div class="d-flex justify-content-end mb-3">
    <a href="{{route('customers.create', ['id' => $institute->id])}}" class="btn btn-success float-right">Register New Customer</a>
</div>

<h4>Search Customer</h4>

<div class="row justify-content-center">
  <div class="col-md-12">
    <div class="card card default">
        <div class="card-body">    
            <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Customer Name or NIC..." />
            <div id="results"></div>
        </div>
    </div>
  </div>
</div>
    
@if(isset($customer))
    <div id="customerForm" class="card card-default mt-3">
        <div class="card-header">
            <b>Customer Data</b>
        </div>
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                    <th scope="row">NIC No:</th>
                    <td>{{ strtoupper($customer->nic_no) }}</td>
                    </tr>
                    <tr>
                    <th scope="row">Fullname:</th>
                    <td>{{ $customer->last_name }} {{ $customer->first_name }}</td>
                    
                    </tr>
                    <tr>
                    <th scope="row">Gender:</th>
                    <td>{{ $customer->gender }}</td>
                    </tr>
                    <tr>
                    <th scope="row">Address:</th>
                    <td>{{ $customer->address }}, 
                        {{ App\GNDivision::find($customer->gn_division)->name }}, 
                        {{ App\DSDivision::find($customer->ds_division)->name }}, 
                        {{ App\District::find($customer->district)->name }}, 
                        {{ App\Province::find($customer->province)->name }} Province</td>
                    </tr>
                    <tr>
                    <th scope="row" class="col-md-2">Contact No:</th>
                    <td>{{ $customer->contact_no }}</td>
                    </tr>
                    <tr>
                    <th scope="row">Email:</th>
                    <td>{{ $customer->email }}</td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="addVisitButton" href="#" class="btn btn-primary mr-1" data-toggle="modal" data-target="#addVisit"><i class="bi bi-person-plus"></i> Create Visit</button>
        </div>
    </div>
@endif



<form action="#" method="POST" id="addVisitForm">
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
                @foreach($institute->branches as $branch)
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
                <textarea class="form-control @error('purpose') is-invalid @enderror" rows="3"></textarea>
                @error('purpose')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="remarks">Remarks</label><small class="d-inline-block form-text text-muted ml-1">(Optional)</small>
                <textarea class="form-control @error('remarks') is-invalid @enderror" rows="3"></textarea>
                @error('remarks')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="modal-footer">
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

        $(document).ready(function(){
            function blink_text() {
            jQuery('#test').fadeOut(500);
            jQuery('#test').fadeIn(500);
        }
        setInterval(blink_text, 1000);

            $('#user_name').on('keyup',function () {

                var query = $(this).val();
                if ($(this).val().length == 0) {
                    // Hide the element
                    $('#results').hide();
                }else {
                    // Otherwise show it
                    $('#results').show();
                }

                $.ajax({

                    url:'{{ route('customer_search.action', ["institute_id" => $institute->id]) }}',

                    type:'GET',

                    data:{'name':query},

                    success:function (data) {

                        $('#results').html(data);

                    }

                })

            });

            jQuery.ajax({
                url : "{{url('/getVisit')}}" + "/" + "@if(isset($customer)) {{ $customer->id }} @endif",
                type : "GET",
                dataType : "json",
                success:function(data2){    
                    if(!jQuery.isEmptyObject(data2)){
                        console.log(data2);
                        jQuery('#customerForm').after('<div id="visitCard" class="card border-primary mt-3"><div  id="visitHeader" class="card-header bg-primary"><b>Visit of </b></div><div class="card-body"><div class="row"><div class="col-4"><p><b>Visited Branch:</b></p><p><b>Visit Created:</b></p><p><b>Status:</b></p></div><div class="col-8"><p name="visited_branch"></p><p name="visited_date"></p><p name="visit_status"></p></div></div></div></div>');
                        jQuery("#visitHeader").append('<b>' + "@if(isset($customer)) {{ $customer->first_name }} @endif" + ' </b><span id="test" class="badge badge-success">ACTIVE</span>');
                        jQuery('p[name="visited_branch"]').text(data2['branch'].name);
                        jQuery('p[name="visited_date"]').text(data2['visit_time']);
                        jQuery('p[name="visit_status"]').text(data2['visit'].status);
                        jQuery('#addVisitButton').attr('disabled', true);
                    }
                    
                }
            });


           
        });

    </script>
@endsection