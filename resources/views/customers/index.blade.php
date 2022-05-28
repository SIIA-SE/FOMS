@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{route('customers.create')}}" class="btn btn-success float-right">Register New Customer</a>
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

                    url:'{{ route('customer_search.action') }}',

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