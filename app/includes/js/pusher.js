// JavaScript Document

 // Enable pusher logging - don't include this in production


	Pusher.logToConsole = true;

	var pusher = new Pusher('38564b8a81e3a4f19f24', {
		cluster: 'eu',
		forceTLS: true
	});

	var channel = pusher.subscribe('my-channel');

	channel.bind('my-event', function(data) {
	
		var msj = JSON.stringify(data);

		if(msj.includes("chatActualizado")){

			chat();

		}
	

	});