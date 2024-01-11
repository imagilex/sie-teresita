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
		document.seg.accion.value="5";
	</script>
</div>
<?php
BH_Ayuda('0.4.51.1','5');
$menu_actual=PostString("menu_opc");

$prefijos=PostString("prefijo");
$opciones=PostString("opcion");
$cambios=PostString("cambio");

$pos=PostString("pos");
$descr=PostString("descr");

$total=max( count($prefijos), count($opciones), count($cambios), count($pos), count($descr));

for($x=0; $x<$total; $x++)
{
	if(isset($cambios[$x]) && $cambios[$x]=='yes')
	{
		$pref=$prefijos[$x];
		$opci=$opciones[$x];
		$posi=$pos[$x];
		$desc=$descr[$x];
		mysql_query("update menu set descripcion='$desc', posicion='$posi' where prefijo_menu='$pref' and opcion='$opci'");
	}
}

?>
<form name="menu" action="menu_sist.php" method="post">
<input type="hidden" name="sincambios" value="" />
	<table border="0" align="center">
		<tr>
			<td align="left" colspan="2">
				Men&uacute;:
				<select name="menu_opc" onchange="javascript: document.menu.sincambios.value='yes'; document.menu.submit()">
					<option value="">Raíz</option>
					<?php
					if($menus_bd=mysql_query("select m1.prefijo_menu as prefijo, m1.opcion as opc, m1.descripcion as descr from menu as m1, menu as m2 where concat(m1.prefijo_menu,'.',m1.opcion)=m2.prefijo_menu or (m1.prefijo_menu='' and m2.prefijo_menu=m1.opcion) group by m1.prefijo_menu, m1.descripcion order by m1.prefijo_menu, m1.opcion"))
					{
						while($menu_bd=mysql_fetch_array($menus_bd))
						{
							?>
							<option value="<?php echo $menu_bd["prefijo"]; ?>.<?php echo $menu_bd["opc"]; ?>"><?php echo (($menu_bd["prefijo"]!="")?($menu_bd["prefijo"]."."):("")).$menu_bd["opc"].". ".$menu_bd["descr"]; ?></option>
							<?php
						}
					}
					?>
				</select>
				<script language="javascript">
					document.menu.menu_opc.value="<?php echo $menu_actual?>";
				</script>
			</td>
			<td align="right">
				<input type="submit" value="Guardar" class="btn_normal" />
			</td>
		</tr>
		<?php
		if(substr($menu_actual,0,1)==".")
			$pref=substr($menu_actual,1);
		else
			$pref=$menu_actual;
		if($elementos=mysql_query("select prefijo_menu, opcion, descripcion, posicion from menu where prefijo_menu='$pref' order by posicion"))
		{
			$x=0;
			while($elemento=mysql_fetch_array($elementos))
			{
				$x++;
				?>
				<tr>
					<td align="right">
						<?php echo $elemento["prefijo_menu"]; ?><?php echo (($elemento["prefijo_menu"]!="")?("."):("")); ?><?php echo $elemento["opcion"]; ?>
					</td>
					<td>
						<input type="text" maxlength="250" size="75" name="descr[]" value="<?php echo $elemento["descripcion"]; ?>" onchange="javascript: $('cambio<?php echo $x; ?>').value='yes';" />
						<input type="text" maxlength="4" size="4" name="pos[]" value="<?php echo $elemento["posicion"]; ?>" onchange="javascript: $('cambio<?php echo $x; ?>').value='yes';" />
						<input type="hidden" name="prefijo[]" value="<?php echo $elemento["prefijo_menu"]; ?>" />
						<input type="hidden" name="opcion[]" value="<?php echo $elemento["opcion"]; ?>" />
						<input type="hidden" name="cambio[]" id="cambio<?php echo $x;?>" value="" />
					</td>
					<td></td>
				</tr>
				<?php
			}
		}
		?>
	</table>
</form>
</body>
</html>
<?php

mysql_close();

?>