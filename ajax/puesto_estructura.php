<?php

header("Expires: Mon, 22 Sep 1997 09:00:00 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");

include("../apoyo.php");
include("../u_db/data_base.php");

$db=new data_base("root", "10.5.0.5", "password123", "teresita_intranet");

$dato=PostString("pto").Get("pto");

if($dato=="") exit();

$pto=@mysql_fetch_array($db->consulta("select descripcion from puesto where clave='$dato'"));
$query="select descripcion from puesto where clave in (select puesto_padre from organigrama where puesto_hijo='$dato')";
$jefe=@mysql_fetch_array($db->consulta($query));
$query="select descripcion from puesto where clave in (select puesto_hijo from organigrama where puesto_padre='$dato') order by descripcion";
$subditos=$db->consulta($query);
?>
<table align="center" border="0">
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	  <th align="right" style="padding-right:20px;">Reporta a: </th>
	  <td valign="middle" align="center"><?php echo htmlentities($jefe["descripcion"]); ?></td></tr>
	<tr><th align="right" style="padding-right:20px;">Puesto: </th><td valign="middle" align="center">
		<img src="Imagenes/up.png" border="0" /><br />&nbsp;<br />
		<font size="+2"><a href="organigrama_puesto.php?pto=<?php echo $dato; ?>" title="Descriptivo de Puesto"><?php echo htmlentities($pto["descripcion"]); ?></a></font><br />&nbsp;<br />
		<?php
		if(mysql_num_rows($subditos)>0) echo '<img src="Imagenes/down.png" border="0" />';
		?>
	</td></tr><?php
	if(mysql_num_rows($subditos)>0)
	{
	?>
	<tr><th align="right" style="padding-right:20px;">Le reportan: </th><td valign="middle" align="center"><?php while($subdito=mysql_fetch_array($subditos)) echo htmlentities($subdito["descripcion"])."<br />" ?></td></tr>
	<?php
	}
	?>
</table>
