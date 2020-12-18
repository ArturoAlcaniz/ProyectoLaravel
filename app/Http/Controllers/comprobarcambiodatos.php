<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class comprobarcambiodatos extends Controller
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
				
			$exp = $u->obtenerdatecambio($u->buscarToken($token));
			
			if(!is_null($exp)){
					
				$exp = base64_decode($exp);
					
				if($this->dateDiff($hoy, $exp)>0){
						
					$comp = true;
						
				}
					
			}
		}

		return $comp;		
	}
	
}
