<?php

namespace App\Http\Controllers;
use Session;
use App\Usuario;

class manager{

    private static $instance = null;

    public function getConnectedusers()
    {
        $this->get_cache('connectedusers');
    }

    private function __construct()
    {
    }
    
    public static function getManager(){

        if(static::$instance == null){
            static::$instance = new manager();
        }

        return static::$instance;

    }

    public function pushConnectedUser($id, $info){

        $url = dirname(__FILE__) . '/connectedusers';
        $this->cleartempfiles($url);
        $created = $this->addtempfile($url, $id);
        session()->put('Tokenf',basename($created));
        file_put_contents($url . '/' . Session('Tokenf'), $info);
    }

    public function deleteConnectedUser()
    {

        if(session()->has('Tokenf')){

            $url = dirname(__FILE__) . '/connectedusers';
            $file_pointer = $url . '/' . Session('Tokenf');
            unlink($file_pointer);

        }

    }

    public function connected(){

        $url = dirname(__FILE__) . '/connectedusers';

        if(Session()->has('Tokenf')){
            $this->cleartempfile($url, Session('Tokenf'));
            if(is_readable($url . '/' . Session('Tokenf'))){
                touch($url . '/' . Session('Tokenf'), time());
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function guardarUsuario($email, $nick, $efectivo, $experiencia){

        $u = new Usuario($email, $nick, $efectivo, $experiencia);
        session()->put('usuarioData', serialize($u));

    }

    public function modificarUsuario($email, $nick, $efectivo, $experiencia){

        $u = $this->obtenerUsuario();

        if($email == null || $email == ''){
            $email = $u['email'];
        }

        if($nick == null || $nick == ''){
            $nick = $u['nick'];
        }

        if($efectivo == null || $efectivo == ''){
            $efectivo = $u['efectivo'];
        }

        if($experiencia == null || $experiencia == ''){
            $experiencia = $u['experiencia'];
        }

        $this->guardarUsuario($email, $nick, $efectivo, $experiencia);

    }

    public function obtenerUsuario(){

        $u = [];

        $user = unserialize(session('usuarioData'));

        $u['email'] = $user->getEmail();
        $u['nick'] = $user->getNick();
        $u['efectivo'] = $user->getEfectivo();
        $u['experiencia'] = $user->getExperiencia();

        return $u;


    }

    private function get_cache($name){

        $url = dirname(__FILE__) . '/' . $name;
        print_r($this->getallcontents($url));

    }

    private function getallfiles($dir){

        $array = [];

        if ($handle = opendir($dir)) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    array_push($array, $entry);
                }
            }

            closedir($handle);
        }

        return $array;

    }

    private function getallcontents($dir){

        $array = $this->getallfiles($dir);
        $i = 0;

        foreach($array as $element){
            $t = file_get_contents($dir . '/' . $element);
            $array[$i] = $t;
            $i++;
        }

        return $array;

    }

    private function getlastmodified($url, $name){

        return date("Y-m-d H:i:s", filemtime($url . '/' . $name));

    }

    private function cleartempfile($url, $prefix){

        if(file_exists($url . '/' . $prefix)){
            $hoy = date("Y-m-d H:i:s", strtotime("now"));
            $last = $this->getlastmodified($url, $prefix);

            if((strtotime($hoy) - strtotime($last))>300){
                $file_pointer = $url . '/' . $prefix;
                unlink($file_pointer);
            }
        }

    }

    private function cleartempfiles($url){

        $hoy = date("Y-m-d H:i:s", strtotime("now"));
        $files = $this->getallfiles($url);

        foreach ($files as $file){

            $last = $this->getlastmodified($url, $file);
            if((strtotime($hoy) - strtotime($last))>300){
                $file_pointer = $url . '/' . $file;
                unlink($file_pointer);
            }
        }
    }

    private function addtempfile($url, $prefix){
        if(!file_exists($url . '/' . $prefix)){

            return tempnam($url, $prefix);
        }
    }



}