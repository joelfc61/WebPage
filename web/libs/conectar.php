<?php
//define("SERVER","localhost");
define("SERVER","127.0.0.1");
define("USER","root");
define("PASSWORD","joelfc61");
define("BD","dolfra_dolfra");
$db_link = mysql_connect(SERVER, USER, PASSWORD) OR die("Error de conexi�n: " . mysql_error());
mysql_select_db(BD,$db_link);
?>