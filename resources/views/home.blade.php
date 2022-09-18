@extends('layouts.app')

@section('menu')
<div class="list-group">
    <a href="{{route('home')}}" class="list-group-item list-group-item-action {{ Route::is('home') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{route('institutes.index')}}" class="list-group-item list-group-item-action {{ Route::is('institutes.index') ? 'active' : '' }}"><i class="bi bi-building"></i> Institutes</a>
</div>
@endsection


@section('content')
<div class="container">
    <div class="my-5 row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <a href="{{route('institutes.index')}}" class="btn btn-primary">View Institutes</a>
                </div>
            </div>
        </div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
</div>
@endsection
