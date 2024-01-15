<?php

include("directorio.php");

function input($var)
{
	if(isset($_POST[$var]) && $_POST[$var]!="")
		return $_POST[$var];
	else if(isset($_GET[$var]) && $_GET[$var]!="")
		return $_GET[$var];
	return "";
}

$d=new directorio(input("directorio"));
echo $d->JSON_archivos("Alfabetico",false,input("pattern"));
?>