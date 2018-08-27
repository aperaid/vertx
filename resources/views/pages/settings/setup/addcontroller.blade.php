@extends('layouts.aspha')

@section('content')

<div class="row">
  <div class="col-md-12">
    <!-- Horizontal Form -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Controller Detail</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form name="addform" class="form-horizontal" action="" method="post">
        <div class="box-body">
        {{ csrf_field() }}
          <div class="form-group">
            <label class="col-sm-2 control-label">IID</label>
            <div class="col-sm-5">
              <input type="number" class="form-control" id="iid" name="iid" value="{{old('iid')}}" autocomplete="off" >
            </div>
						<label class="col-sm-1 control-label">Device</label>
            <div class="col-sm-2">
							<select id="device" class="form-control" name="device" value="{{old('device')}}">
								<option value="" disabled {{ empty(old('device')) ? 'selected' : '' }}>Select Device</option>
								<option value="V1000" {{ old('device') == 'V1000' ? 'selected' : '' }}>V1000</option>
								<option value="V2000" {{ old('device') == 'V2000' ? 'selected' : '' }}>V2000</option>
								<option value="EH400" {{ old('device') == 'EH400' ? 'selected' : '' }}>EH400</option>
							</select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">IP</label>
            <div class="col-sm-5">
              <div class="input-group">
                <div class="input-group-addon">
                <i class="fa fa-laptop"></i>
                </div>
                <input type="text" class="form-control" id="ip" name="ip" value="{{old('ip')}}" data-inputmask="'alias': 'ip'" data-mask autocomplete="off" >
              </div>
            </div>
            <label class="col-sm-1 control-label">Port</label>
            <div class="col-sm-2">
              <div class="input-group">
                <div class="input-group-addon">
                <i class="fa fa-laptop"></i>
                </div>
                <input type="number" class="form-control" id="port" name="port" value="{{old('port')}}" autocomplete="off" min=0 max=65535 >
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Mac</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="mac" name="mac" value="{{old('mac')}}" data-inputmask="'alias': 'mac'" data-mask autocomplete="off" maxlength="17" >
            </div>
						<label class="col-sm-1 control-label">Door Strike</label>
            <div class="col-sm-2">
							<select id="doorstrike" class="form-control" name="doorstrike" value="{{old('doorstrike')}}" disabled>
								<option value="0" {{ old('doorstrike') == '0' ? 'selected' : '' }}>Single Door</option>
								<option value="1" {{ old('doorstrike') == '1' ? 'selected' : '' }}>Double Door</option>
							</select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" autocomplete="off" maxlength="30" >
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" id="buttonadd" class="btn btn-info pull-right">Add</button>
        </div>
        <!-- /.box-footer -->
      </form>
      <!-- /.form -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /. row -->
@endsection

@section('script')
<!-- Jquery UI -->
<script src="{{ asset('adminlte/plugins/jQueryUI/jquery-ui.min.js')}}"></script>
<!-- InputMask -->
<script src="{{ asset('adminlte/plugins/Inputmask-3.3.3/dist/jquery.inputmask.bundle.js') }}"></script>
<script>
$(document).ready(function(){
  $("[data-mask]").inputmask();
	
	if($("#device option:selected").val()=='' || $("#device option:selected").val()=='V1000'){
		document.getElementById('doorstrike').disabled = true;
	}else{
		document.getElementById('doorstrike').disabled = false;
	}
});

$("#device").autocomplete({
	source: function( request, response ) {
		if($("#device option:selected").val()=='V1000'){
			document.getElementById('doorstrike').disabled = true;
		}else{
			document.getElementById('doorstrike').disabled = false;
			$("#doorstrike").val(0);
		}
	}
});
</script>
@endsection
