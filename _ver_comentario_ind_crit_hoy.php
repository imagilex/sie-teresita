<?php

session_start();

include "apoyo.php";

$Con=Conectar();

$id_usuario = Get_Vars_Helper::getPGVar("id_usuario");
$indicador = Get_Vars_Helper::getPGVar("indicador");
$id_indicador_nivel = Get_Vars_Helper::getPGVar("nivel");
$anio = Get_Vars_Helper::getPGVar("anio");
$mes = Get_Vars_Helper::getPGVar("mes");
$fecha = Get_Vars_Helper::getPGVar("fecha");

$usr=mysqli_fetch_array(consulta_directa("select clave as `usuario` from usuario where clave='$id_usuario'"));
$usuario=$usr["usuario"];
$niv=mysqli_fetch_array(consulta_directa("select nivel from indicador_nivel where id_indicador_nivel='$id_indicador_nivel'"));
$nivel=$niv["nivel"];

if($fecha=="hoy")
{
    $comentario=@mysqli_fetch_array(consulta_directa("select comentario from indicador_comentario where anio='$anio' and mes='$mes' and indicador='$indicador' and usuario='$usuario' and nivel='$nivel' and fecha=curdate()"));
    consulta_directa("delete from indicador_comentario where anio='$anio' and mes='$mes' and indicador='$indicador' and usuario='$usuario' and nivel='$nivel' and fecha=curdate()");
}
else
{
    $comentario=@mysqli_fetch_array(consulta_directa("select comentario from indicador_comentario where anio='$anio' and mes='$mes' and indicador='$indicador' and usuario='$usuario' and nivel='$nivel' and fecha='$fecha'"));
    consulta_directa("delete from indicador_comentario where anio='$anio' and mes='$mes' and indicador='$indicador' and usuario='$usuario' and nivel='$nivel' and fecha='$fecha'");
}

echo NoAcute($comentario["comentario"]);
?>
