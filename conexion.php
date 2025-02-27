<?php
	 
	$mysqli = new mysqli('localhost', 'aprendad_carlosTorres', '++im6CEsxw3]', 'aprendad_carlosTorres');
	$mysqli->set_charset("utf8");
	if($mysqli->connect_error){
		
		die('Error en la conexion' . $mysqli->connect_error);
		
	}
?>
