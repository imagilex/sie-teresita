<?php
session_start();


include("apoyo.php");

$Con = Conectar();
$proyecto = getPGVar("proyecto");
$ruta = addslashes(getPGVar("ruta"));
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita.com.mx</title>

</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	function Abrir(numero)
	{
		var tipo=$('tipo'+numero).value;
		var valor=$('valor'+numero).value;
		if(tipo=="carpeta")
		{
			location.href='_explo_archivos.php?ruta='+valor+'&proyecto=<?php echo $proyecto; ?>';
		}
		else if(tipo=="archivo")
		{

			var proyecto=parseInt('<?php echo $proyecto; ?>');
			if(isNaN(proyecto)) proyecto=0;
			valor=valor.substring(25);
			var arch_open='_para_descargas.php?archivo='+valor;
			archivo="archivo"+parseInt((Math.random()*1000));
			window.open(arch_open,archivo);


		}
	}
</script>
</head>

<body>
<strong><?php echo substr($ruta,strlen($Dir."Archivos_Planes/"),strlen($ruta)); ?></strong><hr />
<?php

if ($handle = @opendir(addslashes($ruta)))
{
	while (($file = readdir($handle)))
	{
		if($file!=".." && $file!="." && !is_file($file))
		{
			$archivos_fechas[]=$file;
		}
    }
	closedir($handle);
}
if(@count($archivos_fechas)>0)
{
	$x=0;
	foreach($archivos_fechas as $direct)
	{
		$x++;
		?>
		<div align="left" onmousemove="javascript: this.style.background='999999';" onmouseout="javascript: this.style.background='FFFFFF';" ondblclick="Abrir(<?php echo $x; ?>)">
		<?php
		if(is_dir("$ruta/$direct"))
		{
			?>
			<img src="Imagenes/carpeta.JPG" align="absmiddle" border="0" />
			<input type="hidden" name="tipo<?php echo $x; ?>" id="tipo<?php echo $x; ?>" value="carpeta" />
			<input type="hidden" name="valor<?php echo $x; ?>" id="valor<?php echo $x; ?>" value="<?php echo "$ruta/$direct"; ?>" />
			<?php
		}
		else if(is_file("$ruta/$direct"))
		{
			$inf=pathinfo("$ruta/$direct");
			if($inf["extension"]=="txt" || $inf["extension"]=="doc" || $inf["extension"]=="ppt" || $inf["extension"]=="rar" || $inf["extension"]=="tar" || $inf["extension"]=="pps" || $inf["extension"]=="html" || $inf["extension"]=="xls" || $inf["extension"]=="pdf" || $inf["extension"]=="mht" || $inf["extension"]=="zip" || $inf["extension"]=="odp" || $inf["extension"]=="htm" || $inf["extension"]=="odt" || $inf["extension"]=="pub")
			{
				?>
				<img src="Imagenes/extencion/<?php echo $inf["extension"]; ?>.bmp" align="absmiddle" border="0" />
				<input type="hidden" name="tipo<?php echo $x; ?>" id="tipo<?php echo $x; ?>" value="archivo" />
				<input type="hidden" name="valor<?php echo $x; ?>" id="valor<?php echo $x; ?>" value="<?php echo "$ruta/$direct"; ?>" />
				<?php
			}
			else
			{
				?>
				<img src="Imagenes/archivo.JPG" align="absmiddle" border="0" />
				<input type="hidden" name="tipo<?php echo $x; ?>" id="tipo<?php echo $x; ?>" value="archivo" />
				<input type="hidden" name="valor<?php echo $x; ?>" id="valor<?php echo $x; ?>" value="<?php echo "$ruta/$direct"; ?>" />
				<?php
			}
		}
		echo $direct;
		?>
		</div>
		<?php
	}
}

?>



</body>
</html>
