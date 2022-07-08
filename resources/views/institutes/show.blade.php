@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{ route('institutes.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  <a href="{{ route('customers.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i> Customers</a>
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
  <div class="col-auto"><h4 >{{$institute->name}}</h4></div>
  <div class="col-auto"><span class="badge badge-secondary">{{$institute->code}}</span></div>
  <div class="col"><a href="{{route('institutes.index')}}" class="float-right btn btn-sm btn-danger"><i class="bi bi-box-arrow-left"></i> Exit</a></div>
</div>
<br />
@if(Route::is('add-staff.index'))
  <h4>Staff Requests</h4>
    @foreach(App\Institute::find($institute->id)->staff()->where('status', 2)->get() as $pendingStaff)
      <form action="{{route('add-staff.index', $pendingStaff->id)}}" method="GET">
        <div id="pendingStaff" class="card mb-2">
          <div class="card-body">
            <div class="row">
              <div class="col-8">
                <h4>{{ $pendingStaff->user->name }} (Designation)</h4>
                <h5>{{ $pendingStaff->institute->name }}</h5>
              </div>
              <div class="col-4">
                <button type="submit" name="add_staff_button" value="accept" class="btn btn btn-success mt-2 ml-2 mr-2 rounded-circle"><i class="bi bi-check-lg"></i></button>
                <button type="submit" name="add_staff_button" value="reject" class="btn btn btn-danger mt-2 rounded-circle"><i class="bi bi-x-lg"></i></button>
              </div>
            </div>
          </div>
        </div>
      </form>  
    @endforeach
@endif
@endsection

@section('scripts')


@endsection