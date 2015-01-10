<?php
function pDebug($str)
{

	global $debug;
    if(gettype($str)=="array"){
       $x="";
       foreach ($str as $key => $value) {
       	  $x=$x."[".$key."]=>".$value.",";
       }
       $str=$x;
    }
	$fp = fopen('debug_log.txt','a');
   if($fp)
   {
     //echo "se creó con éxito";
     fwrite($fp,$str."\r\n");
    
     fclose($fp);
    }
     
		//echo $str . "\n";
}

?>