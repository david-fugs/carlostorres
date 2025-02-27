<?php 
require('../../conexion.php');
sleep(1);
if (isset($_POST)) {
    $cc_mie = (string)$_POST['cc_mie'];
    
    $result = $mysqli->query(
        'SELECT * FROM miembros WHERE cc_mie = "'.strtolower($cc_mie).'"'
    );
    
    if ($result->num_rows > 0) {
        echo '<div class="alert alert-danger"><strong>VERIFICA EL NUMERO DE CC!</strong> Ya existe uno igual.</div>';
    } else {
        echo '<div class="alert alert-success"><strong>ES NUEVO REGISTRO!</strong> El integrante no está registrad@.</div>';
    }
}