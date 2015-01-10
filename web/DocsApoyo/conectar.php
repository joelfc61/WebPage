<?php
define("SERVER","localhost");
define("USER","root");
define("PASSWORD","joelfc61");
define("BD","dolfra_dolfra");
$db_link = mysql_connect(SERVER, USER, PASSWORD) OR die("Error de conexión: " . mysql_error());
mysql_select_db(BD,$db_link);
?>
