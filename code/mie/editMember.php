<?php

session_start();

if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../index.php");
    exit();
}

include("../../conexion.php");

header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set("America/Bogota");
$cc_mie = $_GET['cc_mie'];
$row = [];
if (isset($_GET['cc_mie'])) {
    $sql = mysqli_query($mysqli, "SELECT * FROM miembros WHERE cc_mie = '$cc_mie'");
    $row = mysqli_fetch_array($sql);
}

function getMunicipioName($id_mun)
{
    include("../../conexion.php");

    $query = "SELECT nom_mun FROM municipios WHERE id_mun = '$id_mun'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();

    return $row['nom_mun'];
}
function getMunicipios($cod_dane_dep)
{
    include("../../conexion.php");

    $query = "SELECT * FROM municipios WHERE cod_dane_dep = '$cod_dane_dep'";
    $result = $mysqli->query($query);
    return $result;
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CT | SOFT</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/popper.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }

        .form-container {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        fieldset {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        legend {
            font-weight: bold;
            font-size: 0.9em;
            color: #4a4a4a;
            padding: 0 10px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        /* Efecto de enfoque para el fieldset */
        fieldset:focus-within {
            background-color: #e6f7ff;
            /* Azul muy claro */
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
            /* Sombreado azul claro */
        }
    </style>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            const departamentoSelect = document.getElementById("cod_dane_dep");
            const municipioSelect = document.getElementById("id_mun");

            departamentoSelect.addEventListener("change", function() {
                const codDaneDep = this.value;
                municipioSelect.innerHTML = '<option value="">Seleccione el municipio</option>';
                municipioSelect.disabled = true;

                if (codDaneDep) {
                    fetch("get_municipios.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "cod_dane_dep=" + codDaneDep
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                municipioSelect.disabled = false;
                                data.forEach(mun => {
                                    municipioSelect.innerHTML += `<option value="${mun.id_mun}">${mun.nom_mun}</option>`;
                                });
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2(); // Inicializar Select2 en todos los selectores con clase 'select2'
        });
    </script>
    <script>
    </script>
</head>

<body>

    <div class="container" style="margin-top: 29px;">

        <h1><img src='../../img/logo.png' width="171" height="85" class="responsive"><b>REGISTRO DE EQUIPO DE TRABAJO</b></h1>
        <p><i><b>
                    <font size=3 color=#c68615>*Datos obligatorios</i></b></font>
        </p>

        <form id="registroForm" action='updateMembers.php' enctype="multipart/form-data" method="POST">

            <div class="row">
                <div class="col">
                    <div id="result-cc_mie"></div>
                </div>
            </div>

            <div class="form-group">
                <fieldset>
                    <legend>DATOS PERSONALES</legend>
                    <div class="row">
                        <div class="col-12 col-sm-3">
                            <label for="cc_mie">* CC:</label>
                            <input type='number' name='cc_mie' class='form-control' id="cc_mie" value="<?= isset($row['cc_mie']) ? htmlspecialchars($row['cc_mie']) : ''; ?>" />
                        </div>
                        <div class="col-12 col-sm-5">
                            <label for="nom_ape_mie">* NOMBRES APELLIDOS:</label>
                            <input type='text' name='nom_ape_mie' id="nom_ape_mie" class='form-control' value="<?= isset($row['nom_ape_mie']) ? htmlspecialchars($row['nom_ape_mie']) : ''; ?>" style="text-transform:uppercase;" />
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="dir_mie">* DIRECCIÓN:</label>
                            <input type='text' name='dir_mie' id="dir_mie" class='form-control' style="text-transform:uppercase;" value="<?= isset($row['dir_mie']) ? htmlspecialchars($row['dir_mie']) : ''; ?>" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-3">
                            <label for="tel1_arr">* CEL:</label>
                            <input type='text' name='tel1_arr' class='form-control' style="text-transform:uppercase;" value="<?= isset($row['tel1_mie']) ? htmlspecialchars($row['tel1_mie']) : ''; ?>" />
                        </div>
                        <div class="col-12 col-sm-5">
                            <label for="tel2_arr">TEL:</label>
                            <input type='text' name='tel2_arr' class='form-control' style="text-transform:uppercase;" value="<?= isset($row['tel2_mie']) ? htmlspecialchars($row['tel2_mie']) : ''; ?>" />
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="email_arr">EMAIL:</label>
                            <input type='email' name='email_arr' class='form-control' style="text-transform:lowercase;" value="<?= isset($row['email_mie']) ? htmlspecialchars($row['email_mie']) : ''; ?>" />
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="cumpleanios">* FECHA NACIMIENTO:</label>
                            <input type="date" name="cumpleanios" id="cumpleanios" class="form-control"
                                value="<?= isset($row['cumpleanios']) ? $row['cumpleanios'] : ''; ?>" required />
                        </div>

                    </div>
                </fieldset>
            </div>

            <div class="form-group">
                <fieldset>
                    <legend>UBICACION</legend>
                    <div class="row">
                        <div class="col-12 col-sm-2">
                            <label for="estrato_mie">ESTRATO: </label>
                            <select class="form-control" name="estrato_mie" id="estrato_mie">
                                <option value=""></option>
                                <?php
                                $estratoSeleccionado = isset($row['estrato_mie']) ? (string)$row['estrato_mie'] : ''; // Convertir a string explícitamente
                                for ($i = 1; $i <= 10; $i++) {
                                    $selected = ($estratoSeleccionado === (string)$i) ? 'selected' : ''; // Comparación estricta en string
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-12 col-sm-5">
                            <label for="cod_dane_dep">* DEPARTAMENTO:</label>
                            <?php
                            $selected_dep = isset($row['cod_dane_dep']) ? $row['cod_dane_dep'] : '';
                            $selected_mun = isset($row['id_mun']) ? $row['id_mun'] : '';
                            ?>

                            <select id="cod_dane_dep" class="form-control" name="cod_dane_dep" required>
                                <option value="">Seleccione un departamento</option>
                                <?php
                                $sql = $mysqli->prepare("SELECT * FROM departamentos");
                                if ($sql->execute()) {
                                    $g_result = $sql->get_result();
                                    while ($dep = $g_result->fetch_array()) {
                                        $selected = ($dep['cod_dane_dep'] == $selected_dep) ? 'selected' : '';
                                        echo "<option value='{$dep['cod_dane_dep']}' $selected>{$dep['nom_dep']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-12 col-sm-5">
                            <label for="id_mun">* MUNICIPIOS:</label>
                            <select id="id_mun" name="id_mun" class="form-control" required <?= empty($selected_dep) ? 'disabled' : '' ?>>
                                <option value="">Seleccione el municipio</option>
                                <?php
                                if (!empty($selected_dep)) {
                                    $result = getMunicipios($selected_dep);
                                    while ($mun = $result->fetch_array()) {
                                        $selected = ($mun['id_mun'] == $selected_mun) ? 'selected' : '';
                                        echo "<option value='{$mun['id_mun']}' $selected>{$mun['nom_mun']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="cc_mie" value="<?= $cc_mie ?>">
                    </div>
                    <script>
                        //$('#doc_acud').select2();
                        $("#id_mun").select2({
                            tags: true
                        });
                    </script>
                </fieldset>
            </div>

            <!-- Botones para enviar o resetear -->
            <button type="submit" id="submit-btn" class="btn btn-outline-warning">
                <span class="spinner-border spinner-border-sm"></span>
                ACTUALIZAR REGISTRO
            </button>

            <button type="reset" class="btn btn-outline-dark" role='link' onclick="history.back();" type='reset'>
                <img src='../../img/atras.png' width=27 height=27> REGRESAR
            </button>
        </form>
    </div>
</body>
<script src="../../js/jquery-3.1.1.js"></script>
<script type="text/javascript">
</script>

</html>