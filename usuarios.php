<?php

session_start();

include "apoyo.php"; 
include "lists.php";

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]) )
{
	header("location: index.php?noCahce=".rand(0,32000));
	exit();
}

$usuario_form=PostString("usuario");

if(PostString("Guardar")!="")
{
	$clave=PostString("clave");
	$password=PostString("password");
	$persona=PostString("persona");
	$tipo_usuario=PostString("tipo_usuario");
	$estatus=PostString("estatus");
	$fecha_alta=PostDate("fecha_alta");
	if(PostString("add")=="yes" && $clave!="")
	{
		mysql_query("insert into usuario (clave, password, estatus, tipo_usuario, persona, fecha_alta) values ('$clave', '$password', '$estatus', '$tipo_usuario', '$persona', '$fecha_alta')");
		mysql_query("insert into lista (nombre, lista_nivel, usuario, fecha, posicion, estatus) values ('$persona', 'C', '$clave', curdate(), '1', 'A')");
		mysql_query("insert into lista (nombre, lista_nivel, usuario, fecha, posicion, estatus) values ('Seleccionados', 'A', '$clave', curdate(), '2', 'A')");
		mysql_query("insert into lista (nombre, lista_nivel, usuario, fecha, posicion, estatus) values ('Favoritos', 'A', '$clave', curdate(), '3', 'A')");
		$lp=mysql_fetch_array(mysql_query("select lista from lista where nombre = '$persona' and usuario = '$clave'"));
		$ls=mysql_fetch_array(mysql_query("select lista from lista where nombre = 'Seleccionados' and usuario = '$clave'"));
		$lf=mysql_fetch_array(mysql_query("select lista from lista where nombre = 'Favoritos' and usuario = '$clave'"));
		mysql_query("insert into lista_asociada (lista, lista_asociada, posicion) values ('".$lp["lista"]."', '".$ls["lista"]."', '1')");
		mysql_query("insert into lista_asociada (lista, lista_asociada, posicion) values ('".$lp["lista"]."', '".$lf["lista"]."', '2')");
	}
	else if($clave!="")
	{
		mysql_query("update usuario set password='$password', estatus='$estatus', tipo_usuario='$tipo_usuario', persona='$persona', fecha_alta='$fecha_alta' where clave='$clave'");
	}
	$usuario_form=$clave;
}

$datos=@mysql_fetch_array(mysql_query("select * from usuario where clave='$usuario_form'"));

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
<script language="javascript">
	function DataValidation()
	{
			return true;
	}
</script>
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
    <td width="31%"><div align='center'><strong>Usuarios</strong></div></td>
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
//BarraHerramientas();
?>
<div align="right">
	<form action="sistema.php" method="post" name="sist">
		Secci&oacute;n:
		<select name="seccion" onchange="javascript: document.sist.submit();"><option value=""></option>
			<?php menu_items($_SESSION["tipo"],'0.4.51'); ?>
		</select>
	</form>
	<script language="javascript">
		document.sist.seccion.value=1;
	</script>
</div>
<div align="right">
	<form action="seguridad.php" method="post" name="seg">
		Acci&oacute;n:
		<select name="accion" onchange="javascript: document.seg.submit();"><option value=""></option>
			<?php menu_items($_SESSION["tipo"],'0.4.51.1'); ?>
		</select>
	</form>
	<script language="javascript">
		document.seg.accion.value="2";
	</script>
</div>
<?php

