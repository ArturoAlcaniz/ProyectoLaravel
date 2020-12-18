function actualizar(){
	buscar_datos();
	buscar_ganancia();
}


function buscar_datos(){
	var e = document.getElementById("TrabajoELEGIDO");
	$.ajax({
		url: '/actualizarTrabajosDatos',
		type: 'GET',
		data: {consulta: e.options[e.selectedIndex].value},
	})
	.done(function(respuesta){
		var info = respuesta.split(" ");
		document.getElementById("exprequerida").innerHTML = info[0];
		document.getElementById("sueldohora").innerHTML = info[1];
	})
	.fail(function(xhr, textStatus, errorThrown) {	
		alert(xhr.responseText);
	})
	
}


function buscar_ganancia(){
	var e = document.getElementById("horas");
	var t = document.getElementById("TrabajoELEGIDO");
	$.ajax({
		url: '/actualizarTrabajosGanancias',
		type: 'GET',
		data: {horas: e.options[e.selectedIndex].value, trabajo: t.options[t.selectedIndex].value},
	})
	.done(function(respuesta){
		var info = respuesta.split(" ");
		document.getElementById("gananciaexperiencia").innerHTML = info[0];
		document.getElementById("gananciadinero").innerHTML = info[1];
	})
	.fail(function(xhr, textStatus, errorThrown) {	
		alert(xhr.responseText);
	})
	
}