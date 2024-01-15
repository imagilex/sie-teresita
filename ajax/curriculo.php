<?php

header("Expires: Mon, 22 Sep 1997 09:00:00 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");

include("../apoyo.php");
include("../u_db/data_base.php");

$db=new data_base(BD_USR, BD_HOST, BD_PASS, BD_BD);

$dato=PostString("clave").Get("clave");

if($dato=="") exit();

$dbasicos=@mysqli_fetch_array($db->consulta("select fecha_nacimiento,estado_civil, sexo, rfc from persona where clave='$dato'"));

$edo_civil=mysqli_fetch_array($db->consulta("select descripcion from codigos_generales where campo='Estado_civil' and valor='".$dbasicos["estado_civil"]."'"));
$fecha_actual=getdate();
$edad=$fecha_actual["year"]-intval(substr($dbasicos["fecha_nacimiento"],0,4));
if($fecha_actual["mon"]<intval(substr($dbasicos["fecha_nacimiento"],5,2))) $edad--;
else if($fecha_actual["mon"]==intval(substr($dbasicos["fecha_nacimiento"],5,2))) { if($fecha_actual["mday"]<intval(substr($dbasicos["fecha_nacimiento"],8,2))) $edad--; }
$nacionalidad="";
if($vars1=$db->consulta("select descripcion as nacion from persona, codigos_generales where clave='$dato' and campo = 'Nacionalidad' and nacionalidad = valor"))
{
	$vars2=mysqli_fetch_array($vars1);
	$nacionalidad=$vars2["nacion"];
}
if($nacionalidad=="")
{
	if($vars1=$db->consulta("select descripcion as nacion from persona, codigos_generales where clave='$dato' and campo = 'Nacionalidad' and concat( substring( nacionalidad, 1, length( nacionalidad ) -1 ) , 'F' )  = valor"))
	{
		$vars2=mysqli_fetch_array($vars1);
		if($vars2["nacion"]!="") $nacionalidad="Nacionalidad ".$vars2["nacion"];
	}
}
$query="select * from persona_direccion where persona='$dato'";
$direc=@mysqli_fetch_array($db->consulta($query));
$query="select * from codigo_postal where cp='".$direc["codigo_postal"]."'";
$direc2=@mysqli_fetch_array($db->consulta($query));
?>
<table align="center" width="90%" style="text-transform:capitalize;">
	<tr>
		<td width="60%" valign="top" align="left">
			<table border="0">
				<tr><td align="left"><?php echo htmlentities($edad." años"); ?></td></tr>
				<tr><td align="left"><?php echo htmlentities(substr($edo_civil["descripcion"],0,strlen($edo_civil["descripcion"])-1).(($dbasicos["sexo"]=="F")?("a"):("o"))); ?></td></tr>
				<tr><td align="left"><?php echo htmlentities($nacionalidad); ?></td></tr>
				<tr><td align="left" style="text-transform:uppercase;"><?php echo htmlentities($dbasicos["rfc"]); ?></td></tr>
				<tr><td align="left">&nbsp;&nbsp;</td></tr>
				<?php
				if($ests=$db->consulta("select niv.descripcion as p_nivel, nivel, inst.descripcion as p_inst, carrera from persona_estudios inner join codigos_generales as niv on niv.campo='nivel' and niv.valor=persona_estudios.nivel left join codigos_generales as inst on inst.campo='institucion' and inst.valor=persona_estudios.institucion where persona='$dato'"))
				{
					while($est=mysqli_fetch_array($ests))
					{
						$campo="";
						if($est["nivel"]=="1") $campo="doctorado";
						if($est["nivel"]=="2") $campo="maestr_a";
						if($est["nivel"]=="3" || $est["nivel"]=="4" || $est["nivel"]=="5") $campo="carrera";
						$query="select descripcion from codigos_generales where campo like '$campo' and valor='".$est["carrera"]."'";
						$carr=mysqli_fetch_array($db->consulta($query));
						if($est["p_nivel"]!="")
						{
							echo '<tr><td align="left" style="text-transform:none;">';
							echo htmlentities($est["p_nivel"]);
							if($carr["descripcion"]!="") { echo htmlentities(" en ".$carr["descripcion"]); }
							if($est["p_inst"]!="") { echo htmlentities(" (".$est["p_inst"].")"); }
							echo '</td></tr>';
						}
					}
				}
				?>
			</table>
		</td>
		<td width="40%" valign="top" align="center">
			<table border="0">
				<tr><td align="left" colspan="5"><strong>Medios de Contacto</strong></td></tr>
				<?php
				if($medios=$db->consulta("select persona_contacto.medio_contacto,codigos_generales.descripcion as mc, persona_contacto.comentarios as c, persona_contacto.valor as v from persona_contacto inner join codigos_generales on codigos_generales.campo='medio_contacto' and codigos_generales.valor=persona_contacto.medio_contacto where persona_contacto.persona='$dato'"))
				{
					while($medio=mysqli_fetch_array($medios))
					{
						echo '<tr><td align="right">';
						if($medio["medio_contacto"]=="1" || $medio["medio_contacto"]=="2")
						{
							echo htmlentities($medio["mc"]).": <td></td><td align=\"left\" style=\"text-transform:lowercase\"><a href='mailto:".$medio["v"]."'>".htmlentities($medio["v"])."</a>";
						}
						else
						{
							echo htmlentities($medio["mc"]).": <td></td><td align=\"left\">".htmlentities($medio["v"]);
						}
						if($medio["c"]!="") echo htmlentities(" (".$medio["c"].")");
						echo '</td></tr>';
					}
				}
				?>
			</table>
		</td>
	</tr>
	<tr><td colspan="2" align="left">&nbsp;</td></tr>
	<tr><td colspan="2" align="left">
		<strong>Zona de Residencia</strong>
	</td></tr>
	<tr><td colspan="2" style="padding-left:20px;" align="left">
		<?php
		echo htmlentities($direc["calle"]);
		if($direc["numero_exterior"]!="")
			echo htmlentities(" No. ".$direc["numero_exterior"]);
		if($direc["numero_interior"]!="")
			echo htmlentities(" (".$direc["numero_interior"].")");
		?><br />Colonia: <?php echo htmlentities($direc2["colonia"]); ?>,<br /><?php echo htmlentities($direc2["municipio"]); ?>, <?php echo htmlentities($direc2["estado"]); ?>, C.P. <?php echo htmlentities($direc["codigo_postal"]); ?>
	</td></tr>
</table>
