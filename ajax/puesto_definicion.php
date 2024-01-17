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

$pto=@mysqli_fetch_array($db->consulta("select puesto.clave as cla, puesto.descripcion as des, cg.descripcion as are, dep.descripcion as dep, puesto.proposito as pro from puesto inner join codigos_generales as cg on cg.campo='area' and cg.valor=puesto.area left join codigos_generales as dep on dep.campo='departamento' and dep.valor=puesto.departamento where puesto.clave='$dato'"));
?>
<table border="0" align="left">
    <tr><td align="right">Clave:</td><td align="left"><?php echo htmlentities($pto["cla"]); ?></td></tr>
    <tr><td align="right">Descripci&oacute;n:</td><td align="left"><?php echo htmlentities($pto["des"]); ?></td></tr>
    <tr><td align="right">&Aacute;rea:</td><td align="left"><?php echo htmlentities($pto["are"]); ?></td></tr>
    <tr><td align="right">Departamento:</td><td align="left"><?php echo htmlentities($pto["dep"]); ?></td></tr>
    <tr><td align="right">Prop&oacute;sito Principal:</td><td align="left"><font size="+1"><?php echo htmlentities($pto["pro"]); ?></font></td></tr>
</table>
