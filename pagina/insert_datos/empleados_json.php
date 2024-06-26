<?php
require 'config/config.php';
require 'config/database.php';

class EmpleadoController
{
   private $db;

   public function __construct()
   {
      $this->db = new Database();
   }

   public function create($nombre, $apellido, $email, $telefono, $fecha_nacimiento, $fecha_contratacion, $puesto, $salario)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("INSERT INTO empleados (nombre, apellido, email, telefono, fecha_nacimiento, fecha_contratacion, puesto, salario) VALUES (:nombre, :apellido, :email, :telefono, :fecha_nacimiento, :fecha_contratacion, :puesto, :salario)");
      $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
      $sql->bindParam(':apellido', $apellido, PDO::PARAM_STR);
      $sql->bindParam(':email', $email, PDO::PARAM_STR);
      $sql->bindParam(':telefono', $telefono, PDO::PARAM_STR);
      $sql->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
      $sql->bindParam(':fecha_contratacion', $fecha_contratacion, PDO::PARAM_STR);
      $sql->bindParam(':puesto', $puesto, PDO::PARAM_STR);
      $sql->bindParam(':salario', $salario, PDO::PARAM_STR);
      $result = $sql->execute();
      return $result;
   }

   public function getEmpleados()
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, nombre, apellido, email, telefono, fecha_nacimiento, fecha_contratacion, puesto, salario FROM empleados");
      $sql->execute();
      $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
      return $resultado;
   }

   public function buscarEmpleadoPorId($id)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, nombre, apellido, email, telefono, fecha_nacimiento, fecha_contratacion, puesto, salario FROM empleados WHERE id = :id");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->execute();
      $empleado = $sql->fetch(PDO::FETCH_ASSOC);
      return $empleado;
   }
}

// Crear instancia del controlador
$empleadoController = new EmpleadoController();

// Manejar petición GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   if (isset($_GET['id']) && is_numeric($_GET['id'])) {
       // Obtener empleado por ID
       $id = $_GET['id'];
       $empleado = $empleadoController->buscarEmpleadoPorId($id);

       if ($empleado) {
           // Empleado encontrado, devolver como JSON
           header('Content-Type: application/json');
           echo json_encode($empleado);
       } else {
           // Empleado no encontrado, devolver mensaje de error
           http_response_code(404);
           echo json_encode(array('message' => 'Empleado no encontrado'));
       }
   } else {
       // Obtener todos los empleados
       $empleados = $empleadoController->getEmpleados();

       // Devolver resultado como JSON
       header('Content-Type: application/json');
       echo json_encode($empleados);
   }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);
 
    // Verificar si se recibieron datos válidos
    if ($data && isset($data['nombre']) && isset($data['apellido']) && isset($data['email']) && isset($data['telefono']) && isset($data['fecha_nacimiento']) && isset($data['fecha_contratacion']) && isset($data['puesto']) && isset($data['salario'])) {
       // Crear nuevo empleado
       $nombre = $data['nombre'];
       $apellido = $data['apellido'];
       $email = $data['email'];
       $telefono = $data['telefono'];
       $fecha_nacimiento = $data['fecha_nacimiento'];
       $fecha_contratacion = $data['fecha_contratacion'];
       $puesto = $data['puesto'];
       $salario = $data['salario'];
 
       $result = $empleadoController->create($nombre, $apellido, $email, $telefono, $fecha_nacimiento, $fecha_contratacion, $puesto, $salario);
 
       if ($result) {
          // Empleado creado exitosamente
          http_response_code(201);
          echo json_encode(array('message' => 'Empleado creado correctamente'));
       } else {
          // Error al crear el empleado
          http_response_code(500);
          echo json_encode(array('message' => 'Error al crear el empleado'));
       }
    } else {
       // Datos no válidos en el cuerpo de la solicitud
       http_response_code(400);
       echo json_encode(array('message' => 'Datos de empleado no válidos'));
    }
 }
 
?>
