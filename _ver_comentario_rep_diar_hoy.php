<?php

session_start();

include "apoyo.php";

$Con=Conectar();

$id_usuario = getPGVar("id_usuario");
$id_reporte = getPGVar("id_reporte");
$fecha = getPGVar("fecha");
$fecha_reporte = getPGVar("fecha_reporte");

$usr=mysqli_fetch_array(consulta_directa("select clave as `usuario` from usuario where clave='$id_usuario'"));
$usuario=$usr["usuario"];

if($fecha=="hoy")
{
	$comentario=@mysqli_fetch_array(consulta_directa("select comentario from reporte_comentario where id_reporte='$id_reporte' and usuario='$usuario' fecha=curdate() and fecha_reporte='$fecha_reporte'"));
	consulta_directa("delete from reporte_comentario where id_reporte='$id_reporte' and usuario='$usuario' and fecha=curdate() and fecha_reporte='$fecha_reporte'");
}
else
{
	$comentario=@mysqli_fetch_array(consulta_directa("select comentario from reporte_comentario where id_reporte='$id_reporte' and usuario='$usuario' and fecha='$fecha' and fecha_reporte='$fecha_reporte'"));
	consulta_directa("delete from reporte_comentario where id_reporte='$id_reporte' and usuario='$usuario' and fecha='$fecha' and fecha_reporte='$fecha_reporte'");
}

echo NoAcute($comentario["comentario"]);
?>
