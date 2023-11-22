<?php
// Archivo de conexión a la base de datos
include 'conexion.php';

if (isset($_GET['provincia_id'])) {
    $provinciaId = $_GET['provincia_id'];
    $query = "SELECT * FROM municipios WHERE idprovincia = $provinciaId";
    $result = mysqli_query($conn, $query);
    $municipios = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($municipios);
}
