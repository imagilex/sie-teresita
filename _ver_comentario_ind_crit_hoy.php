<?php

session_start();

include "apoyo.php";

$Con=Conectar();

$id_usuario=Get("id_usuario").PostString("id_usuario");
$indicador=Get("indicador").PostString("indicador");
$id_indicador_nivel=Get("nivel").PostString("nivel");
$anio=Get("anio").PostString("anio");
$mes=Get("mes").PostString("mes");
$fecha=Get("fecha").PostString("fecha");

$usr=mysql_fetch_array(mysql_query("select clave as `usuario` from usuario where clave='$id_usuario'"));
$usuario=$usr["usuario"];
$niv=mysql_fetch_array(mysql_query("select nivel from indicador_nivel where id_indicador_nivel='$id_indicador_nivel'"));
$nivel=$niv["nivel"];

if($fecha=="hoy")
{
	$comentario=@mysql_fetch_array(mysql_query("select comentario from indicador_comentario where anio='$anio' and mes='$mes' and indicador='$indicador' and usuario='$usuario' and nivel='$nivel' and fecha=curdate()"));
	mysql_query("delete from indicador_comentario where anio='$anio' and mes='$mes' and indicador='$indicador' and usuario='$usuario' and nivel='$nivel' and fecha=curdate()");
}
else
{
	$comentario=@mysql_fetch_array(mysql_query("select comentario from indicador_comentario where anio='$anio' and mes='$mes' and indicador='$indicador' and usuario='$usuario' and nivel='$nivel' and fecha='$fecha'"));
	mysql_query("delete from indicador_comentario where anio='$anio' and mes='$mes' and indicador='$indicador' and usuario='$usuario' and nivel='$nivel' and fecha='$fecha'");
}

echo NoAcute($comentario["comentario"]);

mysql_close($Con);

?>