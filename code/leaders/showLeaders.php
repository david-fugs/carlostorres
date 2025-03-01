<?php
session_start();
include("../../conexion.php");
if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../index.php");
}

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
function getDataLider($cc_lider)
{
    global $mysqli;
    $query = "SELECT * FROM lideres WHERE cc_lider = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $cc_lider);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function deleteMember($usuario)
{
    global $mysqli; // Asegurar acceso a la conexión global

    // Iniciar transacción
    $mysqli->begin_transaction();

    try {
        // Eliminar de la tabla usuarios
        $query1 = "DELETE FROM usuarios WHERE usuario = ?";
        $stmt1 = $mysqli->prepare($query1);
        $stmt1->bind_param("s", $usuario);
        $stmt1->execute();
        $stmt1->close();

        // Eliminar de la tabla lideres
        $query2 = "DELETE FROM lideres WHERE cc_lider = ?";
        $stmt2 = $mysqli->prepare($query2);
        $stmt2->bind_param("s", $usuario);
        $stmt2->execute();
        $stmt2->close();

        // Confirmar transacción si ambas consultas fueron exitosas
        $mysqli->commit();

        echo "<script>alert('Líder eliminado correctamente');
        window.location = 'showLeaders.php';</script>";
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $mysqli->rollback();

        echo "<script>alert('Error al eliminar el líder');
        window.location = 'showLeaders.php';</script>";
    }
}
$cc = isset($_GET['cc_mie']) ? trim($_GET['cc_mie']) : '';
$nombre = isset($_GET['nom_ape_mie']) ? trim($_GET['nom_ape_mie']) : '';

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
        .boton {
            border: none;
            background: none;
            padding: 0;
            cursor: pointer;
        }

        .boton i {
            font-size: 18px;
            /* Ajusta el tamaño del icono según necesites */
            color: #333;
            /* Cambia el color según tu diseño */
        }

        .boton:hover i {
            color: #007bff;
            /* Color al pasar el mouse */
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

    <!-- Modal lideres -->
    <div class="modal fade" id="modalLider" tabindex="-1" aria-labelledby="modalLiderLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLiderLabel">Información de Líder</h1>
                    <?php  ?>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="fas fa-id-card"></i> <strong>Cédula del líder:</strong> <span id="ccLiderModal"></span></p>
                            <p><i class="fas fa-user"></i> <strong>Nombre Lider:</strong> <span id="nomApeModal"></span></p>
                            <p><i class="fas fa-users"></i> <strong>Tipo de usuario:</strong> <span id="tipoUsuModal"></span></p>
                            <p><i class="fas fa-birthday-cake"></i> <strong>Cumpleaños:</strong> <span id="cumpleaniosModal"></span></p>
                            <p><i class="fas fa-phone"></i> <strong>Teléfono:</strong> <span id="telefonoModal"></span></p>
                            <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <span id="emailModal"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="fas fa-briefcase"></i> <strong>Profesión:</strong> <span id="profesionModal"></span></p>
                            <p><i class="fas fa-graduation-cap"></i> <strong>Postgrados:</strong> <span id="postgradosModal"></span></p>
                            <p><i class="fas fa-map-marker-alt"></i> <strong>Dirección:</strong> <span id="direccionModal"></span></p>
                            <p><i class="fas fa-building"></i> <strong>Último trabajo:</strong> <span id="ultimoTrabajoModal"></span></p>
                            <p><i class="fas fa-calendar-alt"></i> <strong>Fecha de alta:</strong> <span id="fechaAltaModal"></span></p>
                            <p><i class="fas fa-check-circle"></i> <strong>Estado:</strong> <span id="estadoModal"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <h1 style="color: #412fd1; text-shadow: #FFFFFF 0.1em 0.1em 0.2em; font-size: 40px; text-align: center;"><b><i class="fa-solid fa-file-signature"></i> LIDERES </b></h1>
    <div class="flex">
        <div class="box">
            <form action="showLeaders.php" method="get" class="form">
                <input name="cc_mie" type="text" placeholder="Cedula Lider" value="<?= htmlspecialchars($cc) ?>">
                <input name="nom_ape_mie" type="text" placeholder="Nombre Lider" value="<?= htmlspecialchars($nombre) ?>">
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
    $query = "SELECT * FROM lideres WHERE 1=1";
    // Agrega filtros si existen
    if (!empty($_GET['cc_mie'])) {
        $cc_mie = $mysqli->real_escape_string($_GET['cc_mie']);
        $query .= " AND cc_lider = '$cc_mie'";
    }

    if (!empty($_GET['nom_ape_mie'])) {
        $nom_ape_mie = $mysqli->real_escape_string($_GET['nom_ape_mie']);
        $query .= " AND nom_ape LIKE '%$nom_ape_mie%'";
    }
    if (!empty($_GET['cumpleanios'])) {
        $cumpleanios = $mysqli->real_escape_string($_GET['cumpleanios']);
        $query .= " AND cumpleanios = '$cumpleanios'";
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
                            <th>TIPO USUARIO</th>
                            <th>VER</th>
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
        echo '
        <tr>
        <td data-label="CEDULA">' . $row['cc_lider'] . '</td>
        <td data-label="NOMBRE"  style="text-transform:uppercase;" >' . $row['nom_ape'] . '</td>
        <td data-label="DEPARTAMENTO">' . tipoUsuario($row['tipo_usu']) . '</td>
        <td data-label="VER"><button type="button" class="boton" data-bs-toggle="modal" data-bs-target="#modalLider" data-cclider="' . $row['cc_lider'] . '">
        <i class="fa-solid fa-eye"></i>
      </button>
        <td data-label="EDITAR"><a href="editLeader.php?usuario=' . $row['cc_lider'] . '"><img src="../../img/editar.png" width=28 height=28></a></td>
        <td data-label="ELIMINAR">
                <a href="?delete=' . $row['cc_lider'] . '" 
                onclick="return confirm(\'¿Estás seguro de que deseas eliminar este Lider?\');">
                    <i class="fa-sharp-duotone fa-solid fa-trash" style="color:red; height:20px;"></i>
                </a>
            </td>   
        </tr>';

        $i++;
    }

    echo '</table>
</div>';
    ?>
    <center>
        <br /><a href="../../access.php"><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a>
    </center>
    <script src="https://www.jose-aguilar.com/scripts/fontawesome/js/all.min.js" data-auto-replace-svg="nest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var modalLider = document.getElementById("modalLider");
            modalLider.addEventListener("show.bs.modal", function(event) {
                var button = event.relatedTarget;
                var ccLider = button.getAttribute("data-cclider");

                // Hacer una consulta a la base de datos con AJAX
                fetch("getLiderData.php?cc_lider=" + ccLider)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("ccLiderModal").textContent = data.cc_lider;
                        document.getElementById("nomApeModal").textContent = data.nom_ape;
                        document.getElementById("tipoUsuModal").textContent = data.tipo_usu;
                        document.getElementById("cumpleaniosModal").textContent = data.cumpleanios;
                        document.getElementById("telefonoModal").textContent = data.telefono;
                        document.getElementById("emailModal").textContent = data.email;
                        document.getElementById("profesionModal").textContent = data.profesion;
                        document.getElementById("postgradosModal").textContent = data.postgrados;
                        document.getElementById("direccionModal").textContent = data.direccion;
                        document.getElementById("ultimoTrabajoModal").textContent = data.ultimo_trabajo;
                        document.getElementById("fechaAltaModal").textContent = data.fecha_alta;
                        document.getElementById("estadoModal").textContent = data.estado === "1" ? "Activo" : "Inactivo";
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    </script>
</body>

</html>