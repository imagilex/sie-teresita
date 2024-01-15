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

function TieneHijos($prefijo,$opcion)
{
	$mysqli = Conectar();
	if($prefijo!="")
		$cuantos=mysqli_fetch_array(consulta_directa($mysqli, "select count(*) as n from menu where prefijo_menu='$prefijo.$opcion'"));
	else
		$cuantos=mysqli_fetch_array(consulta_directa($mysqli, "select count(*) as n from menu where prefijo_menu='$opcion'"));
	if(intval($cuantos["n"])>0)
		return true;
	return false;
}

function Hijos($prefijo,$opcion,$funcion="")
{
	$mysqli = Conectar();
	$menu=mysqli_fetch_array(consulta_directa($mysqli, "select descripcion from menu where prefijo_menu='$prefijo' and opcion='$opcion'"));
	$cuantos=mysqli_fetch_array(consulta_directa($mysqli, "select count(*) as n from funcion_menu where funcion='$funcion' and prefijo_menu='$prefijo' and opcion='$opcion'"));
	if(intval($cuantos["n"])>0)
	{
		$chec=" checked='checked'";
	}
	else
	{
		$chec="";
	}
	echo "\n".'<li><label><input type="checkbox" name="Reg[]" id="'.$prefijo.'-'.$opcion.'" value="'.$prefijo.'-'.$opcion.'" onchange="javascript: if(this.checked) Checkar(this.id);"'.$chec.' />('.$prefijo.'-'.$opcion.') '.$menu["descripcion"].'</label></li>';
	if(TieneHijos($prefijo,$opcion))
	{
		if($prefijo!="")
			$hijos_bd=consulta_directa($mysqli, "select prefijo_menu,opcion from menu where prefijo_menu='$prefijo.$opcion' order by posicion");
		else
			$hijos_bd=consulta_directa($mysqli, "select prefijo_menu,opcion from menu where prefijo_menu='$opcion' order by posicion");
		echo "\n<ul>";
		while($hijo_actual=mysqli_fetch_array($hijos_bd))
			Hijos($hijo_actual["prefijo_menu"],$hijo_actual["opcion"],$funcion);
		echo "\n</ul>";
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
<style type="text/css">
	ul
	{
		list-style-type: none;
	}
</style>
<script language="javascript">
	function Checkar(id_objeto)
	{
		var nuevo="",aux="",aux2="";
		if($(id_objeto).checked)
		{
			aux2=id_objeto
			while(aux2!="" && aux2!="-")
			{
				aux=aux2.substring(0,aux2.lastIndexOf('-'));
				nuevo=aux.substring(0,aux.lastIndexOf('.'))+'-'+aux.substring(aux.lastIndexOf('.')+1);
				if(nuevo!="" && nuevo!="-")
					$(nuevo).checked="checked";
				aux2=nuevo;
			}
		}
	}
</script>
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
		document.seg.accion.value="4";
	</script>
</div>
<?php
BH_Ayuda('0.4.51.1','4');

$funcion=PostString("funcion");
if(PostString("sincambios")!="yes" && $funcion!="")
{
	$registros=PostString("Reg");
	$total=count($registros);
	if($total>0)
	{
		consulta_directa($Con, "delete from funcion_menu where funcion='$funcion'");
	}
	for($x=0;$x<$total && isset($registros[$x]);$x++)
	{
		$actual=explode("-",$registros[$x]);
		$prefijo=$actual[0];
		$opcion=$actual[1];
		consulta_directa($Con, "insert into funcion_menu (funcion, prefijo_menu, opcion) values ('$funcion', '$prefijo', '$opcion');");
		ErrorMySQLAlert();
	}
}
?>
<form name="derechos" action="asign_opc.php" method="post">
<input type="hidden" name="sincambios" value="" />
<table border="0" align="center">
	<tr>
		<td>
			<table border="0" align="left">
				<tr>
					<td align="right">
						Tipo de funci&oacute;n:
					</td>
					<td>
						<select name="funcion" onchange="javascript: document.derechos.sincambios.value='yes'; document.derechos.submit()">
							<option value=""></option>
							<?php
							echo CboCG("funcion");
							?>
						</select>
						<script language="javascript">
							document.derechos.funcion.value="<?php echo $funcion; ?>";
						</script>
					</td>
				</tr>
			</table>
		</td>
		<td align="right">
			<input type="submit" value="Guardar" class="btn_normal" />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			Opciones:
		</td>
	</tr>
	<tr>
		<td style="font-size:small;" colspan="2">
			<?php
			if($raiz=consulta_directa($Con, "select prefijo_menu,opcion, descripcion from menu where prefijo_menu='' order by posicion"))
			{
				echo "\n<ul>";
				while($menu=mysqli_fetch_array($raiz))
				{
					Hijos($menu["prefijo_menu"],$menu["opcion"],$funcion);
				}
				echo "\n</ul>";
			}
			?>
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?php

mysqli_close($Con);

?>
