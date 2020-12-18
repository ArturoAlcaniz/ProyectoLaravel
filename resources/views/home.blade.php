<!DOCTYPE html>


<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<body>

		<?php use App\Http\Controllers\ControlInicio; $c = new ControlInicio(); ?>

		<script src="https://<?php echo $_SERVER['HTTP_HOST']."/js/jquery-3.4.1.min.js"; ?>"></script>
		<script src="https://<?php echo $_SERVER['HTTP_HOST']."/js/perfil.js"; ?>"></script>
		
		@if(ControlInicio::logeado())
			
			@include('header')
			@include('headerHome')

		@endif

		
		@include('footer')
		
	
	</body>