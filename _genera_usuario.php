<?php

include("apoyo.php");

$Con=Conectar();

$nombre=PostString("nombre");
$apaterno=PostString("apaterno");
$usuario=substr($nombre,0,1).$apaterno;
$usuario=str_replace("�","a",$usuario);$usuario=str_replace("�","e",$usuario);$usuario=str_replace("�","i",$usuario);$usuario=str_replace("�","o",$usuario);
$usuario=str_replace("�","u",$usuario);$usuario=str_replace("�","a",$usuario);$usuario=str_replace("�","e",$usuario);$usuario=str_replace("�","i",$usuario);
$usuario=str_replace("�","o",$usuario);$usuario=str_replace("�","u",$usuario);$usuario=str_replace("�","a",$usuario);$usuario=str_replace("�","e",$usuario);
$usuario=str_replace("�","i",$usuario);$usuario=str_replace("�","o",$usuario);$usuario=str_replace("�","u",$usuario);$usuario=str_replace("�","a",$usuario);
$usuario=str_replace("�","e",$usuario);$usuario=str_replace("�","i",$usuario);$usuario=str_replace("�","o",$usuario);$usuario=str_replace("�","u",$usuario);
$usuario=str_replace("�","ni",$usuario);$usuario=str_replace(" ","",$usuario);
$usuario=strtolower($usuario);

$num=mysql_fetch_array(mysql_query("select count(*) as n from usuario where usuario.usuario like '$usuario%'"));
if($num["n"]>0)
	$usuario=$usuario.($num["n"]+1);
echo $usuario;

mysql_close($Con);
?>