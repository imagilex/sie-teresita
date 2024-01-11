<?php 
include("apoyo.php"); 
$Con=Conectar();

$title="";
if(Get("file")!="")
{
	$titul=@mysql_fetch_array(mysql_query("select descripcion from ayuda where archivo='".Get("file")."'"));
	if($titul["descripcion"]!="") $title=$titul["descripcion"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo (($title!="")?($title):("Teresita.com.mx")); ?></title>
<link href="style/Style_01.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php

BarraHerramientas();

if(Get("file")!="")
{
	?>
	<table border="0" width="65%" align="center"><tr><td><?php 
	
	$archivo=Get("file");
	
	$info=pathinfo($Dir."/Ayuda/".$archivo);
	
	
	if(strtoupper($info["extension"])=="TXT")
	{
		MostrarArchivo($Dir."/Ayuda/".$archivo); 
	}
	else if(strtoupper($info["extension"])=="JPG" || strtoupper($info["extension"])=="PNG" || strtoupper($info["extension"])=="BMP" || strtoupper($info["extension"])=="GIF")
	{
		?>
		<img src="Ayuda/<?php echo $archivo; ?>" border="0" />
		<?
	}
	else
	{
		echo "NO SE RECONOCE EL TIPO DE ARCHIVO";
	}	
		
	?></td></tr></table>
	<?php
}

?>
</body>
</html>
<?php
mysql_close($Con);
?>