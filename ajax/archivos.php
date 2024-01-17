<?php
include("../apoyo.php");

/*echo "<br />accion = ".($accion = getPGVar("accion"));
echo "<br />url_retorno = ".($url_retorno = getPGVar("url_retorno"));
echo "<br />carpeta = ".($carpeta = getPGVar("carpeta"));
echo "<br />ruta = ".($ruta = getPGVar("ruta"));*/
$accion = getPGVar("accion");
$url_retorno = getPGVar("url_retorno");
$carpeta = getPGVar("carpeta");
$ruta = getPGVar("ruta");

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
		$target=str_replace("-","_",str_replace(".",".",str_replace(" ","_",$_FILES["archivo"]["name"])));
			move_uploaded_file($_FILES["archivo"]["tmp_name"],$ruta."/".basename($target));
		}
		$new_ruta=$ruta;
	}
}
header("location: ".$_SERVER['HTTP_REFERER']."&ruta_expl=$new_ruta");
?>
