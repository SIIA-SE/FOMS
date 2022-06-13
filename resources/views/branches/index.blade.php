@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{route('institutes.index')}}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
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
    <a href="#" class="btn btn-success float-right" data-toggle="modal" data-target="#addBranch"><i class="bi bi-plus-circle"></i> Add New Branch</a>
</div>
<br />
@foreach($institute->branches as $branch)
 <div class="d-inline-block mt-4 mr-2 card" style="width: auto;">
  <div class="card-body">
      <h5 class="card-title">{{ $branch->name }}</h5>
      <p class="card-text">Branch Head: {{ $branch->branch_head }}</p>
      <a href="" class="btn btn-primary mr-2"><i class="bi bi-people"></i> Queue</a>
      <a href="" class="btn btn-success mr-2"><i class="bi bi-plus-circle"></i> Create Visit</a>
  </div>
</div>
@endforeach
<form action="{{ route('branches.store', ['institute_id' => $institute->id]) }}" method="POST" id="addBranchForm">
  @csrf
  <div class="modal fade" id="addBranch" tabindex="-1" role="dialog" aria-labelledby="addBranchLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addBranchLabel">Add New Branch to {{ $institute->name }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Branch Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ isset($customer) ? $customer->first_name : old('name') }}">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="branch_head">Branch Head</label><small class="d-inline-block form-text text-muted ml-1">(Optional)</small>
            <input type="text" class="form-control @error('branch_head') is-invalid @enderror" name="branch_head" value="{{ isset($customer) ? $customer->first_name : old('branch_head') }}">
            @error('branch_head')
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

