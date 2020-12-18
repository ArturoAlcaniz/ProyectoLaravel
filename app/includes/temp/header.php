<div class="header">
	<div class="header_text">
	Dinero: <div <?php  if(usuarioDAO::buscarDinero($_SESSION["token"])>0) echo "class='dinero1'"; else echo "class='dinero2'";?>>
					<?php setlocale(LC_MONETARY, 'nl_NL'); $dinero = money_format('%.2n', usuarioDAO::buscarDinero($_SESSION["token"])); echo $dinero;  ?>
		
			</div>
	</div>
	<div class="header_text2">
		Experiencia: <?php echo usuarioDAO::obtenerExperiencia($_SESSION["token"]); ?>
	</div>	
	
</div>