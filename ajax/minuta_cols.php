<?php
session_start();
include("../apoyo.php");
$accion = getPGVar("accion");
$herramienta = getPGVar("herramienta");

if($accion!="" && $herramienta!="")
{
	$thpp=CTabla("herr_pant_preferencias");
	if($accion=="mostrar")
	{
		$thpc=CTabla("herr_pant_campos");
		$rc=$thpc->select("*","tabla in (select id_tabla from herr_pant_tablas where id_herramienta='$herramienta')","alias");
		$rp=$thpp->select("*","herramienta='$herramienta' and usuario='".$_SESSION["id_usr"]."'","posicion, etiqueta");
		?>
		<form action="minuta_cols.php" method="post" id="dataFrm" name="dataFrm">
		<input type="hidden" id="accion" name="accion" value="salvar" />
		<input type="hidden" id="herramienta" name="herramienta" value="<?php echo $herramienta; ?>" />
		<table border="0" align="center"><tr><td valign="top">
		<table border="0" align="center">
			<thead><tr><th align="left">Visible</th><th align="left">Columna</th><th align="left">Etiqueta</th><th align="left">Posici&oacute;n</th><th align="left">Orden</th></tr></thead>
			<tbody id="dataTbl">
			<?php
			$x=0;
			$act_cols=array();
			foreach($rp as $reg)
			{
				$x++;
				$alias=$thpc->select("alias","id_campo='".$reg["campo"]."'");
				$act_cols[]=$reg["campo"];
				?>
				<tr>
					<td align="center">
						<input type="checkbox" checked="checked" name="vis_<?php echo $x; ?>" id="vis_<?php echo $x; ?>" value="<?php echo $reg["campo"]; ?>" />
					</td>
					<td>
						<?php echo htmlentities($alias[0]["alias"]); ?>
					</td>
					<td>
						<input type="text" maxlength="250" size="25" name="eti_<?php echo $x; ?>" id="eti_<?php echo $x; ?>" value="<?php echo htmlentities($reg["etiqueta"]); ?>" />
					</td>
					<td>
						<input type="text" maxlength="4" size="4" name="pos_<?php echo $x; ?>" id="pos_<?php echo $x; ?>" value="<?php echo htmlentities($reg["posicion"]); ?>" />
					</td>
					<td>
						<select name="ord_<?php echo $x; ?>" id="ord_<?php echo $x; ?>">
							<option value="ASC" <?php echo (($reg["orden"]=="ASC")?('selected="selected"'):('')); ?>>Ascendente</option>
							<option value="DESC" <?php echo (($reg["orden"]=="ASC")?(''):('selected="selected"')); ?>>Descendente</option>
						</select>
					</td>
				</tr>
				<?php
			}
			foreach($rc as $reg)
			{
				$esta=false;
				foreach($act_cols as $col)
				{
					if($reg["id_campo"]==$col)
					{
						$esta=true;
						break;
					}
				}
				if(!$esta)
				{
					$x++;
					?>
					<tr>
						<td align="center">
							<input type="checkbox" name="vis_<?php echo $x; ?>" id="vis_<?php echo $x; ?>" value="<?php echo $reg["id_campo"]; ?>" />
						</td>
						<td>
							<?php echo htmlentities($reg["alias"]); ?>
						</td>
						<td>
							<input type="text" maxlength="250" size="25" name="eti_<?php echo $x; ?>" id="eti_<?php echo $x; ?>" value="<?php echo htmlentities($reg["alias"]); ?>" />
						</td>
						<td>
							<input type="text" maxlength="4" size="4" name="pos_<?php echo $x; ?>" id="pos_<?php echo $x; ?>" value="<?php echo htmlentities($x); ?>" />
						</td>
						<td>
							<select name="ord_<?php echo $x; ?>" id="ord_<?php echo $x; ?>">
								<option value="ASC" selected="selected">Ascendente</option>
								<option value="DESC">Descendente</option>
							</select>
						</td>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
		</table></td><td align="right" valign="top"><img src="Imagenes/iconografia/10.png" alt="Guardar Cambios" title="Guardar Cambios" onclick="SaveCols()" /></td></tr></table>
		<input type="hidden" name="total_cols" id="total_cols" value="<?php echo $x; ?>" />
		</form>
		<?php
	}
	else if($accion=="salvar")
	{
		$total = getPGVar("total_cols");
		$thpp->delete("herramienta='$herramienta' and usuario='".$_SESSION["id_usr"]."'");
		for($x=1;$x<=$total;$x++)
		{
			$campo = getPGVar("vis_".$x);
			$etiqueta = getPGVar("eti_".$x);
			$posicion = getPGVar("pos_".$x);
			$orden = getPGVar("ord_".$x);
			if($campo!="")
			{
				$thpp->insert(array("campo"=>$campo, "etiqueta"=>$etiqueta, "usuario"=>$_SESSION["id_usr"], "orden"=>$orden, "posicion"=>$posicion, "herramienta"=>$herramienta));
			}
		}
	}
}

?>
