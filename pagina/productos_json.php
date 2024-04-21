<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);



//session_destroy();

//print_r($_SESSION);
?>

<?php 
   header('Content-type: application/json');
   header('Content-harrys: application/json');
//    var_dump($resultado);
//    echo '[';
//    $comma = "";
//    foreach ($resultado as $row) {
//     echo $comma . '{';
//     echo '"id": "' . $row["id"] . '",';
//     echo '"nombre": "' . $row["nombre"] . '",';
//     echo '"precio": "' . $row["precio"] . '"';
//     echo '}';
//     $comma = ",";
    
//  } 
//  echo ']';

 echo json_encode( $resultado );
 
 ?>



      


