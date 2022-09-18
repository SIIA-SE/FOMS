@extends('layouts.app')

@section('menu')
<div class="list-group">
    <a href="{{route('home')}}" class="list-group-item list-group-item-action {{ Route::is('home') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{route('institutes.index')}}" class="list-group-item list-group-item-action {{ Route::is('institutes.index') ? 'active' : '' }}"><i class="bi bi-building"></i> Institutes</a>
</div>
@endsection


@section('content')
<div class="row border-bottom border-dark">
  <div class="col-auto"><h4 >Welcome, {{ Auth::user()->name }}</h4></div>
  
</div>
<br />

<div class="row">
    <div class="card-body">
      <div class="d-inline-block mt-4 mr-2 card border-dark" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title font-weight-bold"><i class="bi bi-building"></i> Institutes</h5>
          <p class="card-text">Total: {{ count(Auth::user()->institutes()->get()) }} | Joined: {{ count(\App\Staff::where('user_id', Auth::user()->id)->get()) }} | Trashed: {{ count(Auth::user()->institutes()->withTrashed()->get()) }}</p>
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{ count(Auth::user()->institutes()->get()) }}%" aria-valuenow="{{ count(Auth::user()->institutes()->get()) }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div><br />
          <a href="{{route('institutes.index')}}" class="btn btn-primary btn-sm"><i class="bi bi-building"></i> View</a>
        </div>
      </div>
    </div>
</div>

@endsection
