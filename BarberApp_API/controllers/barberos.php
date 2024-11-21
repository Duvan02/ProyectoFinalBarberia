<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

require_once '../models/barberoModel.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Endpoint para buscar disponibilidad de barbero por fecha y hora
$buscarDisponibles = false;
$buscarHorarios = false;
$idFecha = "";
$horaInicio = "";
$horaFin = "";
if (preg_match("/\b" . preg_quote("barberos.php", '/') . "\/" . preg_quote("disponibles", '/') . "\b/", $request_uri, $matches)) {
    $buscarDisponibles = true;
    $fecha = $_GET["fecha"];
    $horaInicio = $_GET["horaInicio"];
    $horaFin = $_GET["horaFin"];
}

elseif (preg_match("/\b" . preg_quote("barberos.php", '/') . "\/" . preg_quote("horarios", '/') . "\b/", $request_uri, $matches)){
    $buscarHorarios = true;
}

// Analizar la solicitud
switch ($request_method) {
    case 'GET':
        if ($buscarDisponibles){
            $disponibles = get_barberos_disponibles($fecha,$horaInicio,$horaFin);
            echo json_encode($disponibles);
        }
        elseif ($buscarHorarios){
            if (!isset($_GET["idBarbero"]) || $_GET["idBarbero"] === 0){
                http_response_code(400);
                echo json_encode("El ID del barbero no es válido");
            }
            else {
                $horarios = get_horarios_barbero($_GET["idBarbero"]);
                echo json_encode($horarios);
            }
        }
        else{
            $nombre = "";
            if(isset($_GET["nombre"])){
                $nombre = $_GET["nombre"];
            }
            $barberos = get_barberos($nombre);
            echo json_encode($barberos);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre = $data['nombre'];
        $horarios = $data['horarios'];
        $imagen = $data['foto'];
        insert_barbero($nombre,$horarios,$imagen);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data["idBarbero"]) || $data["idBarbero"] === 0){
            http_response_code(400);
            echo json_encode("El ID del barbero no es válido");
        }
        else{
            $horarios = $data['horarios'];
            $nombre = $data['nombre'];
            $imagen = $data['foto'];
            $id = $data['idBarbero'];
            update_barbero($nombre,$imagen,$id,$horarios);
            http_response_code(200);
        }
        break;
    case 'DELETE':
        if (isset($_GET['idBarbero'])){
            delete_barbero($_GET['idBarbero']);
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
?>