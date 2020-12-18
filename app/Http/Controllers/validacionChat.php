<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class validacionChat extends Controller
{

	function longitud($variable){
		
		if (strlen($variable)>60){ 
     	 
			return false; 
		}else{
			return true;
		}
	}


	function has_specchar($x,$excludes=array()){
    
		if (is_array($excludes)&&!empty($excludes)) {
       
			foreach ($excludes as $exclude) {
          
				$x=str_replace($exclude,'',$x);        
       
			}   	
	
		}    
    
		if (preg_match('/[^a-z0-9¿?-_.\/ ]+/i',$x)) {
     
			return true;        
 
		}
   
		return false;

	}



	public function enviarMensaje(Request $request){
		
		$hoy = date("Y-m-d H:i:s", strtotime("now"));
		
		$u = new usuarioDAO;
		
		$muteo = $u->obtenerMuteo(Session("token"));
		
		if($muteo != null){
			
			$restante = $u->dateDiff($hoy, $muteo);
			
			if($restante > 0){
				
				$avisochat = "<p class='error'>Se encuentra muteado hasta el $muteo</p>";
				return view('chat')->with('avisochat', $avisochat);
			}
		}
		
		if($this->has_specchar($request->input("Mensaje"))) {
		
			$avisochat = "<p class='error'>Tu mensaje contiene caracteres no permitidos</p>";
			return view('chat')->with('avisochat', $avisochat);
		}
		
		if(empty($request->input("Mensaje"))) {
			
			$avisochat = "<p class='error'>El campo mensaje esta vacio</p>";
			return view('chat')->with('avisochat', $avisochat);
		}
		
		if($u->MisMensajesMandados(Session("token")) > 0){

			if($u->obtenerPoder(Session("token"))<4 && $u->dateDiff($u->obtenerMiMensaje(Session("token")), $hoy)<30){
			
				$avisochat = "<p class='error'>Tu ultimo mensaje fue en menos de 30 segundos</p>";
				return view('chat')->with('avisochat', $avisochat);
			}
		}
			
		if(!$this->longitud($request->input("Mensaje"))) {
			
			$avisochat = "<p class='error'>Tu mensaje es demasiado largo</p>";
			return view('chat')->with('avisochat', $avisochat);
		}
		
		if($request->input("Mensaje")[0] == '/'){
			
			if($request->input("Mensaje") == '/clear'){
				
				if($u->obtenerPoder(Session("token"))<4){
				
					$avisochat = "<p class='error'>No tienes permiso para limpiar el chat</p>";
					return view('chat')->with('avisochat', $avisochat);				
				}

				$u->limpiarChat();
				$u->enviarComando(Session("token"), $hoy);
				$avisochat = "<p class='correcto'>chat limpiado correctamente</p>";
				return view('chat')->with('avisochat', $avisochat);
			}
			
			if(substr($request->input("Mensaje"), 0, 5) == '/mute'){
				
				if($u->obtenerPoder(Session("token"))<4){
				
					$avisochat = "<p class='error'>No tienes permiso para mutear en el chat</p>";
					return view('chat')->with('avisochat', $avisochat);			
				}
				
				$resto = substr($request->input("Mensaje"), 6);
				
				$str_arr = explode (" ", $resto);
				
				$r = $u->buscarNombre($str_arr[0]);
				
				if(empty($r)){
					
					$avisochat = "<p class='error'>No existe ese usuario</p>";
					return view('chat')->with('avisochat', $avisochat);		
				}
				
				$minutos = $str_arr[1];
				
				if(!($minutos > 0)){
					
					$avisochat = "<p class='error'>Horas de muteo incorrectas</p>";
					return view('chat')->with('avisochat', $avisochat);	
				}
				
				$u->mutear($r, $minutos);
				$avisochat = "<p class='correcto'>Usuario $str_arr[0] ha sido muteado $minutos minutos</p>";
				return view('chat')->with('avisochat', $avisochat);
			}
			
			$avisochat = "<p class='error'>No existe ese comando</p>";
			return view('chat')->with('avisochat', $avisochat);
		}		
		$u->enviarMensaje(Session("token"), $request->input("Mensaje"), $hoy);
		//$avisochat = "<p class='correcto'>Mensaje enviado correctamente</p>";
		return view('chat');
	}
}
