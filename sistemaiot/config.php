<?php
$host = "localhost";
$dbname = "prueba";
$username = "root";
$password = "";

// Conexión PDO	
try {
	// Cree un nuevo objeto PDO y guárdelo en la variable $ db
	$db = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
	// Configure el modo de error en PDO para mostrar inmediatamente las excepciones cuando haya errores
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $exception){
	die("Connection error: " . $exception->getMessage());
}
error_reporting(0);
?>