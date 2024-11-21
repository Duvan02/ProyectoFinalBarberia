<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

require_once '../models/servicioModel.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Analizar la solicitud
switch ($request_method) {
    case 'GET':
        $descripcion = "";
        if(isset($_GET["descripcion"])){
            $descripcion = $_GET["descripcion"];
        }
        $servicios = get_servicios($descripcion);
        echo json_encode($servicios);
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $tiempo = $data['tiempoDuracion'];
        $precio = $data['precio'];
        $imagen = $data['imagen'];
        insert_servicio($nombre,$descripcion,$tiempo,$precio,$imagen);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data["idServicio"]) || $data["idServicio"] === 0){
            http_response_code(400);
            echo json_encode("El ID del servicio no es válido");
        }
        else{
            $nombre = $data['nombre'];
            $descripcion = $data['descripcion'];
            $tiempo = $data['tiempoDuracion'];
            $precio = $data['precio'];
            $imagen = $data['imagen'];
            $id = $data['idServicio'];
            update_servicio($nombre,$descripcion,$tiempo,$precio,$imagen,$id);
            http_response_code(200);
        }
        break;
    case 'DELETE':
        if (isset($_GET['idServicio'])){
            delete_servicio($_GET['idServicio']);
            http_response_code(200);
        }
        else {
            http_response_code(400);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}