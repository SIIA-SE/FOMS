@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{ route('institutes.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  @if($staffRole == 'manager' || $staffRole == 'frontdeskuser')
  <a href="{{ route('customers.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i> Customers</a>
  @endif
  <a href="{{ route('branches.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('branches.index') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Branches</a>
</div>

<br />

<div class="list-group">
  @foreach(Auth::user()->institutes as $userInstitute)
    @if($userInstitute->id == $institute->id || $staffRole == 'manager' || $staffRole == 'sys_admin')
        <a href="{{route('add-staff.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-plus-fill"></i> Staff Requests <span class="badge badge-danger">@if(count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) > 0) {{ count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) }} @endif</span></a>
        <a id="staffList" href="{{route('staff-list.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-lines-fill"></i> Staff List</a>
    @endif
  @endforeach
  @if($staffRole == 'manager')
    <a id="generateReports" href="{{route('get-data.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-arrow-down-square-fill"></i> Download Data</a>
  @endif
</div>

<br />
@endsection

@section('content')
<div class="row border-bottom border-dark">
  <div class="col-auto"><h4 >{{ $institute->name }}</h4></div>
  <div class="col-auto"><span class="badge badge-secondary">{{ $institute->code }}</span></div>
  <div class="col"><a href="{{route('institutes.index')}}" class="float-right btn btn-sm btn-danger"><i class="bi bi-box-arrow-left"></i> Exit</a></div>
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
                        @if(isset($customer->gn_division)) {{ \App\GNDivision::find($customer->gn_division)->name }},  @else - @endif 
                        @if(isset($customer->ds_division)) {{ \App\DSDivision::find($customer->ds_division)->name }},  @else - @endif 
                        @if(isset($customer->district)) {{ \App\District::find($customer->district)->name }}, @else - @endif 
                        @if(isset($customer->province)) {{ \App\Province::find($customer->province)->name }} Province @else - @endif</td>
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
            <button type="button" id="previousVisitsButton" href="#" class="btn btn-warning mr-1" data-toggle="modal" data-target="#previousVisits"><i class="bi bi-calendar4-week"></i> Previous Visits</button>
            <a id="editCustomerButton" href="{{route('customers.edit', $customer->id)}}" class="btn btn-info mr-1"><i class="bi bi-pencil-square"></i> Edit Details</a>
        </div>
    </div>
@endif

@if ( $errors->get('branch') || $errors->get('purpose'))
    <script type="text/javascript">
        $( document ).ready(function() {  
          $('#addVisit').modal('show');
        });
    </script>
@endif

<form action="{{ route('visits.store', ['institute_id' => $institute->id]) }}" method="POST" id="addVisitForm">
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
            <input type="hidden" id="custId" name="custId" value="@if(isset($customer)) {{ $customer->id }}  @endif">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Create</button>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="modal fade" id="previousVisits" tabindex="-1" role="dialog" aria-labelledby="previousVisitsLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="previousVisitsLabel">Previous visits of @if(isset($customer)) {{ $customer->first_name }}  @endif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if(isset($visits))
          @if(count($visits) > 0)
            <div class="accordion" id="accordion">
              @foreach($visits as $visit)
                    <div class="card">
                      <div class="card-header" id="heading{{ $visit->id }}">
                        <h5 class="mb-0">
                          <button class="btn" data-toggle="collapse" data-target="#collapse{{ $visit->id }}" aria-expanded="true" aria-controls="collapse{{ $visit->id }}">
                            Visited {{ \Carbon\Carbon::parse($visit->created_at)->diffForHumans() }} @if($visit->status == "SERVING")<span id="test" class="badge badge-success">ACTIVE</span>@endif
                          </button>
                        </h5>
                      </div>
                      <div id="collapse{{ $visit->id }}" class="collapse" aria-labelledby="heading{{ $visit->id }}" data-parent="#accordion">
                        <div class="card-body">
                          <p class="border border-dark rounded d-inline-block mr-2 ml-0 p-1"><i class="bi bi-diagram-2 ml-0"></i> {{ \App\Branch::find($visit->branch_id)->name }} </p>
                          <p class="border border-dark rounded d-inline-block mr-2 ml-0 p-1"><i class="bi bi-calendar-week"></i> {{ \Carbon\Carbon::parse($visit->created_at)->toFormattedDateString() }} </p>
                          <p class="border border-dark rounded d-inline-block mr-2 ml-0 p-1"><i class="bi bi-stopwatch"></i> @if($visit->status == "SERVING")SERVING... @elseif($visit->start_time == null) N/A @else {{ \Carbon\Carbon::parse($visit->start_time)->diffForHumans($visit->end_time, true) }} @endif</p>
                          <p class="border border-dark rounded d-inline-block mr-2 ml-0 p-1"><i class="bi bi-ticket-perforated"></i> {{ $visit->token_no }} </p>
                          <p><b>Purpose:</b> {{ $visit->purpose }}</p>
                          <p><b>Remarks:</b> {{ $visit->remarks }}</p>
                          <p><b>Actions Taken:</b> {{ $visit->action_taken }}</p>
                        </div>
                      </div>
                    </div>
              @endforeach
            
          @else 
            <p>{{ $customer->first_name }} doesn't have any previous visits to {{ $institute->name }}</p>
          @endif
        @endif
      </div>
    </div>
  </div>
</div>
    
@endsection

@section('scripts')
<script type="text/javascript">

  $(document).ready(function(){

    $( "#previousVisits" ).on('shown', function(){
      function blink_text() {
        jQuery('#test').fadeOut(500);
        jQuery('#test').fadeIn(500);
      }
      setInterval(blink_text, 1000);
    });
    
    
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
          jQuery('#customerForm').after('<div id="visitCard" class="card border-primary mt-3"><div  id="visitHeader" class="card-header bg-primary"><b>Visit of </b></div><div class="card-body"><p class="border border-dark rounded d-inline-block mr-2 ml-0 p-1" name="visited_branch"><i class="bi bi-diagram-2 ml-0"></i> </p><p class="border border-dark rounded d-inline-block mr-2 ml-0 p-1" name="visited_date"><i class="bi bi-calendar-week"></i> </p><p class="border border-dark rounded d-inline-block mr-2 ml-0 p-1" name="visit_status"><i class="bi bi-stopwatch"></i> </p><p class="border border-dark rounded d-inline-block mr-2 ml-0 p-1" name="visit_token"><i class="bi bi-ticket-perforated"></i> </p><p name="visit_purpose"><b>Purpose:</b> </p><p name="visit_remarks"><b>Remarks:</b> </p></div>');
          jQuery("#visitHeader").append('<b>' + "@if(isset($customer)) {{ $customer->first_name }} @endif" + ' </b><span id="test" class="badge badge-success">ACTIVE</span>');
          jQuery('p[name="visited_branch"]').append(data2['branch'].name);
          jQuery('p[name="visited_date"]').append(data2['visit_time']);
          jQuery('p[name="visit_status"]').append(data2['visit'].status);
          jQuery('p[name="visit_token"]').append(data2['visit'].token_no);
          jQuery('p[name="visit_purpose"]').append(data2['visit'].purpose);
          jQuery('p[name="visit_remarks"]').append(data2['visit'].remarks);
          jQuery('#addVisitButton').attr('disabled', true);
        }
            
      }
    });


           
  });

</script>
@endsection