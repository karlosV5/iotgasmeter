<?php

require_once('conexionNODEMCU.php');

    

    $idDispositivo = $_POST['idDispositivo'];
    $v_leido = $_POST['v_leido'];
    $consumo = round($v_leido*0.6, 2); //recorta el numero de decimales a 2 redondeando
    //$consumo = bcdiv($v_leido*0.6, 1, 2); //recorta el numero de decimales a 2 sin redondear
    //$_POST['consumo'];

    $conn = new conexion();

    $query = "SELECT * FROM consumo_actual WHERE idDispositivo = '$idDispositivo'";
    $select = mysqli_query($conn->conectardb(), $query);

    if($select->num_rows){
        $query = "UPDATE consumo_actual SET v_leido = $v_leido, consumo = $consumo WHERE idDispositivo = '$idDispositivo'";
        $update = mysqli_query($conn->conectardb(), $query);

        $query = "INSERT INTO historial_consumo(idDispositivo, Variable, Valor, Fecha) VALUES('$idDispositivo', 'v_leido', '$v_leido', NOW())";
        $insert = mysqli_query($conn->conectardb(), $query);

        $query = "INSERT INTO historial_consumo(idDispositivo, Variable, Valor, Fecha) VALUES('$idDispositivo', 'consumo', '$consumo', NOW())";
        $insert = mysqli_query($conn->conectardb(), $query);

        $query = "SELECT servo, led FROM consumo_actual WHERE idDispositivo = '$idDispositivo'";
        $result = mysqli_query($conn->conectardb(), $query);
        $row = mysqli_fetch_row($result);
        echo "{SERVO:".$row[0].", LED:".$row[1]."}<br>";

        echo "{DISPOSITIVO:".$idDispositivo.", VLEIDO:".$v_leido.", CONSUMO:".$consumo."}&";
        

    }
    else{
        echo "*** LA TARJETA NO EXISTE *** <br>";

    }
    
    

?>