<?php
require_once('../config/database.php');
function get_reporte_ventas($fechaInicio, $fechaFin){
    $cn = getConnection();
    $reporte = array();
    $query = "SELECT SUM(s.precio) AS total FROM reservas r INNER JOIN servicios s ON r.idServicio = s.idServicio 
                     WHERE fechaReserva >= '$fechaInicio' AND fechaReserva <= '$fechaFin' 
                     AND r.estado = 'COMPLETADA'";
    $reporte["totalVentas"] = (double)$cn->query($query)->fetch_assoc()["total"];
    $query = "SELECT COUNT(*) AS cantidadReservas FROM reservas 
                     WHERE fechaReserva >= '$fechaInicio' AND fechaReserva <= '$fechaFin'";
    $reporte['cantidadReservas'] = (int)$cn->query($query)->fetch_assoc()["cantidadReservas"];
    $query = "SELECT COUNT(*) AS cantidadServicios FROM servicios";
    $reporte['cantidadServicios'] = (int)$cn->query($query)->fetch_assoc()["cantidadServicios"];
    $query = "SELECT COUNT(*) AS cantidadUsuarios FROM usuarios";
    $reporte['cantidadUsuarios'] = (int)$cn->query($query)->fetch_assoc()["cantidadUsuarios"];
    $query = "SELECT COUNT(*) AS cantidadReservas FROM reservas 
                     WHERE fechaReserva >= '$fechaInicio' AND fechaReserva <= '$fechaFin' 
                     AND estado = 'COMPLETADA'";
    $reporte['cantidadReservasCompletadas'] = (int)$cn->query($query)->fetch_assoc()['cantidadReservas'];
    $query = "SELECT COUNT(*) AS cantidadReservas FROM reservas 
                     WHERE fechaReserva >= '$fechaInicio' AND fechaReserva <= '$fechaFin' 
                     AND estado = 'CONFIRMADA'";
    $reporte['cantidadReservasConfirmadas'] = (int)$cn->query($query)->fetch_assoc()['cantidadReservas'];
    $query = "SELECT COUNT(*) AS cantidadReservas FROM reservas 
                     WHERE fechaReserva >= '$fechaInicio' AND fechaReserva <= '$fechaFin' 
                     AND estado = 'PENDIENTE'";
    $reporte['cantidadReservasPendientes'] = (int)$cn->query($query)->fetch_assoc()['cantidadReservas'];
    $query = "SELECT COUNT(*) AS cantidadReservas FROM reservas 
                     WHERE fechaReserva >= '$fechaInicio' AND fechaReserva <= '$fechaFin' 
                     AND estado = 'CANCELADA'";
    $reporte['cantidadReservasCanceladas'] = (int)$cn->query($query)->fetch_assoc()['cantidadReservas'];
    return $reporte;
}
