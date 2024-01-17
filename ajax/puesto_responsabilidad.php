<?php

header("Expires: Mon, 22 Sep 1997 09:00:00 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");

include("../apoyo.php");
include("../u_db/data_base.php");

$db = new data_base(MAIN_DB->usr, MAIN_DB->host, MAIN_DB->pass, MAIN_DB->bd);

$dato = Get_Vars_Helper::getPGVar("pto");

if($dato=="") exit();
?>
<table border="0" align="left"><tr><td align="left">
	<?php
	if($regs=$db->consulta("select responsabilidad from puesto_responsabilidad where puesto='$dato' order by secuencia"))
	{
		?><ol type="1"><?php
		while($reg=mysqli_fetch_array($regs))
		{
			echo "<li>".htmlentities($reg["responsabilidad"])."</li>";
		}
		?></ol><?php
	}
	if($datos=$db->consulta("select indicador.nombre as nomb, descripcion as descr from persona inner join indicador_responsable on clave=responsable inner join indicador_nivel on id_indicador=id_indicador_nivel inner join indicador on indicador_nivel.indicador=indicador.indicador where puesto_actual='$pto'"))
	{
		$x=0;
		while($dato=mysqli_fetch_array($datos))
		{
			if($x==0){ echo '<p style="font-size:large;">Indicadores</p><ol type="1" style="padding-left:20px;">'; $x++; }
			echo "<li>Indicador de ".htmlentities($dato["nomb"]).(($dato["descr"]!="")?(" (".htmlentities($dato["descr"]).")"):(""))."</li>";
		}
		if($x>0) { echo '</ol>'; }
	}
	if($datos=$db->consulta("select reportes.nombre as nomb, descripcion as descr from persona inner join reportes_responsable on persona.clave=reportes_responsable.responsable inner join reporte_nivel on reportes_responsable.id_reporte=reporte_nivel.id_reporte inner join reportes on reporte_nivel.reporte=reportes.reporte where puesto_actual='$pto'"))
	{
		$x=0;
		while($dato=mysqli_fetch_array($datos))
		{
			if($x==0){ echo '<p style="font-size:large;">Reportes</p><ol type="1" style="padding-left:20px;">'; $x++; }
			echo "<li>Reporte de ".htmlentities($dato["nomb"]).(($dato["descr"]!="")?(" (".htmlentities($dato["descr"]).")"):(""))."</li>";
		}
		if($x>0) { echo '</ol>'; }
	}
	?>
</td></tr></table>
