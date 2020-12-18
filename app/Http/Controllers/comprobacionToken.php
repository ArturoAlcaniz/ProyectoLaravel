<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class comprobacionToken extends Controller
{
   
	public function dateDiff($start, $end) {

		$start_ts = strtotime($start);

		$end_ts = strtotime($end);

		$diff = $end_ts - $start_ts;

		return $diff / 3600;

	}
	
	public function comprobacion($token){
		
		$comp = false;
		
		$u = new usuarioDAO;
		
		if($u->existeToken($token)){
			
			$hoy = date("Y-m-d H:i:s", strtotime("now"));
			$exp = base64_decode($u->obtenerdateExp($token));

			if($this->dateDiff($hoy, $exp)>0){
				$comp = true;
			}
		}
		
		return $comp;
	}
	
	public function comprobacionCookie($token){
		
		$u = new usuarioDAO;
		
		if($u->existeTokenCookie($token)){
			
			$hoy = date("Y-m-d H:i:s", strtotime("now"));
			$exp = base64_decode($u->obtenerdateExpCookie($token));
			
			if($this->dateDiff($hoy, $exp)>0){
				return true;
			}else{
				return false;
			}
			
		}else{
			return false;
		}
	}

	
}
