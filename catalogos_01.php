<?php
session_start();
include "apoyo.php"; 
include "lists.php";
$Con=Conectar();
//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA
/*if(!isset($_SESSION["tipo"]))
{
	header("location: index.php?noCache=".rand(0,32000));
	exit();
}*/
if(!isset($_SESSION["id_usr"])) $_SESSION["id_usr"]="0";
if(!isset($_SESSION["tipo"])) $_SESSION["tipo"]="-1";
$sid_usuario=$_SESSION["id_usr"];
$stipo_usuario=$_SESSION["tipo"];
$lista=PostString("lista").Get("lista");
$sublista=PostString("sublista").Get("sublista");
$accion=PostString("accion").Get("accion");
$ql=stripslashes(PostString("ql").Get("ql"));
$qsl=stripslashes(PostString("qsl").Get("qsl"));
$actlist=PostString("actlist").Get("actlist");
$actsublist=PostString("actsublist").Get("actsublist");

$activity=Get("activity");
if($activity!="")
	{
    $total=Get("total_currs");
	$lista=Get("lista");
    $new_name=Get("new_name");
    $list_name=Get("list_name");
	if($activity=="del_single" && $sublista!="")
    {
		for($x=1;$x<=$total;$x++)
    	{
       		$curr=explode(" ",Get("id_curr$x"));
	       	Del_to_list($sublista, $curr[0], $curr[1]);
    	}
	}
	else if($activity=="change_name" && $sublista!="" && $new_name!="")
	{
   		$nom_actual=@mysql_fetch_array(mysql_query("select nombre from lista where lista='$sublista'"));
    	if($nom_actual["nombre"]=='Seleccionados')
       		$generar="Seleccionados";
		else if($nom_actual["nombre"]=="Favoritos")
       		$generar="Favoritos";
	    else
      		$generar="";
		if(!List_exists($new_name,$sid_usuario))
		{
		    if($generar!="")
			{
		   		mysql_query("insert into lista (usuario, fecha, nombre, lista_nivel, estatus, pantalla1, pantalla2, tipo) values ('$sid_usuario', curdate(), '$generar', 'A', 'A', '1', '1', 'CS')");
				$lista_padre=@mysql_fetch_array(mysql_query("select lista from lista_asociada where lista_asociada = (select lista from lista where nombre = 'Seleccionados' and usuario = '$sid_usuario')"));
				$lista_hijo=@mysql_fetch_array(mysql_query("select lista from lista where nombre = '$generar' and fecha = curdate() and usuario = '$sid_usuario'"));
				if($lista_padre["lista"]!="" && $lista_hijo["lista"]!="")
				{
					$posicion=@mysql_fetch_array(mysql_query("select count(*)+1 as n from lista_asociada where lista = '".$lista_padre["lista"]."'"));
					
					mysql_query("insert into lista_asociada(lista, lista_asocidada, posicion) values ('".$lista_padre["lista"]."', '".$lista_hijo["lista"]."', '".$posicion["n"]."')");
				}
				ErrorMySQLAlert();
			}	
   			Change_name_list($sublista, $new_name);
		}
	}
	else if($activity=="clear_list" && $sublista!="")
	{
		Clear_list($sublista);
	}
	else if(($activity=="send_to") && $list_name!="" && $sublista!="")
	{
		$id_list=@mysql_fetch_array(mysql_query("select lista from lista where nombre='$list_name' and usuario = '$sid_usuario'"));
		if($id_list["lista"]=="")
		{
			mysql_query("insert into lista (usuario, nombre, fecha, lista_nivel, estatus, pantalla1, pantalla2, tipo) values ('$sid_usuario','$list_name', curdate(), 'A', 'A', '1', '1', 'CS')");
			$lista_padre=@mysql_fetch_array(mysql_query("select lista from lista_asociada where lista_asociada = (select lista from lista where nombre = 'Seleccionados' and usuario = '$sid_usuario')"));
			$lista_hijo=@mysql_fetch_array(mysql_query("select lista from lista where nombre = '$list_name' and fecha = curdate() and usuario = '$sid_usuario'"));
			if($lista_padre["lista"]!="" && $lista_hijo["lista"]!="")
			{
				$posicion=@mysql_fetch_array(mysql_query("select count(*)+1 as n from lista_asociada where lista = '".$lista_padre["lista"]."'"));	
				mysql_query("insert into lista_asociada(lista, lista_asocidada, posicion) values ('".$lista_padre["lista"]."', '".$lista_hijo["lista"]."', '".$posicion["n"]."')");
			}
			$id_list=$lista_hijo["lista"];
		}
		$id_lista=$id_list["lista"];
		for($x=1;$x<=$total;$x++)
		{
			$curr=explode(" ",Get("id_curr$x"));
			Move_to_list($sublista, $id_lista, $curr[0], $curr[1]);
		}
	}
	else if(($activity=="copy_to") && $list_name!="" && $lista!="")
	{
		$query="select lista from lista where nombre='$list_name' and usuario = '$sid_usuario'";
		$id_list=@mysql_fetch_array(mysql_query($query));
		if($id_list["lista"]=="")
		{
			$query="insert into lista (usuario, nombre, fecha, lista_nivel, estatus, pantalla1, pantalla2, tipo) values ('$sid_usuario','$list_name', curdate(), 'A', 'A', '1', '1', 'CS')";
			mysql_query($query);
			$lista_padre=@mysql_fetch_array(mysql_query("select lista from lista_asociada where lista_asociada = (select lista from lista where nombre = 'Seleccionados' and usuario = '$sid_usuario')"));
			$lista_hijo=@mysql_fetch_array(mysql_query("select lista from lista where nombre = '$list_name' and fecha = curdate() and usuario = '$sid_usuario'"));
			if($lista_padre["lista"]!="" && $lista_hijo["lista"]!="")
			{
				$posicion=@mysql_fetch_array(mysql_query("select count(*)+1 as n from lista_asociada where lista = '".$lista_padre["lista"]."'"));				
				mysql_query("insert into lista_asociada(lista, lista_asocidada, posicion) values ('".$lista_padre["lista"]."', '".$lista_hijo["lista"]."', '".$posicion["n"]."')");
			}
			$id_list=$lista_hijo["lista"];
		}
		$id_lista=$id_list["lista"];
		for($x=1;$x<=$total;$x++)
		{
			$curr=explode(" ", Get("id_curr$x"));
			Copy_to_list($lista, $id_lista, $curr[0], $curr[1]);
		}
	}
	else if(($activity=="save_as") && $list_name!="")
	{
		$query="select lista from lista where nombre='$list_name' and usuario = '$sid_usuario'";
		$id_list=@mysql_fetch_array(mysql_query($query));
		if($id_list["lista"]=="")
		{
			mysql_query("insert into lista (usuario, nombre, fecha, lista_nivel, estatus, pantalla1, pantalla2, tipo) values ('$sid_usuario','$list_name', curdate(), 'A', 'A', '1', '1', 'CS')");
			$query="select lista from lista_asociada where lista_asociada = (select lista from lista where nombre = 'Seleccionados' and usuario = '$sid_usuario')";
			$lista_padre=@mysql_fetch_array(mysql_query($query));
			$query="select lista from lista where nombre = '$list_name' and fecha = curdate() and usuario = '$sid_usuario'";
			$lista_hijo=@mysql_fetch_array(mysql_query($query));
			if($lista_padre["lista"]!="" && $lista_hijo["lista"]!="")
			{
				$query="select count(*)+1 as n from lista_asociada where lista = '".$lista_padre["lista"]."'";
				$posicion=@mysql_fetch_array(mysql_query($query));
				$query="insert into lista_asociada(lista, lista_asociada, posicion) values ('".$lista_padre["lista"]."', '".$lista_hijo["lista"]."', '".$posicion["n"]."')";
				mysql_query($query);
			}
			$id_lista=$lista_hijo["lista"];
		}
		else
			$id_lista=$id_list["lista"];
		for($x=1;$x<=$total;$x++)
		{
			$curr=explode(" ",Get("id_curr$x"));
			Add_to_list($id_lista, $curr[0], $curr[1]);
		}
	}
	else if(($activity=="delete_list") && $sublista!="")
	{
		$duenio=@mysql_fetch_array(mysql_query("select usuario, nombre from lista where lista = '$sublista'"));
		if($_SESSION["id_usr"]==$duenio["usuario"] && $duenio["nombre"] != "Seleccionados" && $duenio["nombre"] != "Favoritos")
			Del_list($sublista);
	}
	$ruta = str_replace("activity","activity_1",$_SERVER['QUERY_STRING']);
	$ruta = str_replace("total_currs","total_currs_1",$ruta);
	$ruta = str_replace("new_name","new_name_1",$ruta);
	$ruta = str_replace("list_name","list_name_1",$ruta);
	$ruta = str_replace("id_curr","id_curr_1",$ruta);
	$variables=explode("&",$_SERVER['QUERY_STRING']);
	$ruta="cont=1";
	for($x=0; $x<@count($variables); $x++)
	{
		$pos=strpos($variables[$x],"activity");
		if($pos===false)
		{
			$pos=strpos($variables[$x],"total_currs");
			if($pos===false)
			{
				$pos=strpos($variables[$x],"new_name");
				if($pos===false)
				{
					$pos=strpos($variables[$x],"list_name");
					if($pos===false)
					{
						$pos=strpos($variables[$x],"id_curr");
						if($pos===false)
						{
							$pos=strpos($variables[$x],"cont");
							if($pos===false)
							{
								$ruta.="&".$variables[$x];
							}
						}
					}
				}
			}
		}
	}
	header("location: catalogos_01.php?$ruta");	
}

