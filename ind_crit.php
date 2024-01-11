<?php

session_start();

include "apoyo.php"; 

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]) )
{
	header("location: index.php?noCache=".rand(0,32000));
	exit();
}
date_default_timezone_set('America/Mexico_City');
$hoy=getdate();
@list($anio,$mes)=explode("-",PostString("fecha_seleccionada"));
$ant=PostString("ant");
$sig=PostString("sig");
$indicador=PostString("indicador");
$nivel=PostString("nivel");
if($indicador=="")
	$indicador="M01";

$btn_ant=((PostString("ant_ind")!="")?(true):(false));
$btn_sig=((PostString("sig_ind")!="")?(true):(false));

if($btn_ant || $btn_sig)
{
	$indicadores=mysql_query("select indicador.indicador as ind from indicador,codigos_generales where campo = 'indicador_tipo' and tipo = valor order by codigos_generales.posicion,indicador.posicion");
	while($indica=mysql_fetch_array($indicadores))
	{
		$ind[]=$indica["ind"];
	}
	//Detectar el indicador actual;
	$ya=false;
	$x=0;
	while($x<count($ind) && !$ya)
	{
		if($ind[$x]==$indicador)
		{
			$ya=true;
			break;
		}
		$x++;
	}
	if($btn_ant)
	{
		if($x>0)
			$x--;
		else
			Alert("¡Ya no existen más indicadores!");
	}
	else if($btn_sig)
	{
		if($x<count($ind)-1)
			$x++;
		else
			Alert("¡Ya no existen más indicadores!");
	}
	$nivel="";
	$indicador=$ind[$x];
}

