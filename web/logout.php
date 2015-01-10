<?php
session_start();
if(!empty($_SESSION['id_admin']) || !empty($_SESSION['id_supervisor']))
{
	session_unset();
	session_destroy();

	header( "Location: index.php" );
}
?> 