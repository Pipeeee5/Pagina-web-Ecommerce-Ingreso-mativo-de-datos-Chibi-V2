<?php
require 'config/config.php';
require 'config/database.php';

class DetalleCompraController
{
   private $db;

   public function __construct()
   {
      $this->db = new Database();
   }

   public function create($id_compra, $id_producto, $nombre, $precio, $cantidad)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (:id_compra, :id_producto, :nombre, :precio, :cantidad)");
      $sql->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
      $sql->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
      $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
      $sql->bindParam(':precio', $precio, PDO::PARAM_STR);
      $sql->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
      $result = $sql->execute();
      return $result;
   }

   public function getDetallesCompra()
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, id_compra, id_producto, nombre, precio, cantidad FROM detalle_compra");
      $sql->execute();
      $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
      return $resultado;
   }

   public function buscarDetallePorId($id)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, id_compra, id_producto, nombre, precio, cantidad FROM detalle_compra WHERE id = :id");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->execute();
      $detalle = $sql->fetch(PDO::FETCH_ASSOC);
      return $detalle;
   }
}

// Crear instancia del controlador
$detalleCompraController = new DetalleCompraController();

// Manejar petición GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   if (isset($_GET['id']) && is_numeric($_GET['id'])) {
      // Obtener detalle de compra por ID
      $id = $_GET['id'];
      $detalle = $detalleCompraController->buscarDetallePorId($id);

      if ($detalle) {
         // Detalle encontrado, devolver como JSON
         header('Content-Type: application/json');
         echo json_encode($detalle);
      } else {
         // Detalle no encontrado, devolver mensaje de error
         http_response_code(404);
         echo json_encode(array('message' => 'Detalle de compra no encontrado'));
      }
   } else {
      // Obtener todos los detalles de compra
      $detalles = $detalleCompraController->getDetallesCompra();

      // Devolver resultado como JSON
      header('Content-Type: application/json');
      echo json_encode($detalles);
   }
}

// Manejar petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);
 
    // Verificar si se recibieron datos válidos
    if ($data && isset($data['id_compra']) && isset($data['id_producto']) && isset($data['nombre']) && isset($data['precio']) && isset($data['cantidad'])) {
       // Crear nuevo detalle de compra
       $id_compra = $data['id_compra'];
       $id_producto = $data['id_producto'];
       $nombre = $data['nombre'];
       $precio = $data['precio'];
       $cantidad = $data['cantidad'];
 
       $result = $detalleCompraController->create($id_compra, $id_producto, $nombre, $precio, $cantidad);
 
       if ($result) {
          // Detalle de compra creado exitosamente
          http_response_code(201);
          echo json_encode(array('message' => 'Detalle de compra creado correctamente'));
       } else {
          // Error al crear el detalle de compra
          http_response_code(500);
          echo json_encode(array('message' => 'Error al crear el detalle de compra'));
       }
    } else {
       // Datos no válidos en el cuerpo de la solicitud
       http_response_code(400);
       echo json_encode(array('message' => 'Datos de detalle de compra no válidos'));
    }
}

?>
