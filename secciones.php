<?php

session_start();

include "apoyo.php";

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]) )
{
	header("location: index.php?noCache=".rand(0,32000));
	exit();
}

$secc=PostString("secc");

if($secc=="1") //¿Como usar un mapa?
{
	header("location: usar_mapa.php?noCache=".rand(0,32000));
	exit();
}
else if($secc=="2") //Indicadores Críticos
{
	header("location: ind_crit.php?noCache=".rand(0,32000));
	exit();
}
else if($secc=="3") // Planes det trabajo
{
	header("location: plan_trab.php?noCache=".rand(0,32000));
	exit();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
</head>

<body>
<?php

BarraHerramientas();

?>
<table align="right">
	<tr>
		<td>
			Secci&oacute;n:
		</td>
		<td>
			<form name="Seccion" action="secciones.php" method="post" style="padding:0px; margin:0px;">
			<select name="secc" onchange="javascript: document.Seccion.submit();"><option value=""></option><?php echo CboCG("mnu_Secciones"); ?></select>
			</form>
		</td>
	</tr>
</table>
<script language="javascript">
	document.Administracion.ira_adm.value="53";
	document.Seccion.secc.value="";
</script>
<?php
BH_Ayuda('','');
?>
</body>
</html>
<?php

mysqli_close($Con);

?>