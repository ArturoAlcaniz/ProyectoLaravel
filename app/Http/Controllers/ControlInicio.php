<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;


class ControlInicio extends Controller
{

    static function obtenerUserData(){
        return manager::getManager()->obtenerUsuario();
    }

	static function logeado(){
/*
		if(!manager::getManager()->connected()){
            ?>
            <script>
                var currentLocation = window.location;
                var host = "https://".concat(document.location.hostname);
                var urlHOST = host.concat("/");

                if(currentLocation != urlHOST){
                    document.location.href="/";
                }
            </script>
            <?php
            return false;
		}else{
		    ?>
            <script>
                if(currentLocation == urlHOST){
                    document.location.href="/home";
                }
            </script>
            <?php
		    return true;
        }*/

		$c = new comprobacionToken;
		
		if(Session::has('token')){
		
			if($c->comprobacion(Session('token'))){
			
				return true;
			
			}
		
		}
		
		return false;

	}

	static function actualizarChat(){
		$e = new MyEvent("chatActualizado " . Session("token"));
		$e->broadcastChatActualizado();
		return true;
	}

	function longitud($variable){
		
		if (strlen($variable) < 3 || strlen($variable)>35){ 
     	 
			return false; 
		}else{
			return true;
		}
	}
	
	function verificar($contenido) {
  		if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$contenido)){
    		return true;
  		}else{
			return false;	
		}	
	}
	
	function has_specchar($x,$excludes=array()){
    
		if (is_array($excludes)&&!empty($excludes)) {
       
			foreach ($excludes as $exclude) {
          
				$x=str_replace($exclude,'',$x);        
       
			}   	
	
		}    
    
		if (preg_match('/[^a-z0-9¿?-_. ]+/i',$x)) {
     
			return true;        
 
		}
   
		return false;

	}
	
	public function login(Request $request){

	    if(manager::getManager()->connected()){

            return redirect('/home');
        }

		if(!$this->verificar($request->input('email-login'))){
					
			$avisologin = "<p class='error'>Email no valido</p>";
			return view('inicio')->with('avisologin', $avisologin);
		}
		
		if($this->has_specchar($request->input('pass-login'))){
		
			$avisologin = "<p class='error'>La contraseña tiene caracteres no validos</p>";
			return view('inicio')->with('avisologin', $avisologin);
		}
		
		if(empty($request->input('email-login'))) {
			
			$avisologin = "<p class='error'>El email esta vacio</p>";
			return view('inicio')->with('avisologin', $avisologin);
		}
			
		if(!$this->longitud($request->input('email-login'))) {
				
			$avisologin = "<p class='error'>Longitud de email no valida</p>";
			return view('inicio')->with('avisologin', $avisologin);
		}
			
		if(empty($request->input('pass-login'))) {
							
			$avisologin = "<p class='error'>El password esta vacio</p>";
			return view('inicio')->with('avisologin', $avisologin);
		}
							
		if(!$this->longitud($request->input('pass-login'))) {
								
			$avisologin = "<p class='error'>La longitud de password no es valida</p>";
			return view('inicio')->with('avisologin', $avisologin);			
		}
						
		$u = new usuarioDAO;
		
		if(!$u->existeEmail($request->input('email-login'))) {

			$avisologin = "<p class='error'>El email introducido no existe</p>";
			return view('inicio')->with('avisologin', $avisologin);
		}

		if($u->login($request->input('email-login'), $request->input('pass-login'))) {

		    $email = $request->input('email-login');
		    $id = $u->buscarEmail($email);
		    $nick = $u->buscarNick($id);
		    $efectivo = $u->buscarDineroId($id);
		    $experiencia = $u->obtenerExperienciaId($id);
		    manager::getManager()->guardarUsuario($email, $nick, $efectivo, $experiencia);
		    $u->saveToken($request);
			return redirect('/home');	
									
		}
		
		$avisologin = "<p class='error'>El email y la password no coinciden</p>";					
		return view('inicio')->with('avisologin', $avisologin);
	}
	
	static function acceptedCookies(){
					
		$u = new usuarioDAO;

		if(Session('tokenCookie') == '1'){

		    return true;

		}else{

		    return false;

        }
	}

	public function registro(Request $request){
		
		if(!$this->verificar($request->input('email'))) {
					
			$avisoregistro = "<p class='error'>Email no valido</p>";
			return view('inicio')->with('avisoregistro', $avisoregistro);
		}
		
		if($this->has_specchar($request->input('pass')) || $this->has_specchar($request->file('pass2'))) {
					
			$avisoregistro = "<p class='error'>Password tiene caracteres no validos</p>";
			return view('inicio')->with('avisoregistro', $avisoregistro);
		}
		
		if(empty($request->input('email'))) {
			
			$avisoregistro = "<p class='error'>El email esta vacio</p>";
			return view('inicio')->with('avisoregistro', $avisoregistro);
		}
			
		if(!$this->longitud($request->input('email'))) {
				
			$avisoregistro = "<p class='error'>Longitud de email no valida</p>";
			return view('inicio')->with('avisoregistro', $avisoregistro);
		}
						
		if(empty($request->input('pass'))) {
							
			$avisoregistro = "<p class='error'>El password esta vacio</p>";
			return view('inicio')->with('avisoregistro', $avisoregistro);		
		}
							
		if(!$this->longitud($request->input('pass'))) {
								
			$avisoregistro = "<p class='error'>La longitud de password no es valida</p>";
			return view('inicio')->with('avisoregistro', $avisoregistro);
		}
	
		if(strcmp(strval($request->input('pass')), strval($request->input('pass2'))) !== 0){
									
			$avisoregistro = "<p class='error'>Password y confirmar password no coinciden</p>";
			return view('inicio')->with('avisoregistro', $avisoregistro);
		}
		
		$u = new usuarioDAO;
		
		if($u->registrar($request->input('email'), $request->input('pass'))) {
										
			$avisoregistro = "<p class='correcto'>Usuario registrado";
			return view('inicio')->with('avisoregistro', $avisoregistro);	
			
		}else{
			
			$avisoregistro = "<p class='error'>El usuario ya existe</p>";						
			return view('inicio')->with('avisoregistro', $avisoregistro);		
			
		}
										
		
		
	}

	static function logout(){
	    manager::getManager()->deleteConnectedUser();
        session_destroy();
        session()->forget('token');
        session()->forget('Tokenf');
        session()->forget('TokenCookies');
        return true;
    }

	public static function prueba(){
	    echo "TokenCookie [" . session('tokenCookie') . "]";
	    echo "Tokenf [" . session('tokenf') . "]";
        print_r(manager::getManager()->getConnectedusers());
        print_r(manager::getManager()->obtenerUsuario());
    }
	
}
