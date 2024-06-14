<?php

// validaciones en backend
function esNulo(array $parametos)
{
    foreach ($parametos as $parameto) {
        if (strlen(trim($parameto)) < 1) {
            return true;
        }
    }
    return false;
}

function esEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function validaPasword($password, $repassword)
{
    if (strcmp($password, $repassword) === 0) {
        return true;
    }
    return false;

}

function validaRut($rut) {
    // Formato: debe tener 7 u 8 dígitos, guion y un dígito verificador (0-9 o k)
    if (!preg_match('/^\d{7,8}-[0-9kK]{1}$/', $rut)) {
        return false;
    }

    // Separar el número del dígito verificador
    list($numero, $verificador) = explode('-', $rut);
    $verificador = strtolower($verificador); // Convertir a minúscula si es 'K'

    // Calcular el dígito verificador
    $suma = 0;
    $factor = 2;

    for ($i = strlen($numero) - 1; $i >= 0; $i--) {
        $suma += $numero[$i] * $factor;
        $factor = ($factor == 7) ? 2 : $factor + 1;
    }

    $resto = $suma % 11;
    $digitoCalculado = 11 - $resto;

    if ($digitoCalculado == 11) {
        $digitoCalculado = '0';
    } elseif ($digitoCalculado == 10) {
        $digitoCalculado = 'k';
    } else {
        $digitoCalculado = (string)$digitoCalculado;
    }

    // Verificar si el dígito calculado es igual al dígito verificador
    return $digitoCalculado === $verificador;
}

//---------------------------------------------------------------------------------------------------------------------
function generarToken()
{
    return md5(uniqid(mt_rand(), false));
    // genera un id pero de forma aleatoria si dos o mas usuarios se reguistran al mismo tiempo
}

function registraCliente(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO clientes (nombres, apellidos, email, telefono, rut, estatus, fecha_alta) 
    VALUES(?,?,?,?,?,1,now())");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}

function registraUsuario(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO usuarios (usuario, password, token, id_cliente) 
    VALUES(?,?,?,?)");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}

function usuarioExiste($usuario, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function emailExiste($email, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function rutExiste($rut, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE rut LIKE ? LIMIT 1");
    $sql->execute([$rut]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function mostrarMensajes(array $errors){
    if(count($errors) > 0){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach($errors as $error){
            echo  '<li>' . $error . '</li>';
        }
        echo '<ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }

}

function ValidaToken($id, $token, $con)
{   
    $msg = "";
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token LIKE ? LIMIT 1");
    $sql->execute([$id, $token]);
    if ($sql->fetchColumn() > 0) {
        if(activarUsuario($id, $con)){
            $msg ="Cuenta activada.";
        } else {
            $msg = "Error al activar cuenta.";
        }
    } else {
        $msg = "No existe el registro del cliente.";
    }
    return $msg;
}

function activarUsuario($id, $con){
    $sql = $con->prepare("UPDATE usuarios SET activacion = 1, token = '' WHERE id = ?");
    return $sql->execute([$id]);

}

function login($usuario, $password, $con, $proceso){
    $sql = $con->prepare("SELECT id, usuario, password, id_cliente FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if($row = $sql->fetch(PDO::FETCH_ASSOC)){
        if(esActivaCuenta($usuario, $con)){
            if(password_verify($password, $row['password'])){
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['usuario'];
                $_SESSION['user_cliente'] = $row['id_cliente'];
                if ($proceso == 'pago'){
                header("Location: checkout.php");
                }else{
                header("Location: index.php");
                }
                exit;
            }
        } else {
            return 'El usuario no ha activado la cuenta por correo.';
        }
    }
    return 'El usuario y/o contraseña son incorrectos.';
}

function esActivaCuenta($usuario, $con){
    $sql = $con->prepare("SELECT activacion FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row['activacion'] == 1){
        return true;
    //     cuenta activa por correo, (1)
    }
    return false;
    //     cuenta no activada por corre(0)
}

function solicitaPassword($user_id, $con){
    $token = generarToken();

    $sql = $con->prepare("UPDATE usuarios SET token_password=?, password_request=1 WHERE id = ?");
    if($sql->execute([$token, $user_id])){
        return $token;
    }
    return null;
}

function verificaTokenRequest($user_id, $token, $con){
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token_password 
    LIKE ? AND password_request=1 LIMIT 1");
    $sql->execute([$user_id, $token]);
    if($sql->fetchColumn() > 0){
        return true;
    }
    return false;
}

function actualizaPassword($user_id, $password, $con){
    $sql = $con->prepare("UPDATE usuarios SET password=?, token_password = '', password_request
    = 0 where id = ?");
    if($sql->execute([$password, $user_id])){
        return true;
    }
    return false;
}


