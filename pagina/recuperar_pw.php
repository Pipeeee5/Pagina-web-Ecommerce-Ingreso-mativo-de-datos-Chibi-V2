<?php
require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

//datos a solicitar en pagina, en este caso como email.
if (!empty($_POST)) {

    $email = trim($_POST['email']);

    if (esNulo([$email])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "La dirección de correo no es valida";
    }

    //con esta consulta se trae la informacion del cliente y usuario que se esta solicitando para cambiar su contraseña.
// se selecciona el ID del usuario y los nombres del cliente asociado, donde apartir del correo electrónico 
// nos pueda traer el ID del usuario.
// Utilizamos (INNER JOIN) entre las tablas usuarios y clientes para llegar al correo del usuario.

    if (count($errors) == 0) {
        if (emailExiste($email, $con)) {
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios 
            INNER JOIN clientes ON usuarios.id_cliente=clientes.id
            WHERE clientes.email LIKE ? LIMIT 1");

            $sql->execute([$email]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];
            $nombres = $row['nombres'];

            $token = solicitaPassword($user_id, $con);

            //se crea correo
            if ($token !== null) {
                require 'clases/Mailer.php';
                $mailer = new Mailer();

                $url = SITE_URL . '/reset_password.php?id=' . $user_id . '&token=' . $token;

                $asunto = "Recuperar password - Chibi Mania";
                $cuerpo = "Estimado <b>$nombres</b>: <br> Si has solicitado el cambio de tu contraseña da click en el siguiente link.
                 <a href='$url'>$url</a>";
                $cuerpo .= "<br><br>Si no hiciste esta solicitud puedes ignorar este correo.";

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "<p><b>Correo enviado:</b></p>";
                    echo "<p>Hemos enviado un correo electronico a la direccion <b>$email</b> para restablecer la contraseña.</p>";
                    echo "<p><br>Si usteded no visualiza el correo, por favor revisar bandeja de spam o en borradores.</p>";
                    echo "<br><br>Pincha este link para volver a la pagina principal: <a href='index.php'>  <b>Página Principal</b></a>";
                    exit;
                }
            }
        } else {
            $errors[] = "No existe una cuenta asociada a esta direccióm de Correo Electronico.";
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


    <main class="form-login m-auto pt-4">
        <h3>Recuperar contraseña</h3>

        <?php mostrarMensajes($errors); ?>

        <form action="recuperar_pw.php" method="post" class="row g-3" autocomplete="off">
            <div class="form-floating">
                <input class="form-control" type="email" name="email" id="email" placeholder="Correo Electronico"
                    required>
                <label for="email">Correo Electronico</label>
            </div>

            <div class="d-grid gap-3 col-12">
                <button typpe="submit" class="btn btn-primary">Continuar</button>
            </div>

            <div class="col-12">
                ¿No tienes una cuenta? <a href="registro.php">Registrate aquí</a>
            </div>
        </form>

    </main>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>


</body>

</html>