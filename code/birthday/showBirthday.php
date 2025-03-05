<?php
session_start();
include("../../conexion.php");
if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../index.php");
}
$tipo_usu = $_SESSION['tipo_usu'];
function tipoUsuario($tipo_usu)
{
    if ($tipo_usu == 1) {
        return "ADMINISTRADOR";
    } else if ($tipo_usu == 2) {
        return "LIDER";
    } else if ($tipo_usu == 3) {
        return "MIEMBRO";
    }
}
if (isset($_GET['delete'])) {
    $usuario = $_GET['delete'];
    deleteMember($usuario);
}
function getLeaderName($cc_lider)
{
    if($cc_lider == 1){
        return "ADMINISTRADOR";
    }
    include("../../conexion.php");

    $query = "SELECT nom_ape FROM lideres WHERE cc_lider = '$cc_lider'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();

    return $row['nom_ape'];
}
function deleteMember($usuario)
{
    global $mysqli; // Asegurar acceso a la conexión global

    $query = "DELETE FROM usuarios WHERE usuario = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $usuario);

    if ($stmt->execute()) {
        echo "<script>alert('Lider eliminado correctamente');
        window.location = 'showLeaders.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el lider');
        window.location = 'showLeaders.php';</script>";
    }

    $stmt->close();
}
$tipoUsuario = isset($_SESSION['tipo_usu']) ? intval($_SESSION['tipo_usu']) : 0; // Tipo de usuario
$usuarioSesion = isset($_SESSION['usuario']) ? $mysqli->real_escape_string($_SESSION['usuario']) : '';

// Obtener los filtros desde el formulario
$cc = isset($_GET['cc_mie']) ? trim($_GET['cc_mie']) : '';
$nombre = isset($_GET['nom_ape_mie']) ? trim($_GET['nom_ape_mie']) : '';
$mes = isset($_GET['mes']) ? (int) $_GET['mes'] : 0;
$cargo = isset($_GET['cargo']) ? trim($_GET['cargo']) : '';
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
    <link rel="stylesheet" type="text/css" href="../mie/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../mie/css/estilos2024.css">
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
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

        td {
            text-align: center;
        }
    </style>
</head>

