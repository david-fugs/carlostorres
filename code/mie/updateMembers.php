<?php 
    print_r($_POST);
    include("../../conexion.php");
    session_start();
    
    if (!isset($_SESSION['id_usu'])) {
        header("Location: ../../index.php");
        exit();
    }
    $id_usu_alta_mie = $_SESSION['id_usu'];
    $cc_mie = $_POST['cc_mie'];
    $nom_ape_mie = $_POST['nom_ape_mie'];
    $dir_mie = $_POST['dir_mie'];
    $tel1_arr = $_POST['tel1_arr'];
    $tel2_arr = $_POST['tel2_arr'];
    $email_arr = $_POST['email_arr'];
    $cod_dane_dep = $_POST['cod_dane_dep'];
    $id_mun = $_POST['id_mun'];
    $estrato_mie = $_POST['estrato_mie'];
    $cumpleanios = $_POST['cumpleanios'];

    $sql_update = "UPDATE miembros SET cc_mie = '$cc_mie', nom_ape_mie = '$nom_ape_mie', dir_mie = '$dir_mie', tel1_mie = '$tel1_arr', tel2_mie = '$tel2_arr', email_mie = '$email_arr', cod_dane_dep = '$cod_dane_dep', id_mun = '$id_mun', estrato_mie = '$estrato_mie', id_usu_alta_mie = '$id_usu_alta_mie', id_usu = '$id_usu_alta_mie' , cumpleanios = '$cumpleanios' WHERE cc_mie = '$cc_mie'";
    if (mysqli_query($mysqli, $sql_update)) {
        echo "<script>
                alert('Registro actualizado correctamente'); 
                window.location.href = 'showMembers.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($mysqli) . "'); 
                window.history.back();
              </script>";
    }


?>