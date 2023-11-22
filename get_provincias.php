<?php
// Archivo de conexión a la base de datos
include 'conexion.php';

if (isset($_GET['comunidad_id'])) {
    $comunidadId = $_GET['comunidad_id'];
    $query = "SELECT * FROM provincias WHERE idcomunidad = $comunidadId";
    $result = mysqli_query($conn, $query);
    $provincias = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($provincias);
}
