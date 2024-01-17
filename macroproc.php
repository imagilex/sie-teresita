<?php

session_start();

include "apoyo.php";
include "util_dir/directorio.php";

$Con = Conectar();

$tipo_reporte = Get_Vars_Helper::getPostVar("tipo_rep");
if($tipo_reporte=="") $tipo_reporte="RH";

$carp=@mysqli_fetch_array(consulta_directa("select otro from codigos_generales where campo = 'Reporte_tipo' and valor = '$tipo_reporte'"));
$carpeta=$carp["otro"];

if(!isset($_SESSION["tipo"]) )
{
	header("location: index.php");
	exit();
}

$id_reporte = Get_Vars_Helper::getPostVar("reporte");
$nivel = Get_Vars_Helper::getPostVar("nivel");
$fecha = Get_Vars_Helper::getPostVar("fecha_reporte");

$btn_ant = ((Get_Vars_Helper::getPostVar("ant_rep")!="")?(true):(false));
$btn_sig = ((Get_Vars_Helper::getPostVar("sig_rep")!="")?(true):(false));

if($btn_ant || $btn_sig)
{
	$reps=consulta_directa("select reporte from reportes where tipo = '$tipo_reporte' and reporte in (select distinct reporte from reporte_nivel where id_reporte in (select id_reporte from reporte_seguridad where usuario='".$_SESSION["id_usr"]."' )) order by posicion");
	while($rep=mysqli_fetch_array($reps))
		$reportes[]=$rep["reporte"];
	$ya=false;
	$x=0;
	while($x<count($reportes) && !$ya)
	{
		if($reportes[$x]==$id_reporte)
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
			Alert("¡Ya no existen más reportes!");
	}
	else if($btn_sig)
	{
		if($x<count($reportes)-1)
			$x++;
		else
			Alert("¡Ya no existen más reportes!");
	}
	$nivel="";
	$id_reporte=$reportes[$x];
}

if(Get_Vars_Helper::getPostVar("reporte_actual")!=$id_reporte)
	$nivel="";

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
	var max_ancho=-32000;
	function Comentar()
	{
		var comentario=(($('comentario')==null)?(""):($('comentario').value));
		var id_usuario="<?php echo $_SESSION["id_usr"]; ?>";
		if(id_reporte=="")
		{
			alert("No hay reporte seleccionado");
			return false;
		}
		if(id_usuario=="")
		{
			alert("No hay usuario en sesión");
			return false;
		}
		var query='comentario='+comentario+'&id_reporte='+id_reporte+'&id_usuario='+id_usuario+"&noCache="+Math.random()+'&fecha='+($('fecha_comentario').value)+"&fecha_reporte=20"+$('fecha_reporte').value;
		$('fecha_comentario').value="";
		petHttp=((window.XMLHttpRequest)?(new XMLHttpRequest()):((window.ActiveXObject)?(new window.ActiveXObject("Microsoft.XMLHTTP")):("")));
		if(petHttp!="")
		{
			petHttp.onreadystatechange = mostrarComentarios;

			petHttp.open('POST', '_add_comentario_frep_diar.php', true);
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
		var id_usuario="<?php echo $_SESSION["id_usr"]; ?>";
		if(id_reporte=="")
		{
			alert("No hay reporte seleccionado");
			return false;
		}
		if(id_usuario=="")
		{
			alert("No hay usuario en sesión");
			return false;
		}
		var query='id_reporte='+id_reporte+'&id_usuario='+id_usuario+"&noCache="+Math.random()+'&fecha='+fecha+"&fecha_reporte=20"+$('fecha_reporte').value;
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
			petHttp02.open('POST', '_ver_comentario_rep_diar_hoy.php', true);
			petHttp02.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			petHttp02.send(query);
		}
	}
