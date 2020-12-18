<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class validacionTrabajo extends Controller
{
	
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

	public function estaTrabajando(){
		$gestor = new gestorTrabajo;
		return $gestor->comprobacionTrabajando(Session("token"));
	}
	
	public function obtenerTiempoTrabajo(){
		
		$gestor = new gestorTrabajo;
		
		if($gestor->comprobacionTrabajando(Session("token"))) {

			$u = new usuarioDAO;
			
			$fecha = $u->obtenerFinTrabajo(Session("token"));
			$hoy = date("Y-m-d H:i:s", strtotime("now"));
			$regresiva = $gestor->dateDiff($hoy, base64_decode($fecha));
			$segundos = $regresiva*3600;
			$horas = floor($regresiva);
			$minutos = floor(($segundos - ($horas * 3600)) / 60);
			$segundos = floor($segundos - ($horas * 3600) - ($minutos * 60));
			$avisotrabajo = "<p class='error'>Ya se encuentra trabajando.</p>
							 <p class='error'>Puede volver a trabajar en:</p>
							 <p class='error'>".$horas." horas ".$minutos." minutos ".$segundos." segundos</p>";

		}else{
			
			$avisotrabajo = "";
			
		}
		
		return $avisotrabajo;
	}
	
	public function trabajar(Request $request){
		
		$gestor = new gestorTrabajo;
		
		if($this->estaTrabajando()){
			
			$avisotrabajo = $this->obtenerTiempoTrabajo();
			return view('trabajo')->with('avisotrabajo', $avisotrabajo);
		
		}
		
		if($this->has_specchar($request->input('TrabajoELEGIDO'))) {
					
			$avisotrabajo = "<p class='error'>Trabajo elegido no valido</p>";
			return view('trabajo')->with('avisotrabajo', $avisotrabajo);
		}
			
		if(empty($request->input('TrabajoELEGIDO'))) {
			
			$avisotrabajo = "<p class='error'>El campo trabajo esta vacio.</p>";
			return view('trabajo')->with('avisotrabajo', $avisotrabajo);
		}
		
		if($gestor->comprobarTrabajo($request->input('TrabajoELEGIDO'))) {
			
			$avisotrabajo = "<p class='error'>El trabajo no existe.</p>";
			return view('trabajo')->with('avisotrabajo', $avisotrabajo);
		}
				
		$exp = $gestor->obtenerInfo($request->input('TrabajoELEGIDO'));
				
		$u = new usuarioDAO;
		
		if($exp[0]>$u->obtenerExperiencia(Session("token"))) {
					
			$avisotrabajo = "<p class='error'>El trabajo requiere mas experiencia de la que tiene.</p>";
			return view('trabajo')->with('avisotrabajo', $avisotrabajo);
		}
		
		if(!($request->input('horas') == 5 || $request->input('horas') == 8)){
						
			$avisotrabajo = "<p class='error'>El numero de horas elegidas es incorrecto.</p>";
			return view('trabajo')->with('avisotrabajo', $avisotrabajo);
		}
							
		if($gestor->obtenerJefe($request->input('TrabajoELEGIDO')) != -1){
								
			if(($gestor->obtenerPuestos($request->input('TrabajoELEGIDO'))-$gestor->obtenerPuestosOcupados($request->input('TrabajoELEGIDO'))) <= 0){
									
				$avisotrabajo = "<p class='error'>No quedan plazas en ese trabajo ahora mismo</p>";
				return view('trabajo')->with('avisotrabajo', $avisotrabajo);	
			}
									
									
			if($gestor->trabajarConJefe(Session("token"), $request->input('horas'), $request->input('TrabajoELEGIDO'), $gestor->obtenerJefe($request->input('TrabajoELEGIDO')))) {
								
				$avisotrabajo = "<p class='correcto'>Estaras trabajando durante ".$_POST['horas']." horas.</p>";
				return view('trabajo')->with('avisotrabajo', $avisotrabajo);			
			}
				
				$avisotrabajo = "<p class='error'>Error al unirte a este trabajo</p>";
				return view('trabajo')->with('avisotrabajo', $avisotrabajo);
								
								
		}else{
								
			if($gestor->trabajarSinJefe(Session("token"), $request->input('horas'), $request->input('TrabajoELEGIDO'))) {
									
				$avisotrabajo = "<p class='correcto'>Estaras trabajando durante ".$request->input('horas')." horas.</p>";
				return view('trabajo')->with('avisotrabajo', $avisotrabajo);
							
			}
				
			$avisotrabajo = "<p class='error'>Error al unirte a este trabajo</p>";
			return view('trabajo')->with('avisotrabajo', $avisotrabajo);
								
		}
						
	}
}
