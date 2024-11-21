<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

// Inclusion el archivo userModel.php
require_once '../models/userModel.php';
require_once '../models/administradorModel.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Analizar la solicitud
switch ($request_method) {
    case 'POST':
        if (!isset($_GET["tipo"])){
            http_response_code(400);
            echo json_encode(['error' => 'Type is required']);
        }
        else{
            $tipo = $_GET["tipo"];
            $data = json_decode(file_get_contents('php://input'), true);
            $usuario = $data['email'];
            $password = $data['password'];
            if ($tipo == "usuario"){
                $response = login_user($usuario,$password);
            }
            else{
                $response = login_administrador($usuario,$password);
            }
            if ($response == null){
                http_response_code(400);
                echo json_encode(['error' => 'Usuario y/o contraseña inválidos']);
            }else{
                http_response_code(200);
                echo json_encode($response);
            }
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}