</script>
<script language="javascript">
	var tiempo=200;
	var intval="";
	function init_report()
	{
		if($('objeto_base'))
		{
			var alto=$('objeto_base').height;
			$('parte12').height=alto;
		}
	}
	function go_left()
	{
		window.frames['parte12'].go_left();
		window.frames['parte22'].go_left();
	}
	function go_right()
	{
		window.frames['parte12'].go_right();
		window.frames['parte22'].go_right();
	}
	function go_top()
	{
		window.frames['parte21'].go_top();
		window.frames['parte22'].go_top();
	}
	function go_bottom()
	{
		window.frames['parte21'].go_bottom();
		window.frames['parte22'].go_bottom();
	}
	function pres_top()
	{
		intval=setInterval("go_top()",tiempo);
	}
	function suel_top()
	{
		clearInterval(intval);
	}
	function pres_bottom()
	{
		intval=setInterval("go_bottom()",tiempo);
	}
	function suel_bottom()
	{
		clearInterval(intval);
	}
	function pres_right()
	{
		intval=setInterval("go_right()",tiempo);
	}
	function suel_right()
	{
		clearInterval(intval);
	}
	function pres_left()
	{
		intval=setInterval("go_left()",tiempo);
	}
	function suel_left()
	{
		clearInterval(intval);
	}
</script>
</head>

<body onload="init_report(); Interna(); Inicializar();">
<table bgcolor='f2f2f2' width='100%' height='40' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
    <td width="37%"><table border='0' align='left' cellpadding='0' cellspacing='0'>
      <tr>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td><img src="Imagenes/menu/home.png" alt="Inicio" title="Inicio" onclick="javascript: /*window.close()*/location.href='entrada.php';" /></td>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td></td>
      </tr>
    </table></td>
    <td width="31%"><div align='center'><strong>Reportes</strong></div></td>
    <td width="32%"><table border='0' align='right' cellpadding='0' cellspacing='0'>
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
//BH_Ayuda('0.4','1');
?>
<form action="frep_diar.php" method="post" name="rep_di">
<input type="hidden" name="tipo_rep" value="<?php echo $tipo_reporte; ?>" />

<table border="0" width="100%">
	<tr>
		<td align="left" width="350" rowspan="2" valign="top">
			Fecha:
			<select name="fecha_reporte" onchange="javascript: document.rep_di.submit();">
				<?php
					if ($handle = opendir($Dir."/$carpeta"))
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
					$direc=new directorio($Dir."/$carpeta");
					$carps=json_decode($direc->JSON_carpetas(true));
					foreach($carps as $key => $val)
					{
						if($val!="." && $val!=".." && $val!="Comunes")
						{
						?>
							<option value="<?php echo $val; ?>"><?php echo $val;?></option>
						<?php
						if($fecha=="")
							$fecha=$val;
						}
					}
				?>
			</select>
			<script language="javascript">
				document.rep_di.fecha_reporte.value="<?php echo $fecha; ?>";
			</script>		</td>
		<td align="right">
			Reporte:		</td>
		<td>
			<select name="reporte" onchange="javascript: document.rep_di.submit();">
				<option value=""></option>
				<?php
				$query="select reporte,nombre from reportes where tipo = '$tipo_reporte' and reporte in (select reporte from reporte_nivel where id_reporte in (select id_reporte from reporte_seguridad where usuario='".$_SESSION["id_usr"]."' )) order by posicion, nombre";
				if($reps_bd=consulta_directa($query))
					while($rep_bd=mysqli_fetch_array($reps_bd))
					{
						?>
						<option value="<?php echo $rep_bd["reporte"]; ?>"><?php echo $rep_bd["nombre"]; ?></option>
						<?php
						if($id_reporte=="")
							$id_reporte=$rep_bd["reporte"];
					}
				?>
			</select>
			<button type="submit" onclick="javascript: document.rep_di.ant_rep.value='yes';" style="background-color:#FFFFFF; border-style:none;"><img src="Imagenes/back.png" /></button>
			<button type="submit" onclick="javascript: document.rep_di.sig_rep.value='yes';" style="background-color:#FFFFFF; border-style:none;"><img src="Imagenes/next.png" /></button>
			<input type="hidden" name="ant_rep" value="" />
			<input type="hidden" name="sig_rep" value="" />		</td>
	</tr>
	<tr>
		<td align="right">
			Nivel:		</td>
		<td>
			<?php
			$quer="select id_reporte,descripcion from reporte_nivel where reporte='$id_reporte' order by posicion, descripcion";
			?>
			<select name="nivel" onchange="javascript: document.rep_di.submit();">
			<?php
				if($niveles_bd=consulta_directa($quer))
				{
					while($nivel_bd=mysqli_fetch_array($niveles_bd))
					{
						?>
						<option value="<?php echo $nivel_bd["id_reporte"]; ?>"><?php echo $nivel_bd["descripcion"]; ?></option>
						<?php
						if($nivel=="")
							$nivel=$nivel_bd["id_reporte"];
					}
				}
			?>
			</select>
			<input type="hidden" name="reporte_actual" value="<?php echo $id_reporte; ?>" />
	  <script language="javascript">
				document.rep_di.reporte.value="<?php echo $id_reporte; ?>";
				document.rep_di.nivel.value="<?php echo $nivel; ?>";
			</script>		</td>
	</tr>
