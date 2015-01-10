 <html> 
  <head> 
  <title>Reading input from the form</title> 
  </head> 
  <body> 
  <?php 
  define("ADAY",(60*60*24));
  $fecha = getdate();
   //echo "Me llego: ".$_GET['valor'];
   echo "La fecha de hoy es: ".date("d/m/Y")."<br>";
   $ayer = mktime(0,0,0,$fecha[mon],$fecha[mday],$fecha[year]) -  ADAY;
   echo "Ayer de ayer fué: ".date("d/m/Y",$ayer);
   
   $desde =date("d/m/Y",$ayer);
   echo "<br> nueva: ".$desde;
  ?> 
 </body> 
 </html>

