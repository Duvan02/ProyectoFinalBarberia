<?php
require_once('../config/database.php');

function login_user($user, $password){
    $cn = getConnection();
    $query = "SELECT * FROM usuarios WHERE email = '$user' AND password = '$password'";
    $datos = $cn->query($query);
    if ($datos->num_rows > 0) {
        $result = $datos->fetch_assoc();
        return $result;
    }
    return null;
}

function get_usuarios(){
    $cn = getConnection();
    $query = "SELECT * FROM usuarios";
    $datos = $cn->query($query);
    $users = [];
    while ($row = $datos->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

function insert_user($nombres, $email, $password, $telefono) {
    $cn = getConnection();
    $query = "INSERT INTO usuarios(nombres,email,telefono,password) VALUES('$nombres','$email','$telefono','$password')";
    $cn->query($query);
}

function update_user($id, $nombres, $telefono, $fotoBase64) {
    $cn = getConnection();
    $query = "UPDATE usuarios SET nombres = '$nombres', telefono = '$telefono', foto = '$fotoBase64' WHERE idUsuario = '$id'";
    $cn->query($query);
}
?>