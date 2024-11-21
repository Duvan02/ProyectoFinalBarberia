<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

require_once '../models/administradorModel.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Analizar la solicitud
switch ($request_method) {
    case 'GET':
        $administradores = get_administradores("");
        echo json_encode($administradores);
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre = $data['nombre'];
        $email = $data['email'];
        $password = $data['password'];
        insert_administrador($nombre,$email,$password);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data["idAdministrador"]) || $data["idAdministrador"] === 0){
            http_response_code(400);
            echo json_encode("El ID del administrador no es válido");
        }
        else{
            $nombre = $data['nombre'];
            $email = $data['email'];
            $password = $data['password'];
            $id = $data['idAdministrador'];
            update_administrador($nombre,$email,$password,$id);
            http_response_code(200);
        }
        break;
    case 'DELETE':
        if (isset($_GET['idAdministrador'])){
            delete_administrador($_GET['idAdministrador']);
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