<!DOCTYPE html>


<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<?php 
		use App\Http\Controllers\ControlInicio; 
		use App\Http\Controllers\usuarioDAO;
		use App\Http\Controllers\gestorTrabajo;
		$u = new usuarioDAO;
		$coste = 1000;
		$puestos = 5;
		if(!isset($avisonegocios)) $avisonegocios = '';
	?>
	
	@if(ControlInicio::logeado())
			
		@include('header')
		@include('headerHome')
		
	@else
		
		<script>window.location = "/";</script>
		
	@endif
	

	<body>

		<div class="negocios">
		
			<form action="/ProyectoLaravel/public/crearnegocio" method="post">

				<label for="TipoN" class="TipoN">Tipo de negocio:</label>
				<select id="TipoNegocio" name="TipoNegocio" onChange='actualizar()'>

					<?php $gestorTrabajo = new gestorTrabajo; $gestorTrabajo->mostrarTipoNegocios();?>

				</select><br/>
				<label for="NombreNegocio" class="NombreNegocio">Nombre:</label><input type="text" name="negocio-nombre" /><br>
				<label for="CosteNegocio" class="CosteNegocio">Coste creacion:&nbsp;</label><label id="costeCreacion" class="costeCreacion"><?php echo $coste; ?></label><br/>
				<label for="puestos" class="puestos">Puestos disponibles:&nbsp;</label><label id="puestosDisponibles" class="puestosDisponibles"><?php echo $puestos; ?></label><br/>
				<input type="submit" name="creacionNegocio" value="Crear Negocio">

				<?php
					echo $avisonegocios;
				?>

			</form>
		</div>
	
	
		<div class="negocios2">
			<div id="cont" class="cont">
				<div id="MisNegocios" class="MisNegocios">
					<label for="MisNeg" class="MisNeg">Mis negocios:</label><br/><br/>
					<?php $gestorTrabajo = new gestorTrabajo; $gestorTrabajo->mostrarNegocios(); ?>
				</div>
			</div>
		</div>

		<script src="{{ asset('js/jquery-3.4.1.min.js') }}"; ?>"></script>
		<script src="{{ asset('js/config.js') }}"></script>
		<script src="{{ asset('js/actualizarNegocio.js') }}"; ?>"></script>
	
		@include('footer')
		
	
	</body>