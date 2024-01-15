<?php

session_start();

include "apoyo.php";

include_once("u_db/data_base.php");

$db=new data_base(BD_USR, BD_HOST, BD_PASS, BD_BD);

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]))
{
	header("location: index.php?noCache=".rand(0,32000));
	exit();
}

$persona=PostString("clave").Get("clave");

$basic_data=mysqli_fetch_array($db->consulta("select clave, concat(nombre, ' ', apaterno) as nomb, imagen, puesto_actual from persona where clave = '$persona'"));

$accion=PostString("accion").Get("accion");
if($accion=="add_docto")
{
	$nombre=PostString("nombre").Get("nombre");
	if($persona!="" && $nombre!="" && isset($_FILES["archivo"]["name"]) && $_FILES["archivo"]["name"]!="")
	{
		$arch="pers$persona@".basename($_FILES["archivo"]["name"]);
		if(move_uploaded_file($_FILES["archivo"]["tmp_name"],str_replace("\\","/",$Dir)."/documentos/$arch"))
			$db->consulta("insert into documento (nombre, propietario, tipo_propietario, archivo) values ('$nombre','$persona','1','$arch')");
	}
}
else if($accion=="del_docto")
{
	$docto=PostString("docto").Get("docto");
	if($docto!="")
	{
		$arch=mysqli_fetch_array($db->consulta("select archivo from documento where id_docto='$docto'"));
		@unlink("$Dir/documentos/".$arch["archivo"]);
		$db->consulta("delete from documento where id_docto='$docto'");
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link rel="stylesheet" type="text/css" href="u_yui/fonts.css" />
<link rel="stylesheet" type="text/css" href="u_yui/container-core.css" />
<script language="javascript" type="text/javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" type="text/javascript" src="u_yui/dragdrop.js"></script>
<script language="javascript" type="text/javascript" src="u_yui/container.js"></script>
<style type="text/css">
/*
Estilos para las cajas panel
panel 1 agregar doctos
panel 2 eliminar doctos
*/
#panel2_c.yui-panel-container.shadow .underlay{
	position:absolute;
	background-color:#000;
	filter:alpha(opacity=12);
	left:3px;
	right:-3px;
	bottom:-3px;
	top:3px;
}
#panel2.yui-panel{
	border:none;
	overflow:visible;
	background-color:transparent;
}
/* Apply styles to the close icon to anchor it to the left side of the header */
#panel2.yui-panel .container-close{
	position:absolute;
	top:3px;
	left:4px;
	height:18px;
	width:17px;
	background:url(u_yui/aqua-hd-close.gif) no-repeat;
}
/* span:hover not supported on IE6 */
#panel2.yui-panel .container-close:hover{
	background:url(u_yui/aqua-hd-close-over.gif) no-repeat;
}
/* Style the header and apply the rounded corners, center the text */
#panel2.yui-panel .hd{
	padding:0;
	border:none;
	background::url(u_yui/aqua-hd-bg.gif) repeat-x;
	color:#000;
	height:22px;
	margin-left:7px;
	margin-right:7px;
	text-align:center;
	overflow:visible;
}
/* Style the body and footer */
#panel2.yui-panel .bd{
	overflow:hidden;
	padding:4px;
	border:1px solid #aeaeae;
	background-color:#FFF;
}
#panel2.yui-panel .ft{
	font-size:75%;
	color:#666;
	padding:2px;
	overflow:hidden;
	border:1px solid #aeaeae;
	border-top:none;
	background-color:#dfdfdf;
}
/* Skin custom elements */
#panel2.yui-panel .hd span{
	vertical-align:middle;
	line-height:22px;
	font-weight:bold;
}
#panel2.yui-panel .hd .tl{
	width:7px;
	height:22px;
	top:0;
	left:0;
	background: url(u_yui/aqua-hd-lt.gif) no-repeat;
	position:absolute;
}
#panel2.yui-panel .hd .tr{
	width:7px;
	height:22px;
	top:0;
	right:0;
	background::url(u_yui/aqua-hd-rt.gif) no-repeat;
	position:absolute;
}
#panel1_c.yui-panel-container.shadow .underlay{
	position:absolute;
	background-color:#000;
	filter:alpha(opacity=12);
	left:3px;
	right:-3px;
	bottom:-3px;
	top:3px;
}
#panel1.yui-panel{
	border:none;
	overflow:visible;
	background-color:transparent;
}
/* Apply styles to the close icon to anchor it to the left side of the header */
#panel1.yui-panel .container-close{
	position:absolute;
	top:3px;
	left:4px;
	height:18px;
	width:17px;
	background:url(u_yui/aqua-hd-close.gif) no-repeat;
}
/* span:hover not supported on IE6 */
#panel1.yui-panel .container-close:hover{
	background:url(u_yui/aqua-hd-close-over.gif) no-repeat;
}
/* Style the header and apply the rounded corners, center the text */
#panel1.yui-panel .hd{
	padding:0;
	border:none;
	background::url(u_yui/aqua-hd-bg.gif) repeat-x;
	color:#000;
	height:22px;
	margin-left:7px;
	margin-right:7px;
	text-align:center;
	overflow:visible;
}
/* Style the body and footer */
#panel1.yui-panel .bd{
	overflow:hidden;
	padding:4px;
	border:1px solid #aeaeae;
	background-color:#FFF;
}
#panel1.yui-panel .ft{
	font-size:75%;
	color:#666;
	padding:2px;
	overflow:hidden;
	border:1px solid #aeaeae;
	border-top:none;
	background-color:#dfdfdf;
}
/* Skin custom elements */
#panel1.yui-panel .hd span{
	vertical-align:middle;
	line-height:22px;
	font-weight:bold;
}
#panel1.yui-panel .hd .tl{
	width:7px;
	height:22px;
	top:0;
	left:0;
	background: url(u_yui/aqua-hd-lt.gif) no-repeat;
	position:absolute;
}
#panel1.yui-panel .hd .tr{
	width:7px;
	height:22px;
	top:0;
	right:0;
	background::url(u_yui/aqua-hd-rt.gif) no-repeat;
	position:absolute;
}
</style>
<script language="javascript">
	function Datos()
	{
		var obj=new Ajax.Updater('contenidos','ajax/curriculo.php',{postBody: "clave=<?php echo $persona; ?>"});
		CeldaActual('opc_datos');
	}
	function Puesto()
	{
		var obj=new Ajax.Updater('contenidos','ajax/puesto_estructura.php',{postBody: "pto=<?php echo $basic_data["puesto_actual"]; ?>"});
		CeldaActual('opc_puesto');
	}
	function Historia()
	{
		var obj=new Ajax.Updater('contenidos','ajax/historia.php',{postBody: "clave=<?php echo $persona; ?>"});
		CeldaActual('opc_historia');
	}
	function Docto(id_celda, arch_name, id_docto, nombre,edicion)
	{
		$('contenidos').innerHTML='Archivo: '+nombre;
		$('docto').value=id_docto;
		DownloadFile('documentos/'+arch_name,edicion);
		CeldaActual(id_celda);
	}
	function CeldaActual(id_celda)
	{
		var x, celdas=$('barra_documentos').rows[0].cells;
		for(x=0; x<=celdas.length-1;x++)
		{
			celdas[x].className="celda_normal";
		}
		$(id_celda).className="celda_actual";
	}

	YAHOO.namespace("example.container");

	YAHOO.util.Event.onDOMReady(function () {

		YAHOO.example.container.panel1 = new YAHOO.widget.Panel("panel1", { width:"350px", visible:false, constraintoviewport:true } );
		YAHOO.example.container.panel1.render();

		YAHOO.example.container.panel2 = new YAHOO.widget.Panel("panel2", { width:"300px", visible:false, constraintoviewport:true } );
		YAHOO.example.container.panel2.render();

		YAHOO.util.Event.addListener("btnAddDocto", "click", YAHOO.example.container.panel1.show, YAHOO.example.container.panel1, true);
		YAHOO.util.Event.addListener("btnDelDocto", "click", YAHOO.example.container.panel2.show, YAHOO.example.container.panel2, true);

		YAHOO.util.Event.addListener("CancelAddDocto", "click", YAHOO.example.container.panel1.hide, YAHOO.example.container.panel1, true);
		YAHOO.util.Event.addListener("NotDelDocto", "click", YAHOO.example.container.panel2.hide, YAHOO.example.container.panel2, true);
	});
