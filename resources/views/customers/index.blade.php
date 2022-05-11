@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="#" class="btn btn-success float-right">Register New Customer</a>
</div>

<h4>Search Customer</h4>

<div class="row justify-content-center">
  <div class="col-md-12">
    <div class="card card default">
        <div class="card-body">
            <form action="">
                <div class="form-group">
                    <input type="text" class="form-control" name="query" placeholder="Enter name or NIC No.">
                </div>
            </form>
        </div>
    </div>
  </div>
</div>
    

    
@endsection

@section('scripts')

    <script>
        function handleDelete(id) {

            var form = document.getElementById('deleteInstituteForm')
            form.action = '/institutes/' + id
            $('#deleteModal').modal('show')

        }
    </script>

@endsection