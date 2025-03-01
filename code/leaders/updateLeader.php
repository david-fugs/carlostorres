<?php
include("../../conexion.php");
session_start();

if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../index.php");
    exit();
}

$usuario = $_POST['usuario'];
$nombre = $_POST['nombre'];
$cumpleanios = $_POST['cumpleanios'];
$cargo = $_POST['cargo'];
$usuario_old = $_POST['usuario_old'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];
$profesion = $_POST['profesion'];
$postgrados = $_POST['postgrados'];
$ultimo_trabajo = $_POST['ultimo_trabajo'];
$current_day = date("Y-m-d");
mysqli_begin_transaction($mysqli);

$sql_update_lideres = "UPDATE lideres SET 
        cc_lider = '$usuario', 
        nom_ape = '$nombre', 
        tipo_usu = '$cargo', 
        cumpleanios = '$cumpleanios', 
        telefono = '$telefono', 
        email = '$email', 
        direccion = '$direccion', 
        profesion = '$profesion', 
        postgrados = '$postgrados', 
        ultimo_trabajo = '$ultimo_trabajo', 
        fecha_edit = '$current_day' 
    WHERE cc_lider = '$usuario_old'";

$sql_update_usuarios = "UPDATE usuarios SET 
        usuario = '$usuario', 
        nombre = '$nombre', 
        tipo_usu = '$cargo' 
    WHERE usuario = '$usuario_old'";

// Ejecutar ambas consultas
if (mysqli_query($mysqli, $sql_update_lideres) && mysqli_query($mysqli, $sql_update_usuarios)) {
    mysqli_commit($mysqli); // Confirmar la transacción si ambas consultas son exitosas
    echo "<script>
                alert('Registro actualizado correctamente'); 
                window.location.href = 'showLeaders.php';
              </script>";
} else {
    mysqli_rollback($mysqli); // Revertir cambios en caso de error
    echo "<script>
                alert('Error: " . mysqli_error($mysqli) . "'); 
                window.history.back();
              </script>";
}
