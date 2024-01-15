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

$persona_form=PostString("persona");
if(PostString("Guardar")!="" || PostString("save")!="")
{
	$clave=PostString("clave");
	$nombre=PostString("nombre");
	$email=PostString("email");
	$tipo_pers=PostString("tipo");
	$estatus=PostString("estatus");
	$fecha=PostDate("fecha");
	if($clave!="" && isset($_FILES["foto"]["name"]) && $_FILES["foto"]["name"]!="")
	{
		$info=pathinfo($_FILES["foto"]["name"]);
		move_uploaded_file($_FILES["foto"]["tmp_name"], $Dir."/Imagenes/".$clave.".".$info["extension"]);
		$foto=basename($Dir."/Imagenes/".$clave.".".$info["extension"]);
	}
	else
	{
		$foto="";
	}
	if(PostString("add")=="yes" && $clave!="")
	{
		consulta_directa($Con, "insert into persona (clave, nombre, email, tipo_persona, estatus, fecha, imagen) values ('$clave', '$nombre', '$email', '$tipo_pers', '$estatus', '$fecha', '$foto')");
	}
	else if($clave!="")
	{
		consulta_directa($Con, "update persona set nombre='$nombre', email='$email', tipo_persona='$tipo_pers', estatus='$estatus', fecha='$fecha'".(($foto!="")?(", imagen='$foto'"):(""))."where clave='$clave'");
	}
	$persona_form=$clave;
}
$datos=@mysqli_fetch_array(consulta_directa($Con, "select * from persona where clave='$persona_form'"));

$photo_src=(($datos["imagen"]!="")?($datos["imagen"]):("photo.jpg"));
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
<?php

BarraHerramientas();

?>
<div align="right">
	<form action="sistema.php" method="post" name="sist" style="padding:0px;">
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
	<form action="seguridad.php" method="post" name="seg" style="padding:0px;">
		Acci&oacute;n:
		<select name="accion" onchange="javascript: document.seg.submit();"><option value=""></option>
			<?php menu_items($_SESSION["tipo"],'0.4.51.1'); ?>
		</select>
	</form>
	<script language="javascript">
		document.seg.accion.value="1";
	</script>
</div>
<?php
BH_Ayuda('0.4.51.1','1');
if(PostString("Nuevo")=="")
{
?>
<form action="personas.php" method="post" enctype="multipart/form-data" name="datos">
<table border="0" align="center">
	<tr>
		<td></td>
		<td align="right">Persona:</td>
		<td>
			<select name="persona" onchange="javascript: document.datos.submit();">
				<option value=""></option>
				<?php
				if($personas=consulta_directa($Con, "select nombre, clave from persona order by nombre"))
				{
					while($persona=mysqli_fetch_array($personas))
					{
						?>
						<option value="<?php echo $persona["clave"]; ?>"><?php echo $persona["nombre"]; ?></option>
						<?php
					}
				}
				?>
			</select>
			<script language="javascript">
				document.datos.persona.value="<?php echo $persona_form; ?>";
			</script>
		</td>
		<td>
			<input type="submit" name="Borrar" value="Borrar" class="btn_normal" />
			<input type="submit" name="Nuevo" value="Nuevo" class="btn_normal" />
			<input type="submit" name="Guardar" value="Guardar" class="btn_normal" />
		</td>
	</tr>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr>
		<td rowspan="5" align="center" valign="middle">
			<img width="100" src="img_personas/<?php echo $photo_src; ?>" />
			<input type="hidden" name="photo" value="<?php echo $datos["imagen"]; ?>" />
		</td>
		<td align="right">Clave:</td>
		<td>
			<input type="text" maxlength="250" size="25" disabled="disabled" value="<?php echo $datos["clave"]; ?>" />
			<input type="hidden" name="clave" value="<?php echo $datos["clave"]; ?>" />
		</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Nombre:</td>
		<td><input type="text" name="nombre" maxlength="250" size="25" value="<?php echo $datos["nombre"]; ?>" /></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">E-Mail:</td>
		<td><input type="text" name="email" maxlength="250" size="25" value="<?php echo $datos["email"]; ?>" /></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Tipo:</td>
		<td><select name="tipo"><?php echo CboCG("tipo_persona"); ?></select></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Estatus:</td>
		<td><select name="estatus"><?php echo CboCG("estatus_usuario"); ?></select></td>
		<td></td>
	</tr>
	<tr>
		<td>
			<input type="file" name="foto" onchange="javascript: document.datos.save.value='yes'; document.datos.submit();" />
			<input type="hidden" name="save" />
		</td>
		<td align="right">Fecha:</td>
		<td><?php echo FormFecha("fecha"); ?></td>
		<td></td>
	</tr>
</table>
<script language="javascript">
	document.datos.tipo.value="<?php echo $datos["tipo_persona"]; ?>";
	document.datos.estatus.value="<?php echo $datos["estatus"]; ?>";
	document.datos.fecha_d.value="<?php echo intval(substr($datos["fecha"],8,2)); ?>";
	document.datos.fecha_m.value="<?php echo intval(substr($datos["fecha"],5,2)); ?>";
	document.datos.fecha_a.value="<?php echo intval(substr($datos["fecha"],0,4)); ?>";
</script>
</form>
<?php
}
else
{
?>
<form action="personas.php" method="post" enctype="multipart/form-data" name="datos">
<input type="hidden" name="add" value="yes" />
<table border="0" align="center">
	<tr>
		<td colspan="3"></td>
		<td align="right">
			<input type="submit" name="Guardar" value="Guardar" class="btn_normal" />
		</td>
	</tr>
	<tr>
		<td rowspan="5" align="center" valign="middle">
			<img src="Imagenes/photo.jpg" width="100" />
		</td>
		<td align="right">Clave:</td>
		<td><input type="text" name="clave" maxlength="250" size="25" onchange="javascript: if(document.datos.clave.value!='') document.datos.foto.disabled=''; else document.datos.foto.disabled='disabled' " /></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Nombre:</td>
		<td><input type="text" name="nombre" maxlength="250" size="25" /></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">E-Mail:</td>
		<td><input type="text" name="email" maxlength="250" size="25" /></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Tipo:</td>
		<td><select name="tipo"><?php echo CboCG("tipo_persona"); ?></select></td>
		<td></td>
	</tr>
	<tr>
		<td align="right">Estatus:</td>
		<td><select name="estatus"><?php echo CboCG("estatus_usuario"); ?></select></td>
		<td></td>
	</tr>
	<tr>
		<td>
			<input type="file" name="foto" disabled="disabled" onchange="javascript: document.datos.save.value='yes'; document.datos.submit();" />
			<input type="hidden" name="save" />
		</td>
		<td align="right">Fecha:</td>
		<td>
			<?php
			echo FormFecha("fecha");
			$hoy=getdate();
			?>
			<script language="javascript">
				document.datos.fecha_d.value="<?php echo intval($hoy["mday"]); ?>";
				document.datos.fecha_m.value="<?php echo intval($hoy["mon"]); ?>";
				document.datos.fecha_a.value="<?php echo intval($hoy["year"]); ?>";
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

mysqli_close($Con);

?>
