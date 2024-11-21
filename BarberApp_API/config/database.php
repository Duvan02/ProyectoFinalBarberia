<?php
// Conexión a la base de datos
    function getConnection(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "db_barber";

        $cn = new mysqli($servername,$username,$password,$dbname);

        if ($cn->connect_error) {
            die("Connection failed: " . $cn->connect_error);
        }
    
        return $cn;
    }

    
?>