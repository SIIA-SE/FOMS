@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{route('institutes.index')}}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  <a href="{{route('customers.index')}}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i>Customers</a>
  <a href="{{route('institutes.show', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-diagram-3"></i> Branches</a>
</div>

<br />

<div class="list-group">
  <a href="" class="list-group-item list-group-item-action"><i class="bi bi-person-plus-fill"></i> Staff Requests <span class="badge badge-danger">4</span></a>
</div>

<br />
@endsection

@section('content')
<div class="row">
  <div class="col-auto"><h4 >{{$institute->name}}</h4></div>
  <div class="col-auto"><span class="badge badge-secondary">{{$institute->code}}</span></div>
</div>
<br />

<h4>Staff Requests</h4>
<div class="card mb-2">
  <div class="card-body">
    This is some text within a card body.
  </div>
</div>
@endsection