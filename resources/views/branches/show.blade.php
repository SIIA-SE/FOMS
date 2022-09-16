@extends('layouts.app')

@section('menu')
<div class="list-group">
  <a href="{{route('branches.index', ['id' => $institute->id])}}" class="list-group-item list-group-item-action"><i class="bi bi-chevron-left"></i>Back</a>
  <a href="{{route('customers.index', ['id' => $institute->id])}}" class="list-group-item list-group-item-action {{ Route::is('customers.index') ? 'active' : '' }}"><i class="bi bi-person-video2"></i> Customers</a>
  <a href="{{ route('branches.index', ['id' => $institute->id]) }}" class="list-group-item list-group-item-action {{ Route::is('branches.show') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Branches</a>
</div>

<br />

@foreach(Auth::user()->institutes as $userInstitute)
  @if($userInstitute->id == $institute->id)
    <div class="list-group">
      <a href="{{route('add-staff.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-plus-fill"></i> Staff Requests <span class="badge badge-danger">@if(count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) > 0) {{ count(App\Institute::find($institute->id)->staff()->where('status', 2)->get()) }} @endif</span></a>
      <a id="staffList" href="{{route('staff-list.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-person-lines-fill"></i> Staff List</a>
      <a id="generateReports" href="{{route('get-data.index', $institute->id)}}" class="list-group-item list-group-item-action"><i class="bi bi-arrow-down-square-fill"></i> Download Data</a>
    </div>
  @else
  @continue
  @endif
@endforeach

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

<div class="row">
  <div class="col-auto"><h4 id="title"> Customer Queue</h4></div>
  <div class="col"><p id="refresh" class="float-right btn btn-sm btn-dark"><i class="bi bi-arrow-repeat"></i> Refresh</p></div>
</div>

<div id="queue" class="rounded p-0">
  @php $count = 0; @endphp
  @foreach($institute->visits()->orderBy('created_at', 'desc')->get() as $visit)
    @if($visit->branch_id == $branch->id)
      @if($visit->status == "IN QUEUE") 
      @php $count++; @endphp
        <div class="queueCard card border-dark mt-3">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <p name="first_name"><i class="bi bi-person-fill"></i> {{ $visit->customer->first_name }}</p>
              </div>
              <div class="col-3">
                <p ame="contact_no"><i class="bi bi-telephone-fill"></i> {{ $visit->customer->contact_no }}</p>
              </div>
              <div class="col-3">
                <p name="visited_date"><i class="bi bi-calendar-week-fill"></i> {{ \Carbon\Carbon::parse($visit->created_at)->diffForHumans() }}</p>
              </div>
              <div class="col">
                <p name="visit_token"><i class="bi bi-ticket-perforated-fill"></i> {{ $visit->token_no }}</p>
              </div>
              <div class="col-2">
                <p name="visit_satus"><i class="bi bi-gear-fill"></i> {{ $visit->status }}</p>
              </div>
            </div>
            
            <p name="visit_purpose"><b>Purpose:</b> {{ $visit->purpose }}</p>
            <p name="visit_remarks"><b>Remarks:</b> {{ $visit->remarks }}</p>
            <div class="queueControlls float-right">
              <button id="customerInfoButton" class="btn btn-sm btn-primary mr-1" data-toggle="modal" data-target="#customerInfo" data-target-id="{{ $visit->customer->nic_no}}"><i class="bi bi-person-video2"></i> Customer Info</button>
              <button id="callupTokenButton" class="btn btn-sm btn-success mr-1" data-toggle="modal" data-target="#callupToken" data-target-id="{{ $visit->token_no }}" data-visit-id="{{ $visit->id }}"><i class="bi bi-display-fill"></i> Callup Customer</button>
            </div>
          </div>
          
        </div>
      @endif
    @endif
  @endforeach
  @if($count == 0)
    <div class="alert alert-primary" role="alert"><i class="bi bi-people-fill"></i> Queue is empty for {{ $branch->name }}!</div>
  @endif
</div>



