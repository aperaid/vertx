@extends('layouts.aspha')

@section('content')
<div class="row">
  <div class="col-lg-8 col-lg-offset-2">
    <div class="box box-primary">
      <form id="restart" name="restart" class="form-horizontal"  action="">
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group">
              <div class="col-lg-6">
                <label>Select Controller</label>
                <select multiple class="form-control" size="10" id="iid">
                  @foreach ($controllers as $controller)
                  <option value={{ $controller -> iid }}>{{ $controller -> name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-6">
                <label>Select Task</label>
                <select multiple class="form-control" size="10" id="task">
                  <option value=99>All</option>
                  <option value=1>Identification</option>
                  <option value=2>Access</option>
                  <option value=3>RS485 Chain 0</option>
                  <option value=4>RS485 Chain 1</option>
                  <option value=5>I/O Linker</option>
                  <option value=6>Event Logger</option>
                  <option value=7>Local I/O</option>
                  <option value=9>Communication</option>
                  <option value=10>Sender</option>
                  <option value=11>Receiver</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-lg-12">
                <p class="help-block">Press CTRL+Left Click for multiple choose</p>
              </div>
            </div>
            <!--<hr>
            <div class="form-group">
              <div class="col-lg-6">
    					  <a class="btn btn-app" id ="resetcredential">
					        <i class="fa fa-refresh"></i>Reset Credentials
    					  </a>
              </div>
              <div class="col-lg-6">
              </div>
            </div>-->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a class="btn btn-danger pull-left" id="clear">Clear</a>
          <button class="btn btn-primary pull-right" type="submit">Send</button>
        </div>
        <!-- /.box-footer -->
      </form>
      <!-- /.form-horizontal -->
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
<script>
/********************
* post the purpose & iid
* Purpose can be :
* restart iotask, identification, access, etc
*********************/
$(document).ready(function(){
});

$("#clear").click(function(){
  $("option").removeAttr("selected");
});

$("#resetcredential").click(function(){
  $(".loading").show();
  $.post( "restart/credential",{ "_token": "{{ csrf_token() }}"}, function( data ) {})
  .done(function(data){
    var Html = '<div class="alert alert-success alert-dismissible">';
    Html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    Html += '<h4><i class="icon fa fa-check"></i> Success!</h4>';
    Html += '</div>';
    $("#globalmessage").html(Html);
    $(".loading").hide();
  })
  .fail(function(data) {
    alert("Failed");
    $(".loading").hide();
  });
});

$("#restart").submit(function(event){
  event.preventDefault();
  $(".loading").show();
  if ($("#iid").val() === null || $("#task").val() === null){
    var Html = '<div class="alert alert-danger alert-dismissible">';
    Html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    Html += '<h4><i class="icon fa fa-ban"></i> Choose both controllers and tasks</h4>';
    Html += '</div>';
    $("#globalmessage").html(Html);
    $(".loading").hide();
  } else {
    $.post( "",{ "_token": "{{ csrf_token() }}", 'iid': $("#iid").val(), 'task': $("#task").val()}, function( data ) {})
    .done(function(data){
      var errorsHtml = '<div class="alert alert-success alert-dismissible">';
      errorsHtml += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
      errorsHtml += '<h4><i class="icon fa fa-check"></i> Success!</h4>';
      errorsHtml += '</div>';
      $("#globalmessage").html(errorsHtml);
      $(".loading").hide();
    })
    .fail(function(data) {
      alert("Failed");
      $(".loading").hide();
    });
  }
});
</script>
@endsection
