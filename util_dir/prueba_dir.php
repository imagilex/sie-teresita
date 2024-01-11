<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>..:: DIRECTORIOS ::..</title>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	var ruta="<?php echo addslashes(dirname(__FILE__));?>/documento";
	function LimpiaCbo(id_obj)
	{
		var obj=$(id_obj),x;
		for(x=obj.childNodes.length-1;x>=0;x--)
		{
			obj.removeChild(obj.childNodes[x]);
		}
	}
	function Doctos()
	{
		obj=new Ajax.Request("dirs.php",{
			postBody: "directorio="+ruta,
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
						$('doctos').appendChild(opt);
						Fechas();
					}
				}
			},
			onFailure: function()
			{
				alert("Error en la carga de documentos");
			}
		});
	}
	function Fechas()
	{
		obj=new Ajax.Request("dirs.php",{
			postBody: "directorio="+ruta+"/"+$F('doctos')+"/",
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
						Archivos();
					}
				}
			},
			onFailure: function()
			{
				alert("Error en la carga de documentos");
			}
		});
	}
	function Archivos()
	{
		obj=new Ajax.Request("archs.php",{
			postBody: "directorio="+ruta+"/"+$F('doctos')+"/"+$F('fecha')+"/",
			onSuccess: function(xhr)
			{
				var dirs=eval("("+xhr.responseText+")"),x,opt;
				LimpiaCbo('referencia');
				for(x=0;x<dirs.length;x++)
				{
					if(dirs[x]!="." && dirs[x]!="..")
					{
						opt=document.createElement("option");
						opt.value=dirs[x];
						opt.innerHTML=dirs[x];
						$('referencia').appendChild(opt);
					}
				}
			},
			onFailure: function()
			{
				alert("Error en la carga de documentos");
			}
		});
	}
</script>
</head>

<body onload="Doctos();">
<table border="0" width="100%">
<tr>
<td align="right">
Documento:
</td>
<td>

</td>
</tr>
<tr>
<td align="right">
Fecha:
</td>
<td>
<select name="fecha" id="fecha" onchange="Archivos();"></select>
</td>
</tr>
<tr>
<td align="right">
Referencia:
</td>
<td>
<select name="referencia" id="referencia"></select>
</td>
</tr>
</table>
</body>
</html>