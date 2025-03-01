<?php
include("../../conexion.php");

if (isset($_POST['cod_dane_dep'])) {
    $cod_dane_dep = $_POST['cod_dane_dep'];
    $query = "SELECT * FROM municipios WHERE cod_dane_dep = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $cod_dane_dep);
    $stmt->execute();
    $result = $stmt->get_result();

    $municipios = [];
    while ($row = $result->fetch_assoc()) {
        $municipios[] = $row;
    }

    echo json_encode($municipios);
}
?>
