@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{ route('institutes.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  <a href="{{ route('customers.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i> Customers</a>
  <a href="{{ route('branches.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('branches.index') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Branches</a>
</div>

<br />

@foreach(Auth::user()->institutes as $userInstitute)
  @if($userInstitute->id == $institute->id)
    <div class="list-group">
      <a href="{{route('add-staff.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-plus-fill"></i> Staff Requests <span class="badge badge-danger">@if(count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) > 0) {{ count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) }} @endif</span></a>
      <a id="staffList" href="{{route('staff-list.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-lines-fill"></i> Staff List</a>
    </div>
  @else
  @continue
  @endif
@endforeach

<br />
@endsection

@section('content')
<div id="content" class="row border-bottom">
  <div class="col-auto"><h4 >{{$institute->name}}</h4></div>
  <div class="col-auto"><span class="badge badge-secondary">{{$institute->code}}</span></div>
  <div class="col"><a href="{{route('institutes.index')}}" class="float-right btn btn-sm btn-danger"><i class="bi bi-box-arrow-left"></i> Exit</a></div>
</div>
<br />
@if(Route::is('add-staff.index'))
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
@if(Route::is('staff-list.index'))
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
    @forelse(App\Institute::find($institute->id)->staff()->where('status', 1)->paginate(5) as $allStaff)
      <form class="{{ $allStaff->role }}" action="{{route('add-staff.index', $allStaff->id)}}" method="GET">
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
                <button type="submit" name="update_staff_button" value="change" class="btn btn btn-success mt-2 ml-2 mr-2 rounded"><i class="bi bi-check-lg"></i> Update</button>
              </div>
            </div>
          </div>
        </div>
      </form>  
    @empty 
    <div class="alert alert-primary" role="alert"><i class="bi bi-person-plus-fill"></i> Your Institute does not have any active staff ! </div>
    @endforelse
  </div>
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
  });

  

</script>

@endsection