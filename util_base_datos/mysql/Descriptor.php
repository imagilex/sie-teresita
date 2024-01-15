<?php include("../../apoyo.php"); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Descriptor de Bases de Datos</title>
</head>

<body>
<?php
$tbls=CTabla("codigos_generales");
if($regs_tablas=$tbls->query("show tables"));
{
	?>
	<table border="1" cellpadding="0" cellspacing="0" align="left">
	<?php
	while($tabla=$tbls->registro($regs_tablas))
	{
		?>
		<tr><td><table border="0" align="left"><tr><td align="left" colspan="2"><strong><?php echo $tabla["Tables_in_".BD_BD]; ?></strong></td></tr><tr style="font-size:small;"><th>Campo</th><th>Tipo</th></tr>
			<?php
			if($regs_fields=$tbls->query("describe ".$tabla["Tables_in_".BD_BD]))
			{
				while($reg=$tbls->registro($regs_fields))
				{
					?>
					<tr style="font-size:small;"><td><?php echo $reg["Field"]; ?></td><td><?php echo $reg["Type"]; ?></td></tr>
					<?php
				}
			}
			?>
		</table>
		</td></tr><tr><td>&nbsp;</td></tr>
		<?php
	}
	?>
	</table>
	<?php
}
?>
</body>
</html>