<?php

include("apoyo.php");

$Con=Conectar();

$nombre = getPostVar("nombre");
$apaterno = getPostVar("apaterno");
$usuario=substr($nombre,0,1).$apaterno;
$usuario=str_replace("á","a",$usuario);$usuario=str_replace("é","e",$usuario);$usuario=str_replace("í","i",$usuario);$usuario=str_replace("ó","o",$usuario);
$usuario=str_replace("ú","u",$usuario);$usuario=str_replace("Á","a",$usuario);$usuario=str_replace("É","e",$usuario);$usuario=str_replace("Í","i",$usuario);
$usuario=str_replace("Ó","o",$usuario);$usuario=str_replace("Ú","u",$usuario);$usuario=str_replace("ä","a",$usuario);$usuario=str_replace("ë","e",$usuario);
$usuario=str_replace("ï","i",$usuario);$usuario=str_replace("ö","o",$usuario);$usuario=str_replace("ü","u",$usuario);$usuario=str_replace("Ä","a",$usuario);
$usuario=str_replace("Ë","e",$usuario);$usuario=str_replace("Ï","i",$usuario);$usuario=str_replace("Ö","o",$usuario);$usuario=str_replace("Ü","u",$usuario);
$usuario=str_replace("ñ","ni",$usuario);$usuario=str_replace(" ","",$usuario);
$usuario=strtolower($usuario);

$num=mysqli_fetch_array(consulta_directa("select count(*) as n from usuario where usuario.usuario like '$usuario%'"));
if($num["n"]>0)
	$usuario=$usuario.($num["n"]+1);
echo $usuario;
?>
