<link href="print.css" rel="stylesheet" type="text/css" media="print"> 
<? require_once('libs/funciones.php'); 

 $varia_x	=	array("-1002","12546");
 $varia_y	=	array("eaeaea 1","eaeaeaea 2");
 
 $varia_x2	=	array("2.156","2.3");
 $varia_y2	=	array("porciento 1","porciento 2");
?> 
<table border="0" align="center" height="100%">
	<tr>
    	<td valign="bottom"><?=grafica_barras($varia_x,$varia_y,'k');?></td>
        <td>&nbsp;</td>
        <td valign="bottom"><?=grafica_barras($varia_x2,$varia_y2,'%');?></td>
    </tr>
</table>