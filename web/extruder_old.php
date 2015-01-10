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
        </table>
	</td>
</tr>
<br />
<tr><td bgcolor="#0A4662" colspan="4" class="style6" align="center"><b>EXTRUDER DE ALTA - NAVE 2</b></td></tr>
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
    <? for($x=1;$x<=3; $x++){?>
<td  width="185" align="center" valign="top"><br />
  <? if($reg<$cant_lic){ ?>
      <table align="center" width="183" border="1" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC">
  <tr class="style7">
    <td width="36%" align="left" class="style7">Extr No. <b><?=$codigo[$reg]?></b></td>
    <? 
    $qOperador = "SELECT * FROM oper_maquina WHERE id_maquina = $id[$reg] ";
    $rOperador = mysql_query($qOperador);
    $dOperador = mysql_fetch_assoc($rOperador);
	
	
	$qAsignacion = "SELECT id_operador, nombre FROM operadores WHERE id_operador = '".$dOperador['id_operador']."'";
	$rAsignacion = mysql_query($qAsignacion);
	$dAsignacion = mysql_fetch_array($rAsignacion);
	
    ?>
      <td width="64%" align="left" class="style5">Op. <?=$dAsignacion['nombre'] ?><input type="hidden"  name="id_operador" value="<?=$dAsignacion['id_operador'] ?>" /> </td>
  </tr>
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
  <tr>
    <td align="left" class="style7">O. T.</td>
	<td align="center" class="style7">Kilogramos</td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="1_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="2_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="3_extr<?=$codigo[$reg]?>"  onchange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();" /></td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="4_extr<?=$codigo[$reg]?>"  onChange="javascript: sumar<?=$codigo[$reg]?>();  sumarExtr();"></td>
  </tr>
  <tr>
    <td align="right" class="style7">Total:</td>
       <td align="right"><input size="14" type="text" name="subtotal_<?=$codigo[$reg]?>_extr_Total" id="subtotal_<?=$codigo[$reg]?>_extr_Total" onchange="sumarExtr();"  readonly="readonly"></td>
  </tr>
  
  </table>
      <br />
  <? $reg++;}?></td><? }?>
</tr>
  <? }?>
<tr>
  <td colspan="3"><table cellpadding="0" cellspacing="0" border="1" align="center" >
    <tr><td width="547" class="style7">
    <table width="547" cellpadding="4" cellspacing="4" >
      <tr>
        <td width="157" class="style7">OBSERVACIONES:</td>
        <td width="587" class="style7"><textarea class="style5" name="observaciones_extr" id="observaciones_extr" rows="4" cols="40"></textarea></td>
      </tr>
      <tr>
        <td class="style7">PRODUCCION HD:</td>
        <td class="style7"><input name="total_extr" onclick="sumartodo();" readonly type="text" size="20" id="total_extr"  value=""/> Kilogramos</td>
      </tr>
      <tr>
        <td class="style7">DESPERDICIO TIRA:</td>
        <td class="style7"><input name="tira"  type="text" size="20" id="total" /> Kilogramos</td>
      </tr>
      <tr>
        <td class="style7">DESPERDICIO DURO:</td>
        <td class="style7"><input name="duro" type="text" size="20" id="total" /> Kilogramos</td>
   	</tr>
    </table></td></tr></table>
 
      <br />
      <br />
<tr>
  <td bgcolor="#0A4662" colspan="4" class="style6" align="center"><b>IMPRESION - NAVE 2</b></td>
 </tr>
  <? 
