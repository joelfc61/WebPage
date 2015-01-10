<?
define ("DS",DIRECTORY_SEPARATOR);
DEFINE ("ROOT",realpath(dirname(__FILE__)).DS);
define ("APP_PATH", ROOT."aplicacion".DS);

echo DS."<br>";
echo ROOT."<br>";
echo APP_PATH."<br>";
echo $_SERVER[PHP_SELF];

echo "1.-".$HTTP_REFERER."<BR>";
echo "2.-".$HTTP_ACCEPT_LANGUAGE."<BR>";
echo "3.-".$HTTP_USER_AGENT."<BR>";
echo "4.-".$REMOTE_ADDR."<BR>";
echo "5.-".$OS."<BR>";
echo "6.-".$REQUEST_METED."<BR>";
echo "7.-".$SERVER_NAME."<BR>";
echo "8.-".$SERVER_SOFTWARE."<BR>";
echo "9.-".$DOCUMENT_ROOT."<BR>";
echo "10.-".$SERVER_ADMIN."<BR>";
echo "11.-".$SERVER_PORT."<BR>";
echo "12.-".$SERVER_SIGNATURE."<BR>";
echo "13.-".$SCRIPT_NAME."<BR>";

echo "<hr><br>";
$conectar = mysql_connect("localhost","root","joelfc61");
$link = mysql_select_db("mispruebas");
$sSuperv ="select * from supervisor";
$rData = mysql_query($sSuperv);
while($row = mysql_fetch_assoc($rData)){
   echo $row['usuario']."->".$row['password']."<br>";
 }
echo "<hr><br>";
$sSuperv ="select * from administrador";
$rData = mysql_query($sSuperv);
while($row = mysql_fetch_assoc($rData)){
   echo $row['user']."->".$row['pass']."<br>";
 }

 mysql_close($conectar);

 /*$mysqli = new Mysqli("localhost","root","joelfc61","mispruebas");
 $datos = $mysqli->query("select * from maquinas");
 */
?>