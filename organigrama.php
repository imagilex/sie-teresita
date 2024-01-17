<?php

session_start();

include "apoyo.php";

include_once("u_db/data_base.php");
include_once "__access_data.php";

$db=new data_base(BD_USR, BD_HOST, BD_PASS, BD_BD);

include_once("u_mapa/mapa.php");

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]))
{
	header("location: index.php?noCache=".rand(0,32000));
	exit();
}

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
<style type="text/css">
	a:link, a:visited, a:hover, a:active
	{
		border-style:none;
		text-decoration:none;
	}
</style>
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	function ChangeArea(nva_area)
	{
		if(nva_area=='')
		{
			location.href="organigrama.php?id_mapa=10";
			return false;
		}
		location.href="organigrama.php?cont="+nva_area+"-";
		return false;
	}
	function ChangeLista(nva_lista)
	{
		location.href="organigrama.php?cont="+$F('ir_a')+"-"+nva_lista;
		return false;
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
    <td width="31%"><div align='center'><strong>Organigrama</strong></div></td>
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
BH_Ayuda('','');
$id_mapa = getPGVar("id_mapa");
$contenido = getPGVar("cont");
?>
<tr><td align="center">
<?php

if($id_mapa=="" && $cont=="") $id_mapa="10";

$cont=new mapa($id_mapa);

?>
<table width="100%"><tr><td><table align="left">
		<tr><td align="right">Ir a:</td><td><select name="ir_a" id="ir_a" onchange="ChangeArea(this.value);"><option value="">Principal</option><?php echo CboCG("area"); ?></select></td></tr>
		<?php
		if($cont->get_tipo()=="5" || $contenido!="")
		{
			if($cont->get_tipo()=="5")
				$area = explode("-",$cont->get_contenido());
			else if($cont!="")
				$area = explode("-",$contenido);
			?>
			<tr><td align="right">Lista:</td><td><select name="lista" id="lista" onchange="ChangeLista(this.value);"><?php
			$listas=$db->consulta("select lista, nombre from lista where lista in (select lista from area_lista where area='".$area[0]."')");
			while($lista=mysqli_fetch_array($listas)) { ?><option value="<?php echo $lista["lista"]; ?>"><?php echo $lista["nombre"]; ?></option><?php }
			?></select></td></tr>
			<?php
		}
		?>
	</table></td></tr><tr><td align="center">
<div style="width:950px; height:450px; overflow:auto; text-align:center;">
	<?php
	if($id_mapa=="10")
	{
		//generacion del mapa
		$hijos=$cont->get_hijos();
		?>
		<map name="mapa" id="mapa">
			<?php
			for($x=0;$x<@count($hijos);$x++)
			{
				$query="select * from mapa_submapa where mapa_padre='".$cont->get_id()."' and mapa_hijo='".$hijos[$x]."'";
				$inpho=mysqli_fetch_array($db->consulta($query));
				$query="select * from mapa where id_mapa='".$hijos[$x]."'";
				$inpho_map=mysqli_fetch_array($db->consulta($query));
				$doctos=@mysqli_fetch_array($db->consulta("select count(*) as n from mapa_doc_cont where id_mapa = '".$hijos[$x]."'"));
				if(($inpho_map["tipo"]!="" && $inpho_map["contenido"]!="") || ($inpho_map["tipo"]=="4" && intval($doctos["n"])>0))
				{
					?>
					<area shape="poly" coords="<?php echo $inpho["coordenadas"];?>" href="<?php echo "organigrama.php?id_mapa=".$hijos[$x]; ?>" title="<?php echo $inpho_map["comentarios"]?>" alt="<?php echo $inpho_map["comentarios"]?>" />
					<?php
				}
			}
			?>
		</map>
		<?php
		//colocacion de la imagen y lige al mapa
		?>
		<center><img src="img_mapas/<?php echo $cont->get_contenido(); ?>" usemap="#mapa" border="0" /></center>
		<?php
	}
	else if($cont->get_tipo()=="5" || $contenido!="") //Area
	{
		?>
		<table border="0" align="center">
			<tr>
			<?php
			if(trim($area[1])=="")
			{
				$query="select lista from area_lista where area='".$area[0]."' limit 1";
				$lista=mysqli_fetch_array($db->consulta($query));
				$area[1]=$lista["lista"];
			}
			$query="select persona from lista_persona where lista = '".$area[1]."' order by secuencia";
			$personas=$db->consulta($query);
			$x=0;
			$linea="";
			while($persona=mysqli_fetch_array($personas))
			{
				$x++;
				$query="select clave, nombre, apaterno, amaterno, imagen, puesto_actual from persona where clave = '".$persona["persona"]."'";
				$datos=mysqli_fetch_array($db->consulta($query));
				?>
				<td valign="baseline"><a href="organigrama_pers.php?clave=<?php echo $datos["clave"]; ?>&ret=1"><img src="img_personas/<?php echo $datos["imagen"];?>" align="<?php echo $datos["nombre"]." ".$datos["apaterno"]." ".$datos["amaterno"]; ?>" title="<?php echo $datos["nombre"]." ".$datos["apaterno"]." ".$datos["amaterno"]; ?>" height="100" border="0" /></a></td>
				<?php
				$pto=mysqli_fetch_array($db->consulta("select descripcion from puesto where clave='".$datos["puesto_actual"]."'"));
				$linea.='<td valign="top" align="center"><a href="organigrama_puesto.php?pto='.$datos["puesto_actual"].'">'.$pto["descripcion"].'</a></td>';
				if($x%5==0)
				{
					echo "</tr><tr>$linea</tr><tr>";
					$linea="";
				}
			}
			?>
			</tr><tr><?php echo $linea; ?><td>
		</table>
		<?php
	}
	else if($cont->get_tipo()=="6") //Personal
	{
		?>
		<script language="javascript">
			location.href='organigrama_pers.php?clave=<?php echo $cont->get_contenido(); ?>&ret=2';
		</script>
		<?php
	}
	?>
</div>
</td></tr></table>
</body>
<?php
if(isset($area[0]))
{
	?><script language="javascript">document.getElementById('ir_a').value='<?php echo $area[0]?>';</script><?php
}
if(isset($area[1]))
{
	?><script language="javascript">document.getElementById('lista').value='<?php echo $area[1]?>';</script><?php
}
?>
</html>
