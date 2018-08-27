@extends ('layouts/aspha') @section('css')
<!-- DataTables -->
<link rel="stylesheet"
	href="{{ asset('adminlte/plugins/datatables/dataTables.bootstrap.css') }}">
@endsection @section('content')
<div class="row">
	<div class="col-lg-9">
		<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title">Live Events Monitoring</h3>
				<div class="box-tools">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-default" id="play">
							<i class="fa fa-play"></i>
						</button>
					</div>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<table id="eventtab" class="table table-hover">
					<thead>
						<tr>
							<th>Date/Time</th>
							<th>Door</th>
							<th>Event Name</th> 
							@if($company=="BCAs")
								<th>Pass ID</th>
							@endif
							<th>Credential</th>
							<th>Method</th>
							<th hidden>BGColor</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($events as $key => $event)
						<tr id={{$event->
							id}}>
							<td>{{$event->msgtime}}</td>
							<td>{{$event->readername}}</td>
							<td>
								@if($event->taskcode == 4 && $event->eventcode == 20)
									{{$event->iolinkname}}
								@else
									{{$event->eventname}}
								@endif
							</td>
							@if($company=="BCAs")
								<td>
									{{$event->passid}}
								</td> 
							@endif
							<td>
								@if($event->credentialname)
									{{$event->credentialname}}
								@else
									None
								@endif
							</td>
							<td>
								@if($event->accesstype==1)
									(Card AND Pin) OR Card Only
								@elseif($event->accesstype==2)
									Card OR Pin
								@elseif($event->accesstype==3)
									Pin only
								@else
									None
								@endif
							</td>
							<td hidden>{{$color[$key]}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
	<div class="col-lg-3">
		<div class="box box-info">
			<form id="editform" class="form-horizontal">
				{{ csrf_field() }}
				<div class="box-body box-profile">
					<h3 class="profile-username text-center eventname">-</h3>
					<p class="text-muted text-center">Front Desk</p>
					<input type="hidden" id="id" class="id">
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<p class="glyphicon glyphicon-user"></p>
							<b> Credential</b> <a
							class="text-green pull-right credentialname">-</a>
						</li>
						<li class="list-group-item">
							<p class="glyphicon glyphicon-registration-mark"></p>
							<b> Unique ID</b> <a class="text-green pull-right uniqueid">-</a>
						</li>
						<li class="list-group-item">
							<p class="fa fa-credit-card"></p>
							<b> Number</b> <a class="text-green pull-right cardnumber">-</a>
						</li>
						<li class="list-group-item">
							<p class="glyphicon glyphicon-barcode"></p>
							<b> Method</b> <a class="text-green pull-right accesstype">-</a>
						</li>
						<li class="list-group-item">
							<p class="fa fa-clock-o"></p>
							<b> Date/time</b> <a class="text-green pull-right msgtime">-</a>
						</li>
					</ul>

					<button type="submit" id="buttonsave"
						class="btn btn-info btn-block" disabled>Save Notes</button>

					<div class="box box-default">
						<div class="box box-body with-border">
							<textarea class="form-control notes" id="notes" rows="5"
								placeholder="Notes"></textarea>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
			</form>
			<div class="overlay disabled"></div>
			<div class="overlay loading" hidden>
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<!-- /.box-loading -->
		</div>
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->


@endsection @section('script')
<!-- DataTables -->
<script
	src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script
	src="{{ asset('adminlte/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>

<style type="text/css">
.highlight {
	background-color: lightblue;
}
</style>

<script>
function highlight(id) {
  $(".loading").show();
  $(".disabled").hide();
  //What happens when the events is clicked
  $.getJSON("/monitor/live/event/" + id,
    function(data, status){
      $("#buttonsave").prop("disabled", true);
      if(status == 'success'){
        $(".id").val(data.id);
        $(".credentialname").text(data.credentialname);
        if(data.credentialname)$(".credentialname").text(data.credentialname);else $(".credentialname").text('None');
        if(data.uniqueid)$(".uniqueid").text(data.uniqueid);else $(".uniqueid").text('None');
        if(data.cardnumber == 0 && data.cardbits == 26 && data.cardsetid == 0)
					$(".cardnumber").text('0 - '+data.printednumber);
				else if(data.cardnumber == 0 && data.cardbits == 26)
					$(".cardnumber").text(data.cardsetid + ' - ' + data.printednumber);
				else if(data.cardnumber == 0 && data.cardbits == 32)
					$(".cardnumber").text(data.printednumber);
				else if(data.hexcardpin)
					$(".cardnumber").text(data.hexcardpin);
				else if(data.cardnumber == null)
					$(".cardnumber").text('None');
				else
					$(".cardnumber").text(data.cardnumber);
        if(data.accesstype==1)
          var accesstype = 'Card and PIN OR Card only';
        else if(data.accesstype==2)
          var accesstype = 'Card OR PIN';
        else if(data.accesstype==3)
          var accesstype = 'PIN only';
        else
          var accesstype = 'None';
        $(".accesstype").text(accesstype);
        if(data.taskcode == 4 && data.eventcode == 20)
          $(".eventname").text(data.iolinkname);
        else
          $(".eventname").text(data.eventname);
        $(".msgtime").text(data.msgtime);
        $(".notes").text(data.notes);
        $("#buttonsave").prop("disabled", false);
      }
  })
  .done(function() {
    $(".loading").hide();
  })
  .fail(function() {
    alert("Failed to contact server");
  });
}

$( document ).ready(function() {
  //Highlight the credential in the flashed session
  @if(Session::has('id'))
    //$('#eventtab tr').attr({{Session::get('id')}}).addClass("highlight");
    $("#{{Session::get('id')}}").addClass("highlight");
    highlight({{Session::get('id')}});
  @endif

  //Initializing table
	if("{{$company}}" == 'BCAs'){
		var table = $("#eventtab").DataTable({
			"lengthChange": false,
			"ordering": true,
			"columnDefs": [
					{
							"render": function ( data, type, row ) {
								var d = data.substring(16, 18);
								var m = data.substring(13, 15);
								var y = data.substring(19, 23);
								var gmt = data.substring(9, 12);
								var time = data.substring(0, 8);
								return y + '/' + m + '/' + d + ' ' + gmt + ' ' + time;
							},
							"targets": 0
					}
			],
			"order": [[ 0, "desc" ]],
			"createdRow": function ( row, data, index ) {
				$('td', row).css('background-color', data[6]);
			}
		});
	}else{
		var table = $("#eventtab").DataTable({
			"lengthChange": false,
			"ordering": true,
			"columnDefs": [
					{
							"render": function ( data, type, row ) {
								var d = data.substring(16, 18);
								var m = data.substring(13, 15);
								var y = data.substring(19, 23);
								var gmt = data.substring(9, 12);
								var time = data.substring(0, 8);
								return y + '/' + m + '/' + d + ' ' + gmt + ' ' + time;
							},
							"targets": 0
					}
			],
			"order": [[ 0, "desc" ]],
			"createdRow": function ( row, data, index ) {
				$('td', row).css('background-color', data[5]);
			}
		});
	}

	//check if have :8000
	var str = "{{$_SERVER['HTTP_HOST']}}";
	if(str.indexOf(":8000")!=-1)
		var ws = str.slice(0, -5);
	else
		var ws = str;
  //create a new WebSocket object.
  var wsUri = "ws://"+ws+":9000/";
  websocket = new WebSocket(wsUri);
  //Message received from server?
  websocket.onmessage = function(ev) {
	var msg = JSON.parse(ev.data); //PHP sends Json data
	var id = msg.id; //user name
	var uname = msg.name; //user name
  var doorname = msg.doors; //door name
	var umsg = msg.message; //message type alarm or event
	var ioevent = msg.ioevent; //iolinkerid
  var msgtime = msg.msgtime; //message time
  var controller = msg.controller; //controller name
  var eventname = msg.eventname; //event name
  var credential = msg.credential; //credential name
  var accesstype = msg.accesstype; //credential accesstype
	var location = msg.location; //remedy location
	var passid = msg.passid; //passid
  var readeraddress = msg.readeraddress; //readeraddress 0 or 1 left or right
  var reader = msg.reader;
  var color = msg.color;

  var doorname = reader;
  /*
  if (readeraddress == 0) {
		doorname = "IN " + doorname;
	} else if (readeraddress == 1) {
		doorname = "OUT " + doorname;
	}
  */
    console.log(msg);

  	if(umsg == 'event' || (umsg == 'alarm' && (ioevent == '9000;1' || ioevent == '9100;1' || ioevent == '9010;1' || ioevent == '9110;1' || ioevent == '9000;0' || ioevent == '9100;0' || ioevent == '9010;0' || ioevent == '9110;0')))
  	{
			if("{{$company}}" == 'BCAs'){
				if("{{env('APP_BCA')}}"==location||location==null){
					var newEvent = table.row.add( [
						msgtime,
						doorname,
						eventname,
						passid,
						credential,
						accesstype,
						""
					] ).draw( false ).node();
					$(newEvent).attr('id', id).css('background-color', color);
				}
			}else{
				var newEvent = table.row.add( [
					msgtime,
					doorname,
					eventname,
					credential,
					accesstype,
					""
				] ).draw( false ).node();
				$(newEvent).attr('id', id).css('background-color', color);
			}
  	}
  };
});

$('#eventtab tbody').on('click', 'tr', function () {
  var selected = $(this).hasClass("highlight");
  $("#eventtab tr").removeClass("highlight");
  if(!selected)
    $(this).addClass("highlight");
    highlight((this.id));
});

$("#editform").submit(function(event){
  $(".loading").show();
  $.post( "event/edit",{ "_token": "{{ csrf_token() }}", id: $("#id").val(), notes: $("#notes").val()}, function( data ) {
    $("#result").html(data);
  })
  .done(function(data){
    location.reload();
    $(".loading").hide();
  })
  .fail(function(data) {
    if( data.status === 422 ) {
        var errors = data.responseJSON; //get the errors response data.

        var errorsHtml = '<div class="alert alert-danger alert-dismissible">';
        errorsHtml += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        errorsHtml += '<h4><i class="icon fa fa-ban"></i> Error!</h4>';

        $.each( errors, function( key, value ) {
            errorsHtml += '<li>' + value[0] + '</li>';
        });

        errorsHtml += '</div>';

        $("#globalmessage").html(errorsHtml);
    }
    $("#loading").hide();
  });
  event.preventDefault();
});

$("#play").click(function(){
  $(".loading").show();
  $.post( "force/event",{ "_token": "{{ csrf_token() }}"}, function( data ) {})
  .done(function(data){
	$(".loading").hide();
  })
  .fail(function(data) {
    alert("Failed");
    $(".loading").hide();
  });
});

</script>
@endsection
