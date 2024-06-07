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

   public function create($id, $nombre, $precio)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("INSERT INTO productos (id, nombre, precio, activo) VALUES (:id, :nombre, :precio, 1)");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
      $sql->bindParam(':precio', $precio, PDO::PARAM_INT);
      $result = $sql->execute();
      return $result;
   }


   public function getProductos()
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
      $sql->execute();
      $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
      return $resultado;
   }

   public function buscarProductoPorId($id)
   {
      $con = $this->db->conectar();
      $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id = :id");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->execute();
      $producto = $sql->fetch(PDO::FETCH_ASSOC);
      return $producto;
   }

   //---------------------------------------------------------------------------------------------------------------------------
   // metodo editar, eliminar no usar hasta nuevo aviso

   // public function update($id, $nombre, $precio)
   // {
   //    $con = $this->db->conectar();
   //    $sql = $con->prepare("UPDATE productos SET nombre = :nombre, precio = :precio WHERE id = :id");
   //    $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
   //    $sql->bindParam(':precio', $precio, PDO::PARAM_INT);
   //    $sql->bindParam(':id', $id, PDO::PARAM_INT);
   //    $result = $sql->execute();
   //    return $result;
   // }

   // public function delete($id)
   // {
   //    $con = $this->db->conectar();
   //    $sql = $con->prepare("DELETE FROM productos WHERE id = :id");
   //    $sql->bindParam(':id', $id, PDO::PARAM_INT);
   //    $result = $sql->execute();
   //    // Verificar si se eliminó alguna fila (producto)
   //    if ($result && $sql->rowCount() > 0) {
   //       return true; // Producto eliminado correctamente
   //    } else {
   //       return false; // Producto no encontrado o no eliminado
   //    }
   // }
   //---------------------------------------------------------------------------------------------------------------------------


}

// Crear instancia del controlador
$productoController = new ProductoController();

// Manejar petición GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   if (isset($_GET['id']) && is_numeric($_GET['id'])) {
       // Obtener producto por ID
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


// Manejar petición POST a través de la URL
// Manejar petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Obtener datos del cuerpo de la solicitud
   $data = json_decode(file_get_contents("php://input"), true);

   // Verificar si se recibieron datos válidos
   if ($data && isset($data['id']) && isset($data['nombre']) && isset($data['precio'])) {
      // Crear nuevo producto
      $id = $data['id'];
      $nombre = $data['nombre'];
      $precio = $data['precio'];

      $result = $productoController->create($id, $nombre, $precio);

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


// Manejar petición PUT
// if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
//    // Verificar si se proporcionó un ID válido en la URL
//    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
//       // Obtener datos del cuerpo de la solicitud
//       $data = json_decode(file_get_contents("php://input"), true);

//       // Verificar si se recibieron datos válidos
//       if ($data && isset($data['nombre']) && isset($data['precio'])) {
//          // Actualizar el producto
//          $id = $_GET['id'];
//          $nombre = $data['nombre'];
//          $precio = $data['precio'];

//          // Buscar producto por ID
//          $producto = $productoController->buscarProductoPorId($id);

//          if ($producto) {
//             // Producto encontrado, actualizar
//             $result = $productoController->update($id, $nombre, $precio);

//             if ($result) {
//                // Producto actualizado exitosamente
//                http_response_code(200);
//                echo json_encode(array('message' => 'Producto actualizado correctamente'));
//             } else {
//                // Error al actualizar el producto
//                http_response_code(500);
//                echo json_encode(array('message' => 'Error al actualizar el producto'));
//             }
//          } else {
//             // Producto no encontrado, devolver mensaje de error
//             http_response_code(404);
//             echo json_encode(array('message' => 'Producto no encontrado'));
//          }
//       } else {
//          // Datos no válidos en el cuerpo de la solicitud
//          http_response_code(400);
//          echo json_encode(array('message' => 'Datos de producto no válidos'));
//       }
//    } else {
//       // ID no proporcionado o no válido, devolver mensaje de error
//       http_response_code(400);
//       echo json_encode(array('message' => 'ID de producto no válido'));
//    }
// }

// // Manejar petición DELETE
// if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
//    // Verificar si se proporcionó un ID válido en la URL
//    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
//       // Obtener el ID del producto
//       $id = $_GET['id'];

//       // Buscar el producto por ID antes de eliminarlo
//       $producto = $productoController->buscarProductoPorId($id);

//       if ($producto) {
//          // El producto existe, intentar eliminarlo
//          $result = $productoController->delete($id);

//          if ($result) {
//             // Producto eliminado exitosamente
//             http_response_code(200);
//             echo json_encode(array('message' => 'Producto eliminado correctamente'));
//          } else {
//             // Error al eliminar el producto
//             http_response_code(500);
//             echo json_encode(array('message' => 'Error al eliminar el producto'));
//          }
//       } else {
//          // Producto no encontrado, devolver mensaje de error
//          http_response_code(404);
//          echo json_encode(array('message' => 'Producto no encontrado'));
//       }
//    } else {
//       // ID no proporcionado o no válido, devolver mensaje de error
//       http_response_code(400);
//       echo json_encode(array('message' => 'ID de producto no válido'));
//    }
// }



?>