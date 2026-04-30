<?php
require __DIR__ . "/../app/vendor/autoload.php";

use App\Models\LibroModel;

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Methods: GET,POST,PATCH,DELETE");
header("Access-Control-Max-Age: 3400");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$db= new LibroModel();


$headers = getallheaders();
$keyRecibida = $headers["x-api-key"] ?? "";

//$rol = obtener_rol($keyRecibida);

//if (!$rol) {
 //   http_response_code(401);
 //   echo json_encode(["error" => "API key no válida"]);
// exit;
//}

$path = trim($uri, "/");
$partes = explode("/", $path);

$index = array_search("api", $partes);

if (!$index || ($partes[$index + 1] ?? '') !== "libros") {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint no existe"]);
    exit;
}

$titulo = $partes[$index + 2] ?? null;
$titulo = $titulo ? urldecode($titulo) : null;

switch ($requestMethod) {

    case 'GET':

        $busqueda = $_GET['busqueda'] ?? '';
        $orden = $_GET['orden'] ?? '';
        $disponibilidad = $_GET['disponibilidad'] ?? '';

        if ($titulo) {
            $libros = $db->obtenerLibroPorId($titulo);
        } elseif (!empty($busqueda)) {
            $libros = $db->buscarLibros($busqueda, $orden, $disponibilidad);
        } else {
            $libros = $db->obtener_listado_Libros($orden, $disponibilidad);
        }

        echo json_encode([
            "status" => "ok",
            "total" => is_array($libros) ? count($libros) : 1,
            "data" => $libros
        ]);
        break;

    case 'POST':

        //if ($rol !== 'ADMIN') {
        //    http_response_code(403);
        //    echo json_encode(["error" => "No tienes permiso"]);
        //    exit;
        //}

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['titulo'])) {
            http_response_code(400);
            echo json_encode(["error" => "Datos inválidos"]);
            exit;
        }

        $id = $db->crearLibro(
            $data["titulo"] ?? null,
            $data["autor"] ?? null,
            $data["genero"] ?? null,
            $data["anio"] ?? null,
            $data["id_usuario"] ?? null,
            $data["caratula"] ?? null,
            );

        http_response_code(201);
        echo json_encode([
            "status" => "created",
            "id" => $id
        ]);
        break;

    case 'PATCH':

        //if ($rol !== 'ADMIN') {
        //    http_response_code(403);
        //    echo json_encode(["error" => "No tienes permiso"]);
        //    exit;
        //}

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "ID requerido"]);
            exit;
        }

        if (!empty($data['caratula'])) {

            $db->actualizarLibroConImagen(
                $data['id'],
                $data['titulo'],
                $data['autor'],
                $data['genero'],
                $data['anio'],
                $data['caratula']
            );

        } else {

            $db->actualizarLibro(
                $data['id'],
                $data['titulo'],
                $data['autor'],
                $data['genero'],
                $data['anio']
            );
        }
        echo json_encode([
            "status" => "updated"
        ]);
        break;

    case 'DELETE':

       // if ($rol !== 'ADMIN') {
         // http_response_code(403);
           // echo json_encode(["error" => "No tienes permiso"]);
            //exit;
        //}

        if (!$titulo) {
            http_response_code(400);
            echo json_encode(["error" => "ID requerido"]);
            exit;
        }

        $db->eliminarLibro($titulo);

        echo json_encode([
            "status" => "deleted"
        ]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
}