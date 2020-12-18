<!DOCTYPE html>


<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<?php 
		use App\Http\Controllers\ControlInicio; 
		use App\Http\Controllers\usuarioDAO;
		$u = new usuarioDAO;
	?>
	
	@if(ControlInicio::logeado())
			
		@include('header')
		@include('headerHome')
		
	@else
		
		<script>window.location = "/";</script>
		
	@endif
	

	<body>
		
		
	@if(ControlInicio::logout())
		<script>window.location = "/ProyectoLaravel/public";</script>

	@endif

		
		
		<script src="{{ URL::asset('js/jquery-3.4.1.min.js') }}"; ?>"></script>
		<script src="{{ URL::asset('js/perfil.js') }}"; ?>"></script>
	
		@include('footer')
		
	
	</body>