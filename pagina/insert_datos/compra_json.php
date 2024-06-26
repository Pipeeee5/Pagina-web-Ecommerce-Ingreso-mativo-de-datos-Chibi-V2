<?php
require 'config/config.php';
require 'config/database.php';

class CompraController
{
   private $db;

   public function __construct()
   {
      $this->db = new Database();
   }

   public function create($id_transaccion, $fecha, $status, $email, $id_cliente, $total, $medio_de_pago = null)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total, medio_de_pago) VALUES (:id_transaccion, :fecha, :status, :email, :id_cliente, :total, :medio_de_pago)");
      $sql->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_STR);
      $sql->bindParam(':fecha', $fecha, PDO::PARAM_STR);
      $sql->bindParam(':status', $status, PDO::PARAM_STR);
      $sql->bindParam(':email', $email, PDO::PARAM_STR);
      $sql->bindParam(':id_cliente', $id_cliente, PDO::PARAM_STR);
      $sql->bindParam(':total', $total, PDO::PARAM_STR);
      $sql->bindParam(':medio_de_pago', $medio_de_pago, PDO::PARAM_STR);
      $result = $sql->execute();
      return $result;
   }

   public function getCompras()
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, id_transaccion, fecha, status, email, id_cliente, total, medio_de_pago FROM compra");
      $sql->execute();
      $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
      return $resultado;
   }

   public function buscarCompraPorId($id)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, id_transaccion, fecha, status, email, id_cliente, total, medio_de_pago FROM compra WHERE id = :id");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->execute();
      $compra = $sql->fetch(PDO::FETCH_ASSOC);
      return $compra;
   }
}

// Crear instancia del controlador
$compraController = new CompraController();

// Manejar petición GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   if (isset($_GET['id']) && is_numeric($_GET['id'])) {
      // Obtener detalle de compra por ID
      $id = $_GET['id'];
      $compra = $compraController->buscarCompraPorId($id);

      if ($compra) {
         // Compra encontrada, devolver como JSON
         header('Content-Type: application/json');
         echo json_encode($compra);
      } else {
         // Compra no encontrada, devolver mensaje de error
         http_response_code(404);
         echo json_encode(array('message' => 'Compra no encontrada'));
      }
   } else {
      // Obtener todas las compras
      $compras = $compraController->getCompras();

      // Devolver resultado como JSON
      header('Content-Type: application/json');
      echo json_encode($compras);
   }
}

// Manejar petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Obtener datos del cuerpo de la solicitud
   $data = json_decode(file_get_contents("php://input"), true);

   // Verificar si se recibieron datos válidos
   if ($data && isset($data['id_transaccion']) && isset($data['fecha']) && isset($data['status']) && isset($data['email']) && isset($data['id_cliente']) && isset($data['total'])) {
      // Crear nueva compra
      $id_transaccion = $data['id_transaccion'];
      $fecha = $data['fecha'];
      $status = $data['status'];
      $email = $data['email'];
      $id_cliente = $data['id_cliente'];
      $total = $data['total'];
      $medio_de_pago = isset($data['medio_de_pago']) ? $data['medio_de_pago'] : null;

      $result = $compraController->create($id_transaccion, $fecha, $status, $email, $id_cliente, $total, $medio_de_pago);

      if ($result) {
         // Compra creada exitosamente
         http_response_code(201);
         echo json_encode(array('message' => 'Compra creada correctamente'));
      } else {
         // Error al crear la compra
         http_response_code(500);
         echo json_encode(array('message' => 'Error al crear la compra'));
      }
   } else {
      // Datos no válidos en el cuerpo de la solicitud
      http_response_code(400);
      echo json_encode(array('message' => 'Datos de compra no válidos'));
   }
}

?>