<body>
<?php include('../../layout/navbar.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

    <center style="margin-top: 20px;">
        <img src='../../img/logo.png' width="300" height="212" class="responsive">
    </center>

    <h1 style="color: #412fd1; text-shadow: #FFFFFF 0.1em 0.1em 0.2em; font-size: 40px; text-align: center;"><b><i class="fa-solid fa-file-signature"></i> CUMPLEAÑOS </b></h1>
    <div class="flex">
        <div class="box">
            <form action="showBirthday.php" method="get" class="form">
                <input name="cc_mie" type="text" placeholder="Cédula" value="<?= htmlspecialchars($cc) ?>">
                <input name="nom_ape_mie" type="text" placeholder="Nombre" value="<?= htmlspecialchars($nombre) ?>">
                <select name="mes">
                    <option value="">-- Seleccionar Mes --</option>
                    <?php
                    setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain', 'Spanish');
                    for ($i = 1; $i <= 12; $i++) {
                        $selected = ($mes == $i) ? 'selected' : '';
                        echo "<option style='text-transform:uppercase;' value='$i' $selected>" . strftime('%B', mktime(0, 0, 0, $i, 1)) . "</option>";
                    }
                    ?>
                </select>
                <select name="cargo">
                    <option value="">-- Seleccionar Cargo --</option>
                    <option value="LIDER" <?= ($cargo == 'LIDER') ? 'selected' : '' ?>>LIDER</option>
                    <option value="MIEMBRO" <?= ($cargo == 'MIEMBRO') ? 'selected' : '' ?>>MIEMBRO</option>
                </select>
                <input value="Realizar Búsqueda" type="submit">
            </form>
        </div>
    </div>
    <br /><a href="../../access.php"><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a><br>
    <?php
    date_default_timezone_set("America/Bogota");
    include("../../conexion.php");
    require_once("../../zebra.php");


    // Base de las consultas individuales
    $queryLideres = "SELECT 'LIDER' AS cargo, cc_lider AS cc, nom_ape AS nombre, cumpleanios, NULL AS id_usu_alta FROM lideres WHERE 1=1";
    $queryMiembros = "SELECT 'MIEMBRO' AS cargo, cc_mie AS cc, nom_ape_mie AS nombre, cumpleanios,  id_usu_alta_mie AS id_usu_alta FROM miembros WHERE 1=1";

    // Aplicar filtros si el usuario no es de tipo 1
    if ($tipoUsuario != 1) {
        $queryLideres .= " AND cc_lider = '$usuarioSesion'";
        $queryMiembros .= " AND id_usu_alta_mie = '$usuarioSesion'";
    }

    // Aplicar filtros de búsqueda
    if (!empty($cc)) {
        $queryLideres .= " AND cc_lider = '$cc'";
        $queryMiembros .= " AND cc_mie = '$cc'";
    }

    if (!empty($nombre)) {
        $queryLideres .= " AND nom_ape LIKE '%$nombre%'";
        $queryMiembros .= " AND nom_ape_mie LIKE '%$nombre%'";
    }

    if (!empty($mes)) {
        $queryLideres .= " AND MONTH(cumpleanios) = $mes";
        $queryMiembros .= " AND MONTH(cumpleanios) = $mes";
    }

    // Manejo del filtro de cargo
    if ($cargo == 'LIDER') {
        $queryFinal = $queryLideres; // Solo lideres
    } elseif ($cargo == 'MIEMBRO') {
        $queryFinal = $queryMiembros; // Solo miembros
    } else {
        // Si no hay filtro de cargo, unir ambas consultas
        $queryFinal = "($queryLideres) UNION ($queryMiembros)";
    }

    // Ordenar por mes de cumpleaños
    $queryFinal .= " ORDER BY MONTH(cumpleanios) ASC";

    // Ejecutar consulta
    $result = $mysqli->query($queryFinal);
    if (!$result) {
        die("Error en la consulta: " . $mysqli->error);
    }
    $num_registros = mysqli_num_rows($mysqli->query($queryFinal));
    $resul_x_pagina = 500;
    $paginacion = new Zebra_Pagination();
    $paginacion->records($num_registros);
    $paginacion->records_per_page($resul_x_pagina);
    // Agrega el LIMIT con paginación
    $queryFinal .= " LIMIT " . (($paginacion->get_page() - 1) * $resul_x_pagina) . ", $resul_x_pagina";
    // Ejecuta la consulta con paginación
    $result = $mysqli->query($queryFinal);
    if (!$result) {
        die("Error en la consulta: " . $mysqli->error);
    }
    // Renderiza la paginación
    $paginacion->render();
    // Genera la tabla con los datos
    echo "<section class='content'>
  <div class='card-body'>
      <div class='table-responsive'>
          <table style='width:1300px; text-align: center;'>
              <thead>
                  <tr>
                      <th>CEDULA</th>
                      <th>NOMBRE</th>
                      <th>CUMPLEAÑOS</th>
                      <th>REFERIDO DE </th>
                      <th>CARGO</th>
                  </tr>
              </thead>
              <tbody>";
    $referido = '';
    while ($row = mysqli_fetch_array($result)) {
        if ($row['cargo'] == 'LIDER') {
            $referido = 'N/A';
        }
        if ($row['cargo'] == 'MIEMBRO') {
            $referido =  getLeaderName($row['id_usu_alta']);
        }
        echo '
<tr>
  <td data-label="CEDULA">' . $row['cc'] . '</td>
  <td data-label="NOMBRE">' . $row['nombre'] . '</td>
  <td data-label="CUMPLEAÑOS">' . $row['cumpleanios'] . '</td>
    <td data-label="CUMPLEAÑOS">' .  $referido . '</td>
  <td data-label="CARGO">' . $row['cargo'] . '</td>
</tr>';
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