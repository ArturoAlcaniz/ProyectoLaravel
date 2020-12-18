<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class gestorTrabajo extends Controller
{
	
	public function actualizarGananciaTrabajo(Request $request){
		$gestorTrabajo = new gestorTrabajo;
		$horas = $request->horas;
		$trabajo = $request->trabajo;
		$horas2 = "experiencia".$horas;
		$info = $gestorTrabajo->obtenerGanancias($trabajo, $horas2);
		echo $info[0] . " " . $info[1]*$horas;
	}

	public function actualizarExpTrabajo(Request $request){
		$gestorTrabajo = new gestorTrabajo;
		$consulta = $request->consulta;
		$info = $gestorTrabajo->obtenerInfo($consulta);
		echo $info[0] . " " . $info[1];
	}

	public function actualizarCosteTrabajo(Request $request){
		$gestorTrabajo = new gestorTrabajo;
		$consulta = $request->consulta;
		$info = $gestorTrabajo->obtenerInfoNegocio($consulta);
		echo $info[0] . " " . $info[1];
	}
	
	public function buscarTrabajos(){
		$sql = "SELECT idTrabajo, nombreTrabajo FROM trabajo";
		$agente = new AgenteBD;
		$resultado = $agente->consulta($sql);
		return $resultado;
	}
	
	public function mostrarTrabajos(){
	
		$trabajos = $this->buscarTrabajos();
		
		foreach($trabajos as $row)
		{
			if($row[0] == 1){
				echo "<option selected value='".$row[0]."'>".$row[1]."</option>";
			}else{
				
				if($this->obtenerJefe($row[0]) == -1){
					
					echo "<option value='".$row[0]."'>".$row[1]."</option>";
			
				}else{
					
					if(($this->obtenerPuestos($row[0])-$this->obtenerPuestosOcupados($row[0])) > 0){
						
						echo "<option value='".$row[0]."'>".$row[1]."</option>";
						
					}
				}
			}
		}
	}
	public function obtenerTipoFecha($idTrabajo){
	
		$sql = "SELECT tipo, fecha_inicio FROM negocios WHERE idTrabajo = ?";
		$datos = array($idTrabajo);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		return $info;
	}
	
	public function nombreNegocio($token){
	
		$u = new usuarioDAO;
		$id = $u->buscarToken($token);
		$sql = "SELECT idTrabajo, nombreTrabajo FROM trabajo WHERE idJefe = ?";
		$datos = array($id);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		return $info;
	}
	
	public function mostrarNegocios(){
		$info = $this->nombreNegocio(Session("token"));
		$t = 1;
		foreach ($info as &$valor) {
			$tipofecha = $this->obtenerTipoFecha($valor[0]);
			$puestosOcupados = $this->obtenerPuestosOcupados($valor[0]);
			$puestosTotales = $this->obtenerInfoNegocio($tipofecha[0][0])[1];
			echo "<label for='negocio" . $t ."' class='negocio" . $t ."'>Negocio nº " . $t ." | " . $valor[1] ." | tipo " . $tipofecha[0][0] ." | fecha creacion " . $tipofecha[0][1] ." | Empleados trabajando " . $puestosOcupados ." / " . $puestosTotales ." |</label><br/>";
			$t++;
		}
	}
	
	
	public function buscarNegociosTipo(){
		$sql = "SELECT tipo FROM negociostipo";
		$agente = new AgenteBD;
		$resultado = $agente->consulta($sql);
		return $resultado;
	}
	
	public function mostrarTipoNegocios(){
		
		$tipos = $this->buscarNegociosTipo();
		
		foreach($tipos as $row){
		
			if($row[0] == 0){
				
				echo "<option selected value='".$row[0]."'>".$row[0]."</option>";
				
			}else{
				
				echo "<option value='".$row[0]."'>".$row[0]."</option>";
				
			}
			
			
		}
		
		
	}
	
	public function obtenerExperienciaNecesaria($trabajo){
		$sql = "SELECT experiencia FROM trabajo WHERE idTrabajo = ?";
		$datos = array($trabajo);
		$agente = new AgenteBD;
		$exp = $agente->consulta($sql, $datos);
		return $exp[0][0];
	}
	
	public function obtenerInfo($trabajo){
		$sql = "SELECT experiencia, sueldohora FROM trabajo WHERE idTrabajo = ?";
		$datos = array($trabajo);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		return $info[0];
	}
	
	public function obtenerInfoNegocio($tipo){
		$sql = "SELECT costecreacion, puestos FROM negociostipo WHERE tipo = ?";
		$datos = array($tipo);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		return $info[0];
	}
	
	public function obtenerJefe($trabajo){
		$sql = "SELECT idJefe FROM trabajo WHERE idTrabajo = ?";
		$datos = array($trabajo);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		return $info[0][0];
	}

	public function obtenerPuestosOcupados($trabajo){
		$sql = "SELECT dateExp FROM trabajando WHERE idTrabajo = ?";
		$datos = array($trabajo);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		$r = 0;
		$hoy = date("Y-m-d H:i:s", strtotime("now"));
		$t = count($info);
		for($i = 0; $i<$t; $i++){
			
			if($this->dateDiff($hoy, base64_decode($info[$i][0]))>0){
				
				$r++;
				
			}
		}
		
		return $r;
	}
	
	public function obtenerPuestos($trabajo){
		$sql = "SELECT puestos FROM negociostipo WHERE tipo = (SELECT tipo FROM negocios WHERE idTrabajo = ?)";
		$datos = array($trabajo);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		return $info[0][0];
	}
	
	public function obtenerGanancias($trabajo, $horas){
		$sql = "SELECT ".$horas.", sueldohora FROM trabajo WHERE idTrabajo = ?";
		$datos = array($trabajo);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		return $info[0];
	}
	
	public function comprobarTrabajo($trabajo) {
		$sql = "SELECT * FROM trabajo WHERE idTrabajo = ?";
		$datos = array($trabajo);
		$agente = new AgenteBD;
		$info = $agente->comprobacion($sql, $datos);
		return $info;
	}
	
	public function dateDiff($start, $end) {

		$start_ts = strtotime($start);

		$end_ts = strtotime($end);

		$diff = $end_ts - $start_ts;

		return $diff / 3600;	
		
	}
	
	public function comprobacionTrabajando($token){
		$comp = false;
		
		$u = new usuarioDAO;
		
		if($u->existeToken($token)){

		    if(!$u->comprobarTrabajando($token)){

                $hoy = date("Y-m-d H:i:s", strtotime("now"));

                $exp = $u->obtenerFinTrabajo($token);

                if(!is_null($exp)){

                    $exp = base64_decode($exp);

                    if($this->dateDiff($hoy, $exp)>0){

                        $comp = true;

                    }
                }
            }
		}

		return $comp;		
	}
	
	public function existeNombreNegocio($nombre){
		$sql = "SELECT * FROM trabajo WHERE nombreTrabajo = ?";
		$datos = array($nombre);
		$agente = new AgenteBD;
		$existente = $agente->comprobacion($sql, $datos);
		return !$existente;
	}

	public function obtenerSueldoEmpleados($tipo){
		$sql = "SELECT sueldoempleados FROM negociostipo WHERE tipo = ?";
		$datos = array($tipo);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		return $info[0][0];
	}
	
	public function obteneridtrabajo($nombre){
		$sql = "SELECT idTrabajo FROM trabajo WHERE nombreTrabajo = ?";
		$datos = array($nombre);
		$agente = new AgenteBD;
		$info = $agente->consulta($sql, $datos);
		return $info[0][0];
	}
	
	public function crearTrabajo($token, $tipo, $nombre){
		
		$u = new usuarioDAO;
		$id = $u->buscarToken($token);
		$sueldohora = $this->obtenerSueldoEmpleados($tipo);
		$exp = 2.00 + $tipo;
		$exp8 = $exp * 1.2;
		$expneed = $tipo * 10;
		$sql = "INSERT INTO trabajo (idJefe, nombreTrabajo, sueldohora, experiencia5, experiencia8, experiencia) VALUES (?, ?, ?, ?, ?, ?)";
		$datos = array($id, $nombre, $sueldohora, $exp, $exp8, $expneed);
		$agente = new AgenteBD;
		$resultado = $agente->modificacion($sql, $datos);
		
		if(!$resultado){
			return false;
		}
		
		$idnegocio = $this->obteneridtrabajo($nombre);
		$hoy = date("Y-m-d H:i:s", strtotime("now"));
		
		$sql = "INSERT INTO negocios (idTrabajo, tipo, fecha_inicio) VALUES (?, ?, ?)";
		$datos = array($idnegocio, $tipo, $hoy);
		$agente = new AgenteBD;
		$resultado = $agente->modificacion($sql, $datos);
		$coste = $this->obtenerInfoNegocio($tipo)[0] * -1;
		$u->usuarioCambiarDinero($id, $coste);
		return $resultado;
		
		
		
	}

	public function trabajarConJefe($token, $horas, $trabajo, $idJefe){
        $userDAO = new usuarioDAO;

        $id = $userDAO->buscarToken($token);
        $userDAO->borrarTrabajando($id);
        $hoy = base64_encode(date("Y-m-d H:i:s", strtotime("now")));
        $hoyexp = base64_encode(date("Y-m-d H:i:s", strtotime("now +".$horas." hours")));

        $userDAO->insertarTrabajando($id, $trabajo, $hoy, $hoyexp);

        $horas2 = "experiencia".$horas;
        $ExperienciaDinero = $userDAO->obtenerExperienciaDinero($id);
        $ganancias = $this->obtenerGanancias($trabajo, $horas2);

        $expactual = $ExperienciaDinero[0];
        $dineroactual = $ExperienciaDinero[1];

        $exptotal = $expactual + $ganancias[0];

        $sueldoEmpleadoJefe = $userDAO->obtenerSueldoEmpleadoJefe($trabajo);

        $gananciadinero = $sueldoEmpleadoJefe[0] * $horas;
        $gananciajefe = $sueldoEmpleadoJefe[1] * $horas;

        $dinerototal = $dineroactual + $gananciadinero;

        $userDAO->saveExperienciaDineroId($id, $exptotal, $dinerototal);
        $userDAO->usuarioCambiarDinero($idJefe, $gananciajefe);
        manager::getManager()->modificarUsuario(null, null, $dinerototal, $exptotal);

        return true;
    }

    public function trabajarSinJefe($token, $horas, $trabajo){
        $userDAO = new usuarioDAO;

        $id = $userDAO->buscarToken($token);
        $userDAO->borrarTrabajando($id);
        $hoy = base64_encode(date("Y-m-d H:i:s", strtotime("now")));
        $hoyexp = base64_encode(date("Y-m-d H:i:s", strtotime("now +".$horas." hours")));

        $userDAO->insertarTrabajando($id, $trabajo, $hoy, $hoyexp);

        $horas2 = "experiencia".$horas;
        $ExperienciaDinero = $userDAO->obtenerExperienciaDinero($id);
        $ganancias = $this->obtenerGanancias($trabajo, $horas2);

        $expactual = $ExperienciaDinero[0];
        $dineroactual = $ExperienciaDinero[1];

        $exptotal = $expactual + $ganancias[0];

        $gananciadinero = $ganancias[1] * $horas;
        $dinerototal = $dineroactual + $gananciadinero;

        $userDAO->saveExperienciaDineroId($id, $exptotal, $dinerototal);
        manager::getManager()->modificarUsuario(null, null, $dinerototal, $exptotal);

        return true;
    }
	
}
