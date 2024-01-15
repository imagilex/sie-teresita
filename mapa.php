<?php

//Requerir/Incluir data_base.php

if(!isset($_MAPA_) || ! $_MAPA_)

{

$_MAPA_ = true;

//Clase

class mapa
{
	public $id;
	function __construct($tmp_id="")
	{
		$this->id=$tmp_id;
	}
	// function mapa($tmp_id="")
	// {
	// 	$this->id=$tmp_id;
	// }
	function seach_id($nombre_mapa)
	{
		global $db;
		$id_find=@mysqli_fetch_array($db->consulta("select id_mapa from mapa where nombre = '$nombre_mapa'"));
		$this->id=$id_find['id_mapa'];
	}
	function get_id()
	{
		if($this->id!="")
		{
			return $this->id;
		}
		return "";
	}
	function get_nombre()
	{
		global $db;
		if($this->id!="")
		{
			$dato=@mysqli_fetch_array($db->consulta("select nombre from mapa where id_mapa='".$this->id."'"));
			return $dato["nombre"];
		}
		return "";
	}
	function get_comentarios()
	{
		global $db;
		if($this->id!="")
		{
			$dato=@mysqli_fetch_array($db->consulta("select comentarios from mapa where id_mapa='".$this->id."'"));
			return $dato["comentarios"];
		}
		return "";
	}
	function get_tipo()
	{
		global $db;
		if($this->id!="")
		{
			$query="select tipo from mapa where id_mapa='".$this->id."'";
			$dato=mysqli_fetch_array($db->consulta($query));
			return $dato["tipo"];
		}
		return "";
	}
	function get_contenido()
	{
		global $db;
		if($this->id!="")
		{
			$query="select contenido from mapa where id_mapa='".$this->id."'";
			$dato=@mysqli_fetch_array($db->consulta($query));
			return $dato["contenido"];
		}
		return "";
	}
	function set_nombre($nvo_dato)
	{
		global $db;
		if($this->id!="" && $nvo_dato!="")
		{
			$db->consulta("update mapa set nombre = '$nvo_dato' where id_mapa='".$this->id."'");
		}
	}
	function set_comentarios($nvo_dato)
	{
		global $db;
		if($this->id!="" && $nvo_dato!="")
		{
			$db->consulta("update mapa set comentarios = '$nvo_dato' where id_mapa='".$this->id."'");
		}
	}
	function set_tipo($nvo_dato)
	{
		global $db;
		if($this->id!="" && $nvo_dato!="")
		{
			$db->consulta("update mapa set tipo = '$nvo_dato' where id_mapa='".$this->id."'");
		}
	}
	function set_contenido($nvo_dato)
	{
		global $db;
		if($this->id!="" && $nvo_dato!="")
		{
			$db->consulta("update mapa set contenido = '$nvo_dato' where id_mapa='".$this->id."'");
		}
	}
	function get_padres()
	{
		global $db;
		$datos=array();
		if($this->id!="")
		{
			if($regs=$db->consulta("select mapa_padre from mapa_submapa where mapa_hijo = '".$this->id."'"))
			{
				while($reg=mysqli_fetch_array($regs))
				{
					$datos[]=$reg["mapa_padre"];
				}
			}
		}
		return $datos;
	}
	function get_hijos()
	{
		global $db;
		$datos=array();
		if($this->id!="")
		{
			$query="select mapa_hijo from mapa_submapa where mapa_padre = '".$this->id."'";
			if($regs=$db->consulta($query))
			{
				while($reg=mysqli_fetch_array($regs))
				{
					$datos[]=$reg["mapa_hijo"];
				}
			}
		}
		return $datos;
	}
	function set_padre($nvo_padre)
	{
		global $db;
		if($this->id!="")
		{
			$db->consulta("insert into mapa_submapa (mapa_padre, mapa_hijo) values ('$nov_padre','".$this->id."')");
		}
	}
	function set_hijo($nvo_hijo)
	{
		global $db;
		if($this->id!="")
		{
			$db->consulta("insert into mapa_submapa (mapa_padre, mapa_hijo) values ('".$this->id."','$nvo_hijo')");
		}
	}
	function del_padre($padre)
	{
		global $db;
		if($this->id!="")
		{
			$db->consulta("delete from mapa_submapa where mapa_hijo = '".$this->id."' and mapa_padre = '$padre')");
		}
	}
	function del_hijo($hijo)
	{
		global $db;
		if($this->id!="")
		{
			$db->consulta("delete from mapa_submapa where mapa_padre = '".$this->id."' and mapa_hijo = '$hijo')");
		}
	}
	function set_param_hijo($hijo, $parametro, $valor)
	{
		global $db;
		if($this->id!="")
		{
			$db->consulta("update mapa_submapa set $parametro = '$valor' where mapa_padre='".$this->id."' and mapa_hijo='$hijo'");
		}
	}
	function get_param_hijo($hijo, $parametro)
	{
		global $db;
		$dato="";
		if($this->id!="")
		{
			$data=@mysqli_fetch_array($db->consulta("select $parametro as param form mapa_submapa where mapa_padre='".$this->id."' and mapa_hijo='$hijo'"));
			$dato=$data["param"];
		}
		return $dato;
	}
	function print_mapa($link_page, $ancho, $alto, $id_documento="")
	{
		global $db;
		if($this->get_id()!="")
		{
			$superiores="";
			$actual=$this->get_id();
			while($actual!="")
			{
				$query="select mapa_padre from mapa_submapa where mapa_hijo='$actual'";
				$id_papa=@mysqli_fetch_array($db->consulta($query));
				$query="select id_mapa, nombre from mapa where id_mapa='".$id_papa["mapa_padre"]."'";
				$papa=@mysqli_fetch_array($db->consulta($query));
				$actual=$papa["id_mapa"];
				if($papa["id_mapa"]!="")
				{
					$superiores='<option value="'.$papa["id_mapa"].'">'.$papa["nombre"].'</option>'.$superiores;
				}
			}
			if($superiores!="")
			{
				$superiores='<optgroup label="Superior">'.$superiores.'</optgroup>';
			}
			$inferiores="";
			if($regs=$db->consulta("select id_mapa, nombre from mapa inner join mapa_submapa on id_mapa = mapa_hijo and mapa_padre='".$this->get_id()."' order by posicion, nombre"))
			{
				$inferiores='<optgroup label="Inferior">';
				while($reg=mysqli_fetch_array($regs))
				{
					$inferiores.='<option value="'.$reg["id_mapa"].'">'.$reg["nombre"].'</option>';
				}
				$inferiores.='</optgroup>';
			}
			?>
			<table border="0" align="center" width="95%"><tr><td align="left" width="10">
			<form name="movimientos" action="mapas.php">
			<table border="0" align="left">
				<tr>
					<td align="right">Mapa:</td>
					<td align="left">
						<select name="mapa" id="mapa" onchange="javascript: location.href='<?php echo $link_page; ?>id_mapa='+this.value;"><option value=""></option>
							<?php echo $superiores.$inferiores; ?>
						</select>
						<script language="javascript">
							document.movimientos.mapa.value="<?php echo $id_mapa; ?>";
						</script>
					</td>
				</tr>
			</table>
			</form>
			</td><td width="1114" align="center"><strong style="font-size:medium;"><?php echo $this->get_nombre(); ?></strong></td>
			</tr>
			<tr><td align="center" colspan="2"><div align="center" style="overflow:auto; width:<?php echo $ancho; ?>; height:<?php echo $alto; ?>;">
			<?php
			if($this->get_tipo()=="1") //Tipo Imagen
			{
				//generacion del mapa
				$hijos=$this->get_hijos();
				?>
				<map name="mapa" id="mapa">
				<?php
				for($x=0;$x<@count($hijos);$x++)
				{
					$query="select * from mapa_submapa where mapa_padre='".$this->get_id()."' and mapa_hijo='".$hijos[$x]."'";
					$inpho=mysqli_fetch_array($db->consulta($query));
					$query="select * from mapa where id_mapa='".$hijos[$x]."'";
					$inpho_map=mysqli_fetch_array($db->consulta($query));
					$doctos=@mysqli_fetch_array($db->consulta("select count(*) as n from mapa_doc_cont where id_mapa = '".$hijos[$x]."'"));
					if(($inpho_map["tipo"]!="" && $inpho_map["contenido"]!="") || ($inpho_map["tipo"]=="4" && intval($doctos["n"])>0))
					{
					?>
					<area shape="poly" coords="<?php echo $inpho["coordenadas"];?>" href="<?php echo $link_page."id_mapa=".$hijos[$x]; ?>" title="<?php echo $inpho_map["comentarios"]?>" alt="<?php echo $inpho_map["comentarios"]?>" />
					<?php
					}
				}
				?>
				</map>
				<?php
				//colocacion de la imagen y lige al mapa
				?>
				<center><img src="img_mapas/<?php echo $this->get_contenido(); ?>" usemap="#mapa" border="0" /></center>
				<?php
			}
			else if($this->get_tipo()=="2") //Tipo Grafico
			{
				$hijos=$this->get_hijos();
				?>
				<table border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<?php
						for($x=0;$x<@count($hijos);$x++)
						{
							if($x==0)
							{
								$img01="img_mapas/basic09.png";
								$img02="&nbsp;&nbsp;";
								$img03="&nbsp;&nbsp;";
							}
							else if($x==count($hijos)/2 || $x==(count($hijos)/2 -0.5))
							{
								$img01="img_mapas/basic07.png";
								$img02='<img src=img_mapas/'.$this->get_contenido().' />';
								$img03='<img src="img_mapas/basic01.png" />';
							}
							else if($x==count($hijos)-1)
							{
								$img01="img_mapas/basic10.png";
								$img02="&nbsp;&nbsp;";
								$img03="&nbsp;&nbsp;";
							}
							else
							{
								$img01="img_mapas/basic08.png";
								$img02="&nbsp;&nbsp;";
								$img03="&nbsp;&nbsp;";
							}
							$query="select * from mapa_submapa where mapa_padre='".$this->get_id()."' and mapa_hijo='".$hijos[$x]."'";
							$inpho=mysqli_fetch_array($db->consulta($query));
							$query="select * from mapa where id_mapa='".$hijos[$x]."'";
							$inpho_map=mysqli_fetch_array($db->consulta($query));
							?>
							<td align="center" valign="middle"><?php echo $img02; ?></td>
							<td align="center" valign="middle"><?php echo $img03; ?></td>
							<td align="center" valign="middle"><img src="<?php echo $img01; ?>" /></td>
							<td align="center" valign="middle"><img src="img_mapas/basic03.png" /></td>
							<?php
							if($inpho["preposicion"]!="")
							{
								?>
								<td align="center" valign="middle" style="font-size:9px;; font-weight:bold;">&nbsp;&nbsp;<?php echo $inpho["preposicion"];?>&nbsp;&nbsp;</td>
								<td align="center" valign="middle"><img src="img_mapas/basic03.png" /></td>
								<?php
							}
							?>
							<td align="left" valign="middle" <?php if($inpho["preposicion"]=="") echo 'rowspan="3"'; ?>>
							<?php
							$link="";
							$link2="";
							$doctos=@mysqli_fetch_array($db->consulta("select count(*) as n from mapa_doc_cont where id_mapa = '".$hijos[$x]."'"));
							if(($inpho_map["tipo"]!="" && $inpho_map["contenido"]!="") || ($inpho_map["tipo"]=="4" && intval($doctos["n"])>0))
							{
								$link="<a href=\"$link_page"."id_mapa=".$hijos[$x]."\">";
								$link2="</a>";
							}
							echo $link;
							?>
							<img src="img_mapas/<?php echo $inpho["figura"]; ?>" align="<?php echo $inpho_map["comentarios"]?>" title="<?php echo $inpho_map["comentarios"]?>" border="0" />
							<?php
							echo $link2;
							?>
							</td>
							<?php
							if($x!=count($hijos)-1) echo "</tr><tr>";
						}
						?>
					</tr>
				</table>
				<?php
			}
			else if($this->get_tipo()=="3") //Tipo Lista
			{
				?>
				<center><iframe src="_resultado_consulta_vs_pantalla_for_list.php?pantalla=9&consulta=<?php echo $this->get_contenido(); ?>&encabezado=falso&noCache="+Math.random()+"&sess_usua=<?php echo $sid_usuario; ?>" width="95%" height="450" marginheight="0" marginwidth="0" frameborder="0" name="Reporte" id="Reporte"></iframe></center>
				<?php
			}
			else if($this->get_tipo()=="4") //Tipo Documentacion
			{
				?>
				<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td class="contenedor" align="left">
					<table border="0"><tr>
					<?php
						$query="select id_documento from mapa_doc_cont where id_mapa='".$this->get_id()."'";
						if($doctos=$db->consulta($query))
						{
							while($docto=mysqli_fetch_array($doctos))
							{
								$onclick="";
								$query="select id_documento, nombre_documento from mapa_documento where id_documento='".$docto["id_documento"]."'";
								if($id_documento=="") $id_documento=$docto["id_documento"];
								$prod=@mysqli_fetch_array($db->consulta($query));
								if($prod["nombre_documento"]!="")
								{
									?>
									<td class="<?php echo (($id_documento==$prod["id_documento"])?("celda_actual"):("celda_normal")); ?>" style="font-size:small;" onclick="javascript: location.href='mapas.php?id_mapa=<?php echo $this->get_id(); ?>&docto=<?php echo $prod["id_documento"]; ?>';"><?php echo $prod["nombre_documento"];?></td>
									<?php
								}
							}
						}
						?>
					</tr></table>
					</td><td align="right" class="contenedor">
						<form style="padding:0px; margin:0px;" action="usr_rh68.php" method="post" name="data_del">
							<input type="submit" name="btnDelDocto" value="Borrar" style="border-width:1px; width:75px; height:25px; border-style:solid; border-color:#000000;" />
							<input type="hidden" name="id_mapa" value="<?php echo $this->get_id(); ?>" />
							<input type="hidden" name="docto" value="<?php echo $id_documento; ?>" />
						</form>
					</td></tr></table>
					<table class="contenedor_resultados" align="center">
					<tr>
						<td style="font-size:small;"><div style="overflow:auto; height:375px; width:825px;">
							<?php
							$query="select contenido, tipo_documento from mapa_documento where id_documento='$id_documento'";
							$prod=@mysqli_fetch_array($db->consulta($query));
							ErrorMySQLAlert();
							switch(intval($prod["tipo_documento"]))
							{
								case 1: //tipo texto
									echo str_replace("  ","&nbsp;&nbsp;",str_replace("\n","<br />",$prod["contenido"]));
									break;
								case 2:	//tipo imagen
									?>
									<table border="0" align="center"><tr><td height="200"><center><img src="documentos/<?php echo $prod["contenido"]; ?>" /></center></td></tr></table>
									<?php
									break;
								case 3:	//tipo archivo
									?>
									<table border="0" align="center"><tr><td heigh="200"><center><input name="button" type="button" style="width:100px; height:25px; border-width:1px; border-style:solid;" onclick="javascript: window.open('map_doc/<?php echo $prod["contenido"]; ?>');" value="Archivo" /></center></td></tr></table>
									<?php
									break;
							}
							?>
						</div></td>
					</tr>
				</table>
				<?php
			}
			?>
			</div></td></tr></table>
			<?php
		}
	}
	function add_archivo($file,$directorio_destino,$nombre,$new_name="")
	{
		global $db;
		if(isset($_FILES[$file]["name"]) && $_FILES[$file]["name"]!="")
		{
			if($new_name=="")
				$new_name=$_FILES[$file]["name"];
			if(move_uploaded_file($_FILES[$file]["tmpname"],$directorio_destino."/".$new_name))
			{
				$db->consulta("insert into mapa_documento (nombre_documento, contenido, tipo_documento, fecha) values ('$nombre', '$new_name', '3', currdate())");
				$id_docto=@mysqli_fetch_array($db->consulta("select id_documento from mapa_documento where nombre='$nombre' and contenido='$new_name' and tipo_documento='3' and fecha=curdate()"));
				$db->consulta("insert into mpapa_doc_cont (id_mapa, id_documento, fecha) values ('".$this->get_id()."', '".$id_docto["id_documento"]."', currdate())");
				return true;
			}
		}
		return false;
	}
	function del_archivo($id_file)
	{
		global $db;
		$db->consulta("delete from mapa_documento where id_documento=$id_file");
		$db->consulta("delete from mapa_doc_cont where id_documento=$id_file and id_mapa='".$this->get_id()."'");
	}
	function add_img($file,$directorio_destino,$nombre,$new_name="")
	{
		global $db;
		if(isset($_FILES[$file]["name"]) && $_FILES[$file]["name"]!="")
		{
			if($new_name=="")
				$new_name=$_FILES[$file]["name"];
			if(move_uploaded_file($_FILES[$file]["tmpname"],$directorio_destino."/".$new_name))
			{
				$db->consulta("insert into mapa_documento (nombre_documento, contenido, tipo_documento, fecha) values ('$nombre', '$new_name', '2', currdate())");
				$id_docto=@mysqli_fetch_array($db->consulta("select id_documento from mapa_documento where nombre='$nombre' and contenido='$new_name' and tipo_documento='2' and fecha=curdate()"));
				$db->consulta("insert into mpapa_doc_cont (id_mapa, id_documento, fecha) values ('".$this->get_id()."', '".$id_docto["id_documento"]."', currdate())");
				return true;
			}
		}
		return false;
	}
	function del_img($id_file)
	{
		global $db;
		$db->consulta("delete from mapa_documento where id_documento=$id_file");
		$db->consulta("delete from mapa_doc_cont where id_documento=$id_file and id_mapa='".$this->get_id()."'");
	}
	function add_txt($contenido,$nombre)
	{
		global $db;
		$db->consulta("insert into mapa_documento (nombre_documento, contenido, tipo_documento, fecha) values ('$nombre', '$contenido', '1', currdate())");
		$id_docto=@mysqli_fetch_array($db->consulta("select id_documento from mapa_documento where nombre='$nombre' and contenido='$contenido' and tipo_documento='1' and fecha=curdate()"));
		$db->consulta("insert into mpapa_doc_cont (id_mapa, id_documento, fecha) values ('".$this->get_id()."', '".$id_docto["id_documento"]."', currdate())");
		return true;
	}
	function del_txt($id_txt)
	{
		global $db;
		$db->consulta("delete from mapa_documento where id_documento=$id_txt");
		$db->consulta("delete from mapa_doc_cont where id_documento=$id_txt and id_mapa='".$this->get_id()."'");
	}
}

//Base de Datos

/*

CREATE TABLE `mapa` (
  `id_mapa` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(250) default NULL,
  `comentarios` text,
  `tipo` varchar(250) default NULL,
  `contenido` text,
  PRIMARY KEY  (`id_mapa`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

CREATE TABLE `mapa_documento` (
  `id_documento` int(10) unsigned NOT NULL auto_increment,
  `nombre_documento` varchar(250) NOT NULL,
  `contenido` text NOT NULL,
  `tipo_documento` varchar(250) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY  (`id_documento`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

CREATE TABLE `mapa_doc_cont` (
  `id_mapa` int(10) unsigned NOT NULL default '0',
  `id_documento` int(10) unsigned NOT NULL default '0',
  `fecha` date default NULL,
  PRIMARY KEY  (`id_mapa`,`id_documento`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `mapa_submapa` (
  `mapa_padre` int(11) NOT NULL default '0',
  `mapa_hijo` int(11) NOT NULL default '0',
  `figura` varchar(250) default NULL,
  `coordenadas` varchar(250) default NULL,
  `posicion` int(11) default NULL,
  `preposicion` varchar(250) default NULL,
  PRIMARY KEY  (`mapa_padre`,`mapa_hijo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

//Estilos

/*
.contenedor
{
	border-width:1px;
	border-color:#000000;
	border-bottom-style:solid;
	border-left-style:none;
	border-top-style:solid;
	border-right-style:none;
}
.celda_normal
{
	border-width:1px;
	border-color:#000000;
	border-bottom-style:solid;
	border-left-style:solid;
	border-top-style:solid;
	border-right-style:solid;
	background-color:#CCCCCC;
	color:#000000;
	padding:5px;
	font-size:small;
}
.celda_actual
{
	border-width:1px;
	border-color:#000000;
	border-bottom-style:solid;
	border-left-style:solid;
	border-top-style:solid;
	border-right-style:solid;
	background-color:#666666;
	color:#FFFFFF;
	padding:5px;
	font-size:small;
}
*/

}
?>
