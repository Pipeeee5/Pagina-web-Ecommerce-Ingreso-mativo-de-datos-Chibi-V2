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



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <header>
        <div class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a href="nav-link" class="navbar-brand">
                    <strong>ChibiMania</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Catalogo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registro.php">Crear cuenta</a>
                        </li>
                    </ul>
                    <a href="checkout.php" class="btn btn-primary">
                        Carrito<span id="num_cart" class="badge bg-secondary">
                            <?php echo $num_cart; ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/kimetus.png" class="d-block w-100" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="images/dragon.png" class="d-block w-100" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="images/onepiece.jpg" class="d-block w-100" alt="Imagen 3">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <script>
        $(document).ready(function () {
            $('.carousel').carousel({
                interval: 3000, // Cambia la imagen cada 3 segundos (3000 milisegundos)
                pause: 'hover' // Pausa el carrusel cuando el cursor está sobre él
            });
        });
    </script>

    <main>
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <!-- esto toma las filas de la base de datos en esta caso mysql y hace el proceso automatico pero tienes que descargar imagenes y guardarla en el numero del producto
            y llamarla de la misma foma que en la funcion $imagen= principal.png -->
                <?php foreach ($resultado as $row) { ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <?php
                            $id = $row['id'];
                            $imagen = "images/productos/" . $id . "/principal.png";

                            if (!file_exists($imagen)) {
                                $imagen = "images/no-photo.png";
                            }
                            ?>
                            <img src="<?php echo $imagen; ?>">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $row['nombre']; ?>
                                </h5>
                                <p class="card-text">US $<?php echo number_format($row['precio'], 2,',','.');?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- echo hash_hmac esto es una funcion que permite sifrar las claves junto con el TOKEN -->
                                    <div class="btn-group">
                                        <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php
                                           echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>"
                                            class="btn btn-primary">detalles</a>
                                    </div>
                                    <button class="btn btn-outline-success" type="button" onclick="addProducto
                                    (<?php echo $row['id']; ?>, '<?php echo hash_hmac(
                                           'sha1',
                                           $row['id'],
                                           KEY_TOKEN
                                       ); ?>')">Agregar al carrito</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous">
    </script>

    <script>
        function addProducto(id, token) {
            let url = 'clases/carrito.php'
            // se envian parametros a traves de POST
            //una Mayuscula hace la diferencia xd
            let formData = new FormData()
            formData.append('id', id)
            formData.append('token', token)

            //se envian eventos de fetch
            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero
                    }
                })

        }
    </script>

</body>

</html>