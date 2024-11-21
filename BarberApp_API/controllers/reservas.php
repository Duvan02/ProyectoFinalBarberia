<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

require_once '../models/reservaModel.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Analizar la solicitud
switch ($request_method) {
    case 'GET':
        $idUsuario = 0;
        $idServicio = 0;
        $estado = "";
        $fecha = "";
        if (isset($_GET["idUsuario"])) {
            $idUsuario = $_GET["idUsuario"];
        }
        if (isset($_GET["idServicio"])) {
            $idServicio = $_GET["idServicio"];
        }
        if (isset($_GET["estado"])) {
            $estado = $_GET["estado"];
        }
        if (isset($_GET["fecha"])) {
            $fecha = $_GET["fecha"];
        }
        $reservas = get_reservas($fecha, $idUsuario, $idServicio, $estado);
        echo json_encode($reservas);
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $fechaCreacionReserva = $data['fechaCreacionReserva'];
        $idUsuario = $data['idUsuario'];
        $idEstilista = $data['idEstilista'];
        $idServicio = $data['idServicio'];
        $fechaReserva = $data['fechaReserva'];
        $horaInicio = $data['horaInicio'];
        $horaFin = $data['horaFin'];
        insert_reserva($fechaCreacionReserva, $idUsuario, $idEstilista, $idServicio, $fechaReserva, $horaInicio, $horaFin);
        break;
    case 'PUT':
        if (!isset($_GET["idReserva"]) || $_GET["idReserva"] === 0) {
            http_response_code(400);
            echo json_encode("El ID de la reserva no es válida");
        } elseif (!isset($_GET["estado"]) || $_GET["estado"] === "") {
            http_response_code(400);
            echo json_encode("El estado de la reserva no es válida");
        } else {
            $estado = $_GET['estado'];
            $id = $_GET['idReserva'];
            update_reserva($id, $estado);
            http_response_code(200);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}