<div class="modal fade" id="customerInfo" tabindex="-1" role="dialog" aria-labelledby="customerInfoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerInfoLabel">Customer Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless table-sm">
          <tbody>
              <tr>
              <th scope="row">NIC No:</th>
              <td class="nic_no"></td>
              </tr>
              <tr>
              <th scope="row">Fullname:</th>
              <td class="fullname"></td>
              
              </tr>
              <tr>
              <th scope="row">Gender:</th>
              <td class="gender"></td>
              </tr>
              <tr>
              <th scope="row">Address:</th>
              <td class="address"></td>
              </tr>
              <tr>
              <th scope="row" class="col-md-2">Contact No:</th>
              <td class="contact_no"></td>
              </tr>
              <tr>
              <th scope="row">Email:</th>
              <td class="email"></td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<form action="{{ route('visit.change') }}" method="GET" id="callupCustomerForm">
  <div class="modal fade" id="callupToken" tabindex="-1" role="dialog" aria-labelledby="callupTokenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="callupTokenLabel">Callingup Customer... </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="token_no" style="font-size: 200px;text-align: center;"></div>
        </div>
        <div class="modal-footer">
          <input type="hidden" id="visitId" name="visitId" value="">
          <button type="submit" name="visit_status" value="return" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Return Customer</button>
          <button type="submit" name="visit_status" value="serve" class="btn btn-success"><i class="bi bi-person-check-fill"></i> Serve Customer</button>
        </div>
      </div>
    </div>
  </div>
</form>

<form action="{{ route('visit.change') }}" method="GET" id="completeServiceForm">
  <div class="modal fade" id="completeService" tabindex="-1" role="dialog" aria-labelledby="completeServiceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="completeServiceLabel">Complete Visit of </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
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
          <input type="hidden" id="vis_ID" name="vis_ID" value="">
          <button type="submit" name="visit_status" value="complete" class="btn btn-primary"><i class="bi bi-person-check-fill"></i> Complete</button>
        </div>
      </div>
    </div>
  </div>
