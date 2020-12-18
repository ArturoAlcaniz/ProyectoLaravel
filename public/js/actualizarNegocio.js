// JavaScript Document

function actualizar(){
	buscar_datos();
}


function buscar_datos(){
	var e = document.getElementById("TipoNegocio");
	$.ajax({
		url: '/ProyectoLaravel/public/actualizarTrabajosCoste',
		type: 'GET',
		data: {consulta: e.options[e.selectedIndex].value},
	})
	.done(function(respuesta){
		var info = respuesta.split(" ");
		document.getElementById("costeCreacion").innerHTML = info[0];
		document.getElementById("puestosDisponibles").innerHTML = info[1];
	})
	.fail(function(xhr, textStatus, errorThrown) {	
		alert(xhr.responseText);
	})
	
}
