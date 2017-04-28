	@extends('layouts.ProtectedPagesTemplateContent')
	
	@section('css')
	<link rel = "stylesheet" href = "{{url('/')}}/css/pre_loader.css"/>
	<link rel = "stylesheet" href = "{{url('/')}}/css/chat_admin.css"/>
	@stop

	@section('content')
		<script type="text/javascript">
			var csrf_token = "{{csrf_token()}}"
		</script>
	@stop

	@section('script')	
		<script src="{{url('/')}}/js/angular/angular.min.js"></script>
		<script src="{{url('/')}}/js/angular/controllers/chat_admin.js"></script>
		<script src="{{url('/')}}/js/angular/app/chat/admin.js"></script>
	@stop
