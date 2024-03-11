<?php

class conexion{
    const user='root';
    const pass='';
    const db='iotgasmeter';
    const servidor='localhost';

    public function conectardb(){
        $conectar= new mysqli(self::servidor, self::user, self::pass, self::db);
        if($conectar->connect_errno){
            die("Error en la conexion".$conectar->connect_error);
        }
        return $conectar;
    }

}

?>