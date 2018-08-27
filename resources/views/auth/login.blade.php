@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">UserID</label>

                            <div class="col-md-6">
                                <!--input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus-->
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button id="btn" type="submit" class="btn btn-primary">
                                    Login
                                </button>
																
																@if(env('APP_COMPANY')=="other")
                                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                    Forgot Your Password?
                                </a>
																@endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="{{ asset('adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<script>

/*$(document).on('keyup', '#password', function(e){
	if(e.keyCode == 9 || e.keyCode == 13){
		$('#email').val($("#email").val()+"@dti.co.id");
	}
});

$(document).on('click', '#password', function(e){
	$('#email').val($("#email").val()+"@dti.co.id");
});

$(document).on('select', '#password', function(e){
	$('#email').val($("#email").val()+"@dti.co.id");
});*/

</script>
