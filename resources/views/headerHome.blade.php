<?php 

	use App\Http\Controllers\ControlHeaderHome;
	$c = new ControlHeaderHome;

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

?>
	<meta name="_token" content="{{ csrf_token() }}"/>
	<div class="header">
		<div class="header_text">
		Dinero: <div <?php $c->logicaHeaderHome(Session('token')); ?> </div>
		</div>
		<div class="header_text2">
			Experiencia: <?php $c->logicaHeaderHome2(Session('token')); ?>
		</div>	

		

	</div>	
	<div class="columnaMenu">
			
		<div class="menu">
			
			<a href="<?php echo URL::to('/')."/home/perfil"; ?>">
				
				<div <?php if($_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/home/perfil" || $_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/cambiodatos") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="perfil" src="{{ asset('imagenes/perfil.png') }}">
				</div>
				
			</a>
			
			<a href="<?php echo URL::to('/')."/home/tienda"; ?>">
				
				<div <?php if($_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/home/tienda") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="tienda" src="{{ asset('imagenes/tienda.png') }}">
				</div>
				
			</a>
			
			<a href="<?php echo URL::to('/')."/home/negocios"; ?>">
			
				<div <?php if($_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/home/negocios" || $_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/crearnegocio") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="negocios" src="{{ asset('imagenes/negocios.png') }}">
				</div>
				
			</a>
			
			<a href="<?php echo URL::to('/')."/home/trabajo"; ?>">
			
				<div <?php if($_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/home/trabajo") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="trabajo" src="{{ asset('imagenes/trabajo.png') }}">
				</div>
				
			</a>
			
			<a href="<?php echo URL::to('/')."/home/ranking"; ?>">
			
				<div <?php if($_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/home/ranking") echo "class='btn_header header_current'"; else echo "class='btn_header'";
				?>>
					<img id="ranking" src="{{ asset('imagenes/ranking.png') }}">
				</div>
				
			
			</a>
			
			<a href="<?php echo URL::to('/')."/home/chat"; ?>">
			
				<div <?php if($_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/home/chat" || $_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/enviarmensaje") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="chat" src="{{ asset('imagenes/chat.png') }}">
				</div>
			
			</a>
		
			<a href="<?php echo URL::to('/')."/home/logout"; ?>">
				
				<div <?php if($_SERVER['REQUEST_URI'] == "/ProyectoLaravel/public/home/logout") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="logout" src="{{ asset('imagenes/logout.png') }}">
				</div>
				
			</a>
			
		</div>
	</div>