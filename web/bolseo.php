<?php
$tabla	=	"maquina";
$tabla2 =	"supervisor";
$tabla3 =	"operador";

$indice	=	"id_maquina";
$indice2	=	"id_supervisor";
$indice3	=	"id_operador";

?>
<script languaje="javascript">
function IsNumeric(sText)
 
{
   var ValidChars = "0123456789.,-";
   var IsNumber=true;
   var Char;
 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
}
function sumarExtr()
{
        var a,total, i;
        i = document.getElementsByTagName('INPUT');
        total=0;
        for(a=0; a<i.length; a++)
        {
                if (IsNumeric(i[a].value) && i[a].name.indexOf('_extr_Total')>0 )
                {
                        if (i[a].value.length>0) 
                        {
                                total = parseFloat(total) + parseFloat(i[a].value);
                        }
                }
        }
        document.getElementById("total_extr").value = parseFloat(total);
}


function sumarImpr()
{
        var a,total, i;
        i = document.getElementsByTagName('INPUT');
        total=0;
        for(a=0; a<i.length; a++)
        {
                if (IsNumeric(i[a].value) && i[a].name.indexOf('_impr_Total')>0 )
                {
                        if (i[a].value.length>0) 
                        {
                                total = parseFloat(total) + parseFloat(i[a].value);
                        }
                }
        }
        document.getElementById("total_impr_hd").value = parseFloat(total);
}


 
</script>
<form name="form" action="extruder.php" method="post" >
<table width="60%" align="center" cellpadding="0" cellspacing="0" border="0" >
<tr>
  <td colspan="3" align="left">
    <table border="0" align="left" cellpadding="4" cellspacing="4" >
			<tr>
            	<td class="style7">Fecha: </td>
                <td><input type="text" name="fecha" value="<? echo date('d/m/Y');?>" id="fecha" class="style7" size="10" /></td>
           </tr>
           		<tr>
            	<td class="style7">Supervisor : </td>
                <td><input type="text" name="supervisor"  id="supervisor" class="style7" value="<?=$_SESSION['nombre']?>" readonly="readonly" /></td>
           </tr>
           <tr>
           	<td class="style7">Turno: </td>
            <td><input type="text" size="4" value="" name="turno" class="style7" /></td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
            </tr>
        </table>	</td>
</tr>
<br />
<tr>
  <td bgcolor="#0A4662" colspan="4" class="style6" align="center"><b>BOLSEO - NAVE 2</b></td>
</tr>
 <? 

 
 
