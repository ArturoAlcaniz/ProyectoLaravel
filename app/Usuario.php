<?php

namespace App;

class Usuario{

    private $email;
    private $nick;
    private $efectivo;
    private $experiencia;

    public function __construct($email, $nick, $efectivo, $experiencia){

        $this->email = $email;
        $this->nick = $nick;
        $this->efectivo = $efectivo;
        $this->experiencia = $experiencia;

    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getNick(){
        return $this->nick;
    }

    public function setNick($nick){
        $this->nick = $nick;
    }

    public function getEfectivo(){
        return $this->efectivo;
    }

    public function setEfectivo($efectivo){
        $this->efectivo = $efectivo;
    }

    public function getExperiencia(){
        return $this->experiencia;
    }

    public function setExperiencia($experiencia){
        $this->experiencia = $experiencia;
    }


}
