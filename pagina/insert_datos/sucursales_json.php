<?php
require 'config/config.php';
require 'config/database.php';

class SucursalController
{
   private $db;

   public function __construct()
   {
      $this->db = new Database();
   }

   public function create($nombre_sucursal, $ciudad, $comuna, $direccion, $telefono)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("INSERT INTO sucursales (nombre_sucursal, ciudad, comuna, direccion, telefono) VALUES (:nombre_sucursal, :ciudad, :comuna, :direccion, :telefono)");
      $sql->bindParam(':nombre_sucursal', $nombre_sucursal, PDO::PARAM_STR);
      $sql->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
      $sql->bindParam(':comuna', $comuna, PDO::PARAM_STR);
      $sql->bindParam(':direccion', $direccion, PDO::PARAM_STR);
      $sql->bindParam(':telefono', $telefono, PDO::PARAM_INT);
      $result = $sql->execute();
      return $result;
   }

   public function getSucursales()
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, nombre_sucursal, ciudad, comuna, direccion, telefono FROM sucursales");
      $sql->execute();
      $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
      return $resultado;
   }

   public function buscarSucursalPorId($id)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, nombre_sucursal, ciudad, comuna, direccion, telefono FROM sucursales WHERE id = :id");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->execute();
      $sucursal = $sql->fetch(PDO::FETCH_ASSOC);
      return $sucursal;
   }
}

// Crear instancia del controlador
$sucursalController = new SucursalController();

// Manejar petición GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   if (isset($_GET['id']) && is_numeric($_GET['id'])) {
      // Obtener sucursal por ID
      $id = $_GET['id'];
      $sucursal = $sucursalController->buscarSucursalPorId($id);

      if ($sucursal) {
         // Sucursal encontrada, devolver como JSON
         header('Content-Type: application/json');
         echo json_encode($sucursal);
      } else {
         // Sucursal no encontrada, devolver mensaje de error
         http_response_code(404);
         echo json_encode(array('message' => 'Sucursal no encontrada'));
      }
   } else {
      // Obtener todas las sucursales
      $sucursales = $sucursalController->getSucursales();

      // Devolver resultado como JSON
      header('Content-Type: application/json');
      echo json_encode($sucursales);
   }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Obtener datos del cuerpo de la solicitud
   $data = json_decode(file_get_contents("php://input"), true);

   // Verificar si se recibieron datos válidos
   if ($data && isset($data['nombre_sucursal']) && isset($data['ciudad']) && isset($data['comuna']) && isset($data['direccion']) && isset($data['telefono'])) {
      // Crear nueva sucursal
      $nombre_sucursal = $data['nombre_sucursal'];
      $ciudad = $data['ciudad'];
      $comuna = $data['comuna'];
      $direccion = $data['direccion'];
      $telefono = $data['telefono'];

      $result = $sucursalController->create($nombre_sucursal, $ciudad, $comuna, $direccion, $telefono);

      if ($result) {
         // Sucursal creada exitosamente
         http_response_code(201);
         echo json_encode(array('message' => 'Sucursal creada correctamente'));
      } else {
         // Error al crear la sucursal
         http_response_code(500);
         echo json_encode(array('message' => 'Error al crear la sucursal'));
      }
   } else {
      // Datos no válidos en el cuerpo de la solicitud
      http_response_code(400);
      echo json_encode(array('message' => 'Datos de sucursal no válidos'));
   }
}

?>