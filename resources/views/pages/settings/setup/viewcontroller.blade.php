@extends('layouts.aspha')

@section('content')
<div class="container">
  <div class="box">
    <div class="box-body">
      <div class="row">
        <div class="col-lg-3">
          <button class="btn btn-success" id="syncall">Sync All Controller</button>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-lg-4">
          <div class="list-group" style="height:570px;overflow:auto;overflow-x:hidden">
            @if(count($controllers) === 0)
              <a class="list-group-item" iid=0>No Controller, please add</a>
            @else
              @foreach ($controllers as $controller)
                <a class="list-group-item" iid={{ $controller->iid }}>{{ $controller->name }}</a>
              @endforeach
            @endif
          </div>
        </div>
        <div class="col-lg-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Controller Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form-horizontal">
                <div class="form-group">
                  <label class="col-lg-3 control-label">IID:</label>
                  <label class="col-lg-3 control-label" id="iid"></label>
                </div>
                <div class="form-group">
                  <label class="col-lg-3 control-label">Name:</label>
                  <label class="col-lg-3 control-label" id="name"></label>
                </div>
                <hr>
                <div class="form-group">
                  <label class="col-lg-3 control-label">IP:</label>
                  <label class="col-lg-2 control-label" id="ip"></label>
                  <label class="col-lg-2 control-label">Port:</label>
                  <label class="col-lg-2 control-label" id="port"></label>
                </div>
                <div class="form-group">
                  <label class="col-lg-3 control-label">Mac:</label>
                  <label class="col-lg-2 control-label" id="mac"></label>
									<label class="col-lg-2 control-label">Device:</label>
                  <label class="col-lg-2 control-label" id="device"></label>
                </div>
								<div class="form-group">
                  <label class="col-lg-3 control-label">Door Strike:</label>
                  <label class="col-lg-2 control-label" id="doorstrike"></label>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <button class="btn btn-danger pull-left" id="delete" disabled>Delete</button>
              <div class="btn-group pull-right">
                <button class="btn btn-primary" id="edit" disabled>Edit</button>
                <button class="btn btn-success" id="sync" disabled>Sync</button>
              </div>
            </div>
            <!-- /.box-footer -->
            <div class="overlay loading" hidden>
              <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!-- /.box-loading -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</div>
<!-- /.container -->

<div class="modal fade" id="editmodal">
  <div class="modal-dialog">
      <div class="box">
        <div class="box-header with-border">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Edit Controller</h4>
        </div>
        <!-- form start -->
        <form id="editform" name="editform" class="form-horizontal">
          <input type="hidden" id="oldiid" name="oldiid">
          <input type="hidden" id="oldip" name="oldip">
          <input type="hidden" id="oldmac" name="oldmac">
          <div class="box-body">
            <div id="message">
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">IID</label>
              <div class="col-lg-5">
                <input type="number" class="form-control" id="editiid" name="iid" autocomplete="off" >
              </div>
							<label class="col-lg-1 control-label">Device</label>
              <div class="col-lg-4">
								<select id="editdevice" class="form-control" name="editdevice">
									<option value="" disabled {{ empty(old('device')) ? 'selected' : '' }}>Select Device</option>
									<option value="V1000" {{ old('device') == 'V1000' ? 'selected' : '' }}>V1000</option>
									<option value="V2000" {{ old('device') == 'V2000' ? 'selected' : '' }}>V2000</option>
									<option value="EH400" {{ old('device') == 'EH400' ? 'selected' : '' }}>EH400</option>
								</select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">IP</label>
              <div class="col-lg-5">
                <div class="input-group">
                  <div class="input-group-addon">
                  <i class="fa fa-laptop"></i>
                  </div>
                  <input type="text" class="form-control" id="editip" name="ip" data-inputmask="'alias': 'ip'" data-mask autocomplete="off">
                </div>
              </div>
              <label class="col-lg-1 control-label">Port</label>
              <div class="col-lg-4">
                <div class="input-group">
                  <div class="input-group-addon">
                  <i class="fa fa-laptop"></i>
                  </div>
                  <input type="number" class="form-control" id="editport" name="port" autocomplete="off" min=0 max=65535>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">Mac</label>
              <div class="col-lg-5">
                <input type="text" class="form-control" id="editmac" name="mac" data-inputmask="'alias': 'mac'" data-mask autocomplete="off" maxlength="17">
              </div>
							<label class="col-lg-1 control-label">Door Strike</label>
              <div class="col-lg-4">
								<select id="editdoorstrike" class="form-control" name="editdoorstrike" value="{{old('editdoorstrike')}}">
									<option value="0" {{ old('editdoorstrike') == '0' ? 'selected' : '' }}>Single Door</option>
									<option value="1" {{ old('editdoorstrike') == '1' ? 'selected' : '' }}>Double Door</option>
								</select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-2 control-label">Name</label>
              <div class="col-lg-5">
                <input type="text" class="form-control" id="editname" name="name" autocomplete="off" maxlength="30">
              </div>
            </div>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary pull-right">Save changes</button>
          </div>
        </form>
        <div class="overlay loading" hidden>
          <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- /.box-loading -->
      </div>
      <!-- /.box -->
  </div>
