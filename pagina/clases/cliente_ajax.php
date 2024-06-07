<?php

require_once '../config/database.php';
require_once 'clienteFunciones.php';

$datos = [];

// se traen los datos ingresados por el usuario a traves del metodo POST conectandose a la db
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    $db = new Database();
    $con = $db->conectar();

    if ($action == 'existeUsuario') {
        $datos['ok'] = usuarioExiste($_POST['usuario'], $con);
    } elseif ($action == 'existeEmail') {
        $datos['ok'] = emailExiste($_POST['email'], $con);
    } elseif ($action =='existeRut') {
        $datos['ok'] = rutExiste($_POST['rut'], $con);
    }
}

echo json_encode($datos);