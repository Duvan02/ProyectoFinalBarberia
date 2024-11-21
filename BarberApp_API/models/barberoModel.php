<?php
require_once('../config/database.php');

function get_barberos($nombre){
    $cn = getConnection();
    $query = "SELECT * FROM estilistas WHERE nombres LIKE '%$nombre%'";
    $datos = $cn->query($query);
    $barberos = [];
    while ($row = $datos->fetch_assoc()) {
        $barberos[] = $row;
    }
    return $barberos;
}

function get_horarios_barbero($id){
    $cn = getConnection();
    $query = "SELECT * FROM horarios_estilistas WHERE idEstilista = '$id'";
    $datos = $cn->query($query);
    $horarios = [];
    while ($row = $datos->fetch_assoc()) {
        $horarios[] = $row;
    }
    return $horarios;
}

function insert_barbero($nombres, $horarios, $imagen) {
    $cn = getConnection();
    $query = "INSERT INTO estilistas(nombres,foto) VALUES('$nombres','$imagen')";
    $cn->query($query);
    $result = $cn->query("SELECT LAST_INSERT_ID() as id");
    $id = $result->fetch_assoc()['id'];
    foreach ($horarios as $horario => $value) {
        $query = "INSERT INTO horarios_estilistas(idEstilista,diaSemana,horaIngreso,horaSalida) VALUES('$id','$value[diaSemana]','$value[horaIngreso]','$value[horaSalida]')";
        $cn->query($query);
    }
}

function update_barbero($nombres, $imagen, $id, $horarios) {
    $cn = getConnection();
    $query = "UPDATE estilistas SET nombres = '$nombres', foto = '$imagen' WHERE idEstilista = '$id'";
    $cn->query($query);
    $query = "DELETE FROM horarios_estilistas WHERE idEstilista = '$id'";
    $cn->query($query);
    foreach ($horarios as $horario => $value) {
        $query = "INSERT INTO horarios_estilistas(idEstilista,diaSemana,horaIngreso,horaSalida) VALUES('$id','$value[diaSemana]','$value[horaIngreso]','$value[horaSalida]')";
        $cn->query($query);
    }
}

function delete_barbero($id) {
    $cn = getConnection();
    $query = "DELETE FROM estilistas WHERE idEstilista = $id";
    $cn->query($query);
}

function get_disponibilidad_barbero($idBarbero, $fecha, $horaInicio, $horaFin){
    $cn = getConnection();
    $query = "SELECT * FROM reservas WHERE idEstilista = $idBarbero AND fechaReserva = '$fecha' AND estado = 'PENDIENTE' OR estado = 'CONFIRMADA'";
    $result = $cn->query($query);
    $datos = [];
    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }
    foreach ($datos as $dato) {
        if (($horaInicio >= $dato["horaInicio"] && $horaInicio < $dato["horaFin"]) || ($horaFin > $dato["horaInicio"] && $horaFin <= $dato["horaFin"])){
            return false;
        }
        return true;
    }
    return true;
}

function get_horario_barbero($idBarbero, $fecha, $horaInicio, $horaFin){
    $cn = getConnection();
    $dia = get_diasemana($fecha);
    $query = "SELECT * FROM horarios_estilistas WHERE idEstilista = $idBarbero AND diaSemana = '$dia' AND horaIngreso <= '$horaInicio' AND horaSalida >= '$horaFin'";
    $datos = $cn->query($query);
    $horario = [];
    while ($row = $datos->fetch_assoc()) {
        $horario = $row;
    }
    return $horario;
}

function get_barberos_disponibles($fecha, $horaInicio, $horaFin){
    $barberos = get_barberos("");
    $barberosDisponibles = [];
    foreach ($barberos as $barbero) {
        $horarioBarbero = get_horario_barbero($barbero["idEstilista"],$fecha,$horaInicio,$horaFin);
        if (count($horarioBarbero) > 0){
            if (get_disponibilidad_barbero($barbero["idEstilista"], $fecha, $horaInicio, $horaFin)){
                array_push($barberosDisponibles, $barbero);
            }
        }
    }
    return $barberosDisponibles;
}

function get_diasemana($fecha){
    $timestamp = strtotime($fecha);
    $diaSemanaIngles = date('l', $timestamp);
    // Traducción de los días de la semana
    $diasSemana = [
        'Monday'    => 'lunes',
        'Tuesday'   => 'martes',
        'Wednesday' => 'miércoles',
        'Thursday'  => 'jueves',
        'Friday'    => 'viernes',
        'Saturday'  => 'sábado',
        'Sunday'    => 'domingo',
    ];
    return $diasSemana[$diaSemanaIngles];
}