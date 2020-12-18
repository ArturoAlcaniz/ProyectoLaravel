<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

// tables "chat"

class chatDAO extends Controller
{

    public function obtenerMensaje($id){
        return AgenteBD::getAgente()->consulta("SELECT date FROM chat WHERE idUsers = ? ORDER BY date DESC LIMIT 1", array($id))[0][0];
    }

    public function mensajesMandados($id){
        return AgenteBD::getAgente()->consulta("SELECT COUNT(*) FROM chat WHERE idUsers = ?", array($id))[0][0];
    }

    public function obtenerMensajesChat(){
        return AgenteBD::getAgente()->consulta("SELECT idUsers, msj, date FROM chat ORDER BY date DESC LIMIT 10");
    }

    public function insertarChat($id, $date, $msj){
        return AgenteBD::getAgente()->modificacion("INSERT INTO chat (idUsers, date, msj) VALUES (?, ?, ?)", array($id, $date, $msj));
    }

    public function deleteChat(){
        return AgenteBD::getAgente()->modificacion("DELETE FROM chat");
    }

    public function deleteChat10(){
        return AgenteBD::getAgente()->modificacion("DELETE FROM chat LIMIT 20");
    }

    public function cantidadMensajes(){
        return AgenteBD::getAgente()->consulta("SELECT COUNT(*) FROM chat")[0][0];
    }

}