$sql_impr= " SELECT * FROM maquina INNER JOIN oper_maquina ON maquina.id_maquina = oper_maquina.id_maquina WHERE area = 2  AND rol = '".$_SESSION['rol']."'  ";
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
<tr>
    <? for($y=1;$y<=3; $y++){?>
    <td width="185" align="center" valign="top"><br />
      <? if($registro<$cant_impr){ ?>
      <table align="center" width="180" border="1" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC">
  <tr class="style7">
    <td width="36%" align="left" class="style7">Impr No. <b><?=$impr[$registro]?></b>
    <? 
    $qOperadorImpr = "SELECT * FROM oper_maquina WHERE id_maquina = $id_impr[$registro] ";
    $rOperadorImpr = mysql_query($qOperadorImpr);
    $dOperadorImpr = mysql_fetch_assoc($rOperadorImpr);
	
	
	$qAsignacionImpr = "SELECT id_operador, nombre FROM operadores WHERE id_operador = '".$dOperadorImpr['id_operador']."'";
	$rAsignacionImpr = mysql_query($qAsignacionImpr);
	$dAsignacionImpr = mysql_fetch_array($rAsignacionImpr);
	
    ?>
      <td width="64%" align="left" class="style5">Op. <?=$dAsignacionImpr['nombre'] ?><input type="hidden"  name="id_operador" value="<?=$dAsignacionImpr['id_operador'] ?>" /> </td>
  </tr>
  <script language="javascript">
function sumarImpr2<?=$impr[$registro]?>()
{
        var a,total, i;
        i = document.getElementsByTagName('INPUT');
        total=0;
        for(a=0; a<i.length; a++)
        {
                if (IsNumeric(i[a].value) && i[a].name.indexOf('_impr<?=$impr[$registro]?>')>0 )
                {
                        if (i[a].value.length>0) 
                        {
                                total = parseFloat(total) + parseFloat(i[a].value);
                        }
                }
        }
				
        document.getElementById("subtotal_<?=$impr[$registro]?>_impr_Total").value = total;
}
</script> 
  <tr>
    
    <td align="left" class="style7">O. T.</td>
  
	<td align="center" class="style7">Kilogramos</td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="1_impr<?=$impr[$registro]?>" id="impr_<?=$impr[$registro]?>" onChange="javascript: sumarImpr2<?=$impr[$registro]?>();  sumarImpr();"></td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="2_impr<?=$impr[$registro]?>" onChange="javascript: sumarImpr2<?=$impr[$registro]?>();  sumarImpr();"></td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="3_impr<?=$impr[$registro]?>" onchange="javascript: sumarImpr2<?=$impr[$registro]?>();  sumarImpr();" />
  </td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="4_impr<?=$impr[$registro]?>" onChange="javascript: sumarImpr2<?=$impr[$registro]?>();  sumarImpr();"></td>
  </tr>
  <tr>
    <td align="right" class="style7">Total:</td>
       <td align="right"><input size="14" type="text" name="subtotal_<?=$impr[$registro]?>_impr_Total" id="subtotal_<?=$impr[$registro]?>_impr_Total" onchange="sumarImpr();"  readonly="readonly"></td>
  </tr>
      </table>
      <br />
  <? $registro++;}?>    </td><? }?>
