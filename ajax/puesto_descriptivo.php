<?php

session_start();

include "../apoyo.php";

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]) )
{
	header("location: ../index.php?noCache=".rand(0,32000));
	exit();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita</title>
<link href="../estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../apoyo_js.js"></script>
<script language="javascript" src="../prototype.js"></script>
<style type="text/css">
	.salto
	{
		page-break-after:always;
		visibility:hidden;
	}
</style>
</head>
<?php
$regs = mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Principal' and elemento='Logo'"));
$Logo = $regs["valor"];
$pto = Get_Vars_Helper::getPGVar("pto");
$persona = Get_Vars_Helper::getPGVar("persona");
?>
<body>
<?php
if($pto=="" && $persona=="") exit();
$puestos = array();
if($persona=="" && $pto!="")
{
	$puestos[]=$pto;
}
else if($persona!="" && $pto=="")
{
	$query="select distinct puesto_actual as p from persona where clave in ('".str_replace(",","','",$persona)."')";
	$ps=consulta_directa($query);
	while($p=mysqli_fetch_array($ps)) { $puestos[]=$p["p"]; }
}
else
{
	exit();
}
$aux=0;
foreach($puestos as $pto)
{
$aux++;
$puesto=@mysqli_fetch_array(consulta_directa("select puesto.clave as clave, puesto.descripcion as descripcion, are.descripcion as area, dep.descripcion as departamento, proposito from puesto inner join codigos_generales as are on are.campo='area' and are.valor=puesto.area inner join codigos_generales as dep on dep.campo='departamento' and dep.valor=puesto.departamento where clave='$pto'"));
$query="select descripcion from puesto where clave in (select puesto_padre from organigrama where puesto_hijo='$pto')";
$jefe=@mysqli_fetch_array(consulta_directa($query));
$query="select descripcion from puesto where clave in (select puesto_hijo from organigrama where puesto_padre='$pto') order by descripcion";
$subditos=consulta_directa($query);
?>
<table border="0" align="center" width="800">
	<tr>
		<td align="left" width="50%"><img src="../Archivos_Secciones/<?php echo $Logo?>" /></td>
		<td align="left" width="50%" style="font-size:2em;">Descriptivo de Puesto</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" align="center" style="font-size:1.5em;"><?php echo $puesto["descripcion"]; ?></td></tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td align="left" width="50%" valign="top"><strong>&Aacute;rea: </strong><?php echo $puesto["area"]; ?></td>
		<td align="left" width="50%" valign="top"><strong>Departamento: </strong><?php echo $puesto["departamento"]; ?></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td align="left" width="50%" valign="top"><strong>Reporta a: </strong><?php echo $jefe["descripcion"]; ?></td>
		<td align="left" width="50%" valign="top"><strong>Puestos que le reportan: </strong><ul style="margin:0px;"><?php while($subdito=mysqli_fetch_array($subditos)) echo "<li>".htmlentities($subdito["descripcion"])."</li>" ?></ul></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" align="left"><strong>Prop&oacute;sito principal:</strong></td></tr>
	<tr><td colspan="2" align="left"><?php echo $puesto["proposito"]; ?></td></tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" align="left"><strong>Es responsable de:</strong></td></tr>
	<tr><td colspan="2" align="left"><?php
		if($regs=consulta_directa("select responsabilidad from puesto_responsabilidad where puesto='$pto' order by secuencia"))
		{
			?><ol type="1"><?php
			while($reg=mysqli_fetch_array($regs))
			{
				echo "<li>".htmlentities($reg["responsabilidad"])."</li>";
			}
			?></ol><?php
		}
	?></td></tr>
	<?php
	if($datos=consulta_directa("select distinct indicador.nombre as nomb from persona inner join indicador_responsable on clave=responsable inner join indicador_nivel on id_indicador=id_indicador_nivel inner join indicador on indicador_nivel.indicador=indicador.indicador where puesto_actual='$pto'"))
	{
		$x=0;
		while($dato=mysqli_fetch_array($datos))
		{
			if($x==0){ echo '<tr><td colspan="2" align="left"><strong>Indicadores</strong></td></tr><tr><td colspan="2" align="left"><ol type="1">'; $x++; }
			echo "<li>Indicador de ".htmlentities($dato["nomb"])."</li>";
		}
		if($x>0) { echo '</ol></td></tr>'; }
	}
	if($datos=consulta_directa("select distinct reportes.nombre as nomb from persona inner join reportes_responsable on persona.clave=reportes_responsable.responsable inner join reporte_nivel on reportes_responsable.id_reporte=reporte_nivel.id_reporte inner join reportes on reporte_nivel.reporte=reportes.reporte where puesto_actual='$pto'"))
	{
		$x=0;
		while($dato=mysqli_fetch_array($datos))
		{
			if($x==0){ echo '<tr><td colspan="2" align="left"><strong>Reportes</strong></td></tr><tr><td colspan="2" align="left"><ol type="1">'; $x++; }
			echo "<li>Reporte de ".htmlentities($dato["nomb"])."</li>";
		}
		if($x>0) { echo '</ol></td></tr>'; }
	}
	?>
</table>
<?php
if($aux<@count($puestos)) echo '<hr class="salto" />';
}
?>
</body>
</html>
