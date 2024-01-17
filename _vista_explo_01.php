<?php

session_start();

header("Expires: Mon, 22 Sep 1997 09:00:00 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");

include "apoyo.php";

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]) )
{
	header("location: index.php?noCache=".rand(0,32000));
	exit();
}

$proyecto = Get_Vars_Helper::getPGVar("proyecto");

if($proyecto=="")
{
	$proys_db=mysqli_fetch_array(consulta_directa("select id_documento as proyecto, nombre from docto_general where tipo_documento='2' order by nombre limit 1"));
	$proyecto=$proys_db["proyecto"];
}

$raiz=$Dir."/Archivos_Planes";
if(!file_exists($raiz."/".$proyecto))
{
	mkdir($raiz."/".$proyecto);
}
$ruta=$raiz."/".$proyecto;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MANAIZ</title>
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
<script language="javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" src="u_yui/container_core.js"></script>
<script language="javascript" src="u_yui/menu.js"></script>
<script type="text/javascript">YAHOO.util.Event.onContentReady("barra_menu", function () {var oMenuBar = new YAHOO.widget.MenuBar("barra_menu", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});YAHOO.util.Event.onContentReady("menu_opciones", function () {var oMenuBar = new YAHOO.widget.MenuBar("menu_opciones", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	function AbrirArchivo(archivo,edicion)
	{
		var num_arch="archivo"+parseInt(Math.random()*1000);
		var ruta="_para_descargas.php?archivo="+archivo+"&noCache="+Math.random();
		DownloadFile(archivo,edicion)
		return false;
	}
	function AbrirCarpeta(carpeta)
	{
		location.href='_explorador.php?raiz=Archivos_Planes/<?php echo $proyecto ;?>/'+carpeta+"&proyecto=<?php echo $proyecto; ?>";
	}
	function CambioVer()
	{
	}
	function Documento(id)
	{
		open('ajax/doctos_part.php?act=display_all&id_docto='+id);
	}
</script>
<link rel="stylesheet" type="text/css" href="u_yui/fonts.css" />
<link rel="stylesheet" type="text/css" href="u_yui/container-core.css" />
<link rel="stylesheet" type="text/css" href="Imagenes/win/styles_win.css" />
<script type="text/javascript" src="u_yui/yahoo-dom-event.js"></script>
<script type="text/javascript" src="u_yui/dragdrop.js"></script>
<script type="text/javascript" src="u_yui/container.js"></script>
<script language="javascript">
	function NvaCap()
	{
		YAHOO.example.container.FormCarpeta.center();
		YAHOO.example.container.FormCarpeta.show();
	}
	function CargaArch()
	{
		YAHOO.example.container.FormArchivo.center();
		YAHOO.example.container.FormArchivo.show();
	}
	function FACancel()
	{
		YAHOO.example.container.FormArchivo.hide();
		return false;
	}
	function FCCancel()
	{
		YAHOO.example.container.FormCarpeta.hide();
		return false;
	}
	function FAOk()
	{
		var x,inputs=$('dataArchivo').getElementsByTagName('input');
		for(x=0;x<inputs.length;x++)
		{
			if(inputs[x].type=='hidden' && inputs[x].name=='url_retorno')
			{
				inputs[x].value=location.href;
			}
		}
		$('dataArchivo').submit();
		YAHOO.example.container.FormArchivo.hide();
		return false;
	}
	function FCOk()
	{
		var x,inputs=$('dataCarpeta').getElementsByTagName('input');
		for(x=0;x<inputs.length;x++)
		{
			if(inputs[x].type=='hidden' && inputs[x].name=='url_retorno')
			{
				inputs[x].value=location.href;
			}
		}
		$('dataCarpeta').submit();
		YAHOO.example.container.FormCarpeta.hide();
		return false;
	}
	function FASubmit()
	{
		YAHOO.example.container.FormArchivo.hide();
		return false;
	}
	function FCSubmit()
	{
		YAHOO.example.container.FormCarpeta.hide();
		return false;
	}
	YAHOO.namespace("example.container");
	YAHOO.util.Event.onDOMReady(function(){
		YAHOO.example.container.FormCarpeta = new YAHOO.widget.Panel("FormCarpeta",{
			width: "250px",
			visible: false,
			constraintoviewport: true,
			draggable: true
		});
		YAHOO.example.container.FormCarpeta.render();
	});
	YAHOO.util.Event.onDOMReady(function(){
		YAHOO.example.container.FormArchivo = new YAHOO.widget.Panel("FormArchivo",{
			width: "300px",
			visible: false,
			constraintoviewport: true,
			draggable: true
		});
		YAHOO.example.container.FormArchivo.render();
	});
</script>
</head>

<body>
<?php

BarraHerramientas();

BH_Ayuda('0.4.','');

if ($handle = @opendir(addslashes($ruta)))
{
	while (($file = readdir($handle)))
	{
		if($file!="." && $file!="..")
		{
			if(is_file($ruta."/".$file)===true)
				$archivos[]=$file;
			else
				$directorios[]=$file;
		}
    }
	closedir($handle);
}

if(@count($directorios)>0)
{
	foreach($directorios as $dir_fis)
	{
		if ($handle = @opendir(addslashes($ruta."/".$dir_fis)))
		{
			$x=0;
			while (($file = readdir($handle)))
			{
				if($file!="." && $file!="..")
				{
					$x++;
					if(is_file($ruta."/".$dir_fis."/".$file)===true)
						$archivo_name=$file;
				}
	    	}
			closedir($handle);
			if($x==1)
			{
				$directorios_archivos[] = array("mostrar" => $archivo_name, "real" => $dir_fis);
			}
			else
			{
				$directorios_fisicos[] = $dir_fis;
			}
		}
	}
}

$longi=strlen($Dir)+1;
$ruta_relativa=substr($ruta,$longi);
?>

<form name="data_control" action="_vista_explo_01.php" method="post">
<table border="0" align="center">
	<tr>
		<td>Plan:</td>
		<td>
			<select name="plan" onchange="javascript: location.href='_vista_explo_01.php?proyecto='+this.value">
				<?php
				if($proys_db=consulta_directa("select id_documento as proyecto, nombre from docto_general where tipo_documento='2' order by nombre"))
				{
					while($proy_db=mysqli_fetch_array($proys_db))
					{
						?>
						<option value="<?php echo $proy_db["proyecto"]; ?>"><?php echo $proy_db["nombre"]; ?></option>
						<?php
					}
				}
				?>
			</select>
			<script language="javascript">
				document.data_control.plan.value="<?php echo $proyecto; ?>";
			</script>
		</td>
		<td>Ver:</td>
		<td>
			<select name="ver" onchange="CambioVer(this.value);">
				<?php menu_items($_SESSION["tipo"], "0.4.3.1.1.1"); ?>
			</select>
			<script language="javascript">
				document.data_control.ver.value="1";
			</script>
		</td>
		<td><input type="button" value="Formatos" class="btn_normal" /></td>
		<td><img height="15" src="Imagenes/iconografia/01.png" onclick="Documento('<?php echo $proyecto; ?>'); " /></td>
		<td><img height="15" src="Imagenes/iconografia/nueva_carp.png" onclick="NvaCap()" title="Nueva Carpeta" alt="Nueva Carpeta" /></td>
		<td><img height="15" src="Imagenes/iconografia/upload.gif" onclick="CargaArch()" title="Cargar Archivo" alt="Cargar Archivo" /></td>
	</tr>
</table>
</form>
<div style="visibility:hidden; font-size:12px;">
<div id="FormCarpeta" class="div_panel">
	<div class="hd">Nueva Carpeta</div>
	<div class="bd">
		<form id="dataCarpeta" action="ajax/archivos.php" method="post" onsubmit="return FCSubmit()">
			<input type="hidden" name="accion" id="accion" value="crea_carpteta" />
			<input type="hidden" name="ruta" value="<?php echo $ruta."/"; ?>" />
			<input type="hidden" name="url_retorno" id="url_retorno" value="" />

			<table align="center">
				<tr><td align="left">Nombre: </td></tr>
				<tr><td align="center"><input type="text" maxlength="250" size="30" name="carpeta" id="carpeta" /></td></tr>
				<tr><td align="right"><input type="button" value="Aceptar" onclick="FCOk()" /> <input type="button" value="Cancelar" onclick="FCCancel()" /></td></tr>
			</table>
		</form>
	</div>
</div>
<div id="FormArchivo" class="div_panel">
	<div class="hd">Cargar Archivo</div>
	<div class="bd">
		<form id="dataArchivo" action="ajax/archivos.php" method="post" onsubmit="return FASubmit()" enctype="multipart/form-data">
			<input type="hidden" name="accion" id="accion" value="carga_archivo" />
			<input type="hidden" name="ruta" value="<?php echo $ruta."/"; ?>" />
			<input type="hidden" name="url_retorno" id="url_retorno" value="" />
			<table align="center">
				<tr><td align="left">Archivo: </td></tr>
				<tr><td align="center"><input type="file" maxlength="250" size="30" name="archivo" id="archivo" /></td></tr>
				<tr><td align="right"><input type="button" value="Aceptar" onclick="FAOk()" /> <input type="button" value="Cancelar" onclick="FACancel()" /></td></tr>
			</table>
		</form>
	</div>
</div>
</div>
<table border="0" align="center">
	<tr>
		<td rowspan="2" align="center" valign="middle">
			<?php
			if(@count($directorios_archivos)>0)
			{
				?>
				<table border="0">
				<?php
				foreach($directorios_archivos as $dir_arch)
				{
					?>
					<tr><td width="145" height="55" background="Imagenes/explo_archivos.jpg" align="center" valign="middle" ondblclick="AbrirArchivo('<?php echo "$ruta_relativa/".$dir_arch["real"]."/".$dir_arch["mostrar"]; ?>','<?php
					$cuantos=@mysqli_fetch_array(consulta_directa("select count(*) as n from archivos where archivo='".basename($dir_arch["mostrar"])."'"));
					if(intval($cuantos["n"])>0) $edicion='false';
					else $edicion='true';
					echo $edicion;
					?>')">
					<?php echo $dir_arch["mostrar"]; ?>
					</td></tr>
					<?php
				}
				?>
				</table>
				<?php
			}
			?>
		</td>
		<td rowspan="2" width="50"></td>
		<td align="center" valign="middle">
		</td>
	</tr>
	<tr>
		<td align="center" valign="middle">
			<?php
			if(@count($directorios_fisicos)>0)
			{
				?>
				<table border="0">
				<?php
				foreach($directorios_fisicos as $dir_fis)
				{
					?>
					<tr><td width="135" height="95" background="Imagenes/explo_carpetas.jpg" align="center" valign="middle" ondblclick="AbrirCarpeta('<?php echo $dir_fis; ?>');">
					<?php echo $dir_fis; ?>
					</td></tr>
					<?php
				}
				?>
				</table>
				<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td align="center" valign="middle" colspan="3">
			<?php
			if(@count($archivos)>0)
			{
				$x=0;
				foreach($archivos as $arch)
				{
					$x++;
					?>
					<div align="left" onmousemove="javascript: this.style.background='999999';" onmouseout="javascript: this.style.background='FFFFFF';" ondblclick="AbrirArchivo('<?php echo "$ruta_relativa/$arch"; ?>','<?php
					$cuantos=@mysqli_fetch_array(consulta_directa("select count(*) as n from archivos where archivo='".basename($arch)."'"));
					if(intval($cuantos["n"])>0) $edicion='false';
					else $edicion='true';
					echo $edicion;
					?>')">
					<?php
					$inf=pathinfo("$ruta/$arch");
					if($inf["extension"]=="txt" || $inf["extension"]=="doc" || $inf["extension"]=="ppt" || $inf["extension"]=="rar" || $inf["extension"]=="tar" || $inf["extension"]=="pps" || $inf["extension"]=="html" || $inf["extension"]=="xls" || $inf["extension"]=="pdf" || $inf["extension"]=="mht" || $inf["extension"]=="zip" || $inf["extension"]=="odp" || $inf["extension"]=="htm" || $inf["extension"]=="odt" || $inf["extension"]=="pub")
					{
						?>
						<img src="Imagenes/extencion/<?php echo $inf["extension"]; ?>.bmp" align="absmiddle" border="0" />
						<?php
					}
					else
					{
						?>
						<img src="Imagenes/archivo.JPG" align="absmiddle" border="0" />
						<?php
					}
					echo $arch;
					?>
					</div>
					<?php
				}
			}
			?>
		</td>
	</tr>
</table>

</body>
</html>
