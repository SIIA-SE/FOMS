@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="@if(Route::is('institutes.index')) {{route('home')}} @elseif(Route::is('trashed-institutes.index')) {{route('institutes.index')}}@endif" class="list-group-item list-group-item-action {{ Route::is('home') ? 'active' : '' }}"><i class="bi bi-chevron-left"></i>Back</a>
</div>
<div class="list-group mt-5">
  <a href="{{route('trashed-institutes.index')}}" class="list-group-item list-group-item-action"><i class="bi bi-trash3"></i> Trashed Institutes</a>
</div>
@endsection


@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{route('institutes.create')}}" class="btn btn-success float-right">New Institute</a>
</div>
<h4>Join an Institute</h4>

<div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card card default">
          <div class="card-body">
            <form action="{{ route('join-institutes.index') }}" method="GET">
            @csrf
              <div class="row">
                <div class="col-md-10">
                  <input type="text" name="institute_id" id="institute_id" class="form-control" placeholder="Enter Institute Code..." />
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary" name="institute_id_button" value="join"><i class="bi bi-plus-circle"></i> Join</button>
                </div>
              </div>
            </form>    
          </div>
      </div>
    </div>
</div>

<br />

<div class="accordion" id="accordionExample">
  @if(Route::is('institutes.index'))
    @if(count(Auth::user()->institutes) > 0)
    <div class="card">
      <div class="card-header" id="headingOne">
        <h2 class="mb-0">
          <button class="btn btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            My Institutes
          </button>
        </h2>
      </div>

      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="card-body">
          @forelse($userInstitutes as $institute)
              <div class="d-inline-block mt-4 mr-4 card" style="width: 18rem;">
                  <img class="card-img-top" src="{{asset('/storage/' . $institute->image)}}" alt="{{$institute->name}}">
                  <div class="card-body">
                      <h5 class="card-title">{{$institute->name}}</h5>
                      <p class="card-text">Institute Code: {{$institute->code}}</p>
                      @if(!$institute->trashed())
                          <a href="{{route('institutes.show', $institute->id)}}" class="btn btn-primary btn-sm"><i class="bi bi-door-open"></i> Open</a>
                          <a href="{{route('institutes.edit', $institute->id)}}" class="btn btn-success btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                      @endif
                      <a href="#" class="btn btn-danger btn-sm" onclick="handleDelete({{ $institute->id }})"><i class="bi bi-trash3"></i> {{ $institute->trashed() ? 'Delete': 'Trash' }}</a>
                  </div>
              </div>
          @empty
          <div class="alert alert-info mt-3" role="alert">
          You don't have any Institutes.
          </div>
          @endforelse
        </div>
      </div>
    </div>
    @endif

    @if(count(Auth::user()->staff) > 0)
      <div class="card">
        <div class="card-header" id="headingTwo">
          <h2 class="mb-0">
            <button class="btn btn-block text-left collapsed font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Joined Institutes
            </button>
          </h2>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
          <div class="card-body">
            @forelse($joinedInstitutes as $Joinedinstitute)
            
              @if(!App\Institute::withTrashed()->find($Joinedinstitute->institute_id)->trashed())
                <div class="d-inline-block mt-4 mr-4 card" style="width: 18rem;">
                  <img class="card-img-top" src="{{asset('/storage/' . App\Institute::find($Joinedinstitute->institute_id)->image)}}" alt="{{App\Institute::find($Joinedinstitute->institute_id)->name}}">
                  <div class="card-body">
                    <h5 class="card-title">{{App\Institute::find($Joinedinstitute->institute_id)->name}}</h5>
                    <p class="card-text">Institute Code: {{App\Institute::find($Joinedinstitute->institute_id)->code}}</p>
                    <a href="{{route('institutes.show', App\Institute::find($Joinedinstitute->institute_id)->id)}}" class="btn btn-primary btn-sm"><i class="bi bi-door-open"></i> Open</a>
                      @if($Joinedinstitute->role == 'sys_admin')
                        <a href="{{route('institutes.edit', App\Institute::find($Joinedinstitute->institute_id)->id)}}" class="btn btn-success btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="#" class="btn btn-danger btn-sm" onclick="handleDelete({{ App\Institute::find($Joinedinstitute->institute_id)->id }})"><i class="bi bi-trash3"></i> {{ App\Institute::find($Joinedinstitute->institute_id)->trashed() ? 'Delete': 'Trash' }}</a>
                      @endif
                  </div>
                </div>
              @endif

              
            @empty
            <div class="alert alert-info mt-3" role="alert">
            You don't have any Institutes.
            </div>
            @endforelse
          </div>
        </div>
      </div>
    @endif
  @endif
  @if(Route::is('trashed-institutes.index'))
    @if(count(Auth::user()->institutes()->onlyTrashed()->get()) > 0)
    <div class="card">
      <div class="card-header" id="headingThree">
        <h2 class="mb-0">
          <button class="btn btn-block text-left collapsed font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
            Trashed Institutes
          </button>
        </h2>
      </div>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
        <div class="card-body">
          @forelse($trashedInstitutes as $institute)
              <div class="d-inline-block mt-4 mr-4 card" style="width: 18rem;">
                  <img class="card-img-top" src="{{asset('/storage/' . $institute->image)}}" alt="{{$institute->name}}">
                  <div class="card-body">
                      <h5 class="card-title">{{$institute->name}}</h5>
                      <p class="card-text">Institute Code: {{$institute->code}}</p>
                      <a href="#" class="btn btn-danger btn-sm" onclick="handleDelete({{ $institute->id }})">{{ $institute->trashed() ? 'Delete': 'Trash' }}</a>
                  </div>
              </div>
          @empty
          <div class="alert alert-info mt-3" role="alert">
          You don't have any Institutes.
          </div>
          @endforelse
        </div>
      </div>
    </div>
    @endif
  @endif
</div>



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
                        This will trash the selected item, Do you want to continue?
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