@extends('layouts.app')

@section('menu')
<div class="list-group">
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
<br />
<div class="card card-default">
    <div class="card-header">
        <b>{{ isset($customer) ? 'Create Visit for ' . $customer->first_name : 'Create New Visit'}}</b>
    </div>
    <div class="card-body">
        <form action="{{ route('visits.store', ['institute_id' => $institute->id]) }}" method="POST" enctype="multipart/form-data">
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
                <label for="remarks">Remarks</label>
                <textarea class="form-control @error('remarks') is-invalid @enderror" rows="3"></textarea>
                @error('remarks')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </form>
    </div>
</div>
@endsection