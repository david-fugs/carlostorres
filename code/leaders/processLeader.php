<?php

include("../../conexion.php");
session_start();

if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../index.php");
    exit();
}
$cc = $_POST['cc'];
$nom_ape = $_POST['nom_ape'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$cargo = $_POST['cargo'];
$cumpleanios = $_POST['cumpleanios'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$profesion = $_POST['profesion'];
$postgrados = $_POST['postgrados'];
$direccion = $_POST['direccion'];
$ultimo_trabajo = $_POST['ultimo_trabajo'];
if ($password != $password2) {
    echo "<script>
            alert('Las contraseñas no coinciden'); 
            window.history.back();
          </script>";
    exit();
}
if ($password == "") {
    echo "<script>
            alert('La contraseña no puede estar vacía'); 
            window.history.back();
          </script>";
    exit();
}
$password_encrypt = sha1($password);
$sql_insert = "INSERT INTO lideres (cc_lider,nom_ape,tipo_usu,cumpleanios,telefono,email,profesion,postgrados,direccion,ultimo_trabajo) VALUES ('$cc', '$nom_ape', '$cargo', '$cumpleanios', '$telefono', '$email', '$profesion', '$postgrados', '$direccion', '$ultimo_trabajo')";
if (mysqli_query($mysqli, $sql_insert)) {
    $sql_insert = "INSERT INTO usuarios (usuario,nombre,password,tipo_usu ) VALUES ('$cc', '$nom_ape', '$password_encrypt', '$cargo')";
    if (mysqli_query($mysqli, $sql_insert)) {
        echo "<script>
                alert('Registro insertado correctamente'); 
                window.location = 'showLeaders.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($mysqli) . "'); 
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('Error: " . mysqli_error($mysqli) . "'); 
            window.history.back();
          </script>";
}
