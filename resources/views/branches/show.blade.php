@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{route('branches.index', ['id' => $institute->id])}}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  <a href="{{route('customers.index', ['id' => $institute->id])}}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i> Customers</a>
  <a href="{{ route('branches.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('branches.show') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Branches</a>
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
  <div class="col"><a href="{{route('institutes.index')}}" class="float-right btn btn-sm btn-danger"><i class="bi bi-box-arrow-left"></i> Exit</a></div>
  
</div>
<br />
<div class="justify-content-end mb-3">
    <h3> {{ $branch->name }}</h3>
</div>
<br />

<h4> Customer Queue</h4>

<div id="queueCard" class="card border-primary mt-3">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <p name="visited_date"><i class="bi bi-calendar-week"></i> Visited Time</p>
            </div>
            <div class="col">
                <p ame="visit_token"><i class="bi bi-ticket-perforated"></i> Token No.</p>
            </div>
            <div class="col">
                <p ame="visit_status"><i class="bi bi-stopwatch"></i> Duration</p>
            </div>
        </div>
        <p name="visit_purpose"><b>Purpose:</b> </p>
        <p name="visit_remarks"><b>Remarks:</b> </p>
        <div id="queueControlls" class="float-right">
            <button class="btn btn-sm btn-primary mr-1"><i class="bi bi-person-video2"></i> Customer Info</button>
            <button class="btn btn-sm btn-success mr-1"><i class="bi bi-journal-arrow-down"></i> Serve</button>
            <button class="btn btn-sm btn-danger mr-1"><i class="bi bi-arrow-counterclockwise"></i> Return</button>
        </div>
    <div>
</div>
          
@endsection

@section('scripts')
<script type="text/javascript">

  $(document).ready(function () {
    $('#queueControlls').hide();

    $('#queueCard').hover(
        function () {
        $('#queueControlls').show();
      },
      function () {
        $('#queueControlls').hide();
      }
    );
  });
</script>
@endsection