</table>
</form>
<?php

$arch_1=@mysqli_fetch_array(consulta_directa("select prefijo_reporte, extension from reportes_secciones where id_reporte='$nivel' and nombre='Encabezado_A'"));
$arch_2=@mysqli_fetch_array(consulta_directa("select prefijo_reporte, extension from reportes_secciones where id_reporte='$nivel' and nombre='Encabezado_B'"));
$arch_3=@mysqli_fetch_array(consulta_directa("select prefijo_reporte, extension from reportes_secciones where id_reporte='$nivel' and nombre='Detalle_A'"));
$arch_4=@mysqli_fetch_array(consulta_directa("select prefijo_reporte, extension from reportes_secciones where id_reporte='$nivel' and nombre='Detalle_B'"));
if($arch_1["prefijo_reporte"]!="" && $arch_2["prefijo_reporte"]!="" && $arch_3["prefijo_reporte"]!="" && $arch_4["prefijo_reporte"]!="")
{
	$pref_1=$Dir."/$carpeta/$fecha/";
	$pref_2=$Dir."/$carpeta/Comunes/";
	if(file_exists($pref_1.$arch_1["prefijo_reporte"].".".$arch_1["extension"]))
		$archivo1=$pref_1.$arch_1["prefijo_reporte"].".".$arch_1["extension"];
	else if(file_exists($pref_2.$arch_1["prefijo_reporte"].".".$arch_1["extension"]))
		$archivo1=$pref_2.$arch_1["prefijo_reporte"].".".$arch_1["extension"];
	else if(file_exists($pref_1.$arch_1["prefijo_reporte"]."-$fecha".".".$arch_1["extension"]))
		$archivo1=$pref_1.$arch_1["prefijo_reporte"]."-$fecha".".".$arch_1["extension"];
	else if(file_exists($pref_2.$arch_1["prefijo_reporte"]."-$fecha".".".$arch_1["extension"]))
		$archivo1=$pref_2.$arch_1["prefijo_reporte"]."-$fecha".".".$arch_1["extension"];
	else if(file_exists($pref_2.$arch_1["prefijo_reporte"].".".$arch_1["extension"]))
		$archivo1=$pref_2.$arch_1["prefijo_reporte"].".".$arch_1["extension"];
	else
		$archivo1="";
	if(file_exists($pref_1.$arch_2["prefijo_reporte"].".".$arch_2["extension"]))
		$archivo2=$pref_1.$arch_2["prefijo_reporte"].".".$arch_2["extension"];
	else if(file_exists($pref_2.$arch_2["prefijo_reporte"].".".$arch_2["extension"]))
		$archivo2=$pref_2.$arch_2["prefijo_reporte"].".".$arch_2["extension"];
	else if(file_exists($pref_1.$arch_2["prefijo_reporte"]."-$fecha".".".$arch_2["extension"]))
		$archivo2=$pref_1.$arch_2["prefijo_reporte"]."-$fecha".".".$arch_2["extension"];
	else if(file_exists($pref_2.$arch_2["prefijo_reporte"]."-$fecha".".".$arch_2["extension"]))
		$archivo2=$pref_2.$arch_2["prefijo_reporte"]."-$fecha".".".$arch_2["extension"];
	else if(file_exists($pref_2.$arch_2["prefijo_reporte"].".".$arch_2["extension"]))
		$archivo2=$pref_2.$arch_2["prefijo_reporte"].".".$arch_2["extension"];
	else
		$archivo2="";
	if(file_exists($pref_1.$arch_3["prefijo_reporte"].".".$arch_3["extension"]))
		$archivo3=$pref_1.$arch_3["prefijo_reporte"].".".$arch_3["extension"];
	else if(file_exists($pref_2.$arch_3["prefijo_reporte"].".".$arch_3["extension"]))
		$archivo3=$pref_2.$arch_3["prefijo_reporte"].".".$arch_3["extension"];
	else if(file_exists($pref_1.$arch_3["prefijo_reporte"]."-$fecha".".".$arch_3["extension"]))
		$archivo3=$pref_1.$arch_3["prefijo_reporte"]."-$fecha".".".$arch_3["extension"];
	else if(file_exists($pref_2.$arch_3["prefijo_reporte"]."-$fecha".".".$arch_3["extension"]))
		$archivo3=$pref_2.$arch_3["prefijo_reporte"]."-$fecha".".".$arch_3["extension"];
	else if(file_exists($pref_2.$arch_3["prefijo_reporte"].".".$arch_3["extension"]))
		$archivo3=$pref_2.$arch_3["prefijo_reporte"].".".$arch_3["extension"];
	else
		$archivo3="";
	if(file_exists($pref_1.$arch_4["prefijo_reporte"].".".$arch_4["extension"]))
		$archivo4=$pref_1.$arch_4["prefijo_reporte"].".".$arch_4["extension"];
	else if(file_exists($pref_2.$arch_4["prefijo_reporte"].".".$arch_4["extension"]))
		$archivo4=$pref_2.$arch_4["prefijo_reporte"].".".$arch_4["extension"];
	else if(file_exists($pref_1.$arch_4["prefijo_reporte"]."-$fecha".".".$arch_4["extension"]))
		$archivo4=$pref_1.$arch_4["prefijo_reporte"]."-$fecha".".".$arch_4["extension"];
	else if(file_exists($pref_2.$arch_4["prefijo_reporte"]."-$fecha".".".$arch_4["extension"]))
		$archivo4=$pref_2.$arch_4["prefijo_reporte"]."-$fecha".".".$arch_4["extension"];
	else if(file_exists($pref_2.$arch_4["prefijo_reporte"].".".$arch_4["extension"]))
		$archivo4=$pref_2.$arch_4["prefijo_reporte"].".".$arch_4["extension"];
	else
		$archivo4="";
	?>
	<table border="0" align="center" cellpadding="0" cellspacing="0" width="1000">
		<tr>
			<td width="1%" valign="bottom" height="5" id="celda_base"><?php
				$informacion=pathinfo($archivo1);
				if($archivo1!="" && file_exists($archivo1))
				{
					if($informacion["extension"]=="" || $informacion["extension"]=="txt")
					{
						if($Arch=@fopen($archivo1,"r"))
						{
							?><pre id="objeto_base"><?php
							while(!feof($Arch))
								echo fgets($Arch)."";
							fclose($Arch);
							?></pre><?php
						}
					}
					else if($informacion["extension"]=="jpg" || $informacion["extension"]=="png" || $informacion["extension"]=="bmp" || $informacion["extension"]=="gif")
					{
						?><img src="<?php echo substr($archivo1, strlen($Dir)+1); ?>" border="0" id="objeto_base" /><?php
					}
				}
				else {trigger_error("Archivo no encontrado: $archivo1");}
				?></td>
			<td width="99%" valign="bottom"><iframe id="parte12" name="parte12" width="100%" height="75" scrolling="no" frameborder="0" marginheight="0" marginwidth="0" src="_muestra_archivo_rf.php?archivo=<?php echo substr($archivo2,strlen($Dir)); ?>"></iframe></td>
			<td></td>
		</tr>
		<tr>
			<td><iframe id="parte21" name="parte21" scrolling="no" height="400" width="100%" frameborder="0" marginheight="0" marginwidth="0" src="_muestra_archivo_rf.php?archivo=<?php echo substr($archivo3,strlen($Dir)); ?>"></iframe></td>
			<td><iframe id="parte22" name="parte22" scrolling="no" height="400" width="100%" frameborder="0" marginheight="0" marginwidth="0" src="_muestra_archivo_rf.php?archivo=<?php echo substr($archivo4,strlen($Dir)); ?>"></iframe></td>
			<td valign="middle" style="padding-left:10px;">
			  <p><img src="Imagenes/top.png" border="0" onclick="go_top();" onmousedown="pres_top()" onmouseup="suel_top()" /></p>
			  <p><br />
		      <img src="Imagenes/bottom.png" border="0" onclick="go_bottom();" onmousedown="pres_bottom()" onmouseup="suel_bottom()" /></p></td>
		</tr>
		<tr>
			<td></td>
			<td align="right" style="padding-top:10px;">
			<img src="Imagenes/back.png" border="0" onclick="go_left();" onmousedown="pres_left()" onmouseup="suel_left()" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="Imagenes/next.png" border="0" onclick="go_right();"  onmousedown="pres_right()" onmouseup="suel_right()" />
</td>
			<td></td>
		</tr>
	</table>
	<?php
	}
