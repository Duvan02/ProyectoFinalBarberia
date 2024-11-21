<?php
require_once('../config/database.php');

function login_administrador($email, $password){
    $cn = getConnection();
    $query = "SELECT * FROM administradores WHERE email = '$email' AND password = '$password'";
    $datos = $cn->query($query);
    if ($datos->num_rows > 0) {
        $result = $datos->fetch_assoc();
        return $result;
    }
    return null;
}

function get_administradores($nombre){
    $cn = getConnection();
    $query = "SELECT * FROM administradores WHERE nombre LIKE '%$nombre%'";
    $datos = $cn->query($query);
    $users = [];
    while ($row = $datos->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

function insert_administrador($nombre, $email, $password) {
    $cn = getConnection();
    $query = "INSERT INTO administradores(nombre,email,password) VALUES('$nombre','$email','$password')";
    $cn->query($query);
}

function update_administrador($nombre, $email, $password, $id) {
    $cn = getConnection();
    $query = "UPDATE administradores SET nombre = '$nombre', email = '$email', password = '$password' WHERE idAdministrador = '$id'";
    $cn->query($query);
}

function delete_administrador($id) {
    $cn = getConnection();
    $query = "DELETE FROM administradores WHERE idAdministrador = $id";
    $cn->query($query);
}
?>