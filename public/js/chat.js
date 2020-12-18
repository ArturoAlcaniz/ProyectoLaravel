// JavaScript Document

	chat();
	document.getElementById("Mensaje").addEventListener("keyDown", myEventHandler, false);

function chat(){

	$.ajax({
		url: '/ProyectoLaravel/public/obtenerMensajesChat',
		type: 'GET',
		data:{}
	})

	.done(function(respuesta){
		
		var info = respuesta.split("#");
		
		var nodeMensajesChat = document.getElementById("MensajesChat");
		nodeMensajesChat.innerHTML = '';

		
		for (var i = info.length-1; i >= 0; i--) {

			if(info[i] != ''){
			
				var nombre = document.createElement("label");
				var br = document.createElement("br");
				nombre.innerHTML = info[i--];
				nombre.classList.add("Nombre");
				
				if(nombre.innerHTML.substr(0, 7) == '[Admin]' || nombre.innerHTML.substr(0, 5) == '[Mod]'){
					
					nombre.classList.add("Poder");
					
				}
				
				nodeMensajesChat.appendChild(nombre);
			
				var fechaprox = document.createElement("label");
				var br = document.createElement("br");
				fechaprox.classList.add("FechaProx");
				fechaprox.innerHTML = info[i--];
				nodeMensajesChat.appendChild(fechaprox);
				nodeMensajesChat.appendChild(br);
			
				var mensaje = document.createElement("label");
				var br = document.createElement("br");
				mensaje.classList.add("Mensajes");
				mensaje.innerHTML = info[i];
				nodeMensajesChat.appendChild(mensaje);
				nodeMensajesChat.appendChild(br);
				nodeMensajesChat.appendChild(br);
				nodeMensajesChat.appendChild(br);
				
			}else{
				
				i = i-2;
			
			}
			
		}
		nodeMensajesChat.scrollTop = nodeMensajesChat.scrollHeight;
		

	})
	.fail(function(xhr) {	
		console.log(xhr.responseText);
	})	

	
}

function myEventHandler(e){
    var keyCode = e.keyCode;
    
	if(keyCode == 13){
		enviar_formulario;
    }
	
}

function enviar_formulario(){
   document.chatFormulario.submit()
}

function eliminar_mensaje(){
	document.getElementById("Mensaje").value = '';
}




	