</script>
<style type="text/css">
	a
	{
		text-decoration:none;
	}
</style>
</head>
<body onload="Datos();">
<?php //BarraHerramientas();
B_reportes();
BH_Ayuda('','');
?>
<table width="95%" align="center"><tr><td valign="baseline">
<img height="100" border="0" src="img_personas/<?php echo $basic_data["imagen"]; ?>" /> <strong><?php echo $basic_data["nomb"]; ?></strong>
</td>
<td align="right" valign="baseline">
	<div id="container">
		<div>
			<img src="Imagenes/back.png" onclick="javascript: history.go(-<?php echo PostString("ret").Get("ret"); ?>);" />
			<input type="button" name="btnAddDocto" id="btnAddDocto" value="Anexar" style="width:75px; height:25px;" />
			<input type="button" name="btnDelDocto" id="btnDelDocto" value="Borrar" style="width:75px; height:25px;" />
		</div>
		<div id="panel1" align="left" style="background-color:#CCCCCC; visibility:hidden;">
			<div class="hd"><div class="tl"></div><span>Anexar documento:</span><div class="tr"></div></div>
			<div class="bd">
				<div align="center">
				<form action="organigrama_pers.php?clave=<?php echo $persona?>" method="post" id="frm_add_docto" enctype="multipart/form-data">
					<input type="hidden" name="accion" value="add_docto" />
					<input type="hidden" name="ret" value="<?php echo intval(PostString("ret").Get("ret"))+1; ?>" />
					<table border="0">
						<tr><td align="right">Nombre:</td><td align="left"><input type="text" name="nombre" /></td></tr>
						<tr><td align="right">Archivo:</td><td align="left"><input type="file" name="archivo" /></td></tr>
						<tr><td align="center" colspan="2">
							<input type="submit" value="Aceptar" />
							<input type="button" id="CancelAddDocto" value="Cancelar" />
							</td></tr>
					</table>
				</form>
				</div>
			</div>
		</div>
		<div id="panel2" align="left" style="background-color:#CCCCCC; visibility:hidden;">
			<div class="hd" align="left"><div class="tl"></div><span>Borrar documento</span><div class="tr"></div></div>
			<div class="bd" align="left">
				<table border="0" align="center"><tr>
				  <td align="center">
				Estas Seguro?</td>
				</tr><tr><td align="center">
				<form action="organigrama_pers.php?clave=<?php echo $persona; ?>" method="post" id="frm_del_docto">
					<input type="hidden" name="accion" id="accion" value="del_docto" />
					<input type="hidden" name="docto" id="docto" value="" />
					<input type="hidden" name="ret" value="<?php echo intval(PostString("ret").Get("ret"))+1; ?>" />
					<input type="submit" value="Si" />
					<input type="button" id="NotDelDocto" value="No" />
				</form>
				</td></tr></table>
			</div>
		</div>
	</div>
