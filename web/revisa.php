<? 
@session_start();

//Esta es  una kinea  de prueba para github

require( 'class.Calendar.php' );
	$anho	=	( isset($_GET['anho'] ) && $_GET['anho'] != "" )?intval( $_GET['anho'] ):date("Y");
	$mes	=	( isset($_GET['mes'] ) && $_GET['mes'] != "" )?intval($_GET['mes']):date("m");
	$cal  = new Calendar ( $anho , $mes );
	$cal->setPrimerDomingo( 1 );
	$cal->setTableWidth('100');
	$cal->setDayNameFormat('%A');


?>

<script language="javascript" type="text/javascript">
var win=null;
function NewWindow(mypage,myname,w,h,scroll,pos){
   if(pos=="random"){
      LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;
	  TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;
	  }

   if(pos=="center"){
      LeftPosition=(screen.width)?(screen.width-w)/2:100;
	  TopPosition=(screen.height)?(screen.height-h)/2:100;
	  }

   else if((pos!="center" && pos!="random") || pos==null){
      LeftPosition=0;TopPosition=20
	  }

   settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
   win=window.open(mypage,myname,settings);

}

function validar_forma()
{
 if(document.calendar.mes.value < <?=date('m') ?> && document.calendar.anho.value <= <?=date('Y')?> ) 
  {
  alert("Su fecha debe ser mayor a la del dia de hoy");
  return false;
 } 
}
</script><style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<form action="admin_supervisor.php?seccion=4&accion=nuevo" method="get" name="calendar" id="calendar" onsubmit="javascript: return validar_forma()" >
<table width="697" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td valign="top"><br />
      <br /></td>
    </tr>
    <tr>
      <td width="642" align="center" valign="top">
      	<table width="588" border="0" align="center" cellpadding="2" cellspacing="2">
        <tr>
          <td width="580" class="style4" align="center" >
             		<span class="style7">Hoy es <?=ucfirst( date( "d/m/Y" ) )?></span>
                <? $meses = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"); ?>
                 	 <select name="mes" class="style5">
                 	   <?php for($i=0; $i < count($meses); $i++) {?>
                  		  <option value="<?php echo $i+1; ?>" <?php if(intval($mes) == ($i+1)) echo "selected=\"selected\""; ?>><?php echo $meses[$i]; ?></option>
						<? }?>
                    </select>
                <input type="hidden" name="accion" value="nuevo" />
                                  <select name="anho" class="style5">
                    <?php
						$temp = 0;
						for($i=1; $i <= 3; $i++)
						{
							$temp = date("Y", mktime(0, 0, 0, 0, 0, date("Y")+$i));
							if($temp == date("Y")){
							?>
											<option value="<?php echo date("Y"); ?>" selected="selected"><?php echo date("Y"); ?></option>
											<?php
							} else {
							?>
                    <option value="<?php echo $temp; ?>"><?php echo $temp; ?></option>
                    <?php
	}
}
?>
                </select>
                  <input type="hidden" name="seccion" value="4" />
  
                  
                <input name="submit" type="submit" class="style1" value="IR" />
                <br>
                <br>
                <div align="center"><?php echo $cal->display(); ?></div>           
          </td>
        </tr>       
    
</table> </td></tr></table>
  
</form>