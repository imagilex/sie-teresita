<?php

session_start();

include "apoyo.php";

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]))
{
	header("location: index.php?noCache=".rand(0,32000));
	exit();
}

$ira=PostString("seccion");

if($ira=="")
	$ira="1";

if($ira=="1")
	$archivo=mysqli_fetch_array(consulta_directa($Con, "select valor from seccion where id_seccion='Lineamientos' and elemento='Mision'"));
else if($ira=="2")
	$archivo=mysqli_fetch_array(consulta_directa($Con, "select valor from seccion where id_seccion='Lineamientos' and elemento='Vision'"));
else if($ira=="3")
	$archivo=mysqli_fetch_array(consulta_directa($Con, "select valor from seccion where id_seccion='Lineamientos' and elemento='Valores'"));
else if($ira=="4")
	$archivo=mysqli_fetch_array(consulta_directa($Con, "select valor from seccion where id_seccion='Lineamientos' and elemento='Politica_Calidad'"));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita</title>
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
<table bgcolor='f2f2f2' width='100%' height='40' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
    <td width="35%"><table border='0' align='left' cellpadding='0' cellspacing='0'>
      <tr>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td><img src="Imagenes/menu/home.png" alt="Inicio" title="Inicio" onclick="javascript: /*window.close()*/location.href='entrada.php';" /></td>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td></td>
      </tr>
    </table></td>
    <td width="31%"><div align='center'><strong>Filosofía</strong></div></td>
    <td width="34%"><table border='0' align='right' cellpadding='0' cellspacing='0'>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><img src="Imagenes/Btn_salir.png" onclick="javascript: /*window.close()*/location.href='index.php';" /></td>
        </tr>
    </table></td>
  </tr>
</table>
<?php
//B_reportes();
?>
<div align="right">
<form name="menu1" action="quienes_somos.php" method="post">
		Secci&oacute;n:
		  <select name="seccion" id="seccion" onchange="javascript: document.menu1.submit();">
            <?php menu_items($_SESSION["tipo"],'0.4.5'); ?>
          </select>
		  <script language="javascript">
		  	document.menu1.seccion.value="<?php echo $ira; ?>";
		  </script>
</form>
</div>
<?php
BH_Ayuda('0.4',$ira);
?>
<table align="center" border="0" width="65%">
	<tr>
		<td align="center">
			<?php
				MostrarArchivo(dirname(__FILE__)."/Archivos_Secciones/".$archivo["valor"]);
			?>
		</td>
	</tr>
</table>
</body>
</html>
<?php
mysqli_close($Con);
?>
