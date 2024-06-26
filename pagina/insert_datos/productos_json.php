<?php
require 'config/config.php';
require 'config/database.php';

class ProductoController
{
   private $db;

   public function __construct()
   {
      $this->db = new Database();
   }

   public function create($id, $nombre, $descripcion, $precio, $descuento, $id_categoria, $activo)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("INSERT INTO productos (id, nombre, descripcion, precio, descuento, id_categoria, activo) VALUES (:id, :nombre, :descripcion, :precio, :descuento, :id_categoria, :activo)");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
      $sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
      $sql->bindParam(':precio', $precio, PDO::PARAM_STR);
      $sql->bindParam(':descuento', $descuento, PDO::PARAM_INT);
      $sql->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
      $sql->bindParam(':activo', $activo, PDO::PARAM_INT);
      $result = $sql->execute();
      return $result;
   }

   public function getProductos()
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, nombre, descripcion, precio, descuento, id_categoria, activo FROM productos");
      $sql->execute();
      $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
      return $resultado;
   }

   public function buscarProductoPorId($id)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, nombre, descripcion, precio, descuento, id_categoria, activo FROM productos WHERE id = :id");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->execute();
      $producto = $sql->fetch(PDO::FETCH_ASSOC);
      return $producto;
   }
}

// Crear instancia del controlador
$productoController = new ProductoController();

// Manejar petición GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   if (isset($_GET['id']) && is_numeric($_GET['id'])) {
      // Obtener detalle de producto por ID
      $id = $_GET['id'];
      $producto = $productoController->buscarProductoPorId($id);

      if ($producto) {
         // Producto encontrado, devolver como JSON
         header('Content-Type: application/json');
         echo json_encode($producto);
      } else {
         // Producto no encontrado, devolver mensaje de error
         http_response_code(404);
         echo json_encode(array('message' => 'Producto no encontrado'));
      }
   } else {
      // Obtener todos los productos
      $productos = $productoController->getProductos();

      // Devolver resultado como JSON
      header('Content-Type: application/json');
      echo json_encode($productos);
   }
}

// Manejar petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Obtener datos del cuerpo de la solicitud
   $data = json_decode(file_get_contents("php://input"), true);

   // Verificar si se recibieron datos válidos
   if ($data && isset($data['id']) && isset($data['nombre']) && isset($data['descripcion']) && isset($data['precio']) && isset($data['descuento']) && isset($data['id_categoria']) && isset($data['activo'])) {
      // Crear nuevo producto
      $id = $data['id'];
      $nombre = $data['nombre'];
      $descripcion = $data['descripcion'];
      $precio = $data['precio'];
      $descuento = $data['descuento'];
      $id_categoria = $data['id_categoria'];
      $activo = $data['activo'];

      $result = $productoController->create($id, $nombre, $descripcion, $precio, $descuento, $id_categoria, $activo);

      if ($result) {
         // Producto creado exitosamente
         http_response_code(201);
         echo json_encode(array('message' => 'Producto creado correctamente'));
      } else {
         // Error al crear el producto
         http_response_code(500);
         echo json_encode(array('message' => 'Error al crear el producto'));
      }
   } else {
      // Datos no válidos en el cuerpo de la solicitud
      http_response_code(400);
      echo json_encode(array('message' => 'Datos de producto no válidos'));
   }
}

?>
