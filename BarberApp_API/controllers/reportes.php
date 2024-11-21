<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}
require_once '../models/reporteModel.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Analizar la solicitud
switch ($request_method) {
    case 'GET':
        if (!isset($_GET["fechaInicio"]) || $_GET["fechaInicio"] === ""){
            http_response_code(400);
            echo json_encode("La fecha de inicio no es válido");
        }
        elseif (!isset($_GET["fechaFin"]) || $_GET["fechaFin"] === ""){
            http_response_code(400);
            echo json_encode("La fecha de fin no es válido");
        }
        else{
            $fechaInicio = $_GET["fechaInicio"];
            $fechaFin = $_GET["fechaFin"];
            $reporte = get_reporte_ventas($fechaInicio, $fechaFin);
            echo json_encode($reporte);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}