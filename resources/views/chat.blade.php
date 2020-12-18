<!DOCTYPE html>


<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


	<?php 
		use App\Http\Controllers\ControlInicio; 
		use App\Http\Controllers\usuarioDAO;
		use App\Http\Controllers\validacionChat;
		$u = new usuarioDAO;
		$EnviarMensaje = False;
	?>

	@if(ControlInicio::logeado())
			
		@include('header')
		@include('headerHome')
		
	@else
		
		<script>window.location = "/";</script>
		
	@endif
	
	<script>
		var Session = '<%= Session["token"] %>';
	</script>


	<body>
		<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
		<script src="https://js.pusher.com/5.1/pusher.min.js"></script>
		
		<script src="{{ asset('js/chat.js') }}"></script>
		<script src="{{ asset('js/pusher.js') }}"></script>

		<div class="chat">
	
			<form action="{{ route('enviarMensaje') }}" method="post" name="chatFormulario">

				<div id="cont" class="cont">
					<div id="MensajesChat" class="MensajesChat">




					</div>
				</div>

				<div id="MensajeError" class="MensajeError">

					<?php
						if (empty($avisochat)){
							echo "<p class='error'></p>";
						}else{
							echo $avisochat;
						}
					?>

				</div>

				<input style="width: 70%;" type="text" id="Mensaje" name="Mensaje"/>
				<input type="submit" name="enviarMensaje" value="Enviar Mensaje">



			</form>

		</div>



		@include('footer')
		
		
	</body>
