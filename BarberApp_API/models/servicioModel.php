<?php
require_once('../config/database.php');

function get_servicios($nombre){
    $cn = getConnection();
    $query = "SELECT * FROM servicios WHERE nombre LIKE '%$nombre%'";
    $datos = $cn->query($query);
    $servicios = [];
    while ($row = $datos->fetch_assoc()) {
        $servicios[] = $row;
    }
    return $servicios;
}

function insert_servicio($nombre, $descripcion, $tiempo, $precio, $imagen) {
    $cn = getConnection();
    $query = "INSERT INTO servicios(nombre,descripcion,tiempoDuracion,precio,imagen) VALUES('$nombre','$descripcion','$tiempo','$precio','$imagen')";
    $cn->query($query);
}

function update_servicio($nombre, $descripcion, $tiempo, $precio, $imagen, $idServicio) {
    $cn = getConnection();
    $query = "UPDATE servicios SET nombre = '$nombre', descripcion = '$descripcion', tiempoDuracion = '$tiempo', precio = '$precio', imagen = '$imagen' WHERE idServicio = '$idServicio'";
    $cn->query($query);
}

function delete_servicio($idServicio) {
    $cn = getConnection();
    $query = "DELETE FROM servicios WHERE idServicio = $idServicio";
    $cn->query($query);
}
?>