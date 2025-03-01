<?php

session_start();

if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../index.php");
    exit();
}

include("../../conexion.php");

header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set("America/Bogota");
$usuario = $_GET['usuario'];
$row = [];
if (isset($_GET['usuario'])) {
    $sql = mysqli_query($mysqli, "SELECT * FROM lideres WHERE cc_lider = '$usuario'");
    $row = mysqli_fetch_array($sql);
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
</head>

<body>
    <div class="container" style="margin-top: 35px;">

        <h1><img src='../../img/logo.png' width="171" height="85" class="responsive"><b>ACTUALIZACION DE EQUIPO DE LIDERES </b></h1>
        <p><i><b>
                    <font size=3 color=#c68615>*Datos obligatorios</i></b></font>
        </p>
        <form id="registroForm" action='updateLeader.php' enctype="multipart/form-data" method="POST">
            <div class="row">
                <div class="col">
                    <div id="result-cc_mie"></div>
                </div>
            </div>

            <div class="form-group">
                <fieldset>
                    <legend>DATOS PERSONALES</legend>
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <label for="cc">* CC:</label>
                            <input type='number' name='usuario' class='form-control' id="cc" required  value="<?= isset($row['cc_lider']) ? $row['cc_lider'] : ''; ?>" required /> 
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="nombre">* NOMBRES APELLIDOS:</label>
                            <input type='text' name='nombre' id="nombre" class='form-control' required style="text-transform:uppercase;"  value=" <?= isset($row['nom_ape']) ? $row['nom_ape'] : ''; ?>" required /> 
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="cumpleanios">* FECHA NACIMIENTO:</label>
                            <input type='date' name='cumpleanios' id="cumpleanios" class='form-control' required  value="<?= isset($row['cumpleanios']) ? $row['cumpleanios'] : ''; ?>" required />
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="cargo">* CARGO:</label>
                            <select id="cargo" class="form-control" name="cargo" required="required">
                                <option value="">Seleccione...</option>
                                <option value="1" <?php if($row['tipo_usu'] == 1) echo 'selected' ?> > ADMINISTRADOR</option>
                                <option value="2" <?php if($row['tipo_usu'] == 2) echo 'selected' ?>> LIDER</option>
                            </select>
                        </div>
                        <div class= "col-12 col-sm-4">
                            <label for="telefono">* TELEFONO:</label>
                            <input type='number' name='telefono' id="telefono" class='form-control' required style="text-transform:uppercase;"  value="<?= isset($row['telefono']) ? intval($row['telefono']) : ''; ?>" />
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="email">* EMAIL:</label>
                            <input type='text' name='email' id="email" class='form-control' required style="text-transform:uppercase;"   value=" <?= isset($row['email']) ? $row['email'] : ''; ?>"/>
                        </div>
                        <div class="col-12 col-sm-4 mt-2">
                            <label for="direccion">* DIRECCION:</label>
                            <input type='text' name='direccion' id="direccion" class='form-control' required  value=" <?= isset($row['direccion']) ? $row['direccion'] : ''; ?>"style="text-transform:uppercase;" />
                        </div>

                        <input type="hidden" name="usuario_old" value="<?= $usuario ?>">    
                    </div>
                </fieldset>
            </div>

            <div class="form-group">
                <fieldset>
                    <legend>DATOS PROFESIONALES</legend>
                    <div class="row">
                        <div class="col-12 col-sm-3">
                            <label for="profesion">* PROFESION:</label>
                            <input type='text' name='profesion' class='form-control' id="profesion" required style="text-transform:uppercase;"  value=" <?= isset($row['profesion']) ? $row['profesion'] : ''; ?>" />
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="postgrados">* POSTGRADOS:</label>
                            <input type='text' name='postgrados' id="postgrados" class='form-control' required style="text-transform:uppercase;" value=" <?= isset($row['postgrados']) ? $row['postgrados'] : ''; ?>" />
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="ultimo_trabajo">* ULTIMO LUGAR DE TRABAJO:</label>
                            <input type='text' name='ultimo_trabajo' id="ultimo_trabajo" class='form-control' required style="text-transform:uppercase;"  value=" <?= isset($row['ultimo_trabajo']) ? $row['ultimo_trabajo'] : ''; ?>" />
                        </div>
                    </div>
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

</html>