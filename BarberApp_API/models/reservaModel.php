<?php
require_once('../config/database.php');

function get_reservas($fecha,$idUsuario,$idServicio, $estado){
    $cn = getConnection();
    $query = "SELECT r.idReserva, r.fechaCreacionReserva, r.fechaReserva, r.horaInicio, r.horaFin, r.estado, s.nombre AS servicio, e.nombres as barbero, u.nombres AS usuario 
                FROM reservas r LEFT JOIN servicios s ON r.idServicio = s.idServicio 
                LEFT JOIN estilistas e ON r.idEstilista = e.idEstilista
                INNER JOIN usuarios u ON r.idUsuario = u.idUsuario ";
    if ($fecha != ""){
        $query .= "WHERE r.fechaReserva = '$fecha' ";
    }
    if ($idUsuario != 0){
        if (str_contains($query, 'WHERE')){
            $query .= "AND ";
        }
        else {
            $query .= "WHERE ";
        }
        $query .= "u.idUsuario = '$idUsuario' ";
    }
    if ($idServicio != 0){
        if (str_contains($query, 'WHERE')){
            $query .= "AND ";
        }
        else {
            $query .= "WHERE ";
        }
        $query .= "r.idServicio = '$idServicio' ";
    }
    if ($estado != ""){
        if (str_contains($query, 'WHERE')){
            $query .= "AND ";
        }
        else {
            $query .= "WHERE ";
        }
        $query .= "r.estado = '$estado' ";
    }
    $query .= "ORDER BY r.fechaCreacionReserva DESC";
    $datos = $cn->query($query);
    $reservas = [];
    while ($row = $datos->fetch_assoc()) {
        $fechaCreacion = new DateTime($row['fechaCreacionReserva']);
        $row['fechaCreacionReserva'] = $fechaCreacion->format('Y-m-d\TH:i:s\Z');
        $reservas[] = $row;
    }
    return $reservas;
}

function insert_reserva($fechaCreacion,$idUsuario,$idEstilista,$idServicio,$fechaReserva,$horaInicio,$horaFin) {
    $cn = getConnection();
    $query = "INSERT INTO reservas(fechaCreacionReserva,idUsuario,idEstilista,idServicio,fechaReserva,horaInicio,horaFin,estado) VALUES('$fechaCreacion','$idUsuario','$idEstilista','$idServicio','$fechaReserva','$horaInicio','$horaFin','PENDIENTE')";
    $cn->query($query);
}

function update_reserva($id, $estado) {
    $cn = getConnection();
    $query = "UPDATE reservas SET estado = '$estado' WHERE idReserva = '$id'";
    $cn->query($query);
}

function delete_barbero($id) {
    $cn = getConnection();
    $query = "DELETE FROM estilistas WHERE idEstilista = $id";
    $cn->query($query);
}

function get_disponibilidad_barbero($idBarbero, $fecha, $hora){
    $cn = getConnection();
    $query = "SELECT * FROM reservas WHERE idEstilista = $idBarbero AND fechaReserva = '$fecha'";
    $cn->query($query);
}
?>