<?php
session_start();
include_once("../apoyo.php");

$act = Get_Vars_Helper::getPGVar("act");
if(Get_Vars_Helper::getPGVar("display_page")=="true") $display_page=true;
else $display_page=false;
$tcols_def = CTabla("docto3_columnas_alias");
$tcols = CTabla("docto3_columnas");
if($act!="")
{
	if($act=="modify_cols")
	{
		$col = Get_Vars_Helper::getGetVar("col");
		$eti = Get_Vars_Helper::getGetVar("eti");
		$pos = Get_Vars_Helper::getGetVar("pos");
		$ord = Get_Vars_Helper::getGetVar("ord");
		$tcols->delete("usuario='".$_SESSION["id_usr"]."'");
		for($x=0;$x<count($col);$x++)
		{
			$envio=array("usuario"=>$_SESSION["id_usr"], "columna"=>$col[$x], "posicion"=>$pos[$x], "orden"=>$ord[$x], "etiqueta"=>$eti[$x]);
			$tcols->insert($envio);
		}
	}
	if($act=="display_sql")
	{
		$lista=Get_Vars_Helper::getPGVar("lista");
		$usuar=Get_Vars_Helper::getPGVar("usuario");
		echo DisplaySQLD3($lista,$usuar);
	}
	exit();
}
$dcols_def = $tcols_def->select("*");
$dcols = $tcols->select("*","usuario='".$_SESSION["id_usr"]."'","posicion");
?>
<table border="0" align="center">
	<thead>
	<tr><td align="right" colspan="5">
		<input type="button" value="Actualizar" onclick="ActualizarCols()" />
		<input type="button" value="Cancelar" onclick="CancelarActualizacion()" />
	</td></tr>
	<tr><th>Visible</th><th>Columna</th><th>Etiqueta</th><th>Posicion</th><th>Orden</th></tr>
	</thead>
	<tbody id="dataTbl">
	<?php
	$x=0;
	$act_cols=array();
	foreach($dcols as $col)
	{
		$x++;
		$col_alias=$tcols_def->select("alias","columna='".$col["columna"]."'");
		$act_cols[]=$col["columna"];
		?>
		<tr>
			<td align="center"><input type="checkbox" value="<?php echo $col["columna"]; ?>" name="vis[]" id="vis_<?php echo $x; ?>"  checked="checked" /></td>
			<td><?php echo $col_alias[0]["alias"]; ?></td>
			<td><input type="text" value="<?php echo $col["etiqueta"]; ?>" maxlength="250" size="25" name="eti_<?php echo $col["columna"]; ?>" id="eti_<?php echo $col["columna"]; ?>" /></td>
			<td align="center"><input type="text" value="<?php echo $col["posicion"]; ?>" maxlength="4" size="4" name="pos_<?php echo $col["columna"]; ?>" id="pos_<?php echo $col["columna"]; ?>" /></td>
			<td>
				<select name="ord_<?php echo $col["columna"]; ?>" id="ord_<?php echo $col["columna"]; ?>"><option value="ASC" <?php echo (($col["orden"]=="ASC")?('selected="selected"'):("")); ?>>Ascendente</option><option value="DESC" <?php echo (($col["orden"]=="DESC")?('selected="selected"'):("")); ?>>Descendente</option></select>
			</td>
		</tr>
		<?php
	}
	foreach($dcols_def as $col)
	{
		$esta=false;
		foreach($act_cols as $act_col)
		{
			if($act_col==$col["columna"])
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
				<td align="center"><input type="checkbox" value="<?php echo $col["columna"]; ?>" name="vis[]" id="vis_<?php echo $x; ?>" /></td>
				<td><?php echo $col["alias"]; ?></td>
				<td><input type="text" value="<?php echo $col["alias"]; ?>" maxlength="250" size="25" name="eti_<?php echo $col["columna"]; ?>" id="eti_<?php echo $col["columna"]; ?>" /></td>
				<td align="center"><input type="text" value="<?php echo $x; ?>" maxlength="4" size="4" name="pos_<?php echo $col["columna"]; ?>" id="pos_<?php echo $col["columna"]; ?>" /></td>
				<td>
					<select name="ord_<?php echo $col["columna"]; ?>" id="ord_<?php echo $col["columna"]; ?>"><option value="ASC">Ascendente</option><option value="DESC">Descendente</option></select>
				</td>
			</tr>
			<?php
		}
	}
	?>
	</tbody>
</table>
