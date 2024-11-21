<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

// Incluir el archivo userModel.php
require_once '../models/userModel.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Analizar la solicitud
switch ($request_method) {
    case 'GET':
        $usuarios = get_usuarios();
        echo json_encode($usuarios);
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre = $data['nombres'];
        $email = $data['email'];
        $password = $data['password'];
        $telefono = $data['telefono'];
        insert_user($nombre,$email,$password,$telefono);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data["idUsuario"]) || $data["idUsuario"] === 0){
            http_response_code(400);
            echo json_encode("El ID del usuario no es válido");
        }
        else{
            $nombre = $data['nombres'];
            $telefono = $data['telefono'];
            $foto = $data['foto'];
            $id = $data['idUsuario'];
            update_user($id,$nombre,$telefono,$foto);
            http_response_code(200);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
