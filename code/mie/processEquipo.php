<?php

include("../../conexion.php");
session_start();

if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../index.php");
    exit();
}

$id_usu_alta_mie = $_SESSION['id_usu'];
$usuario = $_SESSION['usuario'];
$cc_mie = $_POST['cc_mie'];
$nom_ape_mie = $_POST['nom_ape_mie'];
$cumpleanios = $_POST['cumpleanios'];
$dir_mie = $_POST['dir_mie'];
$tel1_arr = $_POST['tel1_arr'];
$tel2_arr = $_POST['tel2_arr'];
$email_arr = $_POST['email_arr'];
$cod_dane_dep = $_POST['cod_dane_dep'];
$id_mun = $_POST['id_mun'];
$estrato_mie = $_POST['estrato_mie'];

$sql_insert = "INSERT INTO miembros (cc_mie, nom_ape_mie, dir_mie, tel1_mie, tel2_mie, email_mie, cod_dane_dep, id_mun, estrato_mie,id_usu_alta_mie, id_usu , cumpleanios) VALUES ('$cc_mie', '$nom_ape_mie', '$dir_mie', '$tel1_arr', '$tel2_arr', '$email_arr', '$cod_dane_dep', '$id_mun', '$estrato_mie', '$usuario' , '$id_usu_alta_mie', '$cumpleanios')";
if (mysqli_query($mysqli, $sql_insert)) {
    echo "<script>
            alert('Registro insertado correctamente'); 
            window.location.href = 'showMembers.php';
          </script>";
} else {
    echo "<script>
            alert('Error: " . mysqli_error($mysqli) . "'); 
            window.history.back();
          </script>";
}
