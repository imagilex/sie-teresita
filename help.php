<?php
include("apoyo.php");
$Con=Conectar();

$title="";
if(getGetVar("file")!="")
{
	$titul=@mysqli_fetch_array(consulta_directa("select descripcion from ayuda where archivo='".getGetVar("file")."'"));
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

if(getGetVar("file")!="")
{
	?>
	<table border="0" width="65%" align="center"><tr><td><?php

	$archivo = getGetVar("file");

	$info = pathinfo($Dir."/Ayuda/".$archivo);


	if(strtoupper($info["extension"])=="TXT")
	{
		MostrarArchivo($Dir."/Ayuda/".$archivo);
	}
	else if(strtoupper($info["extension"])=="JPG" || strtoupper($info["extension"])=="PNG" || strtoupper($info["extension"])=="BMP" || strtoupper($info["extension"])=="GIF")
	{
		?>
		<img src="Ayuda/<?php echo $archivo; ?>" border="0" />
		<?php
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
