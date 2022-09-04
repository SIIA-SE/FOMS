@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="@if(Route::is('institutes.index')) {{route('home')}} @elseif(Route::is('trashed-institutes.index')) {{route('institutes.index')}} @else {{route('institutes.index')}} @endif" class="list-group-item list-group-item-action {{ Route::is('home') ? 'active' : '' }}"><i class="bi bi-chevron-left"></i>Back</a>
</div>
<div class="list-group mt-5">
  <a href="{{route('trashed-institutes.index')}}" class="list-group-item list-group-item-action"><i class="bi bi-trash3"></i> Trashed Institutes</a>
</div>
@endsection

@section('content')

<div class="card card-default">
    <div class="card-header">
        <b>{{ isset($institute) ? 'Edit Institute' : 'Create Institute'}}</b>
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

        <form action="{{ isset($institute) ? route('institutes.update', $institute->id) : route('institutes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf 
            
            @if(isset($institute))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Institute Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ isset($institute) ? $institute->name : old('name') }}">
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

            </div>
            <div class="form-group">
                <label for="address">Institute Address</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ isset($institute) ? $institute->address : old('address') }}">
                @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contact_no">Institute Contact Number</label>
                <input type="text" class="form-control @error('contact_no') is-invalid @enderror" name="contact_no" placeholder="i.e:771234567" value="{{ isset($institute) ? $institute->contact_no : old('contact_no') }}">
                @error('contact_no')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="image">Institute Header Image</label>
                <input type="file" class="form-control" name="image" value="{{ isset($institute) ? $institute->image : ''}}">
                <p class="text-sm-left">Image dimensions must be 1024 x 256 pixels</p>
            </div>
            
            <div class="form-group">
                <button class="btn btn-success">{{ isset($institute) ? 'Update Institute' : 'Create Institute'}}</button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')


@endsection