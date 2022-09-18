@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href=" {{ route('institutes.show', $institute->id) }}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  @if($staffRole == 'manager' || $staffRole == 'frontdeskuser')
  <a href="{{ route('customers.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i> Customers</a>
  @endif
  <a href="{{ route('branches.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('branches.index') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Branches</a>

</div>


<br />

<div class="list-group">
  @foreach(Auth::user()->institutes as $userInstitute)
    @if($userInstitute->id == $institute->id || $staffRole == 'manager' || $staffRole == 'sys_admin')
      
        <a href="{{route('add-staff.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-plus-fill"></i> Staff Requests <span class="badge badge-danger">@if(count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) > 0) {{ count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) }} @endif</span></a>
        <a id="staffList" href="{{route('staff-list.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-lines-fill"></i> Staff List</a>
    @endif
  @endforeach
  @if($staffRole == 'manager')
    <a id="generateReports" href="{{route('get-data.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-arrow-down-square-fill"></i> Download Data</a>
  @endif
</div>

<br />
@endsection

@section('content')
<div class="row border-bottom border-dark">
  <div class="col-auto"><h4 >{{ $institute->name }}</h4></div>
  <div class="col-auto"><span class="badge badge-secondary">{{ $institute->code }}</span></div>
  <div class="col"><a href="{{route('institutes.index')}}" class="float-right btn btn-sm btn-danger"><i class="bi bi-box-arrow-left"></i> Exit</a></div>
  
</div>
<br />
@if($staffRole == 'manager' || $staffRole == 'sys_admin')
<div class="d-flex justify-content-end mb-3">
    <a href="#" class="btn btn-success float-right" data-toggle="modal" data-target="#addBranch"><i class="bi bi-plus-circle"></i> Add New Branch</a>
</div>
@endif
<br />
@foreach($institute->branches as $branch)
  @foreach(Auth::user()->staff->where('institute_id', $institute->id) as $userStaff)
    @if($userStaff->branch_id == $branch->id || $staffRole == 'manager' || $staffRole == 'sys_admin' || $staffRole == 'frontdeskuser')
      <div class="d-inline-block mt-4 mr-2 card border-dark" style="width: auto;">
        <div class="card-body">
            <h5 class="card-title">{{ $branch->name }}</h5>
            <p class="card-text">Branch Head: {{ $branch->branch_head }}</p>
            @if($userStaff->branch_id == $branch->id || $staffRole == 'manager' || $staffRole == 'frontdeskuser')
            <a href="{{ route('branches.show', $branch->id) }}" class="btn btn-sm btn-info mr-2"><i class="bi bi-people"></i> View Queue</a>
            @endif
            @if($staffRole == 'frontdeskuser')
            <button type="button" id="addVisitButton" href="#" class="btn btn-sm btn-success mr-1" data-toggle="modal" data-target="#addVisit" data-target-id="{{$branch->id}}"><i class="bi bi-person-video2"></i> Create Visit</button>
            @endif
            @if($staffRole == 'manager' || $staffRole == 'sys_admin')
            <a href="#" class="btn btn-sm btn-success mr-1" data-toggle="modal" data-target="#editBranch" data-target-id="{{$branch->id}}"><i class="bi bi-pencil-square"></i> Edit</a>
            @endif
        </div>
      </div>
    @endif
  @endforeach
@endforeach
<form action="{{ route('branches.store', ['institute_id' => $institute->id]) }}" method="POST" id="addBranchForm">
  @csrf
  <div class="modal fade" id="addBranch" tabindex="-1" role="dialog" aria-labelledby="addBranchLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addBranchLabel">Add New Branch to {{ $institute->name }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Branch Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ isset($customer) ? $customer->first_name : old('name') }}">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="branch_head">Branch Head</label><small class="d-inline-block form-text text-muted ml-1">(Optional)</small>
            <input type="text" class="form-control @error('branch_head') is-invalid @enderror" name="branch_head" value="{{ isset($customer) ? $customer->first_name : old('branch_head') }}">
            @error('branch_head')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Create</button>
        </div>
      </div>
    </div>
  </div>
</form>

<form action="" method="POST" id="editBranchForm">
  @csrf
  @method('PUT')
  <div class="modal fade" id="editBranch" tabindex="-1" role="dialog" aria-labelledby="editBranchLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editBranchLabel">Edit Branch of {{ $institute->name }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Branch Name</label>
            <input type="text" class="name form-control @error('name') is-invalid @enderror" name="name" value="">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="branch_head">Branch Head</label><small class="d-inline-block form-text text-muted ml-1">(Optional)</small>
            <input type="text" class="branch_head form-control @error('branch_head') is-invalid @enderror" name="branch_head" value="">
            @error('branch_head')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </div>
    </div>
  </div>
</form>

<form action="{{ route('visits.store', ['institute_id' => $institute->id]) }}" method="POST" id="addVisitForm">
  @csrf
  <div class="modal fade" id="addVisit" tabindex="-1" role="dialog" aria-labelledby="addVisitLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addVisitLabel">Add New Visit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="branch">Select Customer</label>
                <input type="text" name="customer" id="customer" class="form-control" placeholder="Enter Customer Name or NIC..." />
                <div id="results"></div>
                @error('customer')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="purpose">Purpose of Visit</label>
                <textarea class="form-control @error('purpose') is-invalid @enderror" name="purpose" rows="3"></textarea>
                @error('purpose')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
              <label for="remarks">Remarks</label><small class="d-inline-block form-text text-muted ml-1">(Optional)</small>
              <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks" rows="3"></textarea>
              @error('remarks')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="branchId" name="branch" value="">
            <input type="hidden" id="custId" name="custId" value="">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Create</button>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection

@section('scripts')
<script type="text/javascript">

  $(document).ready(function () {
    $("#addVisit").on("show.bs.modal", function (e) {
      $('#customer').val('');
      var id = $(e.relatedTarget).data('target-id');
      $('#branchId').val(id);

      $('#customer').on('keyup',function () {

        var query = $(this).val();
        if ($(this).val().length == 0) {
            // Hide the element
            $('#results').hide();
        }else {
            // Otherwise show it
            $('#results').show();
        }

        $.ajax({

          url:'{{ route('customer_search.select', ["institute_id" => $institute->id]) }}',

          type:'GET',

          data:{'name':query},

          success:function (data) {

            $('#results').html(data);
            console.log(data);

            $('li[name="result"]').on('click',function(event){
              $('#custId').val(event.target.id);
              $('#customer').val($('#'+event.target.id).text());
              $('#results').hide();

            });

          }

        })

      });

      

        
    });

    $("#editBranch").on("show.bs.modal", function (e) {
      var branch_id = $(e.relatedTarget).data('target-id');
      $('#editBranchForm').attr('action', '{{ url("/branches") }}' + '/' + branch_id);
      $.ajax({

      url: "{{ url('/branches') }}" + "/" + branch_id,

      type:'GET',

      dataType : "json",

      success:function (data) {

        $('.name').val(data.name);
        $('.branch_head').val(data.branch_head);

      }

      })

      
    });
  });
</script>
@endsection