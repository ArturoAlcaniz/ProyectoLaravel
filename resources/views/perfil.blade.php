<!DOCTYPE html>


<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  	<?php
		use App\Http\Controllers\ControlInicio;
		$userData = ControlInicio::obtenerUserData();
		if(!isset($avisocambiardatos)) $avisocambiardatos = '';
	?>
	
	@if(ControlInicio::logeado())
			
		@include('header')
		@include('headerHome')
		
	@else
		
		<script>window.location = "/";</script>
		
	@endif
	

	<body>

		<div class="cambioPerfil">
	
			<div class="perfilFormulario">

				<form action="/cambiodatos" method="post">

					<label for="email" class="email">Email:</label><input type="text" name="cambiarEmail" value="<?php echo  $userData['email']; ?>"/><br/>

					<label for="nick" class="nick">Nick:</label><input type="text" name="cambiarNick" value="<?php echo $userData['nick']; ?>" /><br/>

					<label for="NuevaPass" class="NuevaPass">Nueva Pass</label><input placeholder="En blanco para no cambiar" type="password" name="NuevaPassword" /><br/>

					<label for="NuevaPass2" class="NuevaPass2">Confirmar Nueva Pass</label><input placeholder="En blanco para no cambiar" type="password" name="NuevaPassword2" /><br/>

					<label for="PasswordActual" class="PasswordActual">Actual Pass</label><input type="password" name="PasswordActual" /><br/>

					<input type="submit" name="cambiardatos" value="cambiar datos" /> <br>

					<?php
						echo $avisocambiardatos;
					?>

				</form>

			</div>

		</div>


		<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
		<script src="{{ asset('js/perfil.js') }}"></script>
	
		@include('footer')
		
	
	</body>