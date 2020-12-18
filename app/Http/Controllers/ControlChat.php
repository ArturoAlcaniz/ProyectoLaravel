<?php

namespace App\Http\Controllers;

class ControlChat extends Controller
{

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

    static function actualizarChat(){
        $e = new MyEvent("chatActualizado " . Session("token"));
        $e->broadcastChatActualizado();
        return true;
    }

    public function enviarMensaje(){
		$c = new chatDAO;
		$u = new usuarioDAO;

		if($c->cantidadMensajes() > 30){
            $c->deleteChat10();
        }

		$id = $u->buscarToken($_SESSION["token"]);

		$hoy = date("Y-m-d H:i:s", strtotime("now"));
		$r = $c->insertarChat($id, $_POST["Mensaje"], $hoy);
		$this->actualizarChat();
		return $r;
	}

        public function obtenerMensajes(){

            $c = new chatDAO;

            $info = $c->obtenerMensajesChat();

            $hoy = date("Y-m-d H:i:s", strtotime("now"));
            $t = -1;
            
            foreach ($info as $valor){
                $t++;
                $valor[2] = self::dateDiff($valor[2], $hoy);
                $valor[2] = self::aproxFecha($valor[2]);

                $rango = self::obtenerRango($valor[0]);

              if($t == 0){
                    echo $valor[1]."#".$valor[2]."#".$rango;
                }else{
                    echo "#".$valor[1]."#".$valor[2]."#".$rango;
                }

            }
        }

    /*
        public function obtenerMensajes(){

            $u = new usuarioDAO;
            $info = $u->obtenerMensajesChat();
            $hoy = date("Y-m-d H:i:s", strtotime("now"));
            $t = -1;
            foreach ($info as $valor){
                $t++;
                $valor[2] = $u->dateDiff($valor[2], $hoy);
                $valor[2] = $u->aproxFecha($valor[2]);

                if($t == 0){
                    echo $valor[1]."#".$valor[2]."#".self::obtenerRango($valor[0]);
                }else{
                    echo "#".$valor[1]."#".$valor[2]."#".self::obtenerRango($valor[0]);
                }

            }
        }*/

	public function obtenerRango($id){
		
		$u = new usuarioDAO;
		$poder = $u->obtenerPoderID($id);
		$nick = $u->buscarNick($id);
		
		if($poder != 0){
			
			return "[" . $u->obtenerRango($poder) . "]" . " " . $nick;
		}else{
			return $nick;
		}

	}	
}