$sql_lic= " SELECT * FROM maquina INNER JOIN oper_maquina ON maquina.id_maquina = oper_maquina.id_maquina WHERE area = 4  AND rol = '".$_SESSION['rol']."'  ";
$res_lic=mysql_query($sql_lic);
$cant_lic=mysql_num_rows($res_lic);
$cant=ceil($cant_lic/3);
$a=0;
while($dat_lic=mysql_fetch_assoc($res_lic))
{
$codigo[$a]=$dat_lic['numero'];
$id[$a] 	= $dat_lic['id_maquina'];
$a++;
}
$reg=0;
for($i=0;$i<$cant;$i++)
{
?>
<tr>
    <? for($x=1;$x<=1; $x++){?>
<td  width="185" align="center" valign="top"><br />
  <? if($reg<$cant_lic){ ?>
      <table align="center" width="565" border="1" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC">
   
  <script language="javascript">
function sumar<?=$codigo[$reg]?>()
{
        var a,total, i;
        i = document.getElementsByTagName('INPUT');
        total=0;
        for(a=0; a<i.length; a++)
        {
                if (IsNumeric(i[a].value) && i[a].name.indexOf('_extr<?=$codigo[$reg]?>')>0 )
                {
                        if (i[a].value.length>0) 
                        {
                                total = parseFloat(total) + parseFloat(i[a].value);
                        }
                }
        }
				
        document.getElementById("subtotal_<?=$codigo[$reg]?>_extr_Total").value = total;
}
</script> 
   <? 
    $qOperador = "SELECT * FROM oper_maquina WHERE id_maquina = $id[$reg] ";
    $rOperador = mysql_query($qOperador);
    $dOperador = mysql_fetch_assoc($rOperador);
	
	
	$qAsignacion = "SELECT id_operador, nombre FROM operadores WHERE id_operador = '".$dOperador['id_operador']."'";
	$rAsignacion = mysql_query($qAsignacion);
	$dAsignacion = mysql_fetch_array($rAsignacion);
	
    ?>
  <tr>
        <td width="11%" colspan="3" align="left" class="style5">Op. <?=$dAsignacion['nombre'] ?><input type="hidden"  name="id_operador" value="<?=$dAsignacion['id_operador'] ?>" /> </td>
  </tr>
  <tr>
    <td align="left" class="style7" rowspan="6"><p>Maq.<b>
      <?=$codigo[$reg]?>
    </b></p>
      <b></b></td>
	<td align="center" class="style7">O. Trabajo</td>
    <td width="15%" class="style7">Millares</td>
    <td width="15%" class="style7">Kilogramos</td>
    <td width="15%" class="style7">Des. Tira</td>
    <td width="15%" class="style7">Des. Troquel</td>
    <td width="15%" class="style7">Segundas</td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
    <td align="right"><input size="10" type="text" name="1_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
    <td align="right"><input size="10" type="text" name="1_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
    <td align="right"><input size="10" type="text" name="1_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
    <td align="right"><input size="10" type="text" name="1_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
    <td align="right"><input size="10" type="text" name="1_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="10" type="text" name="2_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
       <td align="right"><input size="10" type="text" name="2_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
       <td align="right"><input size="10" type="text" name="2_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
       <td align="right"><input size="10" type="text" name="2_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
       <td align="right"><input size="10" type="text" name="2_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td> 
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
    <td align="right"><input size="10" type="text" name="3_extr<?=$codigo[$reg]?>"  onchange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();" /></td>
    <td align="right"><input size="10" type="text" name="3_extr<?=$codigo[$reg]?>"  onchange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();" /></td>
    <td align="right"><input size="10" type="text" name="3_extr<?=$codigo[$reg]?>"  onchange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();" /></td>
    <td align="right"><input size="10" type="text" name="3_extr<?=$codigo[$reg]?>"  onchange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();" /></td>
    <td align="right"><input size="10" type="text" name="3_extr<?=$codigo[$reg]?>"  onchange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();" /></td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="10" type="text" name="4_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
       <td align="right"><input size="10" type="text" name="4_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
       <td align="right"><input size="10" type="text" name="4_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
       <td align="right"><input size="10" type="text" name="4_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
       <td align="right"><input size="10" type="text" name="4_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
  </tr>
  <tr>
    <td align="right" class="style7">Total:</td>
       <td align="right"><input size="10" type="text" name="subtotal_<?=$codigo[$reg]?>_extr_Total" id="subtotal_<?=$codigo[$reg]?>_extr_Total" onChange="sumarExtr();"  readonly="readonly"></td>
         <td align="right"><input size="10" type="text" name="subtotal_<?=$codigo[$reg]?>_extr_Total" id="subtotal_<?=$codigo[$reg]?>_extr_Total" onChange="sumarExtr();"  readonly="readonly"></td>
       <td align="right"><input size="10" type="text" name="subtotal_<?=$codigo[$reg]?>_extr_Total" id="subtotal_<?=$codigo[$reg]?>_extr_Total" onChange="sumarExtr();"  readonly="readonly"></td>
       <td align="right"><input size="10" type="text" name="subtotal_<?=$codigo[$reg]?>_extr_Total" id="subtotal_<?=$codigo[$reg]?>_extr_Total" onChange="sumarExtr();"  readonly="readonly"></td>
       <td align="right"><input size="10" type="text" name="subtotal_<?=$codigo[$reg]?>_extr_Total" id="subtotal_<?=$codigo[$reg]?>_extr_Total" onChange="sumarExtr();"  readonly="readonly"></td>
  </tr>

  <tr>
  	<td>
  </table>
      <br />
  <? $reg++;}?></td><? }?>
</tr>
  <? }?>
<tr>
  <td colspan="3">  
  <? 
$sql_impr= " SELECT * FROM maquina INNER JOIN oper_maquina ON maquina.id_maquina = oper_maquina.id_maquina WHERE area = 1  AND rol = '".$_SESSION['rol']."'  ";
$res_impr=mysql_query($sql_impr);
$cant_impr=mysql_num_rows($res_impr);
$cantidad=ceil($cant_impr/3);
$c=0;
while($dat_impr=mysql_fetch_assoc($res_impr))
{
$impr[$c]=$dat_impr['numero'];
$id_impr[$c]=$dat_impr['id_maquina'];
$c++;
}
$registro=0;
for($e=0;$e<$cantidad;$e++)
{
?>
  <? }?>
  <? 
$sql_limpr= " SELECT * FROM maquina INNER JOIN oper_maquina ON maquina.id_maquina = oper_maquina.id_maquina WHERE area = 3 AND rol = '".$_SESSION['rol']."' ";
$res_limpr=mysql_query($sql_limpr);
$cant_limpr=mysql_num_rows($res_limpr);
$cantidadLimp=ceil($cant_limpr/3);
$d=0;
while($dat_limpr=mysql_fetch_assoc($res_limpr))
{
$limpr[$d]=$dat_limpr['numero'];
$Id_limpr[$d]=$dat_limpr['id_maquina'];
$d++;
}
$registroLimp=0;
for($f=0;$f<$cantidadLimp;$f++)
{
?>
  <? }?>
<tr>
	<td colspan="3"><table><br />
    <tr>
    	<td align="right" class="style7">Contraseña:<br />
   	    <br /></td>
        <td width="194"><input type="text" name="password" id="password" /></td>
          <br />
          <br />
      <td width="214"><input type="submit" name="guardar" id="Guardar" value="Guardar" /></td>
     </tr> </table></td></tr>  <br />
        <br />
       <br />
	</table>
</form>