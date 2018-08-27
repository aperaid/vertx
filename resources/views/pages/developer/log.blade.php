@extends('layouts.aspha')

@section('content')
<button id="refresh">refresh</button>
<br>
<textarea id="logArea" cols="100" rows="30">{{$log}}</textarea>
@endsection
@section('script')
<script>
$( "#refresh" ).click(function() {
    $.post("/dev/log/refresh", {"_token": "{{ csrf_token() }}", "type" : "controller" }, function(data, status){
	    $( "#logArea" ).text(data);
      $('#logArea').scrollTop($('#logArea')[0].scrollHeight);
    });
  });
</script>
@endsection