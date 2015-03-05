<?
session_start();
include "conectar.php";

    $tipo_usuario=0;
	
	if($tipo_usuario==0){
	$sSQL="SELECT * FROM supervisor WHERE usuario='".@$_SESSION['user']."' AND password='".@$_SESSION['pass']."'";
    $resultado=mysql_query($sSQL);
	if($dato=mysql_fetch_assoc($resultado)){
	
	 $tipo_usuario=1;
	 $_SESSION['id_supervisor'] = $dato['id_supervisor'];
	 $_SESSION['rol'] =  $dato['rol'];
	 $_SESSION['area'] = $dato['area'];
	 $_SESSION['area2'] = $dato['area2'];
	 $_SESSION['area3'] = $dato['area3'];
	 $_SESSION['area4'] = $dato['area4'];
	 $_SESSION['nombre'] = $dato['nombre'];
	//	 header("Location:admin_supervisor.php?id=".$_SESSION['id_supervisor'].""); 

	 }
	}
	
	if($tipo_usuario==0){
	 $sSQL2="SELECT * FROM vendedores WHERE user='".@$_SESSION['user']."' AND pass='".@$_SESSION['pass']."'";
    $resultado2=mysql_query($sSQL2);
	if($dato2=mysql_fetch_assoc($resultado2)){
	
	 $tipo_usuario=1;
	 $_SESSION['id_vendedor'] = $dato2['id_vendedor'];
 	 $_SESSION['nombre'] = $dato2['nombre'];
	// header("Location:admin_vendedores.php?id=".$_SESSION['id_vendedor'].""); 
	 }
	}
	

	if($tipo_usuario==0){
	$sSQL3="SELECT * FROM administrador WHERE user='".@$_SESSION['user']."' AND pass='".@$_SESSION['pass']."'";
    $resultado3=mysql_query($sSQL3);
	if($dato3=mysql_fetch_row($resultado3)){
	
	 $tipo_usuario=2;
	 $_SESSION['id_admin'] = $dato3[0];
	 }
	}

	if($tipo_usuario==0){
		@session_unset();
		@session_destroy();
		header("Location:index.php?msg=1"); 
		}
	
?>