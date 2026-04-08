<?php
$host = "localhost";
$db = "sistema_objetos";
$user = "root";
$pass = "";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}