<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class usuarioDAO extends Controller
{
   
	public function registrar($email, $pass){

		$sql = "SELECT idUsers, email FROM usuarios WHERE email = ?"; //getIDWithEmailFromUsuarios
		$datos = array($email);
		$agente = AgenteBD::getAgente();
	
		$existe = $agente->comprobacion($sql, $datos);

		if($existe){
			
			$pass = password_hash($pass, PASSWORD_BCRYPT);
			
			$nick = $this->generarNickAleatorio();
			
			$sql = "SELECT nick FROM usuarios WHERE nick = ?"; //getIDWithNickFromUsuarios
			$datos = array($nick);
			
			$existe = $agente->comprobacion($sql, $datos);
			
			while(!$existe){
				
				$nick = $this->generarNickAleatorio();
				$datos = array($nick);
				$existe = $agente->comprobacion($sql, $datos);
				
			}
			
			$sql = "INSERT INTO usuarios (email, nick, pass) VALUES (?, ?, ?)"; //insertINTOUsuarios
			$datos = array($email, $nick, $pass);
			
			$agente->modificacion($sql, $datos);
			return true;
			
			
			
			
		}else{
			return false;
		}
				
	}
	
	public function generarUltimoId(){
		
		$sql = "SELECT MAX(idUsers) FROM usuarios"; //getMaxIDFromUsuarios
		$agente = AgenteBD::getAgente();
		$tables = $agente->consulta($sql);
        return $tables[0][0]+1;
	}
	
	public function buscarNombre($nombre){ //getIDWithNickFromUsuarios
		
		$sql = "SELECT idUsers FROM usuarios WHERE nick = ?";
		$agente = AgenteBD::getAgente();
		$datos = array($nombre);
		$id = $agente->consulta($sql, $datos);
		return $id[0][0];
	}
	
	public function mutear($id, $minutos){ //updateMuteWithIDTimeFromUsuarios
		
		$hoyexp = date("Y-m-d H:i:s", strtotime("now +".$minutos." minutes"));
		$hoyexp =  base64_encode($hoyexp);
		$sql = "UPDATE usuarios SET muteado = ? WHERE idUsers = ?";
		$datos = array($hoyexp, $id);
		$agente = AgenteBD::getAgente();
		return $agente->modificacion($sql, $datos);
		
	}
	
	public function generarNickAleatorio(){
		$nick = "user";
		$nick = $nick . $this->generarUltimoId();
		$nick = $nick . rand(0, 99);
		return $nick;
	}

	public function login($email, $pass){ //getInfoWithEmailFromUsuarios
		
		$sql = "SELECT idUsers, email, pass FROM usuarios WHERE email = ?";
		$datos = array($email);
		$agente = AgenteBD::getAgente();
		$tables = $agente->consulta($sql, $datos);			
		return password_verify($pass, $tables[0][2]);
				
	}
	
	public function comprobarNick($nick){ //getIDWithNickFromUsuarios
		
		$sql = "SELECT nick FROM usuarios WHERE nick = ?";
		$datos = array($nick);
		$agente = AgenteBD::getAgente();
		return $agente->comprobacion($sql, $datos);
		
	}
	
	public function comprobarcambiodatos($id){ //getDateWithIDFromCambiodatos
		
		$sql = "SELECT idUsers FROM cambiodatos WHERE idUsers = ?";
		$datos = array($id);
		$agente = AgenteBD::getAgente();
        return !($agente->comprobacion($sql, $datos));
	}
	
	public static function cambiarpass($email, $pass, $nuevapass){ //updatePassFromUsuarios
		
		$sql = "UPDATE usuarios SET pass = ? WHERE email = ? AND pass = ?";
		$datos = array($nuevapass, $email, $pass);
		$agente = AgenteBD::getAgente();
		return $agente->modificacion($sql, $datos);
		
	}
	
	public function cambiaremail($email, $pass, $nuevoemail){ //updateEmailFromUsuarios
		
		
		$sql = "UPDATE usuarios SET email = ? WHERE email = ? AND pass = ?";
		$datos = array($nuevoemail, $email, $pass);
		$agente = AgenteBD::getAgente();
		return $agente->modificacion($sql, $datos);
		
	}
	
	public function cambiarDatos($token, $email, $nick, $password){
		
		$id = $this->buscarToken($token);
		
		if(is_null($password)){
			$sql = "UPDATE usuarios SET email = ?, nick = ? WHERE idUsers = ?"; //updateEmailNickWithIDFromUsuarios
		}else{
			$sql = "UPDATE usuarios SET email = ?, nick = ?, pass = ? WHERE idUsers = ?"; //updateEmailNickPassFromUsuarios
		}
			
		if(is_null($password)){
			
			$datos = array($email, $nick, $id);
		
		}else{
			
			$datos = array($email, $nick, $password, $id);
	
		}
		manager::getManager()->modificarUsuario($email, $nick);
		$agente = AgenteBD::getAgente();
		return $agente->modificacion($sql, $datos);
			
			
		
	}
	
	public function createRandomToken($len){
		
		$string = "OESdASXzsdASdFGEDCFrtyoasmviyqns43oai43asAQET75UDQE7B5ASEmas53hd23uiq12";
	
		return substr(str_shuffle($string), 0, $len);
	}
	
	public function checkSession(){
		if(!isset($_SESSION["token"])){
			return false;
		}else{
			return true;
		}
	}
	
	public function createSession(Request $request){
		
		$token = $this->createRandomToken(30);
		$request->session()->forget('token');
		$token = $this->eliminarEspacios($token);
		$request->session()->put('token',$token);
		$request->session()->put('email', $request->input('email-login'));
		return $token;
	
	}
	
	public function saveToken(Request $request){
		
		$email = $request->input('email-login');
		$id = $this->buscarEmail($email);

		$sql = "DELETE FROM sesiones WHERE userId = ?"; //deleteWithIDFromSesiones
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$agente->modificacion($sql, $datos);
		
		$token = $this->createSession($request);
        manager::getManager()->pushConnectedUser($id, $token);
		
		$hoy = date("Y-m-d H:i:s", strtotime("now"));
			
		$hoy = base64_encode($hoy);
			
		$hoyexp = date("Y-m-d H:i:s", strtotime("now +2 hours"));
			
		$hoyexp =  base64_encode($hoyexp);
		
		$sql = "INSERT INTO sesiones (token, userId, date, dateExp) VALUES (?, ?, ?, ?)"; //insertSesiones
		$datos = array($token, $id, $hoy, $hoyexp);
		
		$agente->modificacion($sql, $datos);
	}
	
	public function eliminarEspacios($str){

		$t = str_replace(' ', '', $str);
		$t = str_replace('	', '', $t);
		return $t;
	}
	
	public function saveTokenCookie(){
		
		$token = self::createRandomToken(30);
		
		$hoy = date("Y-m-d H:i:s", strtotime("now"));
		
		$hoy = base64_encode($hoy);
	
		$hoyexp = date("Y-m-d H:i:s", strtotime("now +24 hours"));
		
		$hoyexp = base64_encode($hoyexp);
		
		$ip = $this->obtenerIP();
		
		$sql = "DELETE FROM sesionesCookie WHERE ip = ?"; //deleteWithIPFromSesionesCookie
		
		$datos = array($ip);
		
		$agente = AgenteBD::getAgente();
		
		$agente->modificacion($sql, $datos);

		$sql = "INSERT INTO sesionesCookie (ip, token, date, dateExp) VALUES (?, ?, ?, ?)"; //insertSesionesCookie
		
		$datos = array($ip, $token, $hoy, $hoyexp);
		
		$agente->modificacion($sql, $datos);
		
	}
	
	public function obtenerIP(){
		
		if (isset($_SERVER["HTTP_CLIENT_IP"]))
    	{
        	return $_SERVER["HTTP_CLIENT_IP"];
		}
		
		elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    	{
        	return $_SERVER["HTTP_X_FORWARDED_FOR"];
    	}
  
		elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
    	{
			return $_SERVER["HTTP_X_FORWARDED"];
    	}
   
		elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
    	{
        	return $_SERVER["HTTP_FORWARDED_FOR"];
    	}
    
		elseif (isset($_SERVER["HTTP_FORWARDED"]))
    	{
			return $_SERVER["HTTP_FORWARDED"];
		}
    	
		else
		{
			return $_SERVER["REMOTE_ADDR"];
		}
		
	}
	

	
	public function saveCambioDatos($id){
			
		$sql = "DELETE FROM cambiodatos WHERE idUsers = ?"; //deleteWithIDFromCambiodatos
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$agente->modificacion($sql, $datos);
			
		$hoy = date("Y-m-d H:i:s", strtotime("now"));
		$hoy = base64_encode($hoy);
			
		$hoyexp = date("Y-m-d H:i:s", strtotime("now +2 hours"));
		$hoyexp =  base64_encode($hoyexp);
			
		$sql = "INSERT INTO cambiodatos (idUsers, datecambio, datenuevocambio) VALUES (?, ?, ?)"; //insertCambiodatos
		$datos = array($id, $hoy, $hoyexp);
		return $agente->modificacion($sql, $datos);
		
	}
	
	public function buscarEmail($email){
		$sql = "SELECT idUsers FROM usuarios WHERE email = ?"; //getIDWithEmailFromUsuarios
		$datos = array($email);
		$agente = AgenteBD::getAgente();
		$id = $agente->consulta($sql, $datos);
		return $id[0][0];
		
	}
	
	public function buscarDinero($token){
		
		$id = $this->buscarToken($token);
		$sql = "SELECT efectivo FROM usuarios WHERE idUsers = ?"; //getEfectivoWithIDFromUsuarios
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$dinero = $agente->consulta($sql, $datos);
		return $dinero[0][0];
		
	}
	
	public function buscarId($id){
		
		$sql = "SELECT email FROM usuarios WHERE idUsers = ?"; //getEmailWithIDFromUsuarios
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$email = $agente->consulta($sql, $datos);
		return $email[0][0];
		
	}
	
	public static function buscarNick($id){
		
		$sql = "SELECT nick FROM usuarios WHERE idUsers = ?"; //getNickWithIDFromUsuarios
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$nick = $agente->consulta($sql, $datos);		
		return $nick[0][0];
		
	}
	
	public function buscarToken($token){
		
		$sql = "SELECT userId FROM sesiones WHERE token = ?"; //getIDWithTokenFromUsuarios
		$datos = array($token);
		$agente = AgenteBD::getAgente();
		$id = $agente->consulta($sql, $datos);
		return $id[0][0];
	}
	
	public function obtenerdatecambio($id){
		
		$sql = "SELECT datenuevocambio FROM cambiodatos WHERE idUsers = ?"; //getDatenuevocambioWithIDFromcambiodatos
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$date = $agente->consulta($sql, $datos);
			
		if(isset($date[0][0])){
			
			return $date[0][0];
			
		}else{
			
			return NULL;	
			
		}
		
	}
	
	public function existeToken($token){
		
		$sql = "SELECT token FROM sesiones WHERE token = ?"; //getIDWithTokenFromSesiones
		$datos = array($token);
		$agente = AgenteBD::getAgente();
		$existente = $agente->comprobacion($sql, $datos);
		return !$existente;
	}
	
	public function existeTokenCookie($token){
		
		$sql = "SELECT token FROM sesionesCookie WHERE token = ?"; //getIDWithTokenFromSesionesCookie
		$datos = array($token);
		$agente = AgenteBD::getAgente();
		$existente = $agente->comprobacion($sql, $datos);
		return !$existente;
	}
	
	public function comprobarTokenPassword($token, $password){
		
		$id = $this->buscarToken($token);
		$sql = "SELECT pass FROM usuarios WHERE idUsers = ?"; //getPassWithIDFromUsuarios
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$tables = $agente->consulta($sql, $datos);			
		return password_verify($password, $tables[0][0]);
			
	}
	
	public function existeEmail($email){
		
		$sql = "SELECT email FROM usuarios WHERE email = ?"; //getIDWithEmailFromUsuarios
		$datos = array($email);
		$agente = AgenteBD::getAgente();
		$existente = $agente->comprobacion($sql, $datos);
		return !$existente;
		
	}
	
	public function buscarHash($email){
		
		$sql = "SELECT pass FROM usuarios WHERE email = ?"; //getPassWithEmailFromUsuarios
		$datos = array($email);
		$agente = AgenteBD::getAgente();
		$pass = $agente->consulta($sql, $datos);
		return $pass[0][0];
			
		
	}
	
	public function obtenerdateExp($token){
		$sql = "SELECT dateExp FROM sesiones WHERE token = ?"; //getDateExpWithTokenFromSesiones
		$datos = array($token);
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql, $datos);		
		return $resultado[0][0];
		
	}
	
	public function obtenerdateExpCookie($token){
		$sql = "SELECT dateExp FROM sesionesCookie WHERE token = ?"; //getDateExpWithTokenFromSesionesCookie
		$datos = array($token);
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql, $datos);
		return $resultado[0][0];
	}
	
	public function obtenerRanking(){
		$sql = "SELECT nick, efectivo FROM usuarios ORDER BY efectivo DESC LIMIT 10"; //getRankingEfectivoFromUsuarios
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql);
		$sql = "SELECT nick, experiencia FROM usuarios ORDER BY experiencia DESC LIMIT 10"; //getRankingExperienciaFromUsuarios
		$resultado2 = $agente->consulta($sql);
		return array($resultado,$resultado2);
	}
	
	public function obtenerExperiencia($token){
		$id = $this->buscarToken($token);
		$sql = "SELECT experiencia FROM usuarios WHERE idUsers = ?"; //getExperienciaWithIDFromUsuarios
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql, $datos);
		return $resultado[0][0];
	}

	public function obtenerExperienciaDinero($id){
	    return AgenteBD::getAgente()->consulta("SELECT experiencia, efectivo FROM usuarios WHERE idUsers = ?", array($id))[0];
    }

    public function obtenerExperienciaId($id){
        $sql = "SELECT experiencia FROM usuarios WHERE idUsers = ?"; //getExperienciaWithIDFromUsuarios
        $datos = array($id);
        $agente = AgenteBD::getAgente();
        $resultado = $agente->consulta($sql, $datos);
        return $resultado[0][0];
    }
	
	public function comprobarTrabajando($token){
		$id = $this->buscarToken($token);
		$sql = "SELECT * FROM trabajando WHERE idUsers = ?"; //getInfoWithIDFromTrabajando
		$datos = array($id);
		$agente = AgenteBD::getAgente();
        return $agente->comprobacion($sql, $datos);
	}
	
	public function obtenerDateExpTrabajo($token){
		$id = $this->buscarToken($token);
		$sql = "SELECT * FROM trabajando WHERE idUsers = ?"; //getInfoWithIDFromTrabajando
		$datos = array($id);
		$agente = AgenteBD::getAgente();
        return $agente->consulta($sql, $datos);
	}
	
	public function obtenerFinTrabajo($token){
		$info = $this->obtenerDateExpTrabajo($token);
		return $info[0][3];
	}
	
	public function obtenerMuteo($token){
		$id = $this->buscarToken($token);
		$sql = "SELECT muteado FROM usuarios WHERE idUsers = ?"; //getMuteoWithIDFromUsuarios
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql, $datos);
		return base64_decode($resultado[0][0]);
	}
	
	public function obtenerPoder($token){
		$id = $this->buscarToken($token);
		$sql = "SELECT poder FROM usuarios WHERE idUsers = ?"; //getPoderWithIDFromUsuarios
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql, $datos);
		return $resultado[0][0];
	}
	
	public static function obtenerPoderID($id){
		$sql = "SELECT poder FROM usuarios WHERE idUsers = ?"; //getPoderWithIDFromUsuarios
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql, $datos);
		return $resultado[0][0];
	}
	
	public static function obtenerRango($idRango){
		return AgenteBD::getAgente()->consulta("SELECT rango FROM rangos WHERE idRango = ?", array($idRango))[0][0];
	}
	
	public function saveExperiencia($token, $experiencia){
		$id = $this->buscarToken($token);
		$sql = "UPDATE usuarios SET experiencia = ? WHERE idUsers = ?"; //updateExperienciaWithIDFromUsuarios
		$datos = array($experiencia, $id);
		$agente = AgenteBD::getAgente();
		manager::getManager()->modificarUsuario(null, null, null, $experiencia);
        return $agente->modificacion($sql, $datos);
	}

	public function saveExperienciaDineroId($id, $experiencia, $dinero){
	    return AgenteBD::getAgente()->modificacion("UPDATE usuarios SET experiencia = ?, efectivo = ? WHERE idUsers = ?", array($experiencia, $dinero, $id));
    }
	
	public function saveDinero($token, $dinero){
		$id = $this->buscarToken($token);
		$sql = "UPDATE usuarios SET efectivo = ? WHERE idUsers = ?"; //updateEfectivoWithIDFromUsuarios
		$datos = array($dinero, $id);
		$agente = AgenteBD::getAgente();
		manager::getManager()->modificarUsuario(null, null, $dinero, null);
        return $agente->modificacion($sql, $datos);
	}
	
	public function limpiarChat(){
		$sql = "DELETE FROM chat"; //clearChat
		$agente = AgenteBD::getAgente();
        return $agente->modificacion($sql);
	}
	
	public function buscarDineroId($id){
		$sql = "SELECT efectivo FROM usuarios WHERE idUsers = ?";
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql, $datos);
		return $resultado[0][0];
	}
	
	public function enviarMensaje($token, $mensaje, $fecha){
		$sql = "SELECT COUNT(*) FROM chat";
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql);
		if($resultado[0][0] >= 30){
			$sql = "DELETE FROM chat LIMIT 20";
			$agente->modificacion($sql);
		}
		$sql = "INSERT INTO chat (idUsers, date, msj) VALUES (?, ?, ?)";
		$datos = array($this->buscarToken($token), $fecha, $mensaje);
		$resultado = $agente->modificacion($sql, $datos);
        ControlInicio::actualizarChat();
		return $resultado;
	}
	
	public function usuarioCambiarDinero($id, $dinero){
		$actual = $this->buscarDineroId($id);
		$sql = "UPDATE usuarios SET efectivo = ? WHERE idUsers = ?";
		$dinero = $actual + $dinero;
		$datos = array($dinero, $id);
		$agente = AgenteBD::getAgente();
		manager::getManager()->modificarUsuario(null, null, $dinero, null);
        return $agente->modificacion($sql, $datos);
	}
	
	public static function obtenerMensajesChat(){
		$sql = "SELECT idUsers, msj, date FROM chat ORDER BY date DESC LIMIT 10";
		$agente = AgenteBD::getAgente();
        return $agente->consulta($sql);
	}
	
	public function MisMensajesMandados($token){
		$id = $this->buscarToken($token);
		$sql = "SELECT COUNT(*) FROM chat WHERE idUsers = ?";
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql, $datos);
		if(empty($resultado)){
			return 0;	
		}else{
			return $resultado[0][0];
		} 
	}

	public function obtenerMiMensaje($token){
		$id = $this->buscarToken($token);
		$sql = "SELECT date FROM chat WHERE idUsers = ? ORDER BY date DESC LIMIT 1";
		$datos = array($id);
		$agente = AgenteBD::getAgente();
		$resultado = $agente->consulta($sql, $datos);
		return $resultado[0][0];
	}

	public function borrarTrabajando($id){
        AgenteBD::getAgente()->modificacion("DELETE FROM trabajando WHERE idUsers = ?", array($id));
    }

    public function insertarTrabajando($idUsers, $idTrabajo, $date, $dateExp){
	    AgenteBD::getAgente()->modificacion("INSERT INTO trabajando (idUsers, idTrabajo, date, dateExp) VALUES (?, ?, ?, ?)",
            array($idUsers, $idTrabajo, $date, $dateExp));
    }

	public static function dateDiff($start, $end) {

        return (strtotime($end) - strtotime($start));
		
	}
	
	public static function aproxFecha($diff){
		
		if($diff<60){
			return "Hace ".$diff." segundos";
		}else{
			
			if($diff>=60 && $diff<3600){
				
				return "Hace ".floor($diff/60). "minutos";
				
			}else{
				
				if($diff<86400){
					
					return "Hace ".floor($diff/3600). "horas";
					
				}else{
					
					return "Hace ".floor($diff/86400). "dias";
				
				}
				
			}
			
		}
		
	}

	public function obtenerSueldoEmpleadoJefe($trabajo){
	    return AgenteBD::getAgente()->consulta("SELECT ne.sueldoempleados, ne.beneficiojefe FROM negociostipo ne 
                                                    LEFT JOIN negocios n ON n.tipo = ne.tipo WHERE n.idTrabajo = ?", array($trabajo))[0];
    }

	
}