$pantalla=PostString("pantalla").Get("pantalla");
if($pantalla=="") $pantalla = "Códigos";

$query_list="";
$query_sublist="";

if($accion=="")
{
	if($lista=="" && $sublista=="")
	{
		$query_list="select lista, nombre from lista where lista_nivel='C' and (( lista.usuario = '".$_SESSION["id_usr"]."' or lista.lista in ( select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."' ) or lista.tipo = 'CP') ) and lista not in ( select distinct(lista_asociada) from lista_asociada ) order by posicion, lista.lista";
	}
	else if($lista=="1" && $sublista=="")
	{
		$query_list="select lista, nombre from lista where lista_nivel='C' and (( lista.usuario = '".$_SESSION["id_usr"]."' or lista.lista in ( select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."' ) or lista.tipo = 'CP') ) and lista not in ( select distinct(lista_asociada) from lista_asociada ) order by posicion, lista.lista";
	}
}
else if($accion=="mov_list")
{	
	$hijos=@mysql_fetch_array(mysql_query("select count(*) as n from lista_asociada where lista='$lista' and lista_asociada in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."')"));
	if(intval($hijos["n"])>0 && $lista!="")
	{
		$query_list=$ql;	
	}
	else if(intval($hijos["n"])==0 && $lista!="")
	{
		$papa=@mysql_fetch_array(mysql_query("select lista from lista_asociada where lista_asociada = '$lista' and lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."')"));
		if($papa["lista"]!="")
		{
			$sublista=$lista;
			$lista=$papa["lista"];
			$abuelo=@mysql_fetch_array(mysql_query("select lista from lista_asociada where lista_asociada = '$lista' and lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."')"));
			if($abuelo["lista"]!="")
			{
				$query_list="select lista, nombre from lista where lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."') and lista in ( select distinct(lista_asociada) from lista_asociada where lista = '".$abuelo["lista"]."' ) order by posicion, lista.lista";
			}
			else
			{
				$query_list="select lista, nombre from lista where lista_nivel='C' and ( lista.usuario = '".$_SESSION["id_usr"]."' or lista.lista in ( select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."' ) or lista.tipo = 'CP' ) and lista not in ( select distinct(lista_asociada) from lista_asociada ) order by posicion, lista.lista";
			}			
		}
		else
		{
			$sublista="";
			$query_list="select lista, nombre from lista where lista_nivel='C' and ( lista.usuario = '".$_SESSION["id_usr"]."' or lista.lista in ( select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."' ) or lista.tipo = 'CP' ) and lista not in ( select distinct(lista_asociada) from lista_asociada ) order by posicion, lista.lista";
		}		
	}
	else if($lista=="")
	{		
		$papa=@mysql_fetch_array(mysql_query("select lista from lista_asociada where lista_asociada = '$actlist' and lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."')"));
		if($papa["lista"]!="")
		{
			$sublista=$lista;
			$lista=$papa["lista"];
			$abuelo=@mysql_fetch_array(mysql_query("select lista from lista_asociada where lista_asociada = '$lista' and lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."')"));
			if($abuelo["lista"]!="")
			{
				$query_list="select lista, nombre from lista where lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."') and lista in ( select distinct(lista_asociada) from lista_asociada where lista = '".$abuelo["lista"]."' ) order by posicion, lista.lista";
			}
			else
			{
				$query_list="select lista, nombre from lista where lista_nivel='C' and ( lista.usuario = '".$_SESSION["id_usr"]."' or lista.lista in ( select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."' ) or lista.tipo = 'CP' ) and lista not in ( select distinct(lista_asociada) from lista_asociada ) order by posicion, lista.lista";
			}			
		}
		else
		{
			$sublista="";
			$query_list="select lista, nombre from lista where lista_nivel='C' and ( lista.usuario = '".$_SESSION["id_usr"]."' or lista.lista in ( select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."' ) or lista.tipo = 'CP' ) and lista not in ( select distinct(lista_asociada) from lista_asociada ) order by posicion, lista.lista";
		}		
	}
}
else if($accion=="mov_sublist")
{
	if($sublista!="")
	{
		$hijos=@mysql_fetch_array(mysql_query("select count(*) as n from lista_asociada where lista='$sublista' and lista_asociada in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."')"));
		if(intval($hijos["n"])>0 && $sublista!="")
		{
			$lista=$sublista;
			$query_list=$qsl;
			$sublista="";
		}
		else if(intval($hijos["n"])==0 && $sublista!="")
		{
			$query_list=$ql;
			$query_sublist=$qsl;
		}
	}
	else
	{
		$papa=@mysql_fetch_array(mysql_query("select lista from lista_asociada where lista_asociada = '$lista' and lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."')"));
		if($papa["lista"]!="")
		{
			$sublista=$lista;
			$lista=$papa["lista"];
			$abuelo=@mysql_fetch_array(mysql_query("select lista from lista_asociada where lista_asociada = '$lista' and lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."')"));
			if($abuelo["lista"]!="")
			{
				$query_list="select lista, nombre from lista where lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."') and lista in ( select distinct(lista_asociada) from lista_asociada where lista = '".$abuelo["lista"]."' ) order by posicion, lista.lista";
			}
			else
			{
				$query_list="select lista, nombre from lista where lista_nivel='C' and ( lista.usuario = '".$_SESSION["id_usr"]."' or lista.lista in ( select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."' ) or lista.tipo = 'CP' ) and lista not in ( select distinct(lista_asociada) from lista_asociada ) order by posicion, lista.lista";
			}			
		}
		else
		{
			$sublista="";
			$query_list="select lista, nombre from lista where lista_nivel='C' and ( lista.usuario = '".$_SESSION["id_usr"]."' or lista.lista in ( select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."' ) or lista.tipo = 'CP' ) and lista not in ( select distinct(lista_asociada) from lista_asociada ) order by posicion, lista.lista";
		}		
	}
}

//echo "<strong>$accion ($lista - $sublista):</strong><br />$query_list";


if($lista!="")
{
	$query_sublist="select lista, nombre from lista where lista in ( select lista_asociada from lista_asociada where lista = '$lista' ) and lista in (select lista from lista where usuario = '".$_SESSION["id_usr"]."' or tipo = 'CP' union select lista from lista_usuario where usuario = '".$_SESSION["id_usr"]."') order by posicion, nombre, lista";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita</title>
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
<link rel="stylesheet" href="libreria/layout.css" />
<script language="javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" src="u_yui/container_core.js"></script>
<script language="javascript" src="u_yui/menu.js"></script>
<script type="text/javascript">YAHOO.util.Event.onContentReady("barra_menu", function () {var oMenuBar = new YAHOO.widget.MenuBar("barra_menu", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});YAHOO.util.Event.onContentReady("menu_opciones", function () {var oMenuBar = new YAHOO.widget.MenuBar("menu_opciones", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	function SubeNivel()
	{
		$('sublista').value="";
		document.data.accion.value='mov_sublist';
		document.data.submit();
	}
	function CambiaPantalla(pantalla_actual)
	{
		var query_array=location.search.split("&");
		var x=0, variable_name, cambios;
		while(x<query_array.length)
		{
			variable_name = query_array[x].substring(0,"pantalla".length);
			if(variable_name=="pantalla")
			{
				cambios = query_array[x].split("=");
				cambios[1] = ((pantalla_actual=="Códigos")?("Descripción"):("Códigos"));
				query_array[x]="pantalla="+cambios[1];
			}
			x++;
		}
		location.href = query_array.join("&");
	}
	function QuitarMenu()
	{
		<?php
		if ($_SESSION["id_usr"]!="0")
		{
		?>
		var accion=$('action_lst');
		accion.innerHTML="";
		var opc=document.createElement('option');
		opc.value=''; 
		opc.appendChild(document.createTextNode('')); 
		accion.appendChild(opc);
		<?
		$propiedad=mysql_fetch_array(mysql_query("select count(*) as n from lista where lista='$sublista' and usuario = '".$_SESSION["id_usr"]."'"));
		if($_SESSION["id_usr"]=="0" || intval($propiedad["n"])==0)
		{
			?>
			var opt = document.createElement('optgroup');
			opt.label='Producto';
			opc=document.createElement('option'); 
			opc.value='6'; 
			opc.appendChild(document.createTextNode('Ver Detalle')); 
			opt.appendChild(opc);
			opc=document.createElement('option'); 
			opc.value='7'; 
			opc.appendChild(document.createTextNode("Copiar")); 
			opt.appendChild(opc);
			accion.appendChild(opt);
			var opt = document.createElement('optgroup');
			opt.label='Lista';
			opc=document.createElement('option'); 
			opc.value='5'; 
			opc.appendChild(document.createTextNode('Crear')); 
			opt.appendChild(opc);
			accion.appendChild(opt);
			<?php
		}
		else if($_SESSION["id_usr"]!="0" && intval($propiedad["n"])>0)
		{
			?>
			var opt = document.createElement('optgroup');
			opt.label='Producto';
			opc=document.createElement('option'); 
			opc.value='6'; 
			opc.appendChild(document.createTextNode('Ver Detalle')); 
			opt.appendChild(opc);
			opc=document.createElement('option'); 
			opc.value='4'; 
			opc.appendChild(document.createTextNode("Enviar")); 
			opt.appendChild(opc);
			opc=document.createElement('option'); 
			opc.value='7'; 
			opc.appendChild(document.createTextNode("Copiar")); 
			opt.appendChild(opc);
			opc=document.createElement('option'); 
			opc.value='1'; 
			opc.appendChild(document.createTextNode("Eliminar")); 
			opt.appendChild(opc);
			accion.appendChild(opt);
			var opt = document.createElement('optgroup');
			opt.label='Lista';
			opc=document.createElement('option'); 
			opc.value='5'; 
			opc.appendChild(document.createTextNode('Crear')); 
			opt.appendChild(opc);
			opc=document.createElement('option'); 
			opc.value='2'; 
			opc.appendChild(document.createTextNode("Renombrar")); 
			opt.appendChild(opc);
			opc=document.createElement('option'); 
			opc.value='3'; 
			opc.appendChild(document.createTextNode("Limpiar")); 
			opt.appendChild(opc);
			opc=document.createElement('option'); 
			opc.value='8'; 
			opc.appendChild(document.createTextNode("Borrar")); 
			opt.appendChild(opc);
			accion.appendChild(opt);
			<?
		}
		}
		?>
		return true;
	}
	function Inicializar()
	{
		if(Navegador() == "IE")
		{
			$('div_copiar').style.visibility="hidden";
			$('div_enviar').style.visibility="hidden";
			$('div_renombrar').style.visibility="hidden";
			$('div_limpiar').style.visibility="hidden";
			$('div_crear').style.visibility="hidden";
			$('div_borrar').style.visibility="hidden";
		}
		else
		{
			$('div_copiar').style.display="none";
			$('div_enviar').style.display="none";
			$('div_renombrar').style.display="none";
			$('div_limpiar').style.display="none";
			$('div_crear').style.display="none";
			$('div_borrar').style.display="none";
		}
	}
	function Ok_copiar()
	{
		var data_report=window.parent.frameList.document;
		var totalregs=parseInt(data_report.getElementById('total_registros').value);
		var nregistro,ckbregistro, contador_regs_usrs=0, contador_regs_currs=0;
		var id_usr_registro, id_curr_registro,strapoyo;
		var query_str_total="total=", query_str_usrs="", query_str_currs="";
		var cadena_vars="&"+location.search.substring(1);
		for(nregistro=1;nregistro<=totalregs;nregistro++)
			{
				ckbregistro=data_report.getElementById('Registro_'+nregistro);
				if(ckbregistro.checked)
				{
					id_usr_registro="";
					id_curr_registro="";
					strapoyo=ckbregistro.value.split(" ");
					id_usr_registro=strapoyo[0];
					contador_regs_usrs++;
					query_str_usrs += "&id_usr"+contador_regs_usrs+"="+ckbregistro.value;
					if(strapoyo.length == 2)
					{
						contador_regs_currs++;
						id_curr_registro = strapoyo[1];
						query_str_currs += "&id_curr"+contador_regs_currs+"="+ckbregistro.value;
					}
				}
			}
		var query_usrs="total_usrs="+contador_regs_usrs+query_str_usrs;
		var query_currs="total_currs="+contador_regs_currs+query_str_currs;
        $('Accion').value="";
		aux=$('copiar_contenido').value;
                                if(! aux || aux=="")
                                	return false;
                        	var new_url=query_currs+"&activity=copy_to&list_name="+aux+"&"+cadena_vars;
                                location.href='catalogos_01.php?'+new_url;
                                return false;
	}
	function Ok_enviar()
	{
		var data_report=window.parent.frameList.document;
		var totalregs=parseInt(data_report.getElementById('total_registros').value);
		var nregistro,ckbregistro, contador_regs_usrs=0, contador_regs_currs=0;
		var id_usr_registro, id_curr_registro,strapoyo;
		var query_str_total="total=", query_str_usrs="", query_str_currs="";
		var cadena_vars="&"+location.search.substring(1);
		for(nregistro=1;nregistro<=totalregs;nregistro++)
			{
				ckbregistro=data_report.getElementById('Registro_'+nregistro);
				if(ckbregistro.checked)
				{
					id_usr_registro="";
					id_curr_registro="";
					strapoyo=ckbregistro.value.split(" ");
					id_usr_registro=strapoyo[0];
					contador_regs_usrs++;
					query_str_usrs += "&id_usr"+contador_regs_usrs+"="+ckbregistro.value;
					if(strapoyo.length == 2)
					{
						contador_regs_currs++;
						id_curr_registro = strapoyo[1];
						query_str_currs += "&id_curr"+contador_regs_currs+"="+ckbregistro.value;
					}
				}
			}
		var query_usrs="total_usrs="+contador_regs_usrs+query_str_usrs;
		var query_currs="total_currs="+contador_regs_currs+query_str_currs;
        $('Accion').value="";
		aux=$('enviar_contenido').value;
                                if(! aux || aux=="")
                                	return false;
                        	var new_url=query_currs+"&activity=send_to&list_name="+aux+"&"+cadena_vars;
                                location.href='catalogos_01.php?'+new_url;
                                return false;
	}
	function Ok_renombrar()
	{
		var data_report=window.parent.frameList.document;
		var totalregs=parseInt(data_report.getElementById('total_registros').value);
		var nregistro,ckbregistro, contador_regs_usrs=0, contador_regs_currs=0;
		var id_usr_registro, id_curr_registro,strapoyo;
		var query_str_total="total=", query_str_usrs="", query_str_currs="";
		var cadena_vars="&"+location.search.substring(1);
		for(nregistro=1;nregistro<=totalregs;nregistro++)
			{
				ckbregistro=data_report.getElementById('Registro_'+nregistro);
				if(ckbregistro.checked)
				{
					id_usr_registro="";
					id_curr_registro="";
					strapoyo=ckbregistro.value.split(" ");
					id_usr_registro=strapoyo[0];
					contador_regs_usrs++;
					query_str_usrs += "&id_usr"+contador_regs_usrs+"="+ckbregistro.value;
					if(strapoyo.length == 2)
					{
						contador_regs_currs++;
						id_curr_registro = strapoyo[1];
						query_str_currs += "&id_curr"+contador_regs_currs+"="+ckbregistro.value;
					}
				}
			}
		var query_usrs="total_usrs="+contador_regs_usrs+query_str_usrs;
		var query_currs="total_currs="+contador_regs_currs+query_str_currs;
        $('Accion').value="";
		aux=$('renombrar_contenido').value;
                                if(! aux || aux=="")
                                	return false;
                        	var new_url="activity=change_name&new_name="+aux+"&"+cadena_vars;
                                location.href='catalogos_01.php?'+new_url;
                                return false;
	}	
	function Ok_limpiar()
	{
		var data_report=window.parent.frameList.document;
		var totalregs=parseInt(data_report.getElementById('total_registros').value);
		var nregistro,ckbregistro, contador_regs_usrs=0, contador_regs_currs=0;
		var id_usr_registro, id_curr_registro,strapoyo;
		var query_str_total="total=", query_str_usrs="", query_str_currs="";
		var cadena_vars="&"+location.search.substring(1);
		for(nregistro=1;nregistro<=totalregs;nregistro++)
			{
				ckbregistro=data_report.getElementById('Registro_'+nregistro);
				if(ckbregistro.checked)
				{
					id_usr_registro="";
					id_curr_registro="";
					strapoyo=ckbregistro.value.split(" ");
					id_usr_registro=strapoyo[0];
					contador_regs_usrs++;
					query_str_usrs += "&id_usr"+contador_regs_usrs+"="+ckbregistro.value;
					if(strapoyo.length == 2)
					{
						contador_regs_currs++;
						id_curr_registro = strapoyo[1];
						query_str_currs += "&id_curr"+contador_regs_currs+"="+ckbregistro.value;
					}
				}
			}
		var query_usrs="total_usrs="+contador_regs_usrs+query_str_usrs;
		var query_currs="total_currs="+contador_regs_currs+query_str_currs;
        $('Accion').value="";
		var new_url="activity=clear_list"+cadena_vars;
        location.href='catalogos_01.php?'+new_url;
        return false;
	}	
	function Ok_crear()
	{
		var data_report=window.parent.frameList.document;
		var totalregs=parseInt(data_report.getElementById('total_registros').value);
		var nregistro,ckbregistro, contador_regs_usrs=0, contador_regs_currs=0;
		var id_usr_registro, id_curr_registro,strapoyo;
		var query_str_total="total=", query_str_usrs="", query_str_currs="";
		var cadena_vars="&"+location.search.substring(1);
		for(nregistro=1;nregistro<=totalregs;nregistro++)
			{
				ckbregistro=data_report.getElementById('Registro_'+nregistro);
				if(ckbregistro.checked)
				{
					id_usr_registro="";
					id_curr_registro="";
					strapoyo=ckbregistro.value.split(" ");
					id_usr_registro=strapoyo[0];
					contador_regs_usrs++;
					query_str_usrs += "&id_usr"+contador_regs_usrs+"="+ckbregistro.value;
					if(strapoyo.length == 2)
					{
						contador_regs_currs++;
						id_curr_registro = strapoyo[1];
						query_str_currs += "&id_curr"+contador_regs_currs+"="+ckbregistro.value;
					}
				}
			}
		var query_usrs="total_usrs="+contador_regs_usrs+query_str_usrs;
		var query_currs="total_currs="+contador_regs_currs+query_str_currs;
        $('Accion').value="";
		aux=$('crear_contenido').value;
                                if(! aux || aux=="")
                                	return false;
                        	var new_url=query_currs+"&activity=save_as&list_name="+aux+"&"+cadena_vars;
                                location.href='catalogos_01.php?'+new_url;
                                return false;
	}	
	function Ok_borrar()
	{
		var data_report=window.parent.frameList.document;
		var totalregs=parseInt(data_report.getElementById('total_registros').value);
		var nregistro,ckbregistro, contador_regs_usrs=0, contador_regs_currs=0;
		var id_usr_registro, id_curr_registro,strapoyo;
		var query_str_total="total=", query_str_usrs="", query_str_currs="";
		var cadena_vars="&"+location.search.substring(1);
		for(nregistro=1;nregistro<=totalregs;nregistro++)
			{
				ckbregistro=data_report.getElementById('Registro_'+nregistro);
				if(ckbregistro.checked)
				{
					id_usr_registro="";
					id_curr_registro="";
					strapoyo=ckbregistro.value.split(" ");
					id_usr_registro=strapoyo[0];
					contador_regs_usrs++;
					query_str_usrs += "&id_usr"+contador_regs_usrs+"="+ckbregistro.value;
					if(strapoyo.length == 2)
					{
						contador_regs_currs++;
						id_curr_registro = strapoyo[1];
						query_str_currs += "&id_curr"+contador_regs_currs+"="+ckbregistro.value;
					}
				}
			}
		var query_usrs="total_usrs="+contador_regs_usrs+query_str_usrs;
		var query_currs="total_currs="+contador_regs_currs+query_str_currs;
        $('Accion').value="";
		var new_url=query_currs+cadena_vars+"&activity=del_single";
        location.href='catalogos_01.php?'+new_url;
        return false;		
	}
	function Ok_borrar_lista()
	{
		var data_report=window.parent.frameList.document;
		var totalregs=parseInt(data_report.getElementById('total_registros').value);
		var nregistro,ckbregistro, contador_regs_usrs=0, contador_regs_currs=0;
		var id_usr_registro, id_curr_registro,strapoyo;
		var query_str_total="total=", query_str_usrs="", query_str_currs="";
		var cadena_vars="&"+location.search.substring(1);
		for(nregistro=1;nregistro<=totalregs;nregistro++)
			{
				ckbregistro=data_report.getElementById('Registro_'+nregistro);
				if(ckbregistro.checked)
				{
					id_usr_registro="";
					id_curr_registro="";
					strapoyo=ckbregistro.value.split(" ");
					id_usr_registro=strapoyo[0];
					contador_regs_usrs++;
					query_str_usrs += "&id_usr"+contador_regs_usrs+"="+ckbregistro.value;
					if(strapoyo.length == 2)
					{
						contador_regs_currs++;
						id_curr_registro = strapoyo[1];
						query_str_currs += "&id_curr"+contador_regs_currs+"="+ckbregistro.value;
					}
				}
			}
		var query_usrs="total_usrs="+contador_regs_usrs+query_str_usrs;
		var query_currs="total_currs="+contador_regs_currs+query_str_currs;
        $('Accion').value="";
		var new_url="activity=delete_list"+cadena_vars;
        location.href='catalogos_01.php?'+new_url;
        return false;
	}	
	function Cancel_Act(accion)
	{
		if(Navegador() == "IE")
		{
			$('div_'+accion).style.visibility="hidden";
		}
		else
		{
			$('div_'+accion).style.display="none";
		}
		$('Accion').value="";
	}
	function Activa_Msg(accion)
	{
		if(Navegador() == "IE")
		{
			$('div_'+accion).style.visibility="visible";
		}
		else
		{
			$('div_'+accion).style.display="block";
		}
	}
	function Accionar()
	{
		var accion=parseInt($('action_lst').value);
		if(accion == 1) Activa_Msg('borrar');
		else if(accion == 2) Activa_Msg('renombrar');
		else if(accion == 3) Activa_Msg('limpiar');
		else if(accion == 4) Activa_Msg('enviar');
		else if(accion == 5) Activa_Msg('crear');
		else if(accion == 6)
		{
			var data_report=window.parent.frameList.document;
			var totalregs=parseInt(data_report.getElementById('total_registros').value);
			var nregistro,ckbregistro, contador_regs_usrs=0, contador_regs_currs=0;
			var id_usr_registro, id_curr_registro,strapoyo;
			var query_str_total="total=", query_str_usrs="", query_str_currs="";
			var cadena_vars=location.search.substring(1);
			for(nregistro=1;nregistro<=totalregs;nregistro++)
			{
				ckbregistro=data_report.getElementById('Registro_'+nregistro);
				if(ckbregistro.checked)
				{
					id_usr_registro="";
					id_curr_registro="";
					strapoyo=ckbregistro.value.split(" ");
					id_usr_registro=strapoyo[0];
					contador_regs_usrs++;
					query_str_usrs += "&id_usr"+contador_regs_usrs+"="+ckbregistro.value;
					if(strapoyo.length == 2)
					{
						contador_regs_currs++;
						id_curr_registro = strapoyo[1];
						query_str_currs += "&id_curr"+contador_regs_currs+"="+ckbregistro.value;
						window.open('detalle_producto.php?id_usr_c='+(ckbregistro.value.replace(" ","_"))+"&noCache="+Math.random(),(ckbregistro.value.replace(" ","_")));
					}
				}
			}
		}
		else if(accion == 7) Activa_Msg('copiar');
		else if(accion == 8) Activa_Msg('borrar_lista');
		$('action_lst').value="";
	}
</script>
<style type="text/css">
	.capa_mensaje
	{
		position:absolute;
		width:300px;
		height:100px;
		top:50%;
		left:50%;
		margin-left:-100px;
		margin-top:-100px;
		background-color:#CCCCCC;
		padding:15px;
		border-style:solid;
		border-width:1px;
		border-color:#000000;
		color:#000000;
		visibility:hidden;
	}
</style>
</head>
<body onload="QuitarMenu();">
<?php
//echo "$query_list<br />$query_sublist";

BarraHerramientas();

BH_Ayuda('0.4','6');
?>
<!--Inicia el cuerpo del html-->
<div class="wrapper">
<div>
<!--Aquí inicia los menus de listas para Catalogo-->
<!--	<table border="0" width="100%"><tr><td align="left">
	<form name="data" method="get" action="catalogos_01.php">
		<input type="hidden" name="accion" value="" />
		<input type="hidden" name="ql" value="<?php echo $query_list; ?>" />
		<input type="hidden" name="qsl" value="<?php echo $query_sublist; ?>" />
		<input type="hidden" name="actlist" value="<?php echo $lista; ?>" />
		<input type="hidden" name="actsublist" value="<?php echo $sublista; ?>" />
		<input type="hidden" name="pantalla" value="<?php echo $pantalla; ?>" />
		Lista:
		<select name="lista" id="lista" onchange="javascript: document.data.accion.value='mov_list'; document.data.submit();">
			<option value=""></option>
			<?php
			if($regs=mysql_query($query_list))
			{
				while($reg=mysql_fetch_array($regs))
				{
					?>
					<option value="<?php echo $reg["lista"]; ?>"><?php echo $reg["nombre"]; ?></option>
					<?php
				}
			}
			?>
		</select>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Sublista:
		<select name="sublista" id="sublista" onchange="javascript: document.data.accion.value='mov_sublist'; document.data.submit();">
			<option value=""></option>
			<?php
			if($regs=mysql_query($query_sublist))
			{
				while($reg=mysql_fetch_array($regs))
				{
					?>
					<option value="<?php echo $reg["lista"]; ?>"><?php echo $reg["nombre"]; ?></option>
					<?php
				}
			}
			?>
		</select>
		<script language="javascript">
			document.data.lista.value="<?php echo $lista; ?>";
			document.data.sublista.value="<?php echo $sublista; ?>";
		</script>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php
			if($pantalla=="Códigos")
			{
				?>
				<input type="button" class="btn_normal" value="Códigos" onclick="CambiaPantalla('Códigos');" />
				<?php
			}
			else
			{
				?>
				<input type="button" class="btn_normal" value="Descripción" onclick="CambiaPantalla('Descripción');" />
				<?php
			}
	if ($_SESSION["id_usr"]!="0")
	{
		?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Acci&oacute;n:
		<select name="action_lst" id="action_lst" onchange="javascript: Accionar();">
          <option value=""></option>
          <?php menu_items($stipo_usuario, "0.4.6"); ?>
        </select>
		<?
	}
	?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<!--<img src="Imagenes/back.png" title="Página Anterior" alt="Página Anterior" onclick="javascript: window.history.back();" />
	<img src="Imagenes/top.png" title="Subir nivel" alt="Subir nivel" onclick="SubeNivel();" /> ->
	</form>
	</td><td align="left">	
	</td></tr></table>-->
</div>
<table align="left" border="0">
  <tr>
    <td align="left" width="600"><?php
			$lst_name=@mysql_fetch_array(mysql_query("select nombre from lista where lista='$lista'"));
			$slst_name=@mysql_fetch_array(mysql_query("select nombre from lista where lista='$sublista'"));
			?>
      <h3 style="color:#777777;"><?php echo $lst_name["nombre"].(($slst_name["nombre"]!="")?(" - ".$slst_name["nombre"]):("")); ?>
        <?php
			if($query_list!="") $ql="&ql=$query_list";
			else $ql="";
			if($query_sublist!="") $qsl="&qsl=$query_sublist";
			else $qsl="";
			if($lista!="") $actlist="&actlist=$lista";
			else $actlist="";
			if($sublista!="") $actsublist="&actsublist=$sublista";
			else if($sublista=="" && $lista=="1") $actsublist="&actsublist=1";
			else $actsublist="";
			if($lista!="") $listaw="&lista=$lista";
			else $listaw="";
			if($sublista!="") $sublistaw="&sublista=$sublista";
			else $sublistaw="";
			if($pantalla!="") $pantallaw="&pantalla=$pantalla";
			else $pantallaw="";
			?>
      </h3>
      <center>
        <iframe frameborder="0" height="400" width="700" id="frameList" name="frameList" marginheight="0" marginwidth="" src="catalogos_01_sublist.php?accion=mov<?php echo $ql.$qsl.$actlist.$actsublist.$listaw.$sublistaw.$pantallaw; ?>"> </iframe>
      </center></td>
  </tr>
</table>
<div class="capa_mensaje" id="div_copiar">
	<table border="0" width="100%">
		<tr>
			<td align="left">Seleccione la lista al cual se copiaran los elementos: </td>
		</tr>
		<tr>
			<td align="center">
				<select name="copiar_contenido" id="copiar_contenido">
					 <?php
						if($bdlists=mysql_query("select lista, nombre from lista where usuario='$sid_usuario' and estatus='A' and (lista_nivel='A') order by posicion, nombre"))
	                    {
							while($bdlist=mysql_fetch_array($bdlists))
    	    			    {
								?>
            	    	      	<option value="<?php echo $bdlist["nombre"]; ?>"><?php echo $bdlist["nombre"]; ?></option>
                			    <?php
        		    	    }
		                }
						?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" value="Aceptar" style="width:75px; height:25px; border-width:1px;" onclick="Ok_copiar();" />
				<input type="button" value="Cancelar" style="width:75px; height:25px; border-width:1px;" onclick="Cancel_Act('copiar');" />
			</td>
		</tr>
  </table>
</div>
<div class="capa_mensaje" id="div_enviar">
	<table border="0" width="100%">
		<tr>
			<td align="left">Seleccione la lista al cual se enviaran los elementos:</td>
		</tr>
		<tr>
			<td align="center">
				<select name="enviar_contenido" id="enviar_contenido">
					<?php
						if($bdlists=mysql_query("select lista, nombre from lista where usuario='$sid_usuario' and estatus='A' and (lista_nivel='A') order by posicion, nombre"))
	                    {
							while($bdlist=mysql_fetch_array($bdlists))
    	    			    {
								?>
            	    	      	<option value="<?php echo $bdlist["nombre"]; ?>"><?php echo $bdlist["nombre"]; ?></option>
                			    <?php
        		    	    }
		                }
						?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" value="Aceptar" style="width:75px; height:25px; border-width:1px;" onclick="Ok_enviar();" />
				<input type="button" value="Cancelar" style="width:75px; height:25px; border-width:1px;" onclick="Cancel_Act('enviar');" />
			</td>
		</tr>
	</table>
</div>
<div class="capa_mensaje" id="div_renombrar">
	<table border="0" width="100%">
		<tr>
			<td align="left">Indique el nuevo nombre de la lista:</td>
		</tr>
		<tr>
			<td align="center">
				<input type="text" maxlength="250" size="35" name="renombrar_contenido" id="renombrar_contenido" />

			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" value="Aceptar" style="width:75px; height:25px; border-width:1px;" onclick="Ok_renombrar();" />
				<input type="button" value="Cancelar" style="width:75px; height:25px; border-width:1px;" onclick="Cancel_Act('renombrar');" />
			</td>
		</tr>
	</table>
</div>
<div class="capa_mensaje" id="div_limpiar">
	<table border="0" width="100%">
		<tr>
			<td align="left">Se borrará el contenido de la lista actual:</td>
		</tr>
		<tr>
			<td align="center">&nbsp;
				
			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" value="Aceptar" style="width:75px; height:25px; border-width:1px;" onclick="Ok_limpiar();" />
				<input type="button" value="Cancelar" style="width:75px; height:25px; border-width:1px;" onclick="Cancel_Act('limpiar');" />
			</td>
		</tr>
	</table>
</div>
<div class="capa_mensaje" id="div_crear">
	<table border="0" width="100%">
		<tr>
			<td align="left">Ingrese el nombre de la lista a crear:</td>
		</tr>
		<tr>
			<td align="center">
				<input type="text" maxlength="250" size="35" name="crear_contenido" id="crear_contenido" />
			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" value="Aceptar" style="width:75px; height:25px; border-width:1px;" onclick="Ok_crear();" />
				<input type="button" value="Cancelar" style="width:75px; height:25px; border-width:1px;" onclick="Cancel_Act('crear');" />
			</td>
		</tr>
	</table>
</div>
<div class="capa_mensaje" id="div_borrar">
	<table border="0" width="100%">
		<tr>
			<td align="left">Se borrarán los elementos seleccionados</td>
		</tr>
		<tr>
			<td align="center">&nbsp;
				
			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" value="Aceptar" style="width:75px; height:25px; border-width:1px;" onclick="Ok_borrar();" />
				<input type="button" value="Cancelar" style="width:75px; height:25px; border-width:1px;" onclick="Cancel_Act('borrar');" />
			</td>
		</tr>
	</table>
</div>
<div class="capa_mensaje" id="div_borrar_lista">
	<table border="0" width="100%">
		<tr>
			<td align="left">Se borrará la lista actual</td>
		</tr>
		<tr>
			<td align="center">&nbsp;
				
			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" value="Aceptar" style="width:75px; height:25px; border-width:1px;" onclick="Ok_borrar_lista();" />
				<input type="button" value="Cancelar" style="width:75px; height:25px; border-width:1px;" onclick="Cancel_Act('borrar_lista');" />
			</td>
		</tr>
	</table>
</div>
<!--Finaliza el cuerpo del html e Inicia el piede página-->
<div class="push"></div>
</div>
<div class="footer" style="background-color:f2f2f2">
          <p><table width="100%" border="0" bgcolor="f2f2f2">
            <tr>
              <td>&nbsp;</td>
            </tr>
            </table></p>
</div>
</body>
</html>
<?php
mysql_close();
?>