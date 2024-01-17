<?php

session_start();

include "apoyo.php";

include_once("u_db/data_base.php");

$db=new data_base(MAIN_DB->usr, MAIN_DB->host, MAIN_DB->pass, MAIN_DB->bd);

include_once("u_mapa/mapa.php");
include_once("u_tabla/tabla.php");
$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]))
{
	header("location: index.php?noCache=".rand(0,32000));
	exit();
}
$cont = Get_Vars_Helper::getPGVar("cont");
list($area,$lista) = explode("-",$cont);
if(!$lista) $lista="";


if($area=="")
{
	$tmp=mysqli_fetch_array($db->consulta("select valor from codigos_generales where campo='area' order by posicion limit 1"));
	$area=$tmp["valor"];
}
if($lista=="")
{
	$tmp=mysqli_fetch_array($db->consulta("select lista from area_lista where area='".$area."' limit 1"));
	$lista=$tmp["lista"];
}
$ar_aux=$area;
$li_aux=$lista;
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
<script language="javascript" src="prototype.js"></script>
<?php
$txt_nombre_aux = Get_Vars_Helper::getGetVar("txt_nombre");
$txt_area_aux = Get_Vars_Helper::getGetVar("txt_area");
$txt_puesto_aux = Get_Vars_Helper::getGetVar("txt_puesto");
$txt_nombre = $txt_area = $txt_puesto = "";
$txt_nombre = $txt_nombre_aux[0];
$txt_area = $txt_area_aux[0];
$txt_puesto = $txt_puesto_aux[0];

$enc = array("","","Nombre".addslashes(' <br /><input type="text" size="10" name="txt_nombre[]" onclick="Foco(this)" value="'.$txt_nombre.'" />'),"Area".addslashes(' <br /><input type="text" size="10" name="txt_area[]" onclick="Foco(this)" value="'.$txt_area.'" />'),"Puesto".addslashes(' <br /><input type="text" size="10" name="txt_puesto[]" onclick="Foco(this)" value="'.$txt_puesto.'" />'));
$cue = array();
$query="select persona.clave as clav, concat(persona.nombre, ' ', persona.apaterno) as nomb, puesto.descripcion as pues, cg.descripcion as area, persona.imagen as imag
from persona inner join puesto on puesto.clave=persona.puesto_actual left join codigos_generales as cg on cg.campo='area' and cg.valor=puesto.area where persona.clave in ( select persona from lista_persona where lista='$lista' ) and concat(persona.nombre, ' ', persona.apaterno) like '%$txt_nombre%' and (cg.descripcion like '%$txt_area%' or cg.descripcion is null) and (puesto.descripcion like '%$txt_puesto%' or puesto.descripcion is null) order by concat(persona.nombre, ' ', persona.apaterno, ' ', persona.amaterno)";
if($regs=consulta_directa($query))
{
	while($reg=mysqli_fetch_array($regs))
	{
		$tmp=mysqli_fetch_array($db->consulta("select valor from persona_contacto where persona='".$reg["clav"]."' and medio_contacto='3' limit 1"));
		$cue[]=array(
			$reg["clav"]!=""?addslashes('<input type="checkbox" value="'.$reg["clav"].'" />'):'""',
			$reg["imag"]!=""?addslashes('<img src="img_personas/'.$reg["imag"].'" title="'.$tmp["valor"].'" ondblclick="javascript: location.href='."'".'organigrama_pers.php?clave='.$reg["clav"]."&ret=1'".'" height="75" />'):'""',
			$reg["nomb"]!=""?htmlentities($reg["nomb"]):'""',
			$reg["area"]!=""?htmlentities($reg["area"]):'""',
			$reg["pues"]!=""?htmlentities($reg["pues"]):'""'
		);
	}
}
if(@count($enc)>0 && @count($cue)>0)
{
	$tbl = new tabla();

	$tbl->set("ruta_yui","u_yui");
	$tbl->set("alto","400px");
	$tbl->set("ancho","675px");
	$tbl->set("div","Tabla_de_datos");
	$tbl->set("encabezados",$enc);
	$tbl->set("celdas",$cue);

	$tbl->show();
	?>
	<script language="javascript">function SeeRegs(){}</script>
	<?php
}
else
{
	?><script language="javascript">function SeeRegs(){alert("No hay registros que mostrar");}</script><?php
}
?>
<script language="javascript">
	function ChangeArea(nva_area)
	{
		if(nva_area=='')
		{
			location.href="directorio.php";
			return false;
		}
		location.href="directorio.php?cont="+nva_area+"-";
		return false;
	}
	function ChangeLista(nva_lista)
	{
		location.href="directorio.php?cont=<?php echo $area; ?>-"+nva_lista;
		return false;
	}
	function Seleccionados()
	{
		var usuarios=$('Tabla_de_datos').getElementsByTagName('input'),x;
		var datos="";
		for(x=0;x<usuarios.length;x++)
		{
			if(usuarios[x].checked)
			{
				datos+=usuarios[x].value+",";
			}
		}
		datos=datos.substr(0,datos.length - 1);
		return datos;
	}
	function ejecutaAccion(acc)
	{
		$('accion').value="";
		var usuarios=Seleccionados();
		if(usuarios.split('').length <= 0 && (acc<=3))
		{
			alert("Debe seleccionar al menos un usuario.");
			return false;
		}
		if(parseInt(acc)>0)
		{
			switch(parseInt(acc))
			{
				case 1:		// Enviar Mail
					window.open('envia_mail.php?to='+usuarios);
					break;
				case 2:		// Imprimir datos de la lista
					window.open('imprime_lista.php?usrs='+usuarios);
					break;
				case 3:
					window.open('ajax/puesto_descriptivo.php?persona='+usuarios);
					break;
				case 4:		//Seleccionar Todos;
					var usuarios=$('Tabla_de_datos').getElementsByTagName('input'),x;
					for(x=0;x<usuarios.length;x++)
						usuarios[x].checked=true;
					break;
				case 5:		//Deseleccionar todos
					var usuarios=$('Tabla_de_datos').getElementsByTagName('input'),x;
					for(x=0;x<usuarios.length;x++)
						usuarios[x].checked=false;
					break;
					break;
			}
		}
	}
	function Foco(obj)
	{
		window.event.cancelBubble=true;
		obj.focus();
		obj.select();
	}
