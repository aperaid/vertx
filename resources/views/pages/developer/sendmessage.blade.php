@extends('layouts.aspha')

@section('content')
<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#hint">Search Hint</button>
<button type="button" id="resetcredential" class="btn btn-danger pull-right btn-sm">Reset Credential</button>
<button type="button" id="insertall" class="btn btn-success btn-sm">Insert All</button>
<form action="" method='post'>
	{{ csrf_field() }}
	Code: <input type="text" name="code" autocomplete="off">Message: <input type="text" name="message" autocomplete="off"><input type="submit" value="Submit">
</form>
	
<div class="modal fade" id="hint">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
           <h4 class="modal-title">Search Hint</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-body">
                <ul class="list-unstyled">
                    <li># VERTX_READ_INTERFACE_MEMORY;msglen;interfaceNumber;memoryType;address;bytes;dataFormat;</li>
										<li># read door strike = 0006;xxxx;0;2;7e02;1;d;</li>
                    <li># VERTX_WRITE_EEPROM;msglen;interfaceNumber;address;bytes;memoryData;enddata;</li>
										<li># write door strike = 0007;xxxx;0;7e02;1;00;1;</li>
                    </ol>
              </div>
            </div>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
/* Insert All */
$("#insertall").click(function(event){
  $(".loading").show();
  $.post( "sendmessage/insertall",{ "_token": "{{ csrf_token() }}"}, function( data ) {
  })
  .done(function(data){
    location.reload();
    $(".loading").hide();
  })
  .fail(function(data) {
    $(".loading").hide();
  });
  event.preventDefault();
});

/* Reset Credential */
$("#resetcredential").click(function(event){
  $(".loading").show();
  $.post( "sendmessage/resetcredential",{ "_token": "{{ csrf_token() }}"}, function( data ) {
  })
  .done(function(data){
    location.reload();
    $(".loading").hide();
  })
  .fail(function(data) {
    $(".loading").hide();
  });
  event.preventDefault();
});
</script>
@endsection