if(PostString("indicador_actual")!=$indicador)
{
	$nivel="";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita</title>
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
<script language="javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" src="u_yui/container_core.js"></script>
<script language="javascript" src="u_yui/menu.js"></script>
<script type="text/javascript">YAHOO.util.Event.onContentReady("barra_menu", function () {var oMenuBar = new YAHOO.widget.MenuBar("barra_menu", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});YAHOO.util.Event.onContentReady("menu_opciones", function () {var oMenuBar = new YAHOO.widget.MenuBar("menu_opciones", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	var petHttp;
	var petHttp02;
	function Comentar()
	{
		var comentario=(($('comentario')==null)?(""):($('comentario').value));
		var anio="<?php echo "20".$anio; ?>";
		var mes="<?php echo $mes; ?>";
		var id_usuario="<?php echo $_SESSION["id_usr"]; ?>";
		if(indicador=="") 
		{
			alert("No hay indicador seleccionado");
			return false;
		}
		if(nivel=="") 
		{
			alert("No hay nivel seleccionado");
			return false;
		}
		if(id_usuario=="")
		{
			alert("No hay usuario en sesión");
			return false;
		}
		var query='comentario='+comentario+'&indicador='+indicador+'&nivel='+nivel+'&id_usuario='+id_usuario+"&noCache="+Math.random()+'&anio='+anio+'&mes='+mes+'&fecha='+($('fecha_comentario').value);
		$('fecha_comentario').value="";
		petHttp=((window.XMLHttpRequest)?(new XMLHttpRequest()):((window.ActiveXObject)?(new window.ActiveXObject("Microsoft.XMLHTTP")):("")));
		if(petHttp!="")
		{
			petHttp.onreadystatechange = mostrarComentarios;
			
			petHttp.open('POST', '_add_comentario_ind_crit.php', true);
			petHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			petHttp.send(query);
		}
	}
	function NoComentarioAgregado()
	{
		alert("Error: \n\tEl comentario no ha sido agregado");
	}
	function ComentarioAgregado(respuesta)
	{
		document.getElementById('comentarios_reporte').innerHTML=respuesta.responseText;
	}
	function mostrarComentarios()
	{
		if(petHttp.readyState == 4)
		{
			if(petHttp.status == 200)
			{
				ComentarioAgregado(petHttp);
			}
			else
			{
				NoComentarioAgregado();
			}
		}
	}
	function Interna()
	{
		if($('comentario')==null)
			Comentar();
	}
	function Inicializar()
	{
		setInterval(Interna,5*1000);
	}
	function AddCommentSpace(fecha)
	{
		var anio="<?php echo $anio; ?>";
		var mes="<?php echo $mes; ?>";
		var id_usuario="<?php echo $_SESSION["id_usr"]; ?>";
		if(indicador=="") 
		{
			alert("No hay indicador seleccionado");
			return false;
		}
		if(nivel=="") 
		{
			alert("No hay nivel seleccionado");
			return false;
		}
		if(id_usuario=="")
		{
			alert("No hay usuario en sesión");
			return false;
		}
		var query='indicador='+indicador+'&nivel='+nivel+'&id_usuario='+id_usuario+"&noCache="+Math.random()+'&anio='+anio+'&mes='+mes+'&fecha='+fecha;
		petHttp02=((window.XMLHttpRequest)?(new XMLHttpRequest()):((window.ActiveXObject)?(new window.ActiveXObject("Microsoft.XMLHTTP")):("")));
		if(petHttp02!="")
		{
			petHttp02.onreadystatechange = function()
				{
					if(petHttp02.readyState == 4)
					{
						if(petHttp02.status == 200)
						{
							var texto = petHttp02.responseText;
							if(fecha!="hoy")
							{
								$(fecha).innerHTML="";
								$('fecha_comentario').value=fecha;
							}
							$('elComentario').innerHTML='<div style="padding:0px; margin:0px"><textarea name="comentario" id="comentario" rows="6" cols="75">'+texto+'</textarea><br /><input name="button" type="button" value="Guardar" onclick="Comentar();" class="btn_normal" /></div>';
						}
						else
						{
							$('elComentario').innerHTML='<div style="padding:0px; margin:0px"><textarea name="comentario" id="comentario" rows="6" cols="150"></textarea><br /><input name="button" type="button" value="Guardar" onclick="Comentar();" class="btn_normal" /></div>';
						}
					}
				};			
			petHttp02.open('POST', '_ver_comentario_ind_crit_hoy.php', true);
			petHttp02.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			petHttp02.send(query);
		}
	}
</script>
</head>

<body onload="Interna(); Inicializar();">
<table bgcolor='f2f2f2' width='100%' height='40' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
    <td width="35%"><table border='0' align='left' cellpadding='0' cellspacing='0'>
      <tr>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td><img src="Imagenes/menu/home.png" alt="Inicio" title="Inicio" onclick="javascript: /*window.close()*/location.href='entrada.php';" /></td>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td></td>
      </tr>
    </table></td>
    <td width="31%"><div align='center'><strong>Indicadores</strong></div></td>
    <td width="34%"><table border='0' align='right' cellpadding='0' cellspacing='0'>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><img src="Imagenes/Btn_salir.png" onclick="javascript: /*window.close()*/location.href='index.php';" /></td>
        </tr>
    </table></td>
  </tr>
</table>
<?php
//B_reportes();
BH_Ayuda('0.4','2'); 
?>
<table border="0" width="100%" align="center"><tr><td>
<form name="datos" action="ind_crit.php" method="post">
	<table border="0" align="center" width="100%">
		<tr>
			<td align="left" width="250" valign="top">
			<?php
				if ($handle = opendir($Dir.'/Archivos_Indicadores_Mensual')) 
				{
   					while (false !== ($file = readdir($handle))) 
					{
						if(intval($file)>0)
						{
								$archivos_fechas[]=$file;
						}
				    }
    				closedir($handle);
				}
				rsort($archivos_fechas);
				foreach($archivos_fechas as $key => $val)
				{
					$anio_disp[]=".20".substr($val,0,2);
					$anio_num[]=substr($val,0,2);
					$mes_num[]=substr($val,2,2);
					if(substr($val,2,2)=="01") $mes_disp[]="Ene";
					else if(substr($val,2,2)=="02") $mes_disp[]="Feb";
					else if(substr($val,2,2)=="03") $mes_disp[]="Mar";
					else if(substr($val,2,2)=="04") $mes_disp[]="Abr";
					else if(substr($val,2,2)=="05") $mes_disp[]="May";
					else if(substr($val,2,2)=="06") $mes_disp[]="Jun";
					else if(substr($val,2,2)=="07") $mes_disp[]="Jul";
					else if(substr($val,2,2)=="08") $mes_disp[]="Ago";
					else if(substr($val,2,2)=="09") $mes_disp[]="Sep";
					else if(substr($val,2,2)=="10") $mes_disp[]="Oct";
					else if(substr($val,2,2)=="11") $mes_disp[]="Nov";
					else if(substr($val,2,2)=="12") $mes_disp[]="Dic";
					if($anio=="") $anio=substr($val,0,2);
					if($mes=="") $mes=substr($val,2,2);
				}
			?>
			Fecha:
			<select name="fecha_seleccionada" onchange="javascript: document.datos.submit();">
			<?php
				foreach($anio_disp as $key => $val)
				{
					$fech=getdate();
					?>
					<option value="<?php echo $anio_num[$key]."-".$mes_num[$key]; ?>"><?php echo $mes_disp[$key]." ".($val!=$fech["year"]?$val:""); ?></option>
					<?
				}
			?>
			</select>
				<script language="javascript">
					document.datos.fecha_seleccionada.value="<?php echo $anio; ?>-<?php echo $mes; ?>";
				</script>
			</td>
			<td align="left" valign="middle" width="300"><div>Indicador:
				<select name="indicador" onchange="javascript: document.datos.nivel.value=''; document.datos.submit();">
					<?php
						$x=1;
						if($tipos_indicadores=mysql_query("select valor, descripcion from codigos_generales where campo ='indicador_tipo' order by posicion, descripcion"))
						{
							while($tipo_indicador=mysql_fetch_array($tipos_indicadores))
							{
								?>
								<optgroup label="<?php echo $tipo_indicador["descripcion"]; ?>">
									<?php
									if($indicadores_bd=mysql_query("select indicador, nombre from indicador where tipo='".$tipo_indicador["valor"]."' order by posicion"))
									{
										while($indicador_bd=mysql_fetch_array($indicadores_bd))
										{
											?>
											<option value="<?php echo $indicador_bd["indicador"]; ?>" <?php if ($x==1) echo 'selected="selected"';?>><?php echo $indicador_bd["nombre"]; ?></option>
											<?php
											$x++;
										}
									}
									?>
								</optgroup>
								<?php
							}
						}
					?>
				</select>
				<?php
				if($indicador!="")
				{
					?>
					<script language="javascript">
						document.datos.indicador.value="<?php echo $indicador; ?>";
					</script>
					<?php
				}
				?>
				<input type="hidden" name="indicador_actual" value="<?php echo $indicador; ?>" />
				<button type="submit" style="background-color:#FFFFFF; border-style:none;" onclick="javascript: document.datos.ant_ind.value='yes';"><img src="Imagenes/back.png" /></button>
				<button type="submit" style="background-color:#FFFFFF; border-style:none;" onclick="javascript: document.datos.sig_ind.value='yes';"><img src="Imagenes/next.png" /></button></div>
				<input type="hidden" name="ant_ind" value="" />
				<input type="hidden" name="sig_ind" value="" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nivel:
				<select name="nivel" onchange="javascript: document.datos.submit();">
					<?php
						if($niveles_bd=mysql_query("select id_indicador_nivel, descripcion from indicador_nivel where indicador = '$indicador' order by posicion, descripcion"))
						{
							$x=1;
							while($nivel_bd=mysql_fetch_array($niveles_bd))
							{
								?>
								<option value="<?php echo $nivel_bd["id_indicador_nivel"]; ?>" <?php if ($x==1) echo 'selected="selected"';?>><?php echo $nivel_bd["descripcion"]; ?></option>
								<?php
								if($nivel=="")
								{
									$nivel=$nivel_bd["id_indicador_nivel"];
								}
								$x++;
							}
						}
					?>
				</select>
				<?php
				if($nivel!="")
				{
					?>
					<script language="javascript">
						document.datos.nivel.value="<?php echo $nivel; ?>";
					</script>
					<?php
				}
				else
				{
					$id_nivel=mysql_fetch_array(mysql_query("select id_indicador_nivel from indicador_nivel where indicador = '$indicador' and descripcion=''"));
					$nivel=$id_nivel["id_indicador_nivel"];
				}
				?>
				<?php
				$imagen_indice="";
				$imagen_nivel="";
				$imagen_archivo="";
				$imagen_reporte="";
				$img_ind=@mysql_fetch_array(mysql_query("select tipo_tendencia from indicador where indicador='$indicador'"));
				if($img_ind["tipo_tendencia"]=="1" || $img_ind["tipo_tendencia"]=="2" || $img_ind["tipo_tendencia"]=="3")
					$imagen_indice="T".$img_ind["tipo_tendencia"].".png";
				$img_niv=@mysql_fetch_array(mysql_query("select semaforo, tendencia from indicador_archivo where anio='20"."$anio' and mes='".intval($mes)."' and id_indicador_nivel='$nivel'"));
				if(($img_niv["tendencia"]=="1" || $img_niv["tendencia"]=="2" || $img_niv["tendencia"]=="3") && ($img_niv["semaforo"]=="A" || $img_niv["semaforo"]=="R" || $img_niv["semaforo"]=="V"))
					$imagen_nivel="S".$img_niv["semaforo"]."T".$img_niv["tendencia"].".png";
				$imagen_archivo=@mysql_fetch_array(mysql_query("select prefijo_archivo from indicador_nivel where id_indicador_nivel = '$nivel'"));
				if($imagen_archivo["prefijo_archivo"]!="")
				{
					$cad2=$anio.$mes;
					if(file_exists($Dir."/Archivos_Indicadores_Mensual/$cad2/".$imagen_archivo["prefijo_archivo"].".jpg"))
					{	
						$imagen_reporte=$imagen_archivo["prefijo_archivo"];
					}
					else
					{
						$imagen_reporte=$imagen_archivo["prefijo_archivo"]."-".$cad2;
					}
				}
				?>
			</td>
			<td valign="top" align="left">
				<?php
				if($imagen_indice!="")
				{
					?>
					<img border="0" src="Imagenes/<?php echo $imagen_indice; ?>" align="middle" />
					<?php
				}
				if($imagen_nivel!="")
				{
					?>
					<img border="0" src="Imagenes/<?php echo $imagen_nivel; ?>" align="middle" />
					<?php
				}
				?>
			</td>
		</tr>
	</table>
</form>
</td></tr></table>
<table border="0" align="center">
	<tr>
		<td>
			<?php
			if($imagen_reporte!="" && file_exists($Dir."/Archivos_Indicadores_Mensual/$cad2/$imagen_reporte.jpg"))
			{
				?>
				<img border="0" src="Archivos_Indicadores_Mensual/<?php echo $cad2."/".$imagen_reporte; ?>.jpg" />
				<?php
			}
			?>
		</td>
	</tr>
</table>
<br />
<div id="comentarios_reporte"></div>
<input type="hidden" id="fecha_comentario" value="" />
<script language="javascript">	
	var indicador="<?php echo $indicador; ?>";
	var nivel="<?php echo $nivel; ?>";
</script>
</body>
</html>
<?php

mysql_close();

?>