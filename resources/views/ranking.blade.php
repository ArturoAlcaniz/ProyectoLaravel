<!DOCTYPE html>


<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<?php 
		use App\Http\Controllers\ControlInicio; 
		use App\Http\Controllers\usuarioDAO;
		$u = new usuarioDAO;
		$datosRanking = $u->obtenerRanking();
	?>
	
	@if(ControlInicio::logeado())
			
		@include('header')
		@include('headerHome')
		
	@else
		
		<script>window.location = "/";</script>
		
	@endif
	

	<body>
	
		<div class="ranking" id="ranking">
			<?php

				$t = 1;

				foreach ($datosRanking[0] as &$valor) {

					echo "<label for='top" . $t . "' class='top" . $t . "'>" . $t . "º" . "&nbsp" . $valor[0] . "&nbsp" . $valor[1] . "</label>";
					$t++;
					echo "<br/>";
				}
			?>



		</div>
	
		
		<script src="{{ URL::asset('js/jquery-3.4.1.min.js') }}"; ?>"></script>
		<script src="{{ URL::asset('js/cambiarRanking.js') }}"; ?>"></script>
	
		@include('footer')
		
	
	</body>