<?php

session_start();

if (!isset($_SESSION['id_usu'])) {
    header("Location: ../../index.php");
    exit();
}

include("../../conexion.php");

header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set("America/Bogota");

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
    <!--SCRIPT PARA VALIDAR SI EL REGISTRO YA ESTÁ EN LA BD-->
    <script type="text/javascript">
        $(document).ready(function() {
            let cc_mie_valido = false; // Variable de control

            $('#cc_mie').on('blur', function() {
                $('#result-cc_mie').html('<img src="../../img/loader.gif" />').fadeOut(1000);
                var cc_mie = $(this).val();
                var dataString = 'cc_mie=' + cc_mie;

                $.ajax({
                    type: "POST",
                    url: "chkmie.php",
                    data: dataString,
                    success: function(data) {
                        $('#result-cc_mie').fadeIn(1000).html(data);

                        // Verifica si la cédula ya existe
                        if (data.includes("Ya existe uno igual")) {
                            cc_mie_valido = false;
                            $('#submit-btn').prop('disabled', true); // Deshabilita el botón
                        } else {
                            cc_mie_valido = true;
                            $('#submit-btn').prop('disabled', false); // Habilita el botón
                        }
                    }
                });
            });

            // Evita el envío si la cédula es inválida
            $('#miFormulario').on('submit', function(e) {
                if (!cc_mie_valido) {
                    e.preventDefault(); // Cancela el envío del formulario
                    alert("No puedes registrar este número de cédula. ¡Verifica antes de continuar!");
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
        function ordenarSelect(id_componente) {
            var selectToSort = jQuery('#' + id_componente);
            var optionActual = selectToSort.val();
            selectToSort.html(selectToSort.children('option').sort(function(a, b) {
                return a.text === b.text ? 0 : a.text < b.text ? -1 : 1;
            })).val(optionActual);
        }

        $(document).ready(function() {
            ordenarSelect('cod_dane_dep');
            ordenarSelect('id_mun');
        });
    </script>
</head>

<body>

    <div class="container">

        <h1><img src='../../img/logo.png' width="171" height="85" class="responsive"><b>REGISTRO DE EQUIPO DE TRABAJO</b></h1>
        <p><i><b>
                    <font size=3 color=#c68615>*Datos obligatorios</i></b></font>
        </p>

        <form id="registroForm" action='processEquipo.php' enctype="multipart/form-data" method="POST">

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
                            <input type='number' name='cc_mie' class='form-control' id="cc_mie" required />
                        </div>
                        <div class="col-12 col-sm-5">
                            <label for="nom_ape_mie">* NOMBRES APELLIDOS:</label>
                            <input type='text' name='nom_ape_mie' id="nom_ape_mie" class='form-control' required style="text-transform:uppercase;" />
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="dir_mie">* DIRECCIÓN:</label>
                            <input type='text' name='dir_mie' id="dir_mie" class='form-control' style="text-transform:uppercase;" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-2">
                            <label for="tel1_arr">* CEL:</label>
                            <input type='text' name='tel1_arr' class='form-control' style="text-transform:uppercase;" required />
                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="tel2_arr">TEL:</label>
                            <input type='text' name='tel2_arr' class='form-control' style="text-transform:uppercase;" />
                        </div>
                        <div class="col-12 col-sm-8">
                            <label for="email_arr">EMAIL:</label>
                            <input type='email' name='email_arr' class='form-control' style="text-transform:lowercase;" />
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="form-group">
                <fieldset>
                    <legend>UBICACION</legend>
                    <div class="row">
                        <div class="col-12 col-sm-5">
                            <label for="cod_dane_dep">* DEPARTAMENTO:</label>
                            <select id="cod_dane_dep" class="form-control" name="cod_dane_dep" required="required">
                                <option value=""></option>
                                <?php
                                $sql = $mysqli->prepare("SELECT * FROM departamentos");
                                if ($sql->execute()) {
                                    $g_result = $sql->get_result();
                                }
                                while ($row = $g_result->fetch_array()) {
                                ?>
                                    <option value="<?php echo $row['cod_dane_dep'] ?>"><?php echo $row['nom_dep'] ?></option>
                                <?php
                                }
                                $mysqli->close();
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-5">
                            <label for="id_mun">* MUNICIPIOS:</label>
                            <select id="id_mun" name="id_mun" class="form-control" disabled="disabled" required="required">
                                <option value="">* SELECCIONE EL MUNICIPIO:</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="estrato_mie">ESTRATO:</label>
                            <select class="form-control" name="estrato_mie" id="estrato_mie">
                                <option value=""></option>
                                <option value=1>1</option>
                                <option value=2>2</option>
                                <option value=3>3</option>
                                <option value=4>4</option>
                                <option value=5>5</option>
                                <option value=6>6</option>
                                <option value=7>7</option>
                                <option value=8>8</option>
                                <option value=9>9</option>
                                <option value=10>10</option>
                            </select>
                        </div>
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
            <button type="submit" id="submit-btn" class="btn btn-outline-warning" disabled>
                <span class="spinner-border spinner-border-sm"></span>
                INGRESAR REGISTRO
            </button>

            <button type="reset" class="btn btn-outline-dark" role='link' onclick="history.back();" type='reset'>
                <img src='../../img/atras.png' width=27 height=27> REGRESAR
            </button>
        </form>
    </div>
</body>
<script src="../../js/jquery-3.1.1.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#cod_dane_dep').on('change', function() {
            if ($('#cod_dane_dep').val() == "") {
                $('#id_mun').empty();
                $('<option value = "">Seleccione un municipio</option>').appendTo('#id_mun');
                $('#id_mun').attr('disabled', 'disabled');
            } else {
                $('#id_mun').removeAttr('disabled', 'disabled');
                $('#id_mun').load('modules_get.php?cod_dane_dep=' + $('#cod_dane_dep').val());
            }
        });
    });
</script>

</html>