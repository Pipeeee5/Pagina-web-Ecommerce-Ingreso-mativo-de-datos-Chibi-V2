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
        return true;
    }
    return false;
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
