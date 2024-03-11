<?php
// Declaramos el fichero de conexión
include_once("config.php");

  //Variables enviadas por el formulario
  $monto = $_POST['monto'];
  $venta_fecha = $_POST['venta_fecha'];

  // Datos de estado de cuenta preparados
  $query = $db->prepare("INSERT INTO `tbl_ventas` (`monto`, `venta_fecha`)
  VALUES (:monto, :venta_fecha)");
  $query->bindParam(":monto", $monto);
  $query->bindParam(":venta_fecha", $venta_fecha);
  // Ejecuta SQL
  $query->execute();
  
  // redireccion a URL
  header("Location: index.php");
  /*echo("<script>location.href = index.php';</script>");*/
?>