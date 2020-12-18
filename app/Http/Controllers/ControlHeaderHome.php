<?php

namespace App\Http\Controllers;

class ControlHeaderHome extends Controller
{

	public function logicaHeaderHome(){

		$dinero = ControlInicio::obtenerUserData()['efectivo'];
		if($dinero>0){
			echo "class='dinero1'>";
		}else{
			echo "class='dinero2'>";
		}
		setlocale(LC_MONETARY, 'nl_NL');
		$dinero = '$' . number_format($dinero, 2);
 		echo $dinero;
	}

	public function logicaHeaderHome2($token){
		
		echo ControlInicio::obtenerUserData()['experiencia'];
	}
}
