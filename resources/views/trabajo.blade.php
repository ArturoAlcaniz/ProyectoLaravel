<!DOCTYPE html>


<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<?php
		use App\Http\Controllers\ControlInicio;
		use App\Http\Controllers\gestorTrabajo;
		use App\Http\Controllers\validacionTrabajo;
		$v = new validacionTrabajo;
	

		if($v->estaTrabajando()){
			$avisotrabajo = $v->obtenerTiempoTrabajo();
		}else{
			$avisotrabajo = '';
		}

	?>
	
	@if(ControlInicio::logeado())
			
		@include('header')
		@include('headerHome')
		
	@else
		
		<script>window.location = "/";</script>
		
	@endif
	

	<body>

		<div class="unionTrabajo">

			<form action="/ProyectoLaravel/public/trabajar" method="post">
				<label for="trabajo" class="trabajo">Trabajo:</label>
				<select id="TrabajoELEGIDO" name="TrabajoELEGIDO" onChange='actualizar()'>
					<?php $gestorTrabajo = new gestorTrabajo; $gestorTrabajo->mostrarTrabajos();?>
				</select>
				<br/>
				<label for="experiencia" class="experiencia">Experiencia necesaria:&nbsp;</label><label id="exprequerida" class="exprequerida"><?php echo 0; ?></label>
				<br/>
				<label for="sueldo" class="sueldo">Sueldo hora:&nbsp;</label><label id="sueldohora" class="sueldohora"><?php echo 8; ?></label>
				<br/>
				<label for="duracion" class="duracion">Duracion:&nbsp;</label>
				<select id="horas" name="horas" onChange='actualizar()'>
					<option value="5">5</option>
					<option value="8">8</option>
				</select>
				<br/>
				<label for="exper" class="exper">Ganancia experiencia:&nbsp;</label><label id="gananciaexperiencia" class="gananciaexperiencia"><?php echo 1; ?></label>
				<br/>
				<label for="din" class="din">Ganancia dinero:&nbsp;</label><label id="gananciadinero" class="gananciadinero"><?php echo 40; ?></label>
				<?php
					if($avisotrabajo == ''){
						echo '<input type="submit" name="trabajo" value="Trabajar" />';
					}else{
						echo $avisotrabajo;
					}

				?>

			</form>
		
		</div>

		<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
		<script src="{{ asset('js/config.js') }}"></script>
		<script src="{{ asset('js/actualizarEXP.js') }}"></script>
	
		@include('footer')
		
	
	</body>