<?php
session_start();
include("../../conexion.php");
if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../index.php");
}
function getLideres()
{
    include("../../conexion.php");

    $query = "SELECT * FROM lideres";
    $result = $mysqli->query($query);
    return $result;
}

function getDepartamentName($cod_dane_dep)
{
    include("../../conexion.php");

    $query = "SELECT nom_dep FROM departamentos WHERE cod_dane_dep = '$cod_dane_dep'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();

    return $row['nom_dep'];
}
function getMunicipioName($id_mun)
{
    include("../../conexion.php");

    $query = "SELECT nom_mun FROM municipios WHERE id_mun = '$id_mun'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();

    return $row['nom_mun'];
}
$nombre = $_SESSION['nombre'];
$tipo_usu = $_SESSION['tipo_usu'];

if (isset($_GET['delete'])) {
    $cc_mie = $_GET['delete'];
    deleteMember($cc_mie);
}
function getNameLeader($cc_lider)
{
    if ($cc_lider == 1) {
        return "ADMINISTRADOR";
    }
    include("../../conexion.php");

    $query = "SELECT nom_ape FROM lideres WHERE cc_lider = '$cc_lider'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();

    return $row['nom_ape'];
}
function deleteMember($cc_mie)
{
    global $mysqli; // Asegurar acceso a la conexión global

    $query = "DELETE FROM miembros WHERE cc_mie = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $cc_mie);

    if ($stmt->execute()) {
        echo "<script>alert('Miembro eliminado correctamente');
        window.location = 'showMembers.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el miembro');
        window.location = 'showMembers.php';</script>";
    }

    $stmt->close();
}
// Obtener los filtros desde el formulario
$cc = isset($_GET['cc_mie']) ? trim($_GET['cc_mie']) : '';
$nombre = isset($_GET['nom_ape_mie']) ? trim($_GET['nom_ape_mie']) : '';
$liderSeleccionado = isset($_GET['cc_lider']) ? $_GET['cc_lider'] : ''; // Valor seleccionado
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CT | SOFT</title>
    <script src="js/64d58efce2.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/estilos2024.css">
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <style>
        th {
            font-size: 15px;
        }

        td {
            font-size: 15px;
        }

        .responsive {
            max-width: 100%;
            height: auto;
        }

        .selector-for-some-widget {
            box-sizing: content-box;
        }

        .pending {
            background-color: orange;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .ok {
            background-color: lightblue;
            color: black;
            font-weight: bold;
            text-align: center;
        }

        .disabled-link {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
</head>

<body>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

    <center style="margin-top: 20px;">
        <img src='../../img/logo.png' width="300" height="212" class="responsive">
    </center>

    <h1 style="color: #412fd1; text-shadow: #FFFFFF 0.1em 0.1em 0.2em; font-size: 40px; text-align: center;"><b><i class="fa-solid fa-file-signature"></i> MIEMBROS </b></h1>

    <div class="flex">
        <div class="box">
            <form action="showMembers.php" method="get" class="form">
                <input name="cc_mie" type="text" placeholder="Cedula Miembro" value="<?= htmlspecialchars($cc) ?>">
                <input name="nom_ape_mie" type="text" placeholder="Nombre Miembro" value="<?= htmlspecialchars($nombre) ?>">
                <?php if ($_SESSION['tipo_usu'] == 1) : ?>
                    <select name="cc_lider" value="<?= htmlspecialchars($lider) ?>">
                        <option value="">Seleccione un líder</option>
                        <?php
                        include("../../conexion.php");
                        $lideres = getLideres();
                        while ($lider = $lideres->fetch_assoc()) {
                            $selected = ($lider['cc_lider'] == $liderSeleccionado) ? 'selected' : '';
                            echo "<option value='{$lider['cc_lider']}' $selected>{$lider['nom_ape']}</option>";
                        }
                        ?>
                    </select>
                <?php endif; ?>

                <input value="Realizar Busqueda" type="submit">
            </form>
        </div>
    </div>

    <br /><a href="../../access.php"><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a><br>

    <?php

    date_default_timezone_set("America/Bogota");
    include("../../conexion.php");
    require_once("../../zebra.php");

    // Inicializa la consulta base
    $query = "SELECT * FROM miembros WHERE 1=1";

    // Agrega filtros si existen
    if (!empty($_GET['cc_mie'])) {
        $cc_mie = $mysqli->real_escape_string($_GET['cc_mie']);
        $query .= " AND cc_mie = '$cc_mie'";
    }

    if (!empty($_GET['nom_ape_mie'])) {
        $nom_ape_mie = $mysqli->real_escape_string($_GET['nom_ape_mie']);
        $query .= " AND nom_ape_mie LIKE '%$nom_ape_mie%'";
    }
    // Si el tipo de usuario es diferente de 1, filtrar por id_usu_alta
    if ($_SESSION['tipo_usu'] != 1) {
        $id_usu_alta = $mysqli->real_escape_string($_SESSION['usuario']);
        $query .= " AND id_usu_alta_mie = '$id_usu_alta'";
    }
    // Filtro por líder seleccionado
    if (!empty($_GET['cc_lider'])) {
        $cc_lider = $mysqli->real_escape_string($_GET['cc_lider']);
        $query .= " AND id_usu_alta_mie = '$cc_lider'";
    }


    // Ejecuta la consulta
    $res = $mysqli->query($query);
    if (!$res) {
        die("Error en la consulta: " . $mysqli->error);
    }

    $num_registros = mysqli_num_rows($res);
    $resul_x_pagina = 500;
    echo "<section class='content'>
        <div class='card-body'>
            <div class='table-responsive'>
                <table style='width:1300px;' >
                    <thead>
                        <tr>
                            <th>CEDULA</th>
                            <th>NOMBRE</th>
                            <th>DIRECCION</th>
                            <th>DEPARTAMENTO</th>
                            <th>MUNICIPIO</th>
                            <th>ESTRATO</th>
                            <th>TELEFONO</th>
                            <th>CUMPLEAÑOS </th>
                            <th>REFERIDO DE </th>
                            <th>EMAIL</th>
                            <th>EDITAR</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>";

    $paginacion = new Zebra_Pagination();
    $paginacion->records($num_registros);
    $paginacion->records_per_page($resul_x_pagina);
    // Agrega el LIMIT con paginación
    $query .= " LIMIT " . (($paginacion->get_page() - 1) * $resul_x_pagina) . ", $resul_x_pagina";
    // Ejecuta la consulta con paginación
    $result = $mysqli->query($query);
    if (!$result) {
        die("Error en la consulta: " . $mysqli->error);
    }
    $paginacion->render();
    $i = 1;
    while ($row = mysqli_fetch_array($result)) {
        // Formatear los valores como moneda
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>
        <td data-label="CEDULA">' . $row['cc_mie'] . '</td>
        <td style="text-transform:uppercase;" data-label="NOMBRE">' . $row['nom_ape_mie'] . '</td>
        <td data-label="DIRECCION">' . $row['dir_mie'] . '</td>
        <td data-label="DEPARTAMENTO">' . getDepartamentName($row['cod_dane_dep']) . '</td>
        <td data-label="MUNICIPIO">' . getMunicipioName($row['id_mun']) . '</td>
        <td data-label="ESTRATO">' . $row['estrato_mie'] . '</td>
        <td data-label="TELEFONO">' . $row['tel1_mie'] . (!empty($row['tel2_mie']) ? ' / ' . $row['tel2_mie'] : '') . '</td>
        <td data-label="CUMPLEAÑOS">' . $row['cumpleanios'] . '</td>
        <td data-label="REFERIDO">' . getNameLeader($row['id_usu_alta_mie']) . '</td>
        <td data-label="EMAIL">' . strtolower($row['email_mie']) . '</td>
        <td data-label="EDITAR"><a href="editMember.php?cc_mie=' . $row['cc_mie'] . '"><img src="../../img/editar.png" width=28 height=28></a></td>
        <td data-label="ELIMINAR">
                <a href="?delete=' . $row['cc_mie'] . '" 
                onclick="return confirm(\'¿Estás seguro de que deseas eliminar este miembro?\');">
                    <i class="fa-sharp-duotone fa-solid fa-trash" style="color:red; height:20px;"></i>
                </a>
            </td>   
        </tr>';

            $i++;
        }
    }
    echo '</table>
</div>';
    ?>

    <center>
        <br /><a href="../../access.php"><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a>
    </center>

    <script src="https://www.jose-aguilar.com/scripts/fontawesome/js/all.min.js" data-auto-replace-svg="nest"></script>

</body>

</html>