</div>
@endsection

@section('script')
<!-- Jquery UI -->
<script src="{{ asset('adminlte/plugins/jQueryUI/jquery-ui.min.js')}}"></script>
<!-- InputMask -->
<script src="{{ asset('adminlte/plugins/Inputmask-3.3.3/dist/jquery.inputmask.bundle.js') }}"></script>

<script>
$(document).ready(function(){
  //IP & Mac Mask
  $("[data-mask]").inputmask();
  //Highlight the in the flashed session
  @if(Session::has('iid'))
    highlight({{Session::get('iid')}});
  @endif
});

$("#editdevice").autocomplete({
	source: function( request, response ) {
		if($("#editdevice option:selected").val()=='V1000'){
			document.getElementById('editdoorstrike').disabled = true;
			$("#editdoorstrike").val(0);
		}else{
			document.getElementById('editdoorstrike').disabled = false;
		}
	}
});

//When list-group list is clicked
$('.list-group .list-group-item').on('click', function(){
  highlight($(this).attr("iid"));
});

function highlight(iid) {
    $(".loading").show();
    //List
    $('.list-group .list-group-item.active').removeClass('active');
    $(".list-group .list-group-item[iid='"+iid+"']").toggleClass('active');
    //What happens when the list-group title is clicked
    $.getJSON("/settings/controller/" + iid,
      function(data, status){
        $("#edit").prop("disabled", true);
        $("#delete").prop("disabled", true);
        $("#sync").prop("disabled", true);
        if(status == 'success'){
          $("#iid").text(data.iid);
          $("#name").text(data.name);
          $("#ip").text(data.ip);
          $("#port").text(data.port);
          $("#mac").text(data.mac);
					if(data.doorstrike==0)
						$("#doorstrike").text("Single Door");
					else if(data.doorstrike==1)
						$("#doorstrike").text("Double Door");
					else
						$("#doorstrike").text("None");
					if(data.device=='V1000'){
						document.getElementById('editdoorstrike').disabled = true;
					}
          $("#device").text(data.device);
          $("#edit").prop("disabled", false);
          $("#sync").prop("disabled", false);
          $("#delete").prop("disabled", false);
        }
    })
    .done(function() {
      $(".loading").hide();
    })
    .fail(function() {
      //Do something here
    });
}

//When edit is clicked
$("#edit").click(function(){
  //Toggle the modal
  $('#editmodal').modal('toggle');
  $("#oldiid").val($("#iid").text());
  $("#oldip").val($("#ip").text());
  $("#oldmac").val($("#mac").text());
  $("#editiid").val($("#iid").text());
  $("#editname").val($("#name").text());
  $("#editip").val($("#ip").text());
  $("#editport").val($("#port").text());
  $("#editmac").val($("#mac").text());
	if($("#doorstrike").text()=="Single Door")
		$("#editdoorstrike").val(0);
	else if($("#doorstrike").text()=="Double Door")
		$("#editdoorstrike").val(1);
  $("#editdevice").val($("#device").text());
});
//When edit form is submitted
$("#editform").submit(function(event){
  $(".loading").show();
  $.post( "controller/edit",{ "_token": "{{ csrf_token() }}", oldiid: $("#oldiid").val(), iid: $("#editiid").val(), name: $("#editname").val(), oldip: $("#oldip").val(), ip: $("#editip").val(), port: $("#editport").val(), oldmac: $("#oldmac").val(), mac: $("#editmac").val(), doorstrike: $("#editdoorstrike").val(), device: $("#editdevice").val()}, function( data ) {})
  .done(function(data){
    location.reload();
    $('#editmodal').modal('toggle');
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

      $("#message").html(errorsHtml);
      $(".loading").hide();
    }
  });
  event.preventDefault();
});
//When delete button is clicked
$("#delete").click(function(){
  $(".loading").show();
  $.post("controller/delete", { "_token": "{{ csrf_token() }}", iid: $("#iid").text() }, function(data){})
  .done(function(data){
    location.reload();
  })
  .fail(function(data){
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
    $(".loading").hide();
  });
});

$("#syncall").click(function(){
  $(".loading").show();
  $.post("controller/sync", { "_token": "{{ csrf_token() }}" }, function(data){})
  .done(function(data){
    location.reload();
  })
  .fail(function(data){
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
    $(".loading").hide();
  });
});

$("#sync").click(function(){
  $(".loading").show();
  $.post("controller/sync", { "_token": "{{ csrf_token() }}", iid:$("#iid").text()}, function(data){})
  .done(function(data){
    location.reload();
  })
  .fail(function(data){
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
    $(".loading").hide();
  });
});
</script>
@endsection
