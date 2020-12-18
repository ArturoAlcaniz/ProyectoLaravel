<?php

namespace App\Http\Controllers;

use PDO;

class AgenteBD extends Controller
{
   
    private static $conexion;

	private static $Agente;

	public function __construct(){
    }

    public static function getAgente(){

		if(is_null(static::$Agente)){
			static::$Agente = new AgenteBD;
		}

		return static::$Agente;
	}
	private static function getConexion(){
		
		if(!self::$conexion instanceof PDO){
			
			self::$conexion = new PDO("mysql:host=".database::$server."; dbname=".database::$db."; charset=utf8", database::$user, database::$pass, [
       					PDO::ATTR_EMULATE_PREPARES => false, 
       					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING,
						PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE
     				]);	
		}
			return self::$conexion;

	}
	
	public static function consulta($sql, $datos = null){
		
		$conexion = self::getConexion();
		
		if($conexion){
			
			$stmt = $conexion->prepare($sql);
			
			if(is_null($datos)){
				$stmt->execute();
			}else{
				$stmt->execute($datos);
			}

			return $stmt->fetchAll(PDO::FETCH_NUM);
		}else{
			return 0;
		}
		
		
	}
	
	public static function modificacion($sql, $datos = null){
		
		$conexion = self::getConexion();
		
		if($conexion){
			
			$stmt = $conexion->prepare($sql);
			
			if(is_null($datos)){
				return $stmt->execute();
			}else{
				return $stmt->execute($datos);
			}
			
			
			
		}else{
			return 0;
		}
	}
	
	public static function comprobacion($sql, $datos){
		
		$conexion = self::getConexion();
		
		if($conexion){
			
			$stmt = $conexion->prepare($sql);
			$stmt->execute($datos);
			$existente = $stmt->rowCount();
			if($existente > 0){
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
	
}
