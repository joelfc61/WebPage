<?

$qQuery="select eg.fecha,eg.turno,op.id_orden_produccion,op.total,op.k_h,rme.id_resumen_maquina_ex,rme.subtotal from entrada_general  eg 
inner join orden_produccion op on eg.id_entrada_general= op.id_entrada_general 
inner join resumen_maquina_ex rme on op.id_orden_produccion=rme.id_orden_produccion 
where  year(eg.fecha)=2014 and month(eg.fecha)=10 order by rme.id_resumen_maquina_ex ";

include "conectar.php";

//Limitar que solo lo realice para un rango de fechas


  function redondeado($numero, $decimales) 
      {
        $factor = pow(10, $decimales);
         return (round($numero*$factor)/$factor); 
      }
$rQuery= mysql_query($qQuery);
$cuantosOP=0;
$cuantosST=0;
while($row = mysql_fetch_assoc($rQuery)){
 
   switch($row['turno']){
     case 1: $horasTurno=8; break;
     case 2: $horasTurno=7; break;
     case 3: $horasTurno=9; break;
     default: $horasTurno=1;
    }
  
 //if($cuantosOP==0){ 
    $prodxhora = redondeado($row['total'],2) /$horasTurno;

    //si lo que tiene es diferente...sustituye
    // echo "Row:".$row[k_h] ." prodxhora: ".$prodxhora."<br>";

   // if(redondeado($row['k_h'],2) != redondeado($prodxhora,2))
    //{
      $qActualiza = "update orden_produccion set k_h = ".$prodxhora ." where id_orden_produccion='". $row['id_orden_produccion']."'";
      $rActualiza = mysql_query($qActualiza);     
      //echo $qActualiza."<br>";
    //echo "Total: ". $row['total']." ,HrsTurno: ".$horasTurno." ,Kg/hr: ".$row['k_h']. " ,ProdHora: ".redondeado($prodxhora,2) ."<br>";
      $cuantosOP++;    
    //}

     //echo "Total: ". $row['total']." ,HrsTurno: ".$horasTurno." ,Kg/hr: ".$row['k_h']. " ,ProdHora: ".redondeado($prodxhora,3) ."<br>";
   //}

    $qSumaOT = "select sum(kilogramos) as sumatoria from detalle_resumen_maquina_ex  where id_resumen_maquina_ex = '". $row['id_resumen_maquina_ex']."'";
    $rSumaOT = mysql_query($qSumaOT); 
    $dSumaOT = mysql_fetch_assoc($rSumaOT);
    //echo $qSumaOT; ." row:".$row[subtotal]."dSumaOT:".$dSumaOT[sumatoria]."<br>";
     echo "row: " .$row['subtotal']."  SumaOT: " .$dSumaOT[sumatoria] ."<br>";  
    if($row['subtotal'] != $dSumaOT[sumatoria] && $dSumaOT[sumatoria] >0 ){
      //actualiza resumen_maquina_ex set subtotal= ".$dSumaOT ." where id_resumen_maquina_ex = ".$row['id_resumen_maquina_ex'];
      $qActualST ="update resumen_maquina_ex set subtotal= ".$dSumaOT[sumatoria]." where  id_resumen_maquina_ex = '".$row['id_resumen_maquina_ex']."'";
      $res =mysql_query($qActualST);
       echo $qActualST."<br>"; 
     $cuantosST++;            
    }
     
     //echo "<br>Total: ".$row['total']." HorasTurno:".$horasTurno." KH: ".$row[k_h]." ProdxHora".$prodxhora."<br>";
}    //fin del while

 echo "Se tuvieron ".$cuantosOP." Actualizaciones de hg/hr";// OP  y ".$cuantosST." Subtotales<br>"; 

?>