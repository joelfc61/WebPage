<?
 define("SERVER","127.0.0.1");
 define("USER","root");
 define("PASSWORD","joelfc61");
 //define("BD","mispruebas");
 define("BD","dolfra_dolfra");
 $db_link = mysql_connect(SERVER, USER, PASSWORD) OR die("Error de conexión: " . mysql_error());
 mysql_select_db(BD,$db_link);
  $tipo_user  =3;
  $usuario= $_GET['usuario'];
  $ipuser = $_SERVER['REMOTE_ADDR'];
  echo "Se esta ejecutando desde: ".$_SERVER['REMOTE_ADDR']."<br> Con el usuario tipo: ".$tipo_user;
  echo "<br>El nombre del server es: " .$_SERVER['SERVER_NAME']."<br> Y la ruta es: ".$_SERVER['PHP_SELF'];
  echo "La fecha es: ".date('d-m-Y H:i:s')."<br>";
 
  $qIns = "insert into bitacora(fecha,usuario,tipo,ip_user) values('".date('Y-m-d H:i:s')."','".$usuario."','".$tipo_user."','".$ipuser."')";
   echo "<br>".$qIns;
  $x = mysql_query($qIns);
  mysql_close($db_link);
  //id_consec (int auto_increment),Fecha(date/time),usuario (varchar(30)),tipo_user(int),ipuservarchar(15)
  
?>