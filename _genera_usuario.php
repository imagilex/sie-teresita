<?php

include("apoyo.php");

$Con=Conectar();

$nombre=PostString("nombre");
$apaterno=PostString("apaterno");
$usuario=substr($nombre,0,1).$apaterno;
$usuario=str_replace("á","a",$usuario);$usuario=str_replace("é","e",$usuario);$usuario=str_replace("í","i",$usuario);$usuario=str_replace("ó","o",$usuario);
$usuario=str_replace("ú","u",$usuario);$usuario=str_replace("à","a",$usuario);$usuario=str_replace("è","e",$usuario);$usuario=str_replace("ì","i",$usuario);
$usuario=str_replace("ò","o",$usuario);$usuario=str_replace("ù","u",$usuario);$usuario=str_replace("ä","a",$usuario);$usuario=str_replace("ë","e",$usuario);
$usuario=str_replace("ï","i",$usuario);$usuario=str_replace("ö","o",$usuario);$usuario=str_replace("ü","u",$usuario);$usuario=str_replace("â","a",$usuario);
$usuario=str_replace("ê","e",$usuario);$usuario=str_replace("î","i",$usuario);$usuario=str_replace("ô","o",$usuario);$usuario=str_replace("û","u",$usuario);
$usuario=str_replace("ñ","ni",$usuario);$usuario=str_replace(" ","",$usuario);
$usuario=strtolower($usuario);

$num=mysql_fetch_array(mysql_query("select count(*) as n from usuario where usuario.usuario like '$usuario%'"));
if($num["n"]>0)
	$usuario=$usuario.($num["n"]+1);
echo $usuario;

mysql_close($Con);
?>