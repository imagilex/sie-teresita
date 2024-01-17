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

$proyecto = Get_Vars_Helper::getPGVar("proyecto");
$meses=0;
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
	function CambioVer()
	{
		if(document.data_control.ver.value="1")
		{
			location.href='_vista_detalle.php?proyecto=<?php echo $proyecto ;?>'
		}
		document.data_control.ver.value="1";
		return false;
	}
</script>
<style type="text/css">
	.proyecto
	{
		text-align:center;
		color:#999999;
	}
	.data_title, .data_title_1
	{
		color:#000000;
		background-color:#CCCCFF;
		border-color:#000000;
		border-width:1px;
		border-bottom-style:solid;
		border-left-style:none;
		border-right-style:solid;
		border-top-style:solid;
		text-align:left;
	}
	.data_title_1
	{
		border-left-style:solid;
	}
	.data_fase
	{
		color:#000000;
		background-color:#FFFFCC;
		text-align:left;
		border-bottom-style:solid;
		border-left-style:solid;
		border-right-style:solid;
		border-top-style:none;
		border-width:1px;
		border-color:#000000;
	}
	.data_normal, .data_normal_1, .data_normal_2, .data_foot, .data_foot_1, .data_foot_2
	{
		color:#000000;
		text-align:center;
		border-bottom-style:solid;
		border-left-style:none;
		border-right-style:solid;
		border-top-style:none;
		border-width:1px;
		border-color:#000000;
		padding:5px;
		font-size:10px;
	}
	.data_normal_1, .data_foot_1
	{
		border-left-style:solid;
		text-align:left;
	}
	.data_normal_2, .data_foot_2
	{
		text-align:right;
	}
	.data_foot, .data_foot_1, .data_foot_2
	{
		background-color:#CCCCCC;
	}
</style>
</head>

<body>
<?php

BarraHerramientas();

?>
<?php
BH_Ayuda('0.4','');
?>
<form name="data_control" action="_vista_explo_01.php" method="post">
<table border="0" align="center">
	<tr>
		<td>Plan:</td>
		<td>
			<select name="plan" onchange="javascript: location.href='_vista_explo_01.php?proyecto='+this.value">
				<?php
				if($proys_db=consulta_directa("select proyecto, nombre from proyecto order by nombre"))
				{
					while($proy_db=mysqli_fetch_array($proys_db))
					{
						?>
						<option value="<?php echo $proy_db["proyecto"]; ?>"><?php echo $proy_db["nombre"]; ?></option>
						<?php
					}
				}
				?>
			</select>
			<script language="javascript">
				document.data_control.plan.value="<?php echo $proyecto; ?>";
			</script>
		</td>
		<td>Ver:</td>
		<td>
			<select name="ver" onchange="CambioVer(this.value);">
				<?php menu_items($_SESSION["tipo"], "0.4.3.1.1.1"); ?>
			</select>
			<script language="javascript">
				document.data_control.ver.value="2";
			</script>
		</td>
		<td><input type="button" value="Formatos" class="btn_normal" /></td>
	</tr>
</table>
</form>
<?php
$data_1=@mysqli_fetch_array(consulta_directa("select nombre, lider from proyecto where proyecto='$proyecto'"));
?>
<h1 class="proyecto"><?php echo $data_1["nombre"]; ?> (<?php echo $data_1["lider"]; ?>)</h1>
<table cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td class="data_title_1">Entregable</td>
		<td class="data_title">Estatus</td>
	</tr>
	<?php
	if($data_1_1_db=consulta_directa("select fase, descripcion from proyecto_fase where proyecto = '$proyecto' order by fase"))
	{
		$x=0;
		while($data_1_1=mysqli_fetch_array($data_1_1_db))
		{
			$x++
			?>
			<tr>
				<td class="data_fase" colspan="<?php echo intval($meses+2); ?>">FASE <?php echo $x ;?>: <?php echo $data_1_1["descripcion"]; ?></td>
			</tr>
			<?php
			if($data_1_2_db=consulta_directa("select entregable, descripcion from proyecto_fase_entregable where proyecto = '$proyecto' and fase = '".$data_1_1["fase"]."' order by entregable, descripcion"))
			{
				while($data_1_2=mysqli_fetch_array($data_1_2_db))
				{
					?>
					<tr>
						<td rowspan="3" class="data_normal_1"><?php echo $data_1_2["descripcion"]; ?></td>
						<td class="data_normal_2">Planeado</td>
					</tr>
					<tr>
						<td class="data_normal_2">Avance</td>
					</tr>
					<tr>
						<td class="data_normal_2">Tiempo</td>
					</tr>
					<?php
				}
			}
		}
	}
	?>
	<tr>
		<td rowspan="3" class="data_foot_1">&nbsp;</td>
		<td class="data_foot_2">Planeado</td>
	</tr>
	<tr>
		<td class="data_foot_2">Avance</td>
	</tr>
	<tr>
		<td class="data_foot_2">Tiempo</td>
	</tr>
</table>
</body>
</html>
