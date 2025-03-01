<?php
include("../../conexion.php");
if (isset($_GET['cc_lider'])) {
    $cc_lider = $_GET['cc_lider'];
    $query = "SELECT * FROM lideres WHERE cc_lider = '$cc_lider'";
    $result = mysqli_query($mysqli, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo json_encode(mysqli_fetch_assoc($result));
    } else {
        echo json_encode(["error" => "No se encontraron datos"]);
    }
}
