<?php
require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $rut = trim($_POST['rut']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$nombres, $apellidos, $email, $telefono, $rut, $usuario, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "La dirección de correo no es valida";
    }

    if (!validaPasword($password, $repassword)) {
        $errors[] = "Las contraseñas no coiciden";
    }

    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if (emailExiste($email, $con)) {
        $errors[] = "El correo electronico $email ya existe";
    }

    if (rutExiste($rut, $con)) {
        $errors[] = "El rut ingresado $rut ya existe";
    }

    if (count($errors) == 0) {

        $id = registraCliente([$nombres, $apellidos, $email, $telefono, $rut], $con);
        if ($id > 0) {

            require 'clases/Mailer.php';
            $mailer = new Mailer();
            $token = generarToken();
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);


            $idUsuario = registraUsuario([$usuario, $pass_hash, $token, $id], $con);
            if ($idUsuario > 0) {

                $url = SITE_URL . '/activa_cliente.php?id=' . $idUsuario . '&token=' . $token;

                $asunto = "Activar cuenta - Chibi Mania";
                $cuerpo = "Estimado <b>$nombres</b>: <br> Para continuar con el proceso de registro es indespensable 
                que active su cuenta porfavor de click en la siguiente liga
                 <a href='$url'> Activar cuenta</a>.";


                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "<p><b>Correo enviado:</b></p>";
                    echo "Para terminar el proceso de registro siga las instrucciones que le hemos enviado
                    al correo electronico: <b>$email</b>";
                    echo "<p><br>Si usteded no visualiza el correo, por favor revisar bandeja de spam o en borradores.</p>";
                    echo "<br><br>Pincha este link para volver a la pagina principal: <a href='index.php'>  <b>Página Principal</b></a>";
                    exit;
                }
            } else {
                $errors[] = "Error al registrar usuario";
            }
        } else {
            $errors[] = "Error al registrar cliente";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header>
        <div class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a href="index.php" class="navbar-brand">
                    <strong>ChibiMania</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">Catalogo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contacto</a>
                        </li>
                    </ul>
                    <a href="checkout.php" class="btn btn-primary"><i class="fas fa-shopping-cart"></i>
                        Carrito<span id="num_cart" class="badge bg-secondary">
                            <?php echo $num_cart; ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>


    <main>
        <div class="container">
            <h2>Datos del cliente</h2>

            <?php mostrarMensajes($errors); ?>

            <form class="row g-3" action="registro.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="nombres"><span class="text-danger">*</span> Nombres</label>
                    <input type="text" name="nombres" id="nombres" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="email"><span class="text-danger">*</span> Correo electronico</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    <span id="validaEmail" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="telefono"><span class="text-danger">*</span> Telefono</label>
                    <input type="tel" name="telefono" id="telefono" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="rut"><span class="text-danger">*</span> Rut</label>
                    <input type="text" name="rut" id="rut" class="form-control" required>
                    <span id="validaRut" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="usuario"><span class="text-danger">*</span> usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" required>
                    <span id="validaUsuario" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="password"><span class="text-danger">*</span> Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="repassword"><span class="text-danger">*</span> Confirma contraseña</label>
                    <input type="password" name="repassword" id="repassword" class="form-control" required>
                </div>
                <i><b>Nota:</b> Los campos con asterisco son obligatorios</i>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>

    <!-- script para peticion ajax -->
    <script>
        //usuario
        let txtUsuario = document.getElementById('usuario')
        txtUsuario.addEventListener("blur", function () {
            existeUsuario(txtUsuario.value)
        }, false)

        //email
        let txtEmail = document.getElementById('email')
        txtEmail.addEventListener("blur", function () {
            existeEmail(txtEmail.value)
        }, false)

        //rut
        let txtRut = document.getElementById('rut')
        txtRut.addEventListener("blur", function () {
            existeRut(txtRut.value)
        }, false)

        // funcion usuario
        function existeUsuario(usuario) {
            let url = "clases/cliente_ajax.php"
            let formData = new FormData()
            formData.append("action", "existeUsuario")
            formData.append("usuario", usuario)

            fetch(url, {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('usuario').value = ''
                        document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
                    } else {
                        document.getElementById('validaUsuario').innerHTML = ''
                    }

                })
        }

        // funcion email
        function existeEmail(email) {
            let url = "clases/cliente_ajax.php"
            let formData = new FormData()
            formData.append("action", "existeEmail")
            formData.append("email", email)

            fetch(url, {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('email').value = ''
                        document.getElementById('validaEmail').innerHTML = 'El email ingresado ya esta regristado'
                    } else {
                        document.getElementById('validaEmail').innerHTML = ''
                    }

                })
        }

        // funcion rut
        function existeRut(rut) {
            let url = "clases/cliente_ajax.php"
            let formData = new FormData()
            formData.append("action", "existeRut")
            formData.append("rut", rut)

            fetch(url, {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('rut').value = ''
                        document.getElementById('validaRut').innerHTML = 'El rut ya esta registrado'
                    } else {
                        document.getElementById('validaRut').innerHTML = ''
                    }

                })
        }

    </script>
</body>

</html>