</tr>
  <? }?>

    
    
    
    <tr>
  <td bgcolor="#0A4662" colspan="4" class="style6" align="center"><b>LINEAS DE IMPRESION - NAVE 2</b></td>
 </tr>
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
<tr>
    <? for($z=1;$z<=3; $z++){?>
    <td width="185" align="center" valign="top"><br />
      <? if($registroLimp<$cant_limpr){ ?>
      <table align="center" width="180" border="1" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC">
  <tr class="style7">
      <td width="36%" align="left" class="style7">Impr No. <b><?=$limpr[$registroLimp]?></b>

    <? 
    $qOperadorLimp = "SELECT * FROM oper_maquina WHERE id_maquina = $Id_limpr[$registroLimp] ";
    $rOperadorLimp = mysql_query($qOperadorLimp);
    $dOperadorLimp = mysql_fetch_assoc($rOperadorLimp);
	
	
	$qAsignacionLimp = "SELECT id_operador, nombre FROM operadores WHERE id_operador = '".$dOperadorLimp['id_operador']."'";
	$rAsignacionLimp = mysql_query($qAsignacionLimp);
	$dAsignacionLimp = mysql_fetch_array($rAsignacionLimp);
	
    ?>
      <td width="64%" align="left" class="style5">Op. <?=$dAsignacionLimp['nombre'] ?><input type="hidden"  name="id_operador" value="<?=$dAsignacionLimp['id_operador'] ?>" /> </td>
  </tr>
  <script language="javascript">
function sumarLimpr2<?=$limpr[$registroLimp]?>()
{
        var a,total, i;
        i = document.getElementsByTagName('INPUT');
        total=0;
        for(a=0; a<i.length; a++)
        {
                if (IsNumeric(i[a].value) && i[a].name.indexOf('_limpr<?=$limpr[$registroLimp]?>')>0 )
                {
                        if (i[a].value.length>0) 
                        {
                                total = parseFloat(total) + parseFloat(i[a].value);
                        }
                }
        }
				
        document.getElementById("subtotal_<?=$limpr[$registroLimp]?>_impr_Total").value = total;
}
</script> 
  <tr>
    
    <td align="left" class="style7">O. T.</td>
  
	<td align="center" class="style7">Kilogramos</td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="1_limpr<?=$limpr[$registroLimp]?>"  onChange="javascript: sumarLimpr2<?=$limpr[$registroLimp]?>();  sumarImpr();"></td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="2_limpr<?=$limpr[$registroLimp]?>" onChange="javascript: sumarLimpr2<?=$limpr[$registroLimp]?>();  sumarImpr();"></td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="3_limpr<?=$limpr[$registroLimp]?>" onchange="javascript: sumarLimpr2<?=$limpr[$registroLimp]?>();  sumarImpr();" />
  </td>
  </tr>
  <tr>
    <td align="left"><input type="text" name="oreden" size="7"></td>
       <td align="right"><input size="14" type="text" name="4_limpr<?=$limpr[$registroLimp]?>" onChange="javascript: sumarLimpr2<?=$limpr[$registroLimp]?>();  sumarImpr();"></td>
  </tr>
  <tr>
    <td align="right" class="style7">Total:</td>
       <td align="right"><input size="14" type="text" name="subtotal_<?=$limpr[$registroLimp]?>_impr_Total" id="subtotal_<?=$limpr[$registroLimp]?>_impr_Total" onchange="sumarImpr();"  readonly="readonly"></td>
  </tr>
      </table>
      <br />
  <? $registroLimp++;}?>    </td><? }?>
</tr>
  <? }?>
<tr>
	<td colspan="3"><table cellpadding="0" cellspacing="0" border="1" align="center" >
    <tr><td width="547" class="style7"><table width="547" cellpadding="4" cellspacing="4" >
      <tr>
        <td width="157" class="style7">OBSERVACIONES:</td>
        <td width="587" class="style7"><textarea class="style5" name="observaciones_impr" id="observaciones_impr_tira" rows="4" cols="40"></textarea></td>
      </tr>
      <tr>
        <td class="style7">TOTAL DE PRODUCCION HD:</td>
        <td class="style7"><input name="total_impr_hd" onclick="sumartodo();" readonly type="text" size="20" id="total_impr_hd"  value=""/> Kilogramos</td>
      </tr>
      <tr>
        <td class="style7">TOTAL DE PRODUCCION BD:</td>
        <td class="style7"><input name="total_impr_br" onclick="sumartodo();" readonly type="text" size="20" id="total_impr_bd"  value=""/> Kilogramos</td>
      </tr>
      <tr>
        <td class="style7">DESPERDICIO HD:</td>
        <td class="style7"><input name="tira"  type="text" size="20" id="total_impr_tira" /> Kilogramos</td>
      </tr>
      <tr>
        <td class="style7">DESPERDICIO BD:</td>
        <td class="style7"><input name="duro" type="text" size="20" id="total_impr_duro" /> Kilogramos</td>
   	</tr>
    </table></td></tr></table>
      <br />
 	<tr>
    	<td align="right" class="style7">Contraseña:<br />
   	    <br /></td>
        <td width="194"><input type="text" name="password" id="password" /></td>
          <br />
          <br />
      <td width="214"><input type="submit" name="guardar" id="Guardar" value="Guardar" /></td>
     </tr>   <br />
        <br />
       <br />
	</table>
 </form>