<?php
// Habilita los encabezados CORS y el formato JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Método de solicitud HTTP (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Rutas API
if ($method == 'GET') {
    echo json_encode(["message" => "Resource not found"]);
} else {
   echo json_encode(["message" => "Method not allowed"]);
}
