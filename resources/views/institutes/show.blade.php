@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{ Route::is('get-data.index') ? route('institutes.show', $institute->id) : route('institutes.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
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
  <div class="col-auto"><h4 >{{$institute->name}}</h4></div>
  <div class="col-auto"><span class="badge badge-secondary">{{$institute->code}}</span></div>
  <div class="col"><a href="{{route('institutes.index')}}" class="float-right btn btn-sm btn-danger"><i class="bi bi-box-arrow-left"></i> Exit</a></div>
</div>
<br />
@if(Route::is('institutes.show'))
  <div class="justify-content-end mb-3">
      <h3> Dashboard </h3>
  </div>
  <div class="row">
    <div class="card-body">
      <div class="d-inline-block mt-4 mr-2 card border-dark" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title font-weight-bold">Total Staff</h5>
          <p class="card-text">{{ count(\App\Institute::find($institute->id)->staff()->get()) }}</p>
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{ count(\App\Institute::find($institute->id)->staff()->get()) }}%" aria-valuenow="{{ count(\App\Institute::find($institute->id)->staff()->get()) }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div><br />
          <a href="" class="btn btn-primary btn-sm"><i class="bi bi-person-lines-fill"></i> Staff List</a>
        </div>
      </div>

      <div class="d-inline-block mt-4 mr-2 card border-dark" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title font-weight-bold">Total Customers</h5>
          <p class="card-text">{{ count(\App\Institute::find($institute->id)->customers()->get()) }}</p>
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{ count(\App\Institute::find($institute->id)->customers()->get()) }}%" aria-valuenow="{{ count(\App\Institute::find($institute->id)->customers()->get()) }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div><br />
          <a href="" class="btn btn-primary btn-sm"><i class="bi bi-person-lines-fill"></i> Customers</a>
        </div>
      </div>
    

      <div class="d-inline-block mt-4 mr-2 card border-dark" style="width: 18rem;">
          <div class="card-body">
            <h5 class="card-title font-weight-bold">Total Visits</h5>
            <p class="card-text">{{ count(\App\Institute::find($institute->id)->visits()->get()) }}</p>
            <div class="progress">
              <div class="progress-bar" role="progressbar" style="width: {{ count(\App\Institute::find($institute->id)->visits()->get()) }}%" aria-valuenow="{{ count(\App\Institute::find($institute->id)->visits()->get()) }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div><br />
            <a href="" class="btn btn-primary btn-sm"><i class="bi bi-person-lines-fill"></i> Customers</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif

@if(Route::is('add-staff.index'))
  @if($staffRole == 'manager' || $staffRole == 'sys_admin')
    <h4 id="title"><i class="bi bi-person-plus-fill"></i> Staff Requests</h4>
    @forelse(App\Institute::find($institute->id)->staff()->where('status', 2)->get() as $pendingStaff)
      <form action="{{route('add-staff.index', $pendingStaff->id)}}" method="GET">
        <div id="pendingStaff" class="card mb-2">
          <div class="card-body">
            <div class="row">
              <div class="col-8">
                <h4>{{ $pendingStaff->user->name }}</h4>
                <h5>{{ $pendingStaff->institute->name }}</h5>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="exampleFormControlSelect1">User Type:</label>
                  <select class="form-control" id="user_type" name="user_type">
                    <option selected disabled>Select...</option>
                    <option value="user">Branch Staff</option>
                    <option value="frontdeskuser">Fron desk Staff</option>
                    <option value="manager">Management Staff</option>
                    <option value="sys_admin">IT Staff</option>
                  </select>
                </div>
                <div id="branchSelect" class="form-group">
                  <label for="branch">Select Branch</label>
                  <select class="form-control @error('branch') is-invalid @enderror" id="branch" name="branch" value="{{ old('branch') }}">
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
                <button type="submit" name="add_staff_button" value="accept" class="btn btn btn-success mt-2 ml-2 mr-2 rounded-circle"><i class="bi bi-check-lg"></i></button>
                <button type="submit" name="add_staff_button" value="reject" class="btn btn btn-danger mt-2 rounded-circle"><i class="bi bi-x-lg"></i></button>
              </div>
            </div>
          </div>
        </div>
      </form>  
    @empty 
    <div class="alert alert-primary" role="alert"><i class="bi bi-person-plus-fill"></i> Your Institute does not have any Staff requests ! </div>
    @endforelse
  @endif
@endif
@if(Route::is('staff-list.index'))
  @if($staffRole == 'manager' || $staffRole == 'sys_admin')
    <div class="row">
      <div class="col-auto"><h4 id="title"><i class="bi bi-person-lines-fill"></i> Staff List</h4></div>
      <div class="col">
        <select class="filterUser float-right" name="filterUser">
          <option value="all" selected>All Staff</option>
          <option value="user">Branch Staff</option>
          <option value="frontdeskuser">Fron desk Staff</option>
          <option value="manager">Management Staff</option>
          <option value="sys_admin">IT Staff</option>
        </select>
      </div>
    </div>
    <div id="queue" class="rounded p-0">
      @forelse(App\Institute::find($institute->id)->staff()->get() as $allStaff)
        @if($institute->user->id == $allStaff->user_id)
          @continue
        @else
          <form class="@if($allStaff->status == '2') disabled @else {{ $allStaff->role }} @endif" action="{{route('add-staff.index', $allStaff->id)}}" method="GET">
            <div id="allStaff" class="card mb-2">
              <div class="card-body">
                <div class="row">
                  <div class="col-8">
                    <h4>{{ $allStaff->user->name }}</h4>
                    <h5>Joined at: {{ \Carbon\Carbon::parse($allStaff->created_at)->toFormattedDateString() }}</h5>
                    <h5>Email: {{ $allStaff->user->email }}</h5>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="user_type">User Type:</label>
                      <select class="user_type form-control" name="user_type">
                        <option value="user" @if($allStaff->role == 'user') selected @endif)>Branch Staff</option>
                        <option value="frontdeskuser" @if($allStaff->role == 'frontdeskuser') selected @endif>Fron desk Staff</option>
                        <option value="manager" @if($allStaff->role == 'manager') selected @endif>Management Staff</option>
                        <option value="sys_admin" @if($allStaff->role == 'sys_admin') selected @endif>IT Staff</option>
                      </select>
                    </div>
                    <div id="branchSelect{{ $allStaff->id }}" class="branchSelect form-group" name="branchSelect">
                      <label for="branch">Select Branch</label>
                      <select class="form-control @error('branch') is-invalid @enderror" name="branch" value="{{ old('branch$allStaff->id') }}">
                      <option selected disabled>Select...</option>
                      @foreach($institute->branches as $branch)
                          <option value="{{ $branch->id }}" @if($allStaff->branch_id == $branch->id) selected @endif>{{ $branch->name }}</option>
                      @endforeach
                      </select>
                      @error('branch')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                      <button type="submit" name="update_staff_button" value="change" class="btn btn btn-success mt-2 ml-2 rounded"><i class="bi bi-check-lg"></i> Update</button>
                      @if($allStaff->status == '1')
                        <button type="submit" name="update_staff_button" value="disable" class="btn btn btn-danger mt-2 ml-2 rounded"><i class="bi bi-exclamation-octagon-fill"></i> Disable</button>
                      @elseif($allStaff->status == '3')
                        <button type="submit" name="update_staff_button" value="enable" class="btn btn btn-primary mt-2 ml-2 rounded"><i class="bi bi-play-circle-fill"></i> Enable</button>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>  
        @endif
      @empty 
      <div class="alert alert-primary" role="alert"><i class="bi bi-person-plus-fill"></i> Your Institute does not have any active staff ! </div>
      @endforelse
    </div>
  @endif
@endif

@if(Route::is('get-data.index'))
  @if($staffRole == 'manager')
    <h4 id="title"><i class="bi bi-arrow-down-square-fill"></i> Download Data</h4>
    <form id="customerData" class="downloadData" action="{{route('get-data.index', $institute->id)}}" method="GET">
      <div id="downloadData" class="card mb-2">
        <div class="card-body">
          <div class="row">
            <div class="col-8">
              <h4>Customer Data</h4>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="customerSelection" id="allCustomers" value="allCustomers" checked>
                <label class="form-check-label" for="customerSelection">
                  All Customers
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="customerSelection" id="selectedCustomers" value="selectedCustomers">
                <label class="form-check-label" for="customerSelection">
                  Selected Customer
                </label>
              </div>
              <br />
              <div class="form-group">
                <label for="branch">Select Customer</label>
                <input type="text" name="customer" id="customer" class="form-control" placeholder="Enter Customer Name or NIC..." disabled/>
                <div id="results"></div>
                <input type="hidden" id="custId" name="custId" value="0">
                @error('customer')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <input type="hidden" id="Inst_ID" name="Inst_ID" value="{{ $institute->id }}">
            </div>
            <div class="col-4">
              <button type="submit" name="download_data_button" value="customer" class="btn btn btn-primary mt-2 ml-2 rounded"><i class="bi bi-arrow-down-square-fill"></i> Download</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  

    <form id="visitData" class="downloadData" action="{{route('get-data.index', $institute->id)}}" method="GET">
      <div id="downloadData" class="card mb-2">
        <div class="card-body">
          <div class="row">
            <div class="col-8">
              <h4>Visit Data</h4>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="visitSelection" id="allVisits" value="allVisits" checked>
                <label class="form-check-label" for="visitSelection">
                  All Visits
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="visitSelection" id="selectedVisits" value="selectedVisits">
                <label class="form-check-label" for="visitSelection">
                  Selected Customer
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="visitSelection" id="rengeOfVisits" value="rengeOfVisits">
                <label class="form-check-label" for="visitSelection">
                  Date Range
                </label>
              </div>
              <br />
              <div class="form-group">
                <label for="branch">Select Customer</label>
                <input type="text" name="customerForVisits" id="customerForVisits" class="form-control" placeholder="Enter Customer Name or NIC..." disabled/>
                <div id="results2"></div>
                <input type="hidden" id="customerId" name="customerId" value="0">
                @error('customerForVisits')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
            <div class="col-4">
              <button type="submit" name="download_data_button" value="visit" class="btn btn btn-primary mt-2 ml-2 rounded"><i class="bi bi-arrow-down-square-fill"></i> Download</button>
              <div class="form-group">
                <label for="end_date">Select Start Date</label>
                <div class="input-group date" name="start_date" data-provide="datepicker">
                  <input type="text" id="start_date" name="start_date" class="form-control" disabled>
                  <div class="input-group-addon">
                      <span class="glyphicon glyphicon-th"></span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="start_date">Select End Date</label>
                <div class="input-group date" name="end_date" data-provide="datepicker">
                  <input type="text" id="end_date" name="end_date" class="form-control" disabled>
                  <div class="input-group-addon">
                      <span class="glyphicon glyphicon-th"></span>
                  </div>
                </div>
              </div>
              <input type="hidden" id="Inst_ID" name="Inst_ID" value="{{ $institute->id }}">
            </div>
          </div>
        </div>
      </div>
    </form>
  @endif
@endif
@endsection


@section('scripts')
<script type="text/javascript">
  $(document).ready(function () {
    jQuery('.filterUser').on('change',function(){
      var role = $(this).val();
      if(role == 'all'){

        $('form:hidden').each(function() {
          $(this).show();
        });
        
      }else{
        jQuery('form').not('.' + role).hide();
      }
      
    });

    $('input:radio[name="customerSelection"]').change(function(){
      if(jQuery('#selectedCustomers').is(':checked')){
        jQuery('#customer').prop('disabled', false);
        $('#custId').val('');
      }
      else{
        jQuery('#customer').prop('disabled', true);
        $('#custId').val('0');
      }
    });

    $('input:radio[name="visitSelection"]').change(function(){
      if(jQuery('#selectedVisits').is(':checked')){
        jQuery('#customerForVisits').prop('disabled', false);
        $('#customerId').val('');
      }
      else{
        jQuery('#customerForVisits').prop('disabled', true);
        $('#customerId').val('0');
      }
      if(jQuery('#rengeOfVisits').is(':checked')){
        jQuery('#start_date').prop('disabled', false);
        jQuery('#end_date').prop('disabled', false);
      }else{
        jQuery('#start_date').prop('disabled', true);
        jQuery('#end_date').prop('disabled', true);
      }
    });

    $('#customer').on('keyup',function () {

      var query = $(this).val();
      if ($(this).val().length == 0) {
          // Hide the element
          $('#results').hide();
      }else {
          // Otherwise show it
          $('#results').show();
      }

      $.ajax({

        url:'{{ route('customer_search.select', ["institute_id" => $institute->id]) }}',

        type:'GET',

        data:{'name':query},

        success:function (data) {

          $('#results').html(data);
          console.log(data);

          $('li[name="result"]').on('click',function(event){
            $('#custId').val(event.target.id);
            $('#customer').val($('#'+event.target.id).text());
            $('#results').hide();

          });

        }

      })

    });

    $('#customerForVisits').on('keyup',function () {

      var query = $(this).val();
      if ($(this).val().length == 0) {
          // Hide the element
          $('#results2').hide();
      }else {
          // Otherwise show it
          $('#results2').show();
      }

      $.ajax({

        url:'{{ route('customer_search.select', ["institute_id" => $institute->id]) }}',

        type:'GET',

        data:{'name':query},

        success:function (data) {

          $('#results2').html(data);
          console.log(data);

          $('li[name="result"]').on('click',function(event){
            $('#customerId').val(event.target.id);
            $('#customerForVisits').val($('#'+event.target.id).text());
            $('#results2').hide();

          });

        }

      })

      });
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endsection