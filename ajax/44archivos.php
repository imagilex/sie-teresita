<?php
include("../apoyo.php");

/*echo "<br />accion = ".($accion=PostString("accion").Get("accion"));
echo "<br />url_retorno = ".($url_retorno=PostString("url_retorno").Get("url_retorno"));
echo "<br />carpeta = ".($carpeta=PostString("carpeta").Get("carpeta"));
echo "<br />ruta = ".($ruta=PostString("ruta").Get("ruta"));*/
$accion=PostString("accion").Get("accion");
$url_retorno=PostString("url_retorno").Get("url_retorno");
$carpeta=PostString("carpeta").Get("carpeta");
$ruta=PostString("ruta").Get("ruta");

if($accion!="")
{
	if($accion=='crea_carpteta' && $carpeta!="" && $ruta!="")
	{
		mkdir($ruta."/".$carpeta);
		$new_ruta=$ruta."/".$carpeta;
	}
	else if($accion=="carga_archivo")
	{
		if(isset($_FILES["archivo"]["name"]) && $_FILES["archivo"]["name"]!="")
		{
			move_uploaded_file($_FILES["archivo"]["tmp_name"],$ruta."/".basename($_FILES["archivo"]["name"]));
		}
		$new_ruta=$ruta;
	}
}
header("location: ".$_SERVER['HTTP_REFERER']."&ruta_expl=$new_ruta");
?>