<?php

session_start();

include "apoyo.php";

$Con=Conectar();

$id_usuario=Get("id_usuario").PostString("id_usuario");
$id_reporte=Get("id_reporte").PostString("id_reporte");
$fecha=Get("fecha").PostString("fecha");
$fecha_reporte=Get("fecha_reporte").PostString("fecha_reporte");

$usr=mysql_fetch_array(mysql_query("select clave as `usuario` from usuario where clave='$id_usuario'"));
$usuario=$usr["usuario"];

if($fecha=="hoy")
{
	$comentario=@mysql_fetch_array(mysql_query("select comentario from reporte_comentario where id_reporte='$id_reporte' and usuario='$usuario' fecha=curdate() and fecha_reporte='$fecha_reporte'"));
	mysql_query("delete from reporte_comentario where id_reporte='$id_reporte' and usuario='$usuario' and fecha=curdate() and fecha_reporte='$fecha_reporte'");
}
else
{
	$comentario=@mysql_fetch_array(mysql_query("select comentario from reporte_comentario where id_reporte='$id_reporte' and usuario='$usuario' and fecha='$fecha' and fecha_reporte='$fecha_reporte'"));
	mysql_query("delete from reporte_comentario where id_reporte='$id_reporte' and usuario='$usuario' and fecha='$fecha' and fecha_reporte='$fecha_reporte'");
}

echo NoAcute($comentario["comentario"]);

mysql_close($Con);

?>