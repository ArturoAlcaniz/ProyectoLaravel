	
	<div class="columnaMenu">
			
		<div class="menu">
				
			<a href="perfil.php">
				
				<div <?php if($_SERVER['REQUEST_URI'] == "/home/perfil.php") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="perfil" src="../imagenes/perfil.png">
				</div>
				
			</a>
			
			<a href="tienda.php">
				
				<div <?php if($_SERVER['REQUEST_URI'] == "/home/tienda.php") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="tienda" src="../imagenes/tienda.png">
				</div>
				
			</a>
			
			<a href="negocios.php">
			
				<div <?php if($_SERVER['REQUEST_URI'] == "/home/negocios.php") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="negocios" src="../imagenes/negocios.png">
				</div>
				
			</a>
			
			<a href="trabajo.php">
			
				<div <?php if($_SERVER['REQUEST_URI'] == "/home/trabajo.php") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="trabajo" src="../imagenes/trabajo.png">
				</div>
				
			</a>
			
			<a href="ranking.php">
			
				<div <?php if($_SERVER['REQUEST_URI'] == "/home/ranking.php") echo "class='btn_header header_current'"; else echo "class='btn_header'";
				?>>
					<img id="ranking" src="../imagenes/ranking.png">
				</div>
				
			
			</a>
			
			<a href="chat.php">
			
				<div <?php if($_SERVER['REQUEST_URI'] == "/home/chat.php") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="chat" src="../imagenes/chat.png">
				</div>
			
			</a>
		
			<a href="logout.php">
				
				<div <?php if($_SERVER['REQUEST_URI'] == "/home/logout.php") echo "class='btn_header header_current'"; else echo "class='btn_header'"; ?>>
					<img id="logout" src="../imagenes/logout.png">
				</div>
				
			</a>
			
		</div>
	</div>