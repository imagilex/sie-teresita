<?php

header("Expires: Mon, 22 Sep 1997 09:00:00 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");

include("../apoyo.php");
include("../u_db/data_base.php");

$db=new data_base(BD_USR, BD_HOST, BD_PASS, BD_BD);

$dato = Get_Vars_Helper::getPGVar("clave");

if($dato=="") exit();

$query="select fecha_ingreso from persona where clave='$dato'";
$ant=@mysqli_fetch_array($db->consulta($query));
$fecha_actual=getdate();
$antiguedad=$fecha_actual["year"]-intval(substr($ant["fecha_ingreso"],0,4));
if($fecha_actual["mon"]<intval(substr($ant["fecha_ingreso"],5,2))) $antiguedad--;
else if($fecha_actual["mon"]==intval(substr($ant["fecha_ingreso"],5,2))) { if($fecha_actual["mday"]<intval(substr($ant["fecha_ingreso"],8,2))) $antiguedad--; }
if($antiguedad<1)
{
	$dias="";
	if($fecha_actual["year"]==substr($ant["fecha_ingreso"],0,4))
	{
		$meses=$fecha_actual["mon"]-substr($ant["fecha_ingreso"],5,2);
		if($meses==0)
		{
			$dias=$fecha_actual["mday"]-substr($ant["fecha_ingreso"],8,2);
		}
		else if($meses==1 && $fecha_actual["mday"] < substr($ant["fecha_ingreso"],8,2))
		{
			$dias=$fecha_actual["mday"] + (30 - substr($ant["fecha_ingreso"],8,2));
		}
	}
	else
		$meses=12-substr($ant["fecha_ingreso"],5,2)+$fecha_actual["mon"];
	if($dias!="")
	{
		$antiguedad="$dias D&iacute;as";
	}
	else if($meses>1)
		$antiguedad="$meses Meses";
	else
		$antiguedad="$meses Mes";
}
else if($antiguedad==1)
{
	$antiguedad="$antiguedad A&ntilde;o";
}
else
{
	$antiguedad="$antiguedad A&ntilde;os";
}

?>
<table border="0" width="100%" style="color:#666666;"><tr>
  <td align="right"><strong>Antig&uuml;edad: <?php echo $antiguedad; ?></strong></td>
</tr></table>
<table border="0" align="left">
	<tr style="color:#666666;"><th align="left" style="padding-left:20px;">Fecha</th><th align="left" style="padding-left:20px;">Puesto</th><th align="left" style="padding-left:20px;">Evento</th></tr>
	<?php
	$query="select fecha as anio, puesto, comentarios from historia_empleado where persona='$dato' order by fecha, puesto";
	if($regs=$db->consulta($query))
	{
		$anio="";
		$puesto="";
		while($reg=mysqli_fetch_array($regs))
		{
			$pto=@mysqli_fetch_array($db->consulta("select descripcion from puesto where clave='".$reg["puesto"]."'"));
			echo "<tr><td style='padding-left:20px;' align='right'>";
			if($anio!=$reg["anio"]) {$anio=DateConvencional($reg["anio"]); echo htmlentities($anio); }
			else echo "&nbsp;&nbsp;";
			echo "</td><td style='padding-left:20px;' align='left'>";
			if($puesto!=$reg["puesto"]) {$puesto=$reg["puesto"]; echo htmlentities($pto["descripcion"]); }
			else echo "&nbsp;&nbsp;";
			echo "</td><td style='padding-left:20px;' align='left'>";
			if($reg["comentarios"]!="") {echo htmlentities($reg["comentarios"]); }
			else echo "&nbsp;&nbsp;";
			echo "</td></tr>";
		}
	}
	?>
</table>
