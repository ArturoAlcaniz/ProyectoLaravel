<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	
	<?php
		use App\Http\Controllers\ControlInicio;	
	?>

	@if(ControlInicio::logeado())
		<script>window.location = "/ProyectoLaravel/public/home";</script>
	@endif
	
	@include('header')

	@if(ControlInicio::acceptedCookies())

	@else


		<?php include (app_path().'/includes/temp/cookie.php') ?>

	@endif
	
    <body>		

	<?php
		ini_set('display_errors', 1);

		if(!isset($avisoregistro)) $avisoregistro = '';
		if(!isset($avisologin)) $avisologin = '';

		echo "token =" . Session('tokenCookie');
		echo "avisologin=". $avisologin;

		$c = new ControlInicio();

	?>

	<img src="{{ asset('imagenes/cities.gif') }}" class="fn">
	
	<div class="registro">
		
		<div class="formulario" style="<?php if ($avisologin != '') echo "display: none"; else echo "display: block"; ?>">	
			
			<form action="{{ route('registro') }}" method="POST" name="registro">
					
				<label for="email" class="email">Email</label><input type="text" name="email" onkeypress="if (event.keyCode == 13) enviar_formularioRegistro()"/><br>
				<label for="password" class="pass">Password</label><input type="password" name="pass" onkeypress="if (event.keyCode == 13) enviar_formularioRegistro()"/><br>
				<label for="password2" class="pass2">Confirmar password</label><input type="password" name="pass2" onkeypress="if (event.keyCode == 13) enviar_formularioRegistro()"/>
				<input type="submit" name="registrarse" value="Registrarse" />
				<?php echo $avisoregistro; ?>
					
				<pre></pre>
				
				<div class="toggle">
				
					<span>�Ya esta registrado?</span>
				</div>
		
			</form>
		
		</div>

		<div class="formulario" style="<?php if ($avisologin == '') echo "display: none"; else echo "display: block"; ?>">
	
			<form action="{{ route('login') }}" method="POST" name="login">
			
				<label for="email" class="email">Email</label><input type="text" name="email-login" onkeypress="if (event.keyCode == 13) enviar_formularioLogin()"/><br>
				<label for="password" class="pass">Password</label><input type="password" name="pass-login" onkeypress="if (event.keyCode == 13) enviar_formularioLogin()"/><br>
				<input type="submit" name="login" value="Logear" /> <br>
			
				<?php echo $avisologin;	?>
			
				<pre></pre>
		
				<div class="toggle">
					<span>�No esta registrado?</span>
				</div>
	
			</form>
	
		</div>
	
	</div>
	<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
	<script src="{{ asset('js/main.js') }}"></script>		

	@include('footer')
		
    </body>
</html>
