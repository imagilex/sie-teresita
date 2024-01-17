<?php session_start();

include_once("../apoyo.php");
$vista = getPGVar("vista");
$origen = getPGVar("origen");
$estatus = getPGVar("estatus");
$accion = getPGVar("accion");
$documento = getPGVar("documento");
if($accion!="")
{
	if($accion=="activar" && $documento!="")
	{
		$taux=CTabla("docto6");
		$taux->update(array("estatus"=>"A"),"id_documento='$documento'");
	}
	else if($accion=="ocultar" && $documento!="")
	{
		$taux=CTabla("docto6");
		$taux->update(array("estatus"=>"O"),"id_documento='$documento'");
	}
	else if($accion=="desactivar" && $documento!="")
	{
		$taux=CTabla("docto6");
		$taux->update(array("estatus"=>"I"),"id_documento='$documento'");
	}
	else if($accion=="encaptura" && $documento!="")
	{
		$taux=CTabla("docto6");
		$taux->update(array("estatus"=>"C"),"id_documento='$documento'");
	}
	else if($accion=="suspender" && $documento!="")
	{
		$taux=CTabla("docto6");
		$taux->update(array("estatus"=>"S"),"id_documento='$documento'");
	}
	else if($accion=="terminar" && $documento!="")
	{
		$taux=CTabla("docto6");
		$taux->update(array("estatus"=>"T"),"id_documento='$documento'");
	}
}
if($vista=="1")
{
	$tdocs=CTabla("docto_general");
	$tdoc6=CTabla("docto6");
	if($origen=="1")
	{
		?>
		<table align="center" width="100%" cellpadding="10">
			<?php
			$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and agrupador='1'".($estatus!="all"?" and estatus='$estatus'":"")));
			if(intval($cuantos["n"])>0)
			{
				?>
				<tr>
					<td width="25%"></td>
					<td width="25%"></td>
					<td width="25%" align="right"><img src="Imagenes/docto_agrup_1.png" /></td>
					<td width="25%">
					<?php
					$proys=$tdocs->select("id_documento, nombre, nombre_corto","tipo_documento='2' and origen='$origen' and agrupador='1'","nombre_corto, nombre");
					foreach($proys as $proy)
					{
						?>
						<ul style="margin:0px; padding-left:5px; list-style:none;">
						<?php
						$aux=$tdoc6->select("id_documento,estatus,responsable","id_documento='".$proy["id_documento"]."'");
						if($estatus=="all" || $aux[0]["estatus"]==$estatus)
						{
							?>
							<li style="padding-bottom:5px; padding-top:5px;">
								<input type="radio" name="proyecto" id="proyecto" onclick="SeleccionaProy('<?php echo $aux[0]["id_documento"]; ?>','<?php echo $aux[0]["responsable"]; ?>','<?php echo $_SESSION["id_persona_usr"]; ?>')" />
								<span style="cursor:default;" onclick="VerArchivos('<?php echo $aux[0]["id_documento"]; ?>')" title="<?php echo htmlentities($proy["nombre"]); ?>"><?php echo htmlentities($proy["nombre_corto"]); ?></span><br />
								<span style="font-size:9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Tiempo</span>
							</li>
							<?php
						}
						?>
						</ul>
						<?php
					}
					?>
					</td>
				</tr>
				<?php
			}
			$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and agrupador='2'".($estatus!="all"?" and estatus='$estatus'":"")));
			if(intval($cuantos["n"])>0)
			{
				?>
				<tr>
					<td width="25%"></td>
					<td width="25%"></td>
					<td width="25%" align="right"><img src="Imagenes/docto_agrup_2.png" /></td>
					<td width="25%">
					<?php
					$proys=$tdocs->select("id_documento, nombre, nombre_corto","tipo_documento='2' and origen='$origen' and agrupador='2'","nombre_corto, nombre");
					foreach($proys as $proy)
					{
						?>
						<ul style="margin:0px; padding-left:5px; list-style:none;">
						<?php
						$aux=$tdoc6->select("id_documento,estatus,responsable","id_documento='".$proy["id_documento"]."'");
						if($estatus=="all" || $aux[0]["estatus"]==$estatus)
						{
							?>
							<li style="padding-bottom:5px; padding-top:5px;">
								<input type="radio" name="proyecto" id="proyecto" onclick="SeleccionaProy('<?php echo $aux[0]["id_documento"]; ?>','<?php echo $aux[0]["responsable"]; ?>','<?php echo $_SESSION["id_persona_usr"]; ?>')" />
								<span style="cursor:default;" onclick="VerArchivos('<?php echo $aux[0]["id_documento"]; ?>')" title="<?php echo htmlentities($proy["nombre"]); ?>"><?php echo htmlentities($proy["nombre_corto"]); ?></span><br />
								<span style="font-size:9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Tiempo</span>
							</li>
							<?php
						}
						?>
						</ul>
						<?php
					}
					?>
					</td>
				</tr>
				<?php
			}
			$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and (agrupador='3' or agrupador='7')".($estatus!="all"?" and estatus='$estatus'":"")));
			if(intval($cuantos["n"])>0)
			{
				?>
				<tr>
					<td width="25%" align="right">
						<?php
						$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and agrupador='3'".($estatus!="all"?" and estatus='$estatus'":"")));
						if(intval($cuantos["n"])>0)
						{
							?>
							<img src="Imagenes/docto_agrup_3.png" />
							<?php
						}
						?>
					</td>
					<td width="25%">
					<?php
					$proys=$tdocs->select("id_documento, nombre, nombre_corto","tipo_documento='2' and origen='$origen' and agrupador='3'","nombre_corto, nombre");
					foreach($proys as $proy)
					{
						?>
						<ul style="margin:0px; padding-left:5px; list-style:none;">
						<?php
						$aux=$tdoc6->select("id_documento,estatus,responsable","id_documento='".$proy["id_documento"]."'");
						if($estatus=="all" || $aux[0]["estatus"]==$estatus)
						{
							?>
							<li style="padding-bottom:5px; padding-top:5px;">
								<input type="radio" name="proyecto" id="proyecto" onclick="SeleccionaProy('<?php echo $aux[0]["id_documento"]; ?>','<?php echo $aux[0]["responsable"]; ?>','<?php echo $_SESSION["id_persona_usr"]; ?>')" />
								<span style="cursor:default;" onclick="VerArchivos('<?php echo $aux[0]["id_documento"]; ?>')" title="<?php echo htmlentities($proy["nombre"]); ?>"><?php echo htmlentities($proy["nombre_corto"]); ?></span><br />
								<span style="font-size:9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Tiempo</span>
							</li>
							<?php
						}
						?>
						</ul>
						<?php
					}
					?>
					</td>
					<td width="25%" align="right">
						<?php
						$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and agrupador='7'".($estatus!="all"?" and estatus='$estatus'":"")));
						if(intval($cuantos["n"])>0)
						{
							?>
							<img src="Imagenes/docto_agrup_7.png" />
							<?php
						}
						?>
					</td>
					<td width="25%">
					<?php
					$proys=$tdocs->select("id_documento, nombre, nombre_corto","tipo_documento='2' and origen='$origen' and agrupador='7'","nombre_corto, nombre");
					foreach($proys as $proy)
					{
						?>
						<ul style="margin:0px; padding-left:5px; list-style:none;">
						<?php
						$aux=$tdoc6->select("id_documento,estatus,responsable","id_documento='".$proy["id_documento"]."'");
						if($estatus=="all" || $aux[0]["estatus"]==$estatus)
						{
							?>
							<li style="padding-bottom:5px; padding-top:5px;">
								<input type="radio" name="proyecto" id="proyecto" onclick="SeleccionaProy('<?php echo $aux[0]["id_documento"]; ?>','<?php echo $aux[0]["responsable"]; ?>','<?php echo $_SESSION["id_persona_usr"]; ?>')" />
								<span style="cursor:default;" onclick="VerArchivos('<?php echo $aux[0]["id_documento"]; ?>')" title="<?php echo htmlentities($proy["nombre"]); ?>"><?php echo htmlentities($proy["nombre_corto"]); ?></span><br />
								<span style="font-size:9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Tiempo</span>
							</li>
							<?php
						}
						?>
						</ul>
						<?php
					}
					?>
					</td>
				</tr>
				<?php
			}
			$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and (agrupador='8' or agrupador='4')".($estatus!="all"?" and estatus='$estatus'":"")));
			if(intval($cuantos["n"])>0)
			{
				?>
				<tr>
					<td width="25%" align="right">
						<?php
						$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and agrupador='8'".($estatus!="all"?" and estatus='$estatus'":"")));
						if(intval($cuantos["n"])>0)
						{
							?>
							<img src="Imagenes/docto_agrup_8.png" />
							<?php
						}
						?>
					</td>
					<td width="25%">
					<?php
					$proys=$tdocs->select("id_documento, nombre, nombre_corto","tipo_documento='2' and origen='$origen' and agrupador='8'","nombre_corto, nombre");
					foreach($proys as $proy)
					{
						?>
						<ul style="margin:0px; padding-left:5px; list-style:none;">
						<?php
						$aux=$tdoc6->select("id_documento,estatus,responsable","id_documento='".$proy["id_documento"]."'");
						if($estatus=="all" || $aux[0]["estatus"]==$estatus)
						{
							?>
							<li style="padding-bottom:5px; padding-top:5px;">
								<input type="radio" name="proyecto" id="proyecto" onclick="SeleccionaProy('<?php echo $aux[0]["id_documento"]; ?>','<?php echo $aux[0]["responsable"]; ?>','<?php echo $_SESSION["id_persona_usr"]; ?>')" />
								<span style="cursor:default;" onclick="VerArchivos('<?php echo $aux[0]["id_documento"]; ?>')" title="<?php echo htmlentities($proy["nombre"]); ?>"><?php echo htmlentities($proy["nombre_corto"]); ?></span><br />
								<span style="font-size:9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Tiempo</span>
							</li>
							<?php
						}
						?>
						</ul>
						<?php
					}
					?>
					</td>
					<td width="25%" align="right">
						<?php
						$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and agrupador='3'".($estatus!="all"?" and estatus='$estatus'":"")));
						if(intval($cuantos["n"])>0)
						{
							?>
							<img src="Imagenes/docto_agrup_4.png" />
							<?php
						}
						?>
					</td>
					<td width="25%">
					<?php
					$proys=$tdocs->select("id_documento, nombre, nombre_corto","tipo_documento='2' and origen='$origen' and agrupador='4'","nombre_corto, nombre");
					foreach($proys as $proy)
					{
						?>
						<ul style="margin:0px; padding-left:5px; list-style:none;">
						<?php
						$aux=$tdoc6->select("id_documento,estatus,responsable","id_documento='".$proy["id_documento"]."'");
						if($estatus=="all" || $aux[0]["estatus"]==$estatus)
						{
							?>
							<li style="padding-bottom:5px; padding-top:5px;">
								<input type="radio" name="proyecto" id="proyecto" onclick="SeleccionaProy('<?php echo $aux[0]["id_documento"]; ?>','<?php echo $aux[0]["responsable"]; ?>','<?php echo $_SESSION["id_persona_usr"]; ?>')" />
								<span style="cursor:default;" onclick="VerArchivos('<?php echo $aux[0]["id_documento"]; ?>')" title="<?php echo htmlentities($proy["nombre"]); ?>"><?php echo htmlentities($proy["nombre_corto"]); ?></span><br />
								<span style="font-size:9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Tiempo</span>
							</li>
							<?php
						}
						?>
						</ul>
						<?php
					}
					?>
					</td>
				</tr>
				<?php
			}
			$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and agrupador='9'".($estatus!="all"?" and estatus='$estatus'":"")));
			if(intval($cuantos["n"])>0)
			{
				?>
				<tr>
					<td width="25%"></td>
					<td width="25%"></td>
					<td width="25%" align="right"><img src="Imagenes/docto_agrup_9.png" /></td>
					<td width="25%">
					<?php
					$proys=$tdocs->select("id_documento, nombre, nombre_corto","tipo_documento='2' and origen='$origen' and agrupador='9'","nombre_corto, nombre");
					foreach($proys as $proy)
					{
						?>
						<ul style="margin:0px; padding-left:5px; list-style:none;">
						<?php
						$aux=$tdoc6->select("id_documento,estatus,responsable","id_documento='".$proy["id_documento"]."'");
						if($estatus=="all" || $aux[0]["estatus"]==$estatus)
						{
							?>
							<li style="padding-bottom:5px; padding-top:5px;">
								<input type="radio" name="proyecto" id="proyecto" onclick="SeleccionaProy('<?php echo $aux[0]["id_documento"]; ?>','<?php echo $aux[0]["responsable"]; ?>','<?php echo $_SESSION["id_persona_usr"]; ?>')" />
								<span style="cursor:default;" onclick="VerArchivos('<?php echo $aux[0]["id_documento"]; ?>')" title="<?php echo htmlentities($proy["nombre"]); ?>"><?php echo htmlentities($proy["nombre_corto"]); ?></span><br />
								<span style="font-size:9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Tiempo</span>
							</li>
							<?php
						}
						?>
						</ul>
						<?php
					}
					?>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}
	else if($origen=="2")
	{
		?>
		<table align="center" width="100%" cellpadding="10">
			<?php
			$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and agrupador='5'".($estatus!="all"?" and estatus='$estatus'":"")));
			if(intval($cuantos["n"])>0)
			{
				?>
				<tr>
					<td width="25%" align="right"><img src="Imagenes/docto_agrup_5.png" /></td>
					<td width="25%">
					<?php
					$proys=$tdocs->select("id_documento, nombre, nombre_corto","tipo_documento='2' and origen='$origen' and agrupador='5'","nombre_corto, nombre");
					foreach($proys as $proy)
					{
						?>
						<ul style="margin:0px; padding-left:5px; list-style:none;">
						<?php
						$aux=$tdoc6->select("id_documento,estatus,responsable","id_documento='".$proy["id_documento"]."'");
						if($estatus=="all" || $aux[0]["estatus"]==$estatus)
						{
							?>
							<li style="padding-bottom:5px; padding-top:5px;">
								<input type="radio" name="proyecto" id="proyecto" onclick="SeleccionaProy('<?php echo $aux[0]["id_documento"]; ?>','<?php echo $aux[0]["responsable"]; ?>','<?php echo $_SESSION["id_persona_usr"]; ?>')" />
								<span style="cursor:default;" onclick="VerArchivos('<?php echo $aux[0]["id_documento"]; ?>')" title="<?php echo htmlentities($proy["nombre"]); ?>"><?php echo htmlentities($proy["nombre_corto"]); ?></span><br />
								<span style="font-size:9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Tiempo</span>
							</li>
							<?php
						}
						?>
						</ul>
						<?php
					}
					?>
					</td>
				</tr>
				<?php
			}
			$cuantos=$tdocs->registro($tdocs->query("select count(*) as 'n' from docto_general inner join docto6 on docto_general.id_documento=docto6.id_documento where tipo_documento='2' and origen='$origen' and agrupador='6'".($estatus!="all"?" and estatus='$estatus'":"")));
			if(intval($cuantos["n"])>0)
			{
				?>
				<tr>
					<td width="25%" align="right"><img src="Imagenes/docto_agrup_6.png" /></td>
					<td width="25%">
					<?php
					$proys=$tdocs->select("id_documento, nombre, nombre_corto","tipo_documento='2' and origen='$origen' and agrupador='6'","nombre_corto, nombre");
					foreach($proys as $proy)
					{
						?>
						<ul style="margin:0px; padding-left:5px; list-style:none;">
						<?php
						$aux=$tdoc6->select("id_documento,estatus,responsable","id_documento='".$proy["id_documento"]."'");
						if($estatus=="all" || $aux[0]["estatus"]==$estatus)
						{
							?>
							<li style="padding-bottom:5px; padding-top:5px;">
								<input type="radio" name="proyecto" id="proyecto" onclick="SeleccionaProy('<?php echo $aux[0]["id_documento"]; ?>','<?php echo $aux[0]["responsable"]; ?>','<?php echo $_SESSION["id_persona_usr"]; ?>')" />
								<span style="cursor:default;" onclick="VerArchivos('<?php echo $aux[0]["id_documento"]; ?>')" title="<?php echo htmlentities($proy["nombre"]); ?>"><?php echo htmlentities($proy["nombre_corto"]); ?></span><br />
								<span style="font-size:9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/semV.png" /> Tiempo</span>
							</li>
							<?php
						}
						?>
						</ul>
						<?php
					}
					?>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}
}
?>