</td>
</tr><tr><td valign="baseline" colspan="2">
	<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td class="contenedor" align="left">
		<table border="0" id="barra_documentos"><tr>
			<td class="celda_normal" onclick="Datos();" id="opc_datos">Datos Personales</td>
			<td class="celda_normal" onclick="Puesto()" id="opc_puesto">Puesto</td>
			<td class="celda_normal" onclick="Historia()" id="opc_historia">Trayectoria</td>
			<?php
			if($doctos=$db->consulta("select id_docto, nombre, archivo from documento where propietario='$persona' and tipo_propietario=1"))
			{
				$x=0;
				while($docto=mysqli_fetch_array($doctos))
				{
					$x++;
					$cuantos=@mysqli_fetch_array(consulta_directa($Con, "select count(*) as n from archivos where archivo='".basename($docto["archivo"])."'"));
					echo $Con->error;
					if(intval($cuantos["n"])>0) $edicion='false';
					else $edicion='true';
					?>
					<td class="celda_normal" id="opc_docto<?php echo $x; ?>" onclick="Docto(this.id,'<?php echo $docto["archivo"]; ?>','<?php echo $docto["id_docto"]; ?>','<?php echo $docto["nombre"]; ?>','<?php echo $edicion; ?>')"><?php echo $docto["nombre"]; ?></td>
					<?php
				}
			}
			?>
		</tr></table>
		</td></tr></table>
</td></tr><tr><td valign="baseline">
</td></tr></table>
<center><div id="contenidos" style="width:950px; height:450px; overflow:auto"></div></center>
</body>
</html>

<?php

mysqli_close($Con);

?>