BH_Ayuda('0.4.51.1','2');
if(PostString("Nuevo")=="")
{
?>
<form method="post" enctype="multipart/form-data" name="datos" action="usuarios.php">
<table border="0" align="center">
	<tr>
		<td></td>
		<td align="right">Usuario:</td>
		<td>
			<select name="usuario" onchange="javascript: document.datos.submit();">
				<option value=""></option>
				<?php
				if($usuarios=mysql_query("select clave from usuario order by clave"))
				{
					while($usuario=mysql_fetch_array($usuarios))
					{
						?>
						<option value="<?php echo $usuario["clave"]; ?>"><?php echo $usuario["clave"]; ?></option>
						<?php
					}
				}
				?>
			</select>		</td>
		<td>
			<input type="submit" name="Borrar" value="Borrar" class="btn_normal" />
			<input type="submit" name="Nuevo" value="Nuevo" class="btn_normal" />
			<input type="submit" name="Guardar" value="Guardar" class="btn_normal" />		</td>
	</tr>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr>
		<td rowspan="6">		</td>
		<td align="right">Usuario:</td>
		<td>
			<input type="text" maxlength="250" size="25" disabled="disabled" value="<?php echo $datos["clave"]; ?>" />
			<input type="hidden" name="clave" value="<?php echo $datos["clave"]; ?>" />		</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Contrase&ntilde;a:</td>
		<td><input type="password" name="password" maxlength="250" size="25" value="<?php echo $datos["password"]; ?>" /></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Persona:</td>
		<td>
			<select name="persona">
				<option value=""></option>
				<?php
				if($personas=mysql_query("select nombre, clave from persona order by nombre"))
				{
					while($persona=mysql_fetch_array($personas))
					{
						?>
						<option value="<?php echo $persona["clave"]; ?>"><?php echo $persona["nombre"]; ?></option>
						<?php
					}
				}
				?>
			</select>		</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Tipo:</td>
		<td><select name="tipo_usuario"><?php echo CboCG("tipo_usuario"); ?></select></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Estatus:</td>
		<td><select name="estatus"><?php echo CboCG("estatus_usuario"); ?></select></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Fecha:</td>
		<td><?php echo FormFecha("fecha_alta"); ?></td>
		<td></td>
	</tr>
</table>
<script language="javascript">
	document.datos.usuario.value="<?php echo $datos["clave"]; ?>";
	document.datos.persona.value="<?php echo $datos["persona"]; ?>";
	document.datos.tipo_usuario.value="<?php echo $datos["tipo_usuario"]; ?>";
	document.datos.estatus.value="<?php echo $datos["estatus"]; ?>";
	document.datos.fecha_alta_d.value="<?php echo intval(substr($datos["fecha_alta"],8,2)); ?>";
	document.datos.fecha_alta_m.value="<?php echo intval(substr($datos["fecha_alta"],5,2)); ?>";
	document.datos.fecha_alta_a.value="<?php echo intval(substr($datos["fecha_alta"],0,4)); ?>";
</script>
</form>
<?php
}
else
{
?>
<form method="post" enctype="multipart/form-data" name="datos" action="usuarios.php">
<input type="hidden" name="add" value="yes" />
<table border="0" align="center">
	<tr>
		<td colspan="3"></td>
		<td>
			<input type="submit" name="Guardar" value="Guardar" class="btn_normal" />		</td>
	</tr>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr>
		<td rowspan="6">		</td>
		<td align="right">Usuario:</td>
		<td>
			<input type="text" name="clave" maxlength="250" size="25" />		</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Contrase&ntilde;a:</td>
		<td><input type="password" name="password" maxlength="250" size="25" /></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Persona:</td>
		<td>
			<select name="persona">
				<option value=""></option>
				<?php
				if($personas=mysql_query("select nombre, clave from persona order by nombre"))
				{
					while($persona=mysql_fetch_array($personas))
					{
						?>
						<option value="<?php echo $persona["clave"]; ?>"><?php echo $persona["nombre"]; ?></option>
						<?php
					}
				}
				?>
			</select>		</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Tipo:</td>
		<td><select name="tipo_usuario"><?php echo CboCG("tipo_usuario"); ?></select></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Estatus:</td>
		<td><select name="estatus"><?php echo CboCG("estatus_usuario"); ?></select></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Fecha:</td>
		<td>
			<?php echo FormFecha("fecha_alta"); 
			$hoy=getdate();
			?>
			<script language="javascript">
				document.datos.fecha_alta_d.value="<?php echo intval($hoy["mday"]); ?>";
				document.datos.fecha_alta_m.value="<?php echo intval($hoy["mon"]); ?>";
				document.datos.fecha_alta_a.value="<?php echo intval($hoy["year"]); ?>";
			</script>
		</td>
		<td></td>
	</tr>
</table>
</form>
<?php
}
?>
</body>
</html>
<?php

mysql_close();

?> 