else
{
?>
<table id="tbl_reporte" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:11px;"><tr><td style="padding:0px; margin:0px;">
<?php
if($secciones=consulta_directa("select prefijo_reporte, nombre,extension from reportes_secciones where id_reporte='$nivel' order by posicion"))
{
	$maximo=-32000;
	while($seccion=mysqli_fetch_array($secciones))
	{
		?>
		<!--<tr><td style="padding:0px; margin:0px;">-->
		<?php
		$sf_archivo=$Dir."/$carpeta/$fecha/".$seccion["prefijo_reporte"].".".$seccion["extension"];
		$sf_archivo_comun=$Dir."/$carpeta/Comunes/".$seccion["prefijo_reporte"].".".$seccion["extension"];
		$sf_archivo_sd="$carpeta/$fecha/".$seccion["prefijo_reporte"].".".$seccion["extension"];
		$sf_archivo_comun_sd="$carpeta/Comunes/".$seccion["prefijo_reporte"].".".$seccion["extension"];
		$archivo=$Dir."/$carpeta/$fecha/".$seccion["prefijo_reporte"]."-".$fecha.".".$seccion["extension"];
		$archivo_comun=$Dir."/$carpeta/Comunes/".$seccion["prefijo_reporte"].".".$seccion["extension"];
		$archivo_sd="$carpeta/$fecha/".$seccion["prefijo_reporte"]."-".$fecha.".".$seccion["extension"];
		$archivo_comun_sd="$carpeta/Comunes/".$seccion["prefijo_reporte"].".".$seccion["extension"];
		if(file_exists($sf_archivo) && $seccion["nombre"]!="Detalle")
		{
			$info=pathinfo($sf_archivo);
			if($info["extension"]=="jpg" ||$info["extension"]=="png" ||$info["extension"]=="gif")
			{
				?>
				<div style="margin:0px; padding:0px;" >
					<center><img src="<?php echo $sf_archivo_sd; ?>" border="0" /><font color="#FFFFFF">.......</font></center>
				</div>
				<?php
			}
			else
			{
			?>
			<div style="margin:0px; padding:0px;" ><pre><?php
			$maxi=MostrarArchivo($sf_archivo,"");
			if($maxi>$maximo)
				$maximo=$maxi;
			?></pre></div>
			<?php
			}
		}
		else if(file_exists($sf_archivo_comun) && $seccion["nombre"]!="Detalle")
		{
			$info=pathinfo($sf_archivo_comun);
			if($info["extension"]=="jpg" ||$info["extension"]=="png" ||$info["extension"]=="gif")
			{
				?>
				<div style="margin:0px; padding:0px;" >
					<center><img src="<?php echo $sf_archivo_comun_sd; ?>" border="0" /><font color="#FFFFFF">.......</font></center>
				</div>
				<?php
			}
			else
			{
			?>
			<div style="margin:0px; padding:0px;" ><pre><?php
			$maxi=MostrarArchivo($sf_archivo_comun,"");
			if($maxi>$maximo)
				$maximo=$maxi;
			?></pre></div>
			<?php
			}
		}
		else if(file_exists($sf_archivo) && $seccion["nombre"]=="Detalle")
		{
			?>
			<iframe src="lolo.php?archivo=<?php echo "$carpeta/$fecha/".$seccion["prefijo_reporte"].".".$seccion["extension"] ?>" frameborder="0" id="detalle" height="450" width="100%" marginheight="0" marginwidth="0"></iframe>
			<?php
		}
		else if(file_exists($archivo) && $seccion["nombre"]!="Detalle")
		{
			$info=pathinfo($archivo);
			if($info["extension"]=="jpg" ||$info["extension"]=="png" ||$info["extension"]=="gif")
			{
				?>
<div style="margin:0px; padding:0px;" >
					<center>
					  <img src="<?php echo $archivo_sd; ?>" border="0" /><font color="#FFFFFF">.......</font>
					</center>
	  </div>
				<?php
			}
			else
			{
			?>
			<div style="margin:0px; padding:0px;" ><pre><?php
			$maxi=MostrarArchivo($archivo,"");
			if($maxi>$maximo)
				$maximo=$maxi;
			?></pre></div>
			<?php
			}
		}
		else if(file_exists($archivo_comun) && $seccion["nombre"]!="Detalle")
		{
			$info=pathinfo($archivo_comun);
			if($info["extension"]=="jpg" ||$info["extension"]=="png" ||$info["extension"]=="gif")
			{
				?>
				<div style="margin:0px; padding:0px;" >
					<center><img src="<?php echo $archivo_comun_sd; ?>" border="0" /><font color="#FFFFFF">.......</font></center>
				</div>
				<?php
			}
			else
			{
			?>
			<div style="margin:0px; padding:0px;" ><pre><?php
			$maxi=MostrarArchivo($archivo_comun,"");
			if($maxi>$maximo)
				$maximo=$maxi;
			?></pre></div>
			<?php
			}
		}
		else if(file_exists($archivo) && $seccion["nombre"]=="Detalle")
		{
			?><iframe src="_mostrar_archivo.php?archivo=<?php echo "$carpeta/$fecha/".$seccion["prefijo_reporte"]."-".$fecha.".".$seccion["extension"] ?>" frameborder="0" id="detalle" height="400" width="100%" marginheight="0" marginwidth="0"></iframe><?php
		}
		?>
		<!--</td></tr>-->
		<?php
	}
}
?>
<div><pre><?php for($mivar=1;$mivar<=$maximo+2;$mivar++) echo " ";?></pre></div>
</td></tr>
</table>
<?php
}
?>
<div id="comentarios_reporte"></div>
<input type="hidden" id="fecha_comentario" value="" />
<script language="javascript">
	var id_reporte="<?php echo $id_reporte; ?>";
</script>
</body>
</html>
