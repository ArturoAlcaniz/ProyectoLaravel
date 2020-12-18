<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class validacionPerfil extends Controller
{
	
	function has_specchar($x,$excludes=array()){
    
		if (is_array($excludes)&&!empty($excludes)) {
       
			foreach ($excludes as $exclude) {
          
				$x=str_replace($exclude,'',$x);        
       
			}   	
	
		}    
    
		if (preg_match('/[^a-zA-Z0-9¿?-_. ]+/i',$x)) {
     
			return true;        
 	
		}
   
		return false;

	}

	function longitud($variable){
		
		if (strlen($variable) < 3 || strlen($variable)>35){ 
     	 
			return false; 
		}else{
			return true;
		}
	}

	function longitudNick($variable){
		
		if (strlen($variable) < 3 || strlen($variable)>14){ 
     	 
			return false; 
		}else{
			return true;
		}
	}


	function permitido($variable){
	 	$result = true;
		
		$permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@.-_";

		for($i=0; $i<strlen($variable); $i++){
			
			if(strpos($permitidos, substr($variable,$i,1)) == false){
				
				$result = false;
			}
		
		}

		return $result;
	
	}

	function verificar($contenido) {
  		if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/",$contenido)){
    		return true;
  		}else{
			return false;	
		}	
	}



	public function cambiardatos(Request $request){

		if(!$this->verificar($request->input('cambiarEmail'))) {
					
			$avisocambiardatos = "<p class='error'>Email no valido</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
			
		}
		
		if($this->has_specchar($request->input('cambiarNick'))) {
					
			$avisocambiardatos = "<p class='error'>El nick contiene caracteres no validos</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
			
		}
		
		if($this->has_specchar($request->input('NuevaPassword')) || $this->has_specchar($request->input('NuevaPassword2'))){
					
			$avisocambiardatos = "<p class='error'>El password contiene caracteres no validos</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
			
		}
		
		if(!$this->longitud($request->input('cambiarEmail')) || !$this->verificar($request->input('cambiarEmail'))){
				
			$avisocambiardatos = "<p class='error'>Email no valido</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
			
		}

		$u = new usuarioDAO;
		
		if($u->existeEmail($request->input('cambiarEmail')) && $request->input('cambiarEmail') != $u->buscarId($u->buscarToken(Session("token")))) {	
												
			$avisocambiardatos = "<p class='error'>El email introducido ya existe</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
												
		}

		if(!$this->longitudNick($request->input('cambiarNick'))) {

			$avisocambiardatos = "<p class='error'>La longitud del nick no es valido</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
			
		}

		if(!$u->comprobarNick($request->input('cambiarNick')) && $request->input('cambiarNick') != $u->buscarNick($u->buscarToken(Session("token")))) {
														
			$avisocambiardatos = "<p class='error'>El nick introducido ya existe</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
			
		}

		if((!empty($request->input('NuevaPassword')) && !$this->longitud($request->input('NuevaPassword'))) || (!empty($request->input('NuevaPassword')) && !$this->longitud($request->input('NuevaPassword2')))) {

			$avisocambiardatos = "<p class='error'>La longitud de la nueva password no es valida</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
			
		}
	
		if(strcmp(strval($request->input('NuevaPassword')), strval($request->input('NuevaPassword2'))) !== 0){

			$avisocambiardatos = "<p class='error'>nueva password y confirmacion no coinciden</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);

		}
				
		if(!$u->comprobarTokenPassword(Session("token"), $request->input('PasswordActual'))) {
								
			$avisocambiardatos = "<p class='error'>Password actual no es valida</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
			
		}
				
		$c = new comprobarcambiodatos;
		
		if($c->comprobacion(Session("token"))) {

			$avisocambiardatos = "<p class='error'>Hace menos de 2horas que hiciste el ultimo cambio</p>";
			return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
			
		}

		empty($request->input('NuevaPassword')) ? $request->input('NuevaPassword') : NULL;
		empty($request->input('NuevaPassword2')) ? $request->input('NuevaPassword2') : NULL;
										
		if(!empty($request->input('NuevaPassword')) && !empty($request->input('NuevaPassword2'))) {
											
			$npass = password_hash($request->input('NuevaPassword'), PASSWORD_BCRYPT);
											
		}else{
											
			$npass = NULL;
											
		}

		if($u->saveCambioDatos($u->buscarToken(Session("token")))){

            if($u->cambiarDatos(Session("token"), $request->input('cambiarEmail'), $request->input('cambiarNick'), $npass)){
			    $avisocambiardatos = "<p class='correcto'>Datos cambiados correctamente</p>";
			    return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
            }
		}
		
		$avisocambiardatos = "<p class='error'>Error al cambiar datos</p>";
		return view('perfil')->with('avisocambiardatos', $avisocambiardatos);
	}
	
}