</script>
</head>

<body onload="javascript: $('ir_a').value='<?php echo $area; ?>'; $('lista').value='<?php echo $lista; ?>'; SeeRegs();">

<table bgcolor='f2f2f2' width='100%' height='40' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
    <td width="37%"><table border='0' align='left' cellpadding='0' cellspacing='0'>
      <tr>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td><img src="Imagenes/menu/home.png" alt="Inicio" title="Inicio" onclick="javascript: /*window.close()*/location.href='entrada.php';" /></td>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td></td>
      </tr>
    </table></td>
    <td width="31%"><div align='center'><strong>Directorio</strong></div></td>
    <td width="32%"><table border='0' align='right' cellpadding='0' cellspacing='0'>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><img src="Imagenes/Btn_salir.png" onclick="javascript: /*window.close()*/location.href='index.php';" /></td>
        </tr>
    </table></td>
  </tr>
</table>

<div class="yui-skin-sam">
<form action="directorio.php" method="get">
<table width="100%"><tr><td width="50%"><table align="left">
		<tr><td align="right">Ir a:</td><td><select name="ir_a" id="ir_a" onchange="ChangeArea(this.value);"><?php echo CboCG("area"); ?></select></td></tr>
		<tr><td align="right">Lista:</td><td><select name="lista" id="lista" onchange="ChangeLista(this.value);"><?php
			$listas=$db->consulta("select lista, nombre from lista where lista in (select lista from area_lista where area='".$area."')");
			while($lista=mysqli_fetch_array($listas)) { ?><option value="<?php echo $lista["lista"]; ?>"><?php echo $lista["nombre"]; ?></option><?php }
			?></select></td></tr>
		</table></td>
		<td align="center" valign="bottom" width="50%">
		</td>
		</tr><tr><td align="center" colspan="2">
		<input type="hidden" name="cont" value="<?php echo $ar_aux; ?>-<?php echo $li_aux; ?>" />
		<table border="0" align="center">
			<tr>
				<td align="right" valign="bottom" style="padding-bottom:7px;">
					<input type="submit" value="Filtrar" style="height:21px; width:75px;" />
					Accion:
			  <select name="accion" id="accion" onchange="ejecutaAccion(this.value);">
				<option value=""></option>
				<option value="4">Seleccionar Todos</option>
				<option value="5">Deseleccionar Todos</option>
				<option value="1">Enviar E-Mail</option>
				<option value="2">Informaci&oacute;n Detallada</option>
				<option value="3">Descriptivo de Puesto</option>
			</select>
				</td>
			</tr>
			<tr><td align="left"><div id="Tabla_de_datos"></div></td></tr>
		</table>
</td></tr></table>
</form>
</div>
</body>
</html>
