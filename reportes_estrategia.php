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
<style type="text/css">
	.sin_espacio
	{
		margin:0px;
		padding:0px;
	}
</style>
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	var ruta="<?php echo addslashes(dirname(__FILE__));?>/Archivos_Estrategia";
	var revisar=<?php
	$query="select revisar_documentos from persona where clave = '".$_SESSION["id_persona_usr"]."'";
	$permiso=mysqli_fetch_array(consulta_directa($Con, $query));
	if($permiso["revisar_documentos"]=="si")
		echo "true";
	else
		echo "false";
	?>;
	function LimpiaCbo(id_obj)
	{
		var obj=$(id_obj),x;
		for(x=obj.options.length-1;x>=0;x--)
		{
			obj.remove(x);
		}
	}
	function Doctos()
	{
		obj=new Ajax.Request("util_dir/dirs.php",{
			postBody: "directorio="+ruta+"/"+$F('fecha')+"/",
			onSuccess: function(xhr)
			{
				var dirs=eval("("+xhr.responseText+")"),x,opt;
				LimpiaCbo('doctos');
				for(x=0;x<dirs.length;x++)
				{
					if(dirs[x]!="." && dirs[x]!="..")
					{
						opt=document.createElement("option");
						opt.value=dirs[x];
						opt.innerHTML=dirs[x];
						$('doctos').appendChild(opt);
					}
				}
				Archivos();
			},
			onFailure: function()
			{
				alert("Error en la carga de documentos");
			}
		});
	}
	function Fechas()
	{
		obj=new Ajax.Request("util_dir/dirs.php",{
			postBody: "directorio="+ruta+"&inverso=true",
			onSuccess: function(xhr)
			{
				var dirs=eval("("+xhr.responseText+")"),x,opt;
				LimpiaCbo('fecha');
				for(x=0;x<dirs.length;x++)
				{
					if(dirs[x]!="." && dirs[x]!="..")
					{
						opt=document.createElement("option");
						opt.value=dirs[x];
						opt.innerHTML=dirs[x];
						$('fecha').appendChild(opt);
					}
				}
				Doctos();
			},
			onFailure: function()
			{
				alert("Error en la carga de documentos");
			}
		});
	}
	function Archivos()
	{
		obj=new Ajax.Request("util_dir/archs.php",{
			postBody: "directorio="+ruta+"/"+$F('fecha')+"/"+$F('doctos')+"/",
			onSuccess: function(xhr)
			{
				var dirs=eval("("+xhr.responseText+")"),x,opt;
				LimpiaCbo('referencia');
				var opc_actual=""; opc_1="";
				for(x=0;x<dirs.length;x++)
				{
					if(dirs[x]!="." && dirs[x]!="..")
					{
						if(opc_actual==dirs[x].split("-")[0])
							continue;
						if(opc_1=="")
							opc_1=opc_actual;
						opc_actual=dirs[x].split("-")[0];
						opt=document.createElement("option");
						opt.value=opc_actual;
						opt.innerHTML=opc_actual;
						$('referencia').appendChild(opt);
					}
				}
				MuestraDocto(opc_1);
			},
			onFailure: function()
			{
				alert("Error en la carga de documentos");
			}
		});
	}
	function MuestraDocto(arch)
	{
		obj=new Ajax.Request("util_dir/archs.php",{
			postBody: "pattern="+arch+"&directorio="+ruta+"/"+$F('fecha')+"/"+$F('doctos')+"/",
			onSuccess: function(xhr)
			{
				var dirs=eval("("+xhr.responseText+")"),x,aux;
				var objs=new Array();
				var opc_actual="";
				var estatus="";
				//for(x=$('doc').rows.length-1;x>=0;x--)
				{
					//$('doc').deleteRow(x);
				}
				for(x=0;x<dirs.length;x++)
				{
					if(dirs[x]!="." && dirs[x]!="..")
					{
						//aux=$('doc').rows.length;
						//$('doc').insertRow(aux);
						//$('doc').rows[aux].insertCell(0);
						//$('doc').rows[aux].cells[0].className="sin_espacio";
						if(x==1)
						{
							//$('doc').rows[aux].cells[0].innerHTML='<div id="secc'+x+'" style="width:900px; font-size:11px; height:200px; overflow:auto;"></div>';
						}
						else
						{
							//$('doc').rows[aux].cells[0].innerHTML='<div id="secc'+x+'" style="width:900px; font-size:11px;"></div>';
						}
						archivo=$F('fecha')+"/"+$F('doctos')+"/"+dirs[x];
						//MuestraArch('secc'+x,archivo);
						fAgrega();
						//$('celda_estatus').innerHTML=estatus;
					}
				}
			},
			onFailure: function()
			{
				alert("Error en la carga de documentos");
			}
		});
	}
	function MuestraArch(elDiv,Arch)
	{
		obj = new Ajax.Request("Archivos_Estrategia/"+Arch,{
			onSuccess: function(xhr)
			{
				$(elDiv).innerHTML="<pre>"+xhr.responseText.substr(0,xhr.responseText.length-10)+"</pre>";
			},
			onFailure: function()
			{
				alert("Error al cargar "+Arch);
			}
		})
	}
	function GoDoc(cuantos)
	{
		if($('doctos').selectedIndex+cuantos>=0 && $('doctos').selectedIndex+cuantos<$('doctos').options.length)
		{
			$('doctos').selectedIndex=$('doctos').selectedIndex+cuantos;
			Archivos();
		}
		else
		{
			alert('Ya no existen más Documentos');
		}
	}
	function GoRef(cuantos)
	{
		if($('referencia').selectedIndex+cuantos>=0 && $('referencia').selectedIndex+cuantos < $('referencia').options.length)
		{
			$('referencia').selectedIndex=$('referencia').selectedIndex+cuantos;
			MuestraDocto($('referencia').options[$('referencia').selectedIndex].value);
		}
		else
		{
			alert('Ya no existen más Registros');
		}
	}
	function Revisar()
	{
		obj=new Ajax.Request("util_dir/archs_rev.php",{
			postBody: "pattern="+$F('referencia')+"&directorio="+ruta+"/"+$F('fecha')+"/"+$F('doctos')+"/",
			onSuccess: function(xhr)
			{
				$('celda_estatus').innerHTML='Revisado';
				$('celda_revision').innerHTML="";
			},
			onFailure: function()
			{
				alert("Error en la carga de documentos");
			}
		});
	}
