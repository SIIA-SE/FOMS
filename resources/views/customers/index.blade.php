@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{route('institutes.show', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  <a href="{{route('customers.index', ['id' => $institute->id])}}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i> Customers</a>
  <a href="{{route('institutes.show', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-diagram-3"></i> Branches</a>
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
    <a href="{{route('customers.create', ['id' => $institute->id])}}" class="btn btn-success float-right">Register New Customer</a>
</div>

<h4>Search Customer</h4>

<div class="row justify-content-center">
  <div class="col-md-12">
    <div class="card card default">
        <div class="card-body">    
            <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Customer Name or NIC..." />
            <div id="results"></div>
        </div>
    </div>
  </div>
</div>
    
@if(isset($customer))
    <div class="card card-default mt-5">
        <div class="card-header">
            <b>Customer Data</b>
        </div>
        <div class="card-body">
            <table class="table table-borderless table-sm table-responsive">
                <tbody>
                    <tr>
                    <th>NIC No:</th>
                    <td>{{ strtoupper($customer->nic_no) }}</td>
                    </tr>
                    <tr>
                    <th>Fullname:</th>
                    <td>{{ $customer->last_name }} {{ $customer->first_name }}</td>
                    
                    </tr>
                    <tr>
                    <th>Gender:</th>
                    <td>{{ $customer->gender }}</td>
                    </tr>
                    <tr>
                    <th>Address:</th>
                    <td>{{ $customer->address }}, 
                        {{ App\GNDivision::find($customer->gn_division)->name }}, 
                        {{ App\DSDivision::find($customer->ds_division)->name }}, 
                        {{ App\District::find($customer->district)->name }}, 
                        {{ App\Province::find($customer->province)->name }} Province</td>
                    </tr>
                    <tr>
                    <th>Contact No:</th>
                    <td>{{ $customer->contact_no }}</td>
                    </tr>
                    <tr>
                    <th>Email:</th>
                    <td>{{ $customer->email }}</td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-primary">Create Visit</button>
        </div>
    </div>
@endif


    
@endsection

@section('scripts')
<script type="text/javascript">

        $(document).ready(function(){

            $('#user_name').on('keyup',function () {

                var query = $(this).val();
                if ($(this).val().length == 0) {
                    // Hide the element
                    $('#results').hide();
                }else {
                    // Otherwise show it
                    $('#results').show();
                }

                $.ajax({

                    url:'{{ route('customer_search.action', ["institute_id" => $institute->id]) }}',

                    type:'GET',

                    data:{'name':query},

                    success:function (data) {

                        $('#results').html(data);

                    }

                })

            });

           
        });

    </script>
@endsection