<?php

namespace App\Http\Controllers;

class MyEvent
{
	private $pusher;
	public $message;

	public function __construct($message)
  	{
		$this->pusher = new \Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
		$this->message = $message;
  	}

	public function broadcastChatActualizado()
	{
		$this->pusher->trigger('my-channel', 'my-event', $this->message);
	}
}


?>