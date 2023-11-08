<?php 

$host = "localhost";
$db = "sitio_gatitos";
$user = "root";
$password = "";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db", $user, $password);
} catch (Exception $ex) {
    echo "Error de conexion " . $ex->getMessage();
}

?>