function fAgrega(valor)
{
var inicioo = "Archivos_Estrategia/";
var carpeta = document.getElementById("fecha").value;
var archivos = document.getElementById("doctos").value;
var jpg = document.getElementById("referencia").value;
//var carpeta = document.getElementById("Text1").value = document.getElementById("fecha").value;
//var archivos = document.getElementById("Text2").value = document.getElementById("doctos").value;
//var jpg = document.getElementById("Text3").value = document.getElementById("referencia").value;

//alert(inicioo);
//alert(carpeta);
//alert(archivos);
//alert(jpg);
var compleeto = inicioo+carpeta+"/"+archivos+"/"+jpg
//alert(compleeto);
window.frames["frame1"].location=compleeto+"-1.jpg";
window.frames["frame2"].location=compleeto+"-2.jpg";
window.frames["frame3"].location=compleeto+"-3.jpg";
}
</script>
</head>
<body onload="Fechas();">
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
    <td width="31%"><div align='center'><strong>Reportes Estratégicos</strong></div></td>
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
?>
<?php
BH_Ayuda('','');
?>
<table border="0" align="center">
	<tr>
		<td align="right" width="100">Plan:</td>
		<td align="left" width="75"><select name="fecha" id="fecha" onchange="Doctos();"></select></td>
		<td align="right" width="100">Reporte:</td>
		<td align="left" width="150"><select name="doctos" id="doctos" onchange="Archivos();fAgrega(this.value);"></select></td>
		<td align="left" width="50"><img src="Imagenes/back.png" border="0" onclick="GoDoc(-1)" /> <img src="Imagenes/next.png" border="0" onclick="GoDoc(+1)" /></td>
		<td align="right" width="100"><!--Estatus:--></td>
		<td align="left" width="75" id="celda_estatus"></td>
		<td align="right" width="75" id="celda_revision"></td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td align="right" width="100">Nivel:</td>
		<td align="left" width="150"><select name="referencia" id="referencia" onchange="MuestraDocto(this.value);"></select></td>
		<td align="left" width="50"><img src="Imagenes/back.png" border="0" onclick="GoRef(-1);fAgrega(this.value);" /> <img src="Imagenes/next.png" border="0" onclick="GoRef(+1);fAgrega(this.value);" /></td>
		<td colspan="3"></td>
	</tr>
</table>
  <iframe align="middle" src="" name="frame1" allowtransparency="true" frameborder="0" width="1187" height="70" scrolling="no"></iframe>
  <iframe align="middle" src="" name="frame2" allowtransparency="true" frameborder="0" width="1187" height="600" scrolling="auto"></iframe>
  <iframe align="middle" src="" name="frame3" allowtransparency="true" frameborder="0" width="1187" height="70" scrolling="no"></iframe>
</body>
</html>
<?php
mysqli_close($Con);
?>