</form>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js" integrity="sha256-80OqMZoXo/w3LuatWvSCub9qKYyyJlK0qnUCYEghBx8=" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">

  $(document).ready(function () {

    $("#customerInfo").on("show.bs.modal", function (e) {
      var customer_nic = $(e.relatedTarget).data('target-id');
      jQuery.ajax({
        url : "{{ url('/getCustomer') }}" +  "/" + "{{ $institute->id }}" + "/" + customer_nic,
        type : "GET",
        dataType : "json",
        success:function(data){    
          if(!jQuery.isEmptyObject(data)){
            //console.log(data);
            jQuery(".nic_no").empty();
            jQuery(".nic_no").append(data[0].nic_no);
            jQuery(".fullname").empty();
            jQuery(".fullname").append(data[0].last_name + ' ' + data[0].first_name);
            jQuery(".gender").empty();
            jQuery(".gender").append(data[0].gender);
            jQuery(".address").empty();
            jQuery(".address").append(data[0].address);
            jQuery(".contact_no").empty();
            jQuery(".contact_no").append(data[0].contact_no);
            jQuery(".email").empty();
            jQuery(".email").append(data[0].email);
          }
        }
      });
    });

    $("#callupToken").on("show.bs.modal", function (e) {
      var token_no = $(e.relatedTarget).data('target-id');
      var visit_id = $(e.relatedTarget).data('visit-id');
      console.log(visit_id);
      jQuery(".token_no").empty();
      jQuery(".token_no").append(token_no);
      jQuery("#visitId").empty();
      jQuery("#visitId").val(visit_id);
      
    });

    $("#completeService").on("show.bs.modal", function (e) {
      var visit_id = $(e.relatedTarget).data('target-id');
      console.log(visit_id);
      jQuery("#vis_ID").empty();
      jQuery("#vis_ID").val(visit_id);
      
    });

    jQuery('#refresh').on('click',function(){
      jQuery('#queue').empty();
      jQuery.ajax({
        url : "{{ url('/getQueue') }}" + "/" + "{{ $branch->id }}",
        type : "GET",
        dataType : "json",
        success:function(data){   
          if(!jQuery.isEmptyObject(data)){
            jQuery.each(data, function(key,value){
              jQuery.ajax({
                url : "{{ url('/getCustomerById') }}" +  "/" + data[key].customer_id,
                type : "GET",
                dataType : "json",
                success:function(data2){    
                    if(!jQuery.isEmptyObject(data2)){
                      //first_name = data2.first_name;
                      //contact_no = data2.contact_no;
                      jQuery('#queue').append('<div class="queueCard card border-dark mt-3"><div class="card-body"><div class="row"><div class="col"><p name="first_name"><i class="bi bi-person-fill"></i> ' + data2.first_name + '</p></div><div class="col-3"><p name="contact_no"><i class="bi bi-telephone-fill"></i> ' + data2.contact_no + '</p></div><div class="col-3"><p name="visited_date"><i class="bi bi-calendar-week-fill"></i> ' + moment(data[key].created_at).fromNow()  + '</p></div><div class="col"><p name="visit_token"><i class="bi bi-ticket-perforated-fill"></i> ' + data[key].token_no + '</p></div><div class="col-2"><p name="visit_satus"><i class="bi bi-gear-fill"></i> ' + data[key].status + '</p></div></div><p name="visit_purpose"><b>Purpose:</b> ' + data[key].purpose + '</p><p name="visit_remarks"><b>Remarks:</b> ' + data[key].remarks + '</p><div class="queueControlls float-right"><button id="customerInfoButton" class="btn btn-sm btn-primary mr-1" data-toggle="modal" data-target="#customerInfo" data-target-id="' + data2.nic_no + '"><i class="bi bi-person-video2"></i> Customer Info</button><button id="callupTokenButton" class="btn btn-sm btn-success mr-1" data-toggle="modal" data-target="#callupToken" data-target-id="' + data[key].token_no + '" data-target-id="' + data[key].id + '"><i class="bi bi-display-fill"></i> Callup Customer</button></div></div></div>');
                    }
                  }
              });
              
              
              
            });
            console.log(data);
            //jQuery('#queue').empty();
            //jQuery('#queue').append("test");
            
          }
          else{
            
            jQuery('#queue').append('<div class="alert alert-primary" role="alert"><i class="bi bi-people-fill"></i> Queue is empty for {{ $branch->name }}!</div>');
            
          }
        }
      });
    });

    jQuery('#serve_list').on('click',function(){
      jQuery('#queue').empty();
      jQuery('#title').text('Serving List');
      jQuery('#refresh').remove();

      jQuery.ajax({
        url : "{{ url('/getServeList') }}" + "/" + "{{ $branch->id }}",
        type : "GET",
        dataType : "json",
        success:function(data){   
          if(!jQuery.isEmptyObject(data)){
            jQuery.each(data, function(key,value){
              jQuery.ajax({
                url : "{{ url('/getCustomerById') }}" +  "/" + data[key].customer_id,
                type : "GET",
                dataType : "json",
                success:function(data2){    
                  if(!jQuery.isEmptyObject(data2)){
                    //first_name = data2.first_name;
                    //contact_no = data2.contact_no;
                    
                    jQuery('#queue').append('<div class="queueCard card border-dark mt-3"><div class="card-body"><div class="row"><div class="col"><p name="first_name"><i class="bi bi-person-fill"></i> ' + data2.first_name + '</p></div><div class="col-3"><p name="contact_no"><i class="bi bi-telephone-fill"></i> ' + data2.contact_no + '</p></div><div class="col-3"><p name="visited_date"><i class="bi bi-calendar-week-fill"></i> ' + moment(data[key].created_at).fromNow()  + '</p></div><div class="col"><p name="visit_token"><i class="bi bi-ticket-perforated-fill"></i> ' + data[key].token_no + '</p></div><div class="col-2"><p id="visit' + data2.id + '" name="visit_satus" class="visit_satus"><i class="bi bi-stopwatch-fill"></i></p></div></div><p name="visit_purpose"><b>Purpose:</b> ' + data[key].purpose + '</p><p name="visit_remarks"><b>Remarks:</b> ' + data[key].remarks + '</p><div class="queueControlls float-right"><button id="customerInfoButton" class="btn btn-sm btn-primary mr-1" data-toggle="modal" data-target="#customerInfo" data-target-id="' + data2.nic_no + '"><i class="bi bi-person-video2"></i> Customer Info</button><button id="completeServe" class="btn btn-sm btn-success mr-1" data-toggle="modal" data-target="#completeService" data-target-id="' + data[key].id + '"><i class="bi bi-person-check-fill"></i> Complete</button></div></div></div>');

                    var start_time = data[key].start_time;

                    setInterval(function() {
                        var duration = moment.duration(moment().diff(start_time));
                        var hours = duration.hours();
                        var minutes = duration.minutes();
                        var seconds = duration.seconds();
                        $('#visit' + data2.id).empty();
                        $('#visit' + data2.id).append('<i class="bi bi-stopwatch-fill"></i> ');
                        $('#visit' + data2.id).append(("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2));
                    }, 1000);
                  }
                }
              });
            })
          }else{
            
            jQuery('#queue').append('<div class="alert alert-primary" role="alert"><i class="bi bi-stickies-fill"></i> Serving List is empty for {{ $branch->name }}!</div>');
            
          }
        }
      });
    });

  });
</script>
@endsection