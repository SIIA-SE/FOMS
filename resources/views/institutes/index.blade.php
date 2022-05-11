@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{route('institutes.create')}}" class="btn btn-success float-right">New Institute</a>
</div>

<h4>Your Institutes</h4>

    @forelse($institutes as $institute)

        <div class="d-inline-block mt-4 mr-4 card" style="width: 18rem;">
            <img class="card-img-top" src="{{asset('/storage/' . $institute->image)}}" alt="{{$institute->name}}">
            <div class="card-body">
                <h5 class="card-title">{{$institute->name}}</h5>
                <p class="card-text">Institute Code: {{$institute->code}}</p>
                <a href="#" class="btn btn-primary btn-sm">Open</a>
                <a href="{{route('institutes.edit', $institute->id)}}" class="btn btn-success btn-sm">Edit</a>
                <a href="#" class="btn btn-danger btn-sm" onclick="handleDelete({{ $institute->id }})">Delete</a>
            </div>
        </div>
        
    @empty
    <div class="alert alert-info mt-3" role="alert">
        You don't have any Institutes.
    </div>
    @endforelse

    <form action="" method="POST" id="deleteInstituteForm">
        @csrf
        @method('DELETE')
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Institute</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center font-weight-bold">
                            This action cannot be reverted, Do you want to continue?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Go back</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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