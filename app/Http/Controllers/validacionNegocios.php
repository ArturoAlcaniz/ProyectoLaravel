<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class validacionNegocios extends Controller
{
	
	
	function longitud($variable){
		
		if (strlen($variable)>30){ 
     	
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
    
		if (preg_match('/[^a-z0-9¿?-_ ]+/i',$x)) {
     
			return true;        
 
		}
   
		return false;

	}


	public function crearNegocio(Request $request){
		
		if($this->has_specchar($request->input('TipoNegocio'))) {
					
			$avisonegocios = "<p class='error'>El tipo negocio es incorrecto</p>";
			return view('negocios')->with('avisonegocios', $avisonegocios);
		}
		
		if($this->has_specchar($request->input('negocio-nombre'))) {
					
			$avisonegocios = "<p class='error'>El nombre de negocio tiene caracteres no validos</p>";
			return view('negocios')->with('avisonegocios', $avisonegocios);
		}
		
		if(!$this->longitud($request->input('negocio-nombre'))) {
				
			$avisonegocios = "<p class='error'>Longitud del nombre no valida</p>";
			return view('negocios')->with('avisonegocios', $avisonegocios);
		}
		
		if(!in_array($request->input('TipoNegocio'), array(0, 1, 2))){
			
			$avisonegocios = "<p class='error'>El tipo negocio es incorrecto</p>";
			return view('negocios')->with('avisonegocios', $avisonegocios);
			
		}
		
		if(empty($request->input('negocio-nombre'))) {
			
			$avisonegocios = "<p class='error'>El nombre de negocio esta vacio</p>";
			return view('negocios')->with('avisonegocios', $avisonegocios);
			
		}
		
		$g = new gestorTrabajo;
		
		if($g->existeNombreNegocio($request->input('negocio-nombre'))) {
			
			$avisonegocios = "<p class='error'>El nombre de negocio ya existe</p>";
			return view('negocios')->with('avisonegocios', $avisonegocios);
			
		}
		
		$u = new usuarioDAO;
		
		if($u->buscarDinero(Session("token"))-$g->obtenerInfoNegocio($request->input('TipoNegocio'))[0] < 0){
			
			$avisonegocios = "<p class='error'>No tienes dinero suficiente para crearlo</p>";
			return view('negocios')->with('avisonegocios', $avisonegocios);
		}
		
		if(!$g->crearTrabajo(Session("token"), $request->input('TipoNegocio'), $request->input('negocio-nombre'))) {
			
			$avisonegocios = "<p class='error'>No se ha podido crear el negocio correctamente</p>";
			return view('negocios')->with('avisonegocios', $avisonegocios);
			
		}else{
			
			$avisonegocios = "<p class='error'>Negocio creado correctamente</p>";
			return view('negocios')->with('avisonegocios', $avisonegocios);
			
		}
		
	}
	
}
