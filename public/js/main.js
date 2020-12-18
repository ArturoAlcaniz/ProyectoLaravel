$('.toggle').click(function(){
	$('.formulario').animate({
		height: "toggle",
		'padding-top': 'toggle',
		'padding-bottom': 'toggle',
		opacity: 'toggle'
	}, "slow");
});	

function quitarmessage(){
	$('.cookie_message').css("z-index", "0");
	$.ajax({
		url: '/cookiesAccepted',
		type: 'GET',
		data : {},
	})
	.done(function(respuesta){
		alert(respuesta);
	})

	.fail(function(xhr, textStatus, errorThrown) {
		alert(xhr.responseText);
	})
}

function enviar_formularioRegistro(){
   document.registro.submit()
}

function enviar_formularioLogin(){
   document.login.submit()
}