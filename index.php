<?php

session_start();

if(isset($_SESSION["tipo"])) unset($_SESSION["tipo"]);
if(isset($_SESSION["id_usr"])) unset($_SESSION["id_usr"]);
if(isset($_SESSION["id_persona_usr"])) unset($_SESSION["id_persona_usr"]);

include "apoyo.php";

$ira=PostString("ira").Get("ira");;

$Con=Conectar();

var_dump($Con);

if($regs=mysqli_query($Con, "select valor from seccion where id_seccion='Principal' and elemento='Principal'"))
	{
		$registro=$regs->fetch_array();
		$principal=$registro["valor"];
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita</title>
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
<link rel="stylesheet" href="libreria/layout_p.css" />
<script language="javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" src="u_yui/container_core.js"></script>
<script language="javascript" src="u_yui/menu.js"></script>
<script type="text/javascript">YAHOO.util.Event.onContentReady("barra_menu", function () {var oMenuBar = new YAHOO.widget.MenuBar("barra_menu", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});YAHOO.util.Event.onContentReady("menu_opciones", function () {var oMenuBar = new YAHOO.widget.MenuBar("menu_opciones", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.frmInicio.usr.value!="" && document.frmInicio.pass.value!="") return true;
		else
		{
			if(document.frmInicio.usr.value=="" && document.frmInicio.pass.value=="") alert("Ingresa tu usuario y contrase�a");
			else if(document.frmInicio.usr.value=="") alert("Ingresa tu usuario");
			else if(document.frmInicio.pass.value=="") alert("Ingresa tu password");
			return false;
		}
	}
</script>
<style type="text/css">
<!--
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #999;
}
a:hover {
	text-decoration: none;
	color: #999;
}
a:active {
	text-decoration: none;
	color: #999;
}
-->
</style>
<script LANGUAGE="JavaScript">
function PantallaCompleta(pagina) {
fullscreen = window.open(pagina, "fullscreen", 'top=0,left=0,width='+(screen.availWidth)+',height ='+(screen.availHeight)+',fullscreen=yes,toolbar=0 ,location=0,directories=0,status=0,menubar=0,resiz able=0,scrolling=0,scrollbars=yes');
}
</script>
</head>
<body>
<div class="wrapper">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="30" bgcolor="#f2f2f2">
  <tr>
    <td width="28%">&nbsp;</td>
    <td width="61%">&nbsp;</td>
    <td width="11%" align="center"><div align="right">
      <table width="0" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><a href="entrada.php"><img src="Imagenes/00_sistema.png" width="25" height="25" border="0" title="SIE" /></a></td>
          <td>&nbsp;</td>
          <td><font color="#666666"><a href="contacto.html"><img src="Imagenes/00_contactanos.png" alt="" width="25" height="25" border="0" title="Contactanos" /></a></font></td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>

<?php
//BarraHerramientas(true,intval($ira),false);

if($ira=="1") //quienes somos
{
	$titulo=mysqli_fetch_array(mysqli_query($Con, "select valor from seccion where id_seccion='Quienes_somos' and elemento='titulo'"));
	$archivo=mysqli_fetch_array(mysqli_query($Con, "select valor from seccion where id_seccion='Quienes_somos' and elemento='texto'"));
	?>
	<table border="0" align="center" width="65%">
		<tr>
			<td height="50">&nbsp;</td>
		</tr>
		<tr>
			<td>
				<h2 align="center"><?php echo $titulo["valor"]; ?></h2>
				<?php
				MostrarArchivo($Dir."/Archivos_Secciones/".$archivo["valor"]);
				?>
			</td>
		</tr>
	</table>
	<?php
}
else if($ira=="2") // nuestros productos
{
	$titulo=mysqli_fetch_array(mysqli_query($Con, "select valor from seccion where id_seccion='nuestros_productos' and elemento='titulo'"));
	$archivo=mysqli_fetch_array(mysqli_query($Con, "select valor from seccion where id_seccion='nuestros_productos' and elemento='texto'"));
	$imagen=mysqli_fetch_array(mysqli_query($Con, "select valor from seccion where id_seccion='nuestros_productos' and elemento='imagen'"));
	?>
	<script language="javascript">
		//location.href="catalogos_01.php?lista=1&noCache="+parseInt(Math.random()*1000);
		location.href="productos/index.php";
	</script>
	<table border="0" align="center" width="70%">
		<tr>
			<td height="50" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td valign="top">
				<h2><?php echo $titulo["valor"]; ?></h2>
				<?php
				MostrarArchivo($Dir."/Archivos_Secciones/".$archivo["valor"]);
				?>
				<p align="right"><a href="catalogos_01.php?lista=1" target="_parent">Ver Catalogo</a></p>
			</td>
			<td valign="middle" align="center" width="50%">
				<img src="Archivos_Secciones/<?php echo $imagen["valor"]; ?>"  />
			</td>
		</tr>
	</table>
	<?php
}
else if($ira=="3") // contactenos
{
	$archivo=mysqli_fetch_array(mysqli_query($Con, "select valor from seccion where id_seccion='contactenos' and elemento='texto'"));
	?>
  <table border="0" align="center" width="65%">
		<tr>
			<td height="75">&nbsp;

			</td>
		</tr>
		<tr>
			<td align="center">
				<?php
				MostrarArchivo($Dir."/Archivos_Secciones/".$archivo["valor"]);
				?>
				<br />
				<a href="mailto:info@manaiz.com"><font color="#990099">info@manaiz.com</font></a>
			</td>
		</tr>
	</table>
	<?php
}
else
{
	$txt1=mysqli_fetch_array(mysqli_query($Con, "select valor from seccion where id_seccion='Principal' and elemento='Slogan'"));
	$txt2=mysqli_fetch_array(mysqli_query($Con, "select valor from seccion where id_seccion='Quienes_somos' and elemento='titulo'"));
	?>

<?php
echo"<SCRIPT LANGUAGE=\"javascript\">location.href = \"http://www.teresita.com.mx/site\";</SCRIPT>";
?>
	<!--<table border="0" align="center" height="92.5%">
		<tr>
			<td height="75" align="center" valign="middle">
				<h2><i><font color="#999999"><?php echo $txt1["valor"]; ?></font></i></h2>
			</td>
		</tr><tr>
			<td height="50" align="center" valign="middle">
				<img src="Archivos_Secciones/<?php echo $principal; ?>" />
			</td>
		</tr>
		<tr>
			<td height="15"><div>
			  <div align="center">
			    <p>&nbsp;</p>
			    <p>Elasticintas Teresita S.A de C.V.</p>
			  </div>
			  <div></div>
		    </div></td>
		</tr>
		<tr>
			<td height="100" align="center" valign="middle">
				<?php $archivo=mysqli_fetch_array(mysqli_query($Con, "select valor from seccion where id_seccion='contactenos' and elemento='texto'"));
	?>
	<table border="0" align="center" width="100%">
		<tr><td></td>
		</tr>
		<tr>
			<td align="justify"><font color="#a6a6a6">
				<?php
				MostrarArchivo($Dir."/Archivos_Secciones/".$archivo["valor"]);
				?></font>
				<br />
				<a href="mailto:ventas@teresita.com.mx"><font color="#990099">ventas@teresita.com.mx</font></a>
			</td>
		</tr>
	</table>
			</td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
	</table>-->
	<!--<table width="50%" align="center">
		<tr>
			<td>
				<marquee style="font-size:medium; color:#666666; font-family:Verdana, Arial, Helvetica, sans-serif; font-style:italic"; scrolldelay="200">
					Lentejuelas,
					Galones de Lentejuela,
					Galones de Tul,
					Galones de Perla,
					El�sticos,
					Espiguilla,
					Espiguilla Met�lica,
					Encaje de Bolillo,
					Cintas,
					Cintas Met�licas,
					Cintas con Alambre,
					Trenzas,
					Trenzas Met�licas,
					Mallas,
					Mallas Met�licas,
					Cordones Trenzados,
					Cordones Torcidos,
					Cordones El�sticos,
					Cordones con Cenefa,
					Cordones con Alambre,
					Flecos,
					Flecos de Cadeneta,
					Fleco Torcido,
					Soutache
				</marquee>
			</td>
		</tr>
	</table>
	-->
	<?php
}

?>
<!--Finaliza el cuerpo del html e Inicia el piede p�gina-->
<!--HAY DOS SENTENCIAS, UNA QUE QUITA EL LINK RECU PASS CUANDO TE LOGEAS, LA SEGUNDA MUESTRA EN PIE EL N�MERO DE ARCHIVOS QUE ESTAN BLOQUEADOS POR EL USUARIO LOGEADO-->
<div class='push'></div>
</div>
<div class='footer' style='background-color:#193452; color: #FFF;'>
<table width='100%' height='28' border='0' cellpadding='0' cellspacing='0' bgcolor='#193452'>
            <tr>
              <td align='right' bgcolor='#FFFFFF'>&nbsp;</td>
              <td align='center' bgcolor='#FFFFFF'>&nbsp;</td>
              <td height='30' align='center' bgcolor='#FFFFFF'>&nbsp;</td>
    </tr>
            <tr>
              <td width='24%' height='30' align='right'>&nbsp;</td>
              <td width='60%' align='center'><font color='#FFFFFF'>Copyright (c) 2011.�Elasticintas Teresita S.A. de C.V.�Todos los derechos reservados.</font></td>
              <td width='16%' align='center'><a href='olvido_pass.php'><font color='#FFFFFF'>Recordar acceso</font></a></td>
            </tr>
            </table>
</div>
<!--Finaliza piede p�gina-->
</body>
</html>
<?php
mysqli_close($Con);
?>
