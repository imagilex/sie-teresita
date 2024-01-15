<?php

session_start();

include "apoyo.php";

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"])  )
{
	header("location: index.php?noCahce=".rand(0,32000));
	exit();
}

if(PostString("add")=='yes')
{
	$nombre=PostString("nombre");
	$cuantos=mysqli_fetch_array(consulta_directa($Con, "select count(*) as n from reporte where nombre = '$nombre'"));
	if(intval($cuantos["n"])>0)
	{
		Alert("Ya existe un reporte con el nombre agregado");
	}
	else
	{
		consulta_directa($Con, "insert into reporte (nombre) values ('$nombre')");
		ErrorMySQLAlert();
		$id=mysqli_fetch_array(consulta_directa($Con, "select id_reporte from reporte where nombre =  '$nombre'"));
		$id_reporte=$id["id_reporte"];
		$num_secc=PostString("num_secc");
		for($x=2;$x<=$num_secc;$x++)
		{
			$seccion=PostString("nom$x");
			$posicion=PostString("pos$x");
			$mostrar=((PostString("mos$x")=="S")?(1):(0));
			if($seccion!="" && isset($_FILES["arc$x"]["name"]) && $_FILES["arc$x"]["name"]!="")
			{
				$info=pathinfo($Dir."/Archivos_Reportes/".$_FILES["arc$x"]["name"]);
				$archivo="rep$id_reporte".preg_replace(" ","",$seccion).".".$info["extension"];
				move_uploaded_file($_FILES["arc$x"]["tmp_name"],$Dir."/Archivos_Reportes/".$archivo);
				consulta_directa($Con, "insert into reporte_detalle (id_reporte, seccion, archivo, posicion, mostrar) values ('$id_reporte', '$seccion', '$archivo', '$posicion', '$mostrar')");
			}
		}
		$seccion=PostString("nom1");
		$posicion=PostString("pos1");
		$mostrar=PostString("mos1");
		consulta_directa($Con, "insert into reporte_detalle (id_reporte, seccion, archivo, posicion, mostrar) values ('$id_reporte', '$seccion', '$archivo', '$posicion', '$mostrar')");
	}
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
<script language="javascript">
	function DataValidation()
	{
		if(document.add_reporte.nombre.value=="") {alert("Debe ingresar el nombre del reporte"); return false;}
		return true;
	}
	function AnexarSec()
	{
		var total=parseInt(document.getElementById('num_secc').value);
		total++;
		document.getElementById('num_secc').value=total;
		var nombre=document.createElement('input');
		var archivo=document.createElement('input');
		var posicion=document.createElement('input');
		var mostrar=document.createElement('input');
		var tabla=document.getElementById('tabla_seccion');
		nombre.id='nom'+total;
		nombre.name='nom'+total;
		nombre.type='text';
		nombre.size="30";
		nombre.maxlength="250";
		archivo.id='arc'+total;
		archivo.name='arc'+total;
		archivo.type='file';
		posicion.id='pos'+total;
		posicion.name='pos'+total;
		posicion.type='text';
		posicion.size="5";
		posicion.maxlength="4";
		mostrar.id='mos'+total;
		mostrar.name='mos'+total;
		mostrar.type='checkbox';
		mostrar.value="S";
		tabla.insertRow(total-1);
		tabla.rows[total-1].insertCell(0);
		tabla.rows[total-1].insertCell(1);
		tabla.rows[total-1].insertCell(2);
		tabla.rows[total-1].insertCell(3);
		tabla.rows[total-1].cells[0].appendChild(nombre);
		tabla.rows[total-1].cells[1].appendChild(archivo);
		tabla.rows[total-1].cells[2].appendChild(posicion);
		tabla.rows[total-1].cells[3].appendChild(mostrar);
	}
</script>
</head>

<body>
<?php

BarraHerramientas();

BH_Ayuda('0.4.','');
?>
<form name="add_reporte" method="post" action="add_report.php" enctype="multipart/form-data" onsubmit="return DataValidation();">
	<input type="hidden" name="add" value="yes" />
	<table border="0" align="center">
		<tr>
			<td align="right">
				Nombre:
			</td>
			<td>
				<input type="text" name="nombre" id="nombre" maxlength="250" size="75" />
				<input type="hidden" name="num_secc" id="num_secc" value="1" />
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2" align="right"><input type="button" value="Anexar Secciones" onclick="AnexarSec();" /></td></tr>
		<tr>
			<td colspan="2">
				<table border="0" align="center" id="tabla_seccion">
					<tr>
						<td>Nombre Secci&oacute;n</td>
						<td>Archivo</td>
						<td>Posici&oacute;n</td>
						<td>Mostrar<br />Nombre</td>
					</tr>
					<tr>
						<td><input type="text" size="30" maxlength="250" value="Comentarios" disabled="disabled" /></td>
						<td>----------------------------------<input type="hidden" name="nom1" value="Comentarios" /></td>
						<td><input type="text" name="pos1" id="pos1" size="5" maxlength="4" /></td>
						<td><input type="checkbox" name="mos1" id="mos1" value="S" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="Aceptar" />
			</td>
		</tr>
	</table>
</form>
</body>
</html>
<?php

mysqli_close($Con);

?>
