<?php
session_start();

header("Expires: Mon, 22 Sep 1997 09:00:00 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");

include("apoyo.php");

$Con=Conectar();
$proyecto = getPGVar("proyecto");
$raiz = getPGVar("raiz");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MANAIZ</title>
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
<script language="javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" src="u_yui/container_core.js"></script>
<script language="javascript" src="u_yui/menu.js"></script>
<script type="text/javascript">YAHOO.util.Event.onContentReady("barra_menu", function () {var oMenuBar = new YAHOO.widget.MenuBar("barra_menu", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});YAHOO.util.Event.onContentReady("menu_opciones", function () {var oMenuBar = new YAHOO.widget.MenuBar("menu_opciones", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	var llevar=false;
	function GoTo(ruta)
	{
		window.parent.frames["frame_archivos"].location.href='_explo_archivos.php?proyecto=<?php echo $proyecto; ?>&ruta='+ruta;
		return false;
	}
</script>
</head>

<body>
<strong>Carpetas:</strong><br />
<?php
function MuestraDirs($ruta, $directorio,$espacio="")
{

	?>
	<div align="left" style="padding-left:20px;" ondblclick="return GoTo('<?php echo $ruta.(($espacio=="")?("/"):("")).$directorio; ?>');" onmousemove="javascript: this.style.background='999999';" onmouseout="javascript: this.style.background='FFFFFF';"><?php echo $espacio; ?><img src="Imagenes/carpeta.JPG" border="0" align="middle" /> <?php echo (($directorio!="")?($directorio):($ruta)); ?></div>
		<?php
		$raiz=str_replace("\\","/",$ruta)."/".$directorio."/";
		if ($handle = @opendir($raiz))
		{
			while (($file = readdir($handle)))
			{
				if($file!=".." && $file!="." && is_file($file)===false)
				{
					$information=pathinfo($file);
					if(@$information["extension"]=="")
						$archivos_fechas[]=$file;
				}
		    }
			closedir($handle);
		}
		if(@count($archivos_fechas)>0)
		{
			foreach($archivos_fechas as $direct)
			{
				MuestraDirs($raiz, $direct,$espacio.".....");
			}
		}
		?>
	<?php
}

	MuestraDirs($raiz, "");
?>
</body>
</html>
