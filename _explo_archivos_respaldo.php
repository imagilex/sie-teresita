<?php
session_start();

header("Expires: Mon, 22 Sep 1997 09:00:00 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");

include("apoyo.php");
include_once("u_tabla/tabla.php");
include "libreria/funciones_ger.php";

$Con=Conectar();
$proyecto=PostString("proyecto").Get("proyecto");
$ruta=addslashes(PostString("ruta").Get("ruta"));
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                PARTE DE UPLOAD

$accion=PostString("accion");
if($accion!="")
{
	if($accion=="add_files")
	{

		$archivos=PostString("archivo");
		$arch=$archivos;
		if($archivos!="")
		{

			/*foreach($archivos as $arch			{)*/

			//echo "file: ".$arch."<br>";
				//$arch_upl=str_replace("-","_",str_replace(".","_",str_replace(" ","_",$arch)));
				$arch_upl="file";
				//echo "kk ".$arch_upl;
				$arch_upl2=str_replace("-","_",str_replace(".",".",str_replace(" ","_",$arch)));
				//cho "kke ".$arch_upl2;
			//echo "<br>";

			//echo $_FILES[$arch_upl]["name"];
				if(isset($_FILES[$arch_upl]["name"]) && $_FILES[$arch_upl]["name"]!="")
				{
					$datos=@mysqli_fetch_array(consulta_directa($Con, "select ruta,archivo from archivos where archivo='$arch' and usuario='".$_SESSION["id_usr"]."'"));
					$noarchivo=$datos["archivo"];
		//			echo "original= ".  $noarchivo."<br>";
		//			echo "noarchivo= ".  basename( $_FILES[$arch_upl]['name']);
					if ($noarchivo == basename( $_FILES[$arch_upl]['name']))
					{
					$original=str_replace("\\","/",$Dir)."/".$datos["ruta"];

					$Pnewfile = explode("/",$datos["ruta"]);
					$PVdir=$Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/";
		//			echo $original."<br>";
		//			echo $PFnewfile."<br>";
          $fil=$Pnewfile[3];
          $filexp=explode(".",$Pnewfile[3]);
          $Filname=$filexp[0];
      //    echo $Filname;
          if ($handle = opendir($PVdir)) {
      //    echo "Archivos:\n";

          /* This is the correct way to loop over the directory. */
            while (false !== ($file = readdir($handle))) {

        //    echo "$file\n";
            $DirfileNAE= explode(".",$file);
            $DirfileNA= explode("-V",$DirfileNAE[0]);
            $DirfileN =$DirfileNA[0];
            $DirfileE=$DirfileNA[1];
         //   echo $DirfileE;
            if ($DirfileN == $Filname )
            {
            $countersas = $countersas+1;
            }


          }
//echo $countersas;

switch ($countersas) {
case 0:
        //echo "No existen versiones anteriores generando -V1";
        $archivoparacopiarNR= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$Filname."-V1.".$filexp[1];
        break;
    case 1:
        //echo "Existe Versión -V1, Generando -V2";
        //$archivoparacopiarNR= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$DirfileN."-V2.".$DirfileNAE[1];
        $archivoparacopiarNR= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$Filname."-V2.".$filexp[1];
        break;
    case 2:
        //$archivoparacopiarNR= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$DirfileN."-V3.".$DirfileNAE[1];
        $archivoparacopiarNR= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$Filname."-V3.".$filexp[1];
        break;
    case 3:
        $archivo1= $DirfileN."-V1.".$DirfileNAE[1];
        $archivo1= $DirfileN."-V1.".$DirfileNAE[1];
        //echo $archivo1;
        $elimina= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$archivo1;
        $Nelimina= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$archivo1;
        unlink($elimina);
        $archivo2=$Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$DirfileN."-V2.".$DirfileNAE[1];
        $archivo21=$Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$DirfileN."-V2.".$DirfileNAE[1];

       // echo $archivo2 ."<br>";
        //echo $Nelimina ."<br>";
         rename($archivo2,$Nelimina);

        $archivoparacopiarNR= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$DirfileN."-V3.".$DirfileNAE[1];
        $archivoparacopiarN= $DirfileN."-V3.".$DirfileNAE[1];
        //echo $archivo21;
         //echo ($archivoparacopiar ."<br>".$archivo21);
         rename($archivoparacopiarNR,$archivo21);

        //$archivoparacopiar= $DirfileN."-V".$countersas.".".$DirfileNAE[1];


        break;
}

    closedir($handle);
}

				//	$PFnewfile= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/".$Pnewfile[3];
					$PFnewfile= $Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/". $archivoparacopiar;




				//	if (!copy($original, $PFnewfile)) {
					if (!copy($original, $archivoparacopiarNR)) {
          echo "copia fallida $original";
          }
//echo str_replace("\\","/",$Dir)."/".$datos["ruta"];

					if(move_uploaded_file($_FILES[$arch_upl]["tmp_name"],str_replace("\\","/",$Dir)."/".$datos["ruta"]))
					{
					//echo str_replace("\\","/",$Dir)."/".$datos["ruta"];
						consulta_directa($Con, "delete from archivos where archivo='$arch' and usuario='".$_SESSION["id_usr"]."' and ruta='".$datos["ruta"]."'");
					}
				}
				else
				{
				$lolo="<script language=\"javascript\">alert('El archivo que esta publicando no coincide con el nombre o con el tipo de archivo');</script>";
				}
				}
			else
			{echo "no hay";}
		}
		else
		{
		echo "nada";
		}
	}
	else if($accion=="free_files")
	{
		$archivos=PostString("archivo");
		if($archivos!="")
		{
			foreach($archivos as $arch)
			{
				$arch_upl=str_replace("-","_",str_replace(".","_",str_replace(" ","_",$arch)));
				consulta_directa($Con, "delete from archivos where archivo='$arch' and usuario='".$_SESSION["id_usr"]."'");
			}
		}
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MANAIZ</title>

</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="prototype.js"></script>
<!-- GERARDO PRUEBA 1-->
<!-- Dependencies -->
		<script src="jquery.js" type="text/javascript"></script>
		<script src="jquery.ui.draggable.js" type="text/javascript"></script>

		<!-- Core files -->
		<script src="jquery.alerts.js" type="text/javascript"></script>
		<link href="jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita</title>
<link rel="stylesheet" href="libreria/layout_p.css" />
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" src="u_yui/container_core.js"></script>
<script language="javascript" src="u_yui/menu.js"></script>

<script type="text/javascript">YAHOO.util.Event.onContentReady("barra_menu", function () {var oMenuBar = new YAHOO.widget.MenuBar("barra_menu", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});YAHOO.util.Event.onContentReady("menu_opciones", function () {var oMenuBar = new YAHOO.widget.MenuBar("menu_opciones", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="prototype.js"></script>
<link rel="stylesheet" type="text/css" href="u_yui/fonts.css" />
<link rel="stylesheet" type="text/css" href="u_yui/container-core.css" />
<link rel="stylesheet" type="text/css" href="Imagenes/win/styles_win.css" />
<script type="text/javascript" src="u_yui/yahoo-dom-event.js"></script>
<script type="text/javascript" src="u_yui/dragdrop.js"></script>
<script type="text/javascript" src="u_yui/container.js"></script>
<!-- GERARDO PRUEBA 1-->
<script language="javascript">
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// muestra aventana
	function Error_Mail(mensaje,titulo)
	{
	//alert('<?php echo $vista;?>');
		YAHOO.example.container.FormArchivo.hide();
var bleer4=mensaje;

YAHOO.example.container.panel1 = new YAHOO.widget.Panel("panel1", { width:"350px", visible:false, draggable:true, close:true } );
YAHOO.example.container.panel1.setHeader(titulo);
YAHOO.example.container.panel1.setBody(bleer4);
YAHOO.example.container.panel1.render("FormArchivo");
YAHOO.example.container.panel1.center();
YAHOO.example.container.panel1.show();
	return false;
}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//haz
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// borra archivo
function haz(param)
{
//alert(param);
 var param=param;
 var fils,i,archivo;
 var names = "";
 var apborrar="";
//alert(window.parent.location);
 fils=document.getElementsByTagName('input');
 for (var i = 0; i < fils.length; ++i) {
	   if (fils[i].getAttribute("value") != null)
        archivo = fils[i].getAttribute("value");
        narchivo = fils[i].getAttribute("name");
        if (archivo == 'siesta')
        {
        apborrar += narchivo + ",";
        }
    }
    if (apborrar != '')
    {
    C_apborrar=apborrar.split(",");
    nvalor =C_apborrar.length;
    nvalor=nvalor-1;
     for (var i = 0; i < nvalor; ++i) {
     valor= C_apborrar[i];
     if (valor.length==11)
     {
     numero = valor.slice(-2);
     }
     else
     {
     numero = valor.slice(-1);
     }
     var tipo=$('tipo'+numero).value;
     var valor=$('valor'+numero).value;
		if(param=="elimina_carpeta")
		{
		if(tipo=="carpeta")
		{
     var cvacia = $('vacia'+numero).value;
		if(tipo=="carpeta" && cvacia=="vacia")
		{
			alert("Quieres borrar la carpeta: "+valor);
			return false;
		}
		else
		{
		var titulo='Error';
//var mensaje=' <table style="width: 100%" cellspacing="0" cellpadding="0"> <tr> <td> &nbsp;</td> <td> 				&nbsp;</td> </tr> <tr> <td> &nbsp;&nbsp;&nbsp;<img src="http://teresita.com.mx/imagesJQ/important.gif" width="32" height="32" /></td> <td>No puede desbloquear los archivos de otros usuarios</td> </tr> <tr> <td> 				&nbsp;</td> <td>&nbsp;</td> </tr> </table>';
var mensaje='no se puede';
window.parent.Error_Mail(mensaje,titulo);
 return false;
		}
		}
		else
		{
		alert("NO ES CARPETA");
		return false;
		}
		}
		if(tipo=="archivo")
		{
			var edicion=$('edicion'+numero).value;
			var file=$('file'+numero).value;
		alert("el archivo "+valor+" tiene la edicionww " + edicion);
		}



   ///
     }

    //acaba if apborrar
    }

}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// accion

	function EjecutaAccion(accion)
	{
		$('sel_accion').value="";
		var form=$('form_arch');
		var inputs=form.getElementsByTagName('input');
		var checks=new Array();
		var hiddens=new Array();
		var y=0,z=0,num=0;
		for(x=0;x<inputs.length;x++)
		{
			if(inputs[x].type && inputs[x].type=="checkbox")
			{
				checks[y]=inputs[x];
				y++;
			}
			if(inputs[x].type && inputs[x].type=="hidden" && inputs[x].name!="accion")
			{
				hiddens[z]=inputs[x];
				z++;
			}
		}
		var personas="";
		for(x=0;x<checks.length;x++)
		{
		alert(hiddens[x].value);
			if(checks[x].checked && personas.split(',').indexOf(hiddens[x].value)==-1) personas += hiddens[x].value+",";
		}
		if(accion=="1")
		{


			if(personas=="")
			{
				 var mensaje='<center>!Seleccione por lo menos un archivo!</center>';
				 var titulo='Error';
				 Error_Mail(mensaje,titulo);
				return false;


			}
			open("envia_mail.php?to="+personas.substr(0,personas.length-1));
		}
		if(accion==2)
		{
			y=0;
			for(x=0;x<checks.length;x++)
			{
			//cuenta archivos
			var num = num +1 ;
				alert(checks[x].value);
				alert(hiddens[x].value);
				if(checks[x].checked && hiddens[x].value!="<?php echo $_SESSION["id_persona_usr"]; ?>")
				{

					alert("¡Accion incorrecta!\nNo puede desbloquear los archivos de otros usuarios");
					//var bleer4=' <table style="width: 100%" cellspacing="0" cellpadding="0"> <tr> <td> </td> <td>No puede desbloquear los archivos de otros usuarios</td> </tr> </table>';
									var bleer4=' <table style="width: 100%" cellspacing="0" cellpadding="0"> <tr> <td> &nbsp;</td> <td> 				&nbsp;</td> </tr> <tr> <td> &nbsp;&nbsp;&nbsp;<img src="http://teresita.com.mx/imagesJQ/important.gif" width="32" height="32" /></td> <td>No puede desbloquear los archivos de otros usuarios</td> </tr> <tr> <td> 				&nbsp;</td> <td>&nbsp;</td> </tr> </table>';



YAHOO.example.container.panel1 = new YAHOO.widget.Panel("panel1", { width:"350px", visible:false, draggable:true, close:true } );
YAHOO.example.container.panel1.setHeader("¡Acción incorrecta!");
YAHOO.example.container.panel1.setBody(bleer4);
YAHOO.example.container.panel1.render("FormArchivo");
YAHOO.example.container.panel1.center();
YAHOO.example.container.panel1.show();








return false;



				}
				if(checks[x].checked)
				{
					y++;

				}
			}
			alert(num);
			if(y>0)
			{

/*
jConfirm('¿Esta seguro de liberar sus \nbloqueados?', 'Desbloquear', function(r) {

				    if( r ) {

				    $('accion').value='free_files';
					$('form_arch').submit();
					return true;

					}
					});
			*/

				if(confirm("¿Esta seguro?"))
				{
					$('accion').value='free_files';
					$('form_arch').submit();
					return true;
				}

				var mensaje='<table style="width: 100%"> <tr> <td colspan="2"><center>&nbsp;</center></td> </tr> <tr> <td colspan="2"><center>¿Esta seguro de liberar sus bloqueados?</center></td> </tr> <tr> <td colspan="2"><center>&nbsp;</center></td> </tr> <tr> <td colspan="2"><center> </center></td> </tr><tr> <td colspan="2"><center> </center></td> </tr><tr> <td align="center"> <input name="Aceptar" onclick="javascript: desbloquea_ach();" type="button" value="Aceptar" /> </td> <td align="center"> <input onclick="javascript: FACancel();" name="Cancelar" type="button" value="Cancelar" /></td> </tr> <tr> <td colspan="2"><center>&nbsp;</center></td> </tr> </table>';
				var titulo='Desbloquear';
          Error_Mail(mensaje,titulo);
				return false;




			}
			else
			{
				//alert("No hay datos seleccionados");
				var bleer4=' <table style="width: 100%" cellspacing="0" cellpadding="0"> <tr> <td> &nbsp;</td> <td> 				&nbsp;</td> </tr> <tr> <td> &nbsp;&nbsp;&nbsp;<img src="http://teresita.com.mx/imagesJQ/important.gif" width="32" height="32" /></td> <td>No hay datos seleccionados</td> </tr> <tr> <td> 				&nbsp;</td> <td>&nbsp;</td> </tr> </table>';




YAHOO.example.container.panel1 = new YAHOO.widget.Panel("panel1", { width:"350px", visible:false, draggable:true, close:true } );
YAHOO.example.container.panel1.setHeader("¿Acción incorrecta!");
YAHOO.example.container.panel1.setBody(bleer4);
YAHOO.example.container.panel1.render("FormArchivo");
YAHOO.example.container.panel1.center();
YAHOO.example.container.panel1.show();
				return false;
			}
		}

	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function Abrir(numero)
	{
		var tipo=$('tipo'+numero).value;
		var valor=$('valor'+numero).value;

		if(tipo=="carpeta")
		{
			location.href='_explo_archivos.php?ruta='+valor+'&proyecto=<?php echo $proyecto; ?>';
		}
		else if(tipo=="archivo")
		{
			var edicion=$('edicion'+numero).value;
			var file=$('file'+numero).value;


			DownloadFile(valor,edicion,file)
		return false;
		/*
			var proyecto=parseInt('<?php echo $proyecto; ?>');
			if(isNaN(proyecto)) proyecto=0;
			var longitud="<?php echo $Dir; ?>".lenght;
			valor=valor.substring(longitud+1);
			var arch_open='_para_descargas.php?archivo='+valor;
			archivo="archivo"+parseInt((Math.random()*1000));
			window.open(arch_open,archivo);
			*/


		}
	}






	function details(directorio,nproy)
	{
/*
	var Arr_Archivo = directorio.split("/");

	dir=directorio;
	var bleer2= '<iframe  frameborder="0" ID="Frame1" style="height: 200px; width: 340px" SRC="Ldir.php?dir='+dir+'">';
	var bleer3='</iframe>';
	var bleer4 =bleer2 + bleer3;

YAHOO.example.container.panel1 = new YAHOO.widget.Panel("panel1", { width:"350px", visible:false, draggable:true, close:true } );
YAHOO.example.container.panel1.setHeader(nproy+','+Arr_Archivo[7]);
YAHOO.example.container.panel1.setBody(bleer4);
YAHOO.example.container.panel1.render("FormCarpeta");
		YAHOO.example.container.panel1.center();
		YAHOO.example.container.panel1.show();
		*/
		window.parent.details2(directorio,nproy);
//        return false;

	}



function CargaArch(archivo,file)
{
	files=file;

	var URLI = archivo;
	var temp = new Array();
temp = URLI.split('/');
upo=temp[3];
//alert(upo);
file='file:///C://WT//'+temp[3];
	//alert (file);




//	var bleer= '<table style="width: 100%" cellspacing="2" cellpadding="4"><tr><td><input type="button" value="   Leer   " onClick="window.open(\''+URLI+'\');"/>';
	var bleer= '<table style="width: 100%" cellspacing="2" cellpadding="4"><tr>';
	//var bleer2= '<td><input type="button" value="Modificar" onClick="window.open(\''+URLI+'\');"/>';
	//file:///C:/WT/
	var bleer2= '</td><td><input type="button" value=" Modificar" onClick="window.open(\'PTabrelocal.php?file='+file+'&archivo='+archivo+'\&user=<?php echo $_SESSION["id_usr"];?>\');parent.ActivaVFile(\''+upo+'\');"/>';
	//var bleer3= '</td><td><input type="button" value=" Publicar " onClick="parent.CargaArch();"></td></tr></table>';
	var bleer3= '</td><td><input type="button" value=" Publicar " onClick="parent.ActivaVFile(\''+temp[3]+'\');"></td></tr></table>';
	var bleer4 = bleer + bleer2 + bleer3

		        parent.YAHOO.example.container.FormCarpeta.setHeader("Acci&oacute;n");
				parent.YAHOO.example.container.FormCarpeta.setBody(bleer4);
		parent.YAHOO.example.container.FormCarpeta.center();

		parent.YAHOO.example.container.FormCarpeta.show();
	}


	function CargaArchger(archivo)
	{

	//var bleer= '<img src="http://localhost/Teresita/Imagenes/iconografia/UserIcon.gif" onClick="window.open(\'http://google.com.mx\');"/>';
	var URLI = archivo;

	var bleer= '<center><input type="button" value="Leer" onClick="window.open(\''+URLI+'\');"/></center>';
parent.YAHOO.example.container.FormCarpeta.setHeader("Acci&oacute;n");
		parent.YAHOO.example.container.FormCarpeta.setBody(bleer);

		parent.YAHOO.example.container.FormCarpeta.center();

		parent.YAHOO.example.container.FormCarpeta.show();
	}


	function CargaArchgerF(archivo,file)
{
	files=file;

	var URLI = archivo;
	var temp = new Array();
temp = URLI.split('/');
file='file:///C://WT//'+temp[3];
	//alert (file);




	var bleer= '<table style="width: 100%" cellspacing="2" cellpadding="4"><tr><td><input type="button" value="   Leer   " onClick="window.open(\''+URLI+'\');"/>';
	//var bleer= '<table style="width: 100%" cellspacing="2" cellpadding="4"><tr>';
	//var bleer2= '<td><input type="button" value="Modificar" onClick="window.open(\''+URLI+'\');"/>';
	//file:///C:/WT/
	var bleer2= '</td><td><input type="button" value=" Modificar" onClick="window.open(\'PTabrelocal.php?file='+file+'&archivo='+archivo+'\&user=<?php echo $_SESSION["id_usr"];?>\');"/>';
	var bleer3= '</td></tr></table>';
	//var bleer3= '</td><td><input type="button" value=" Publicar " onClick="parent.CargaArch();"></td></tr></table>';
	var bleer4 = bleer + bleer2 + bleer3

		        parent.YAHOO.example.container.FormCarpeta.setHeader("Acci&oacute;n");
				parent.YAHOO.example.container.FormCarpeta.setBody(bleer4);
		parent.YAHOO.example.container.FormCarpeta.center();

		parent.YAHOO.example.container.FormCarpeta.show();
	}

























function getElementsByValue(value, tag, node) {
	var values = new Array();
	if (tag == null)
		tag = "*";
	if (node == null)
		node = document;
	var search = node.getElementsByTagName(tag);
	var pat = new RegExp(value, "i");
	for (var i=0; i<search.length; i++) {
		if (pat.test(search[i].value))
	if (search[i].checked)
	{
		if(search[i].value==value)
	{
	search[i].checked=false;
	}


	}
	else
	{
	if(search[i].value==value)
	{
	search[i].checked='true'
	}


	}
	values.push(search[i]);
	}
	}


function mouseovert(obj) {

	objeto=document.getElementById(obj);

	objetoa=document.getElementById('a'+obj);
	objetob=document.getElementById('b'+obj);
	objetoc=document.getElementById('c'+obj);
	//objetod=document.getElementById('d'+obj);
	objetoe=document.getElementById('e'+obj);
	objetof=document.getElementById('f'+obj);
	objetog=document.getElementById('g'+obj);
	objetoh=document.getElementById('h'+obj);
	objetoi=document.getElementById('i'+obj);
	select=objeto.className;
//	alert(select);
	if (select == '')
	{
		objeto.className='mover';
		objetoa.className='mover';
		objetob.className='mover';
		objetoc.className='mover';
		//objetod.className='mover';
		objetoe.className='mover';
		objetof.className='mover';
		objetog.className='mover';
		objetoh.className='mover';
		objetoi.className='mover';
	}
	else
	{
	if (select=='select')
	{
		objeto.className='select';
		objetoa.className='select';
		objetob.className='select';
		objetoc.className='select';
		//objetod.className='select';
		objetoe.className='select';
		objetof.className='select';
		objetog.className='select';
		objetoh.className='select';
		objetoi.className='select';
	}
}
}
function mouseout(obj) {
	//alert(obj);
	objeto=document.getElementById(obj);
	objetoa=document.getElementById('a'+obj);
	objetob=document.getElementById('b'+obj);
	objetoc=document.getElementById('c'+obj);
//	objetod=document.getElementById('d'+obj);
	objetoe=document.getElementById('e'+obj);
	objetof=document.getElementById('f'+obj);
	objetog=document.getElementById('g'+obj);
	objetoh=document.getElementById('h'+obj);
	objetoi=document.getElementById('i'+obj);
	select=objeto.className;
	//alert(select);
	if (select == 'select')
	{
		objeto.className='select';
	}
	else
	{
		objeto.className='';
		objetoa.className='';
		objetob.className='';
		objetoc.className='';
//		objetod.className='';
		objetoe.className='';
		objetof.className='';
		objetog.className='';
		objetoh.className='';
		objetoi.className='';
	}

}
function highlight(obj) {
var seleccionado=$('selection'+obj);
var valor=$('file'+obj);
var tipo=$('tipo'+obj);

//alert(seleccionado.value);
	objeto=document.getElementById(obj);
	objetoa=document.getElementById('a'+obj);
	objetob=document.getElementById('b'+obj);
	objetoc=document.getElementById('c'+obj);
//	objetod=document.getElementById('d'+obj);
	objetoe=document.getElementById('e'+obj);
	objetof=document.getElementById('f'+obj);
	objetog=document.getElementById('g'+obj);
	objetoh=document.getElementById('h'+obj);
	objetoi=document.getElementById('i'+obj);
	select=objeto.className;
//	alert(select);
	if (select == ''||select=='mover')
	{
		seleccionado.value="siesta";



		if(tipo.value=="carpeta")
		{

		}
		else
		{
		if (tipo.value=="archivo")
		{
		getElementsByValue(valor.value);
		}
		}




		objeto.className='select';
		objetoa.className='select';
		objetob.className='select';
		objetoc.className='select';
//		objetod.className='select';
		objetoe.className='select';
		objetof.className='select';
		objetog.className='select';
		objetoh.className='select';
		objetoi.className='select';
	}
	else
	{
	if (select=='select')
	{
			seleccionado.value="noesta";
if(tipo.value=="carpeta")
		{

		}
		else
		{
		if (tipo.value=="archivo")
		{
		getElementsByValue(valor.value);
		}
		}
		objeto.className='';
		objetoa.className='';
		objetob.className='';
		objetoc.className='';
//		objetod.className='';
		objetoe.className='';
		objetof.className='';
		objetog.className='';
		objetoh.className='';
		objetoi.className='';
	}
}
}

function getElementsById(id)
{
   var divs = document.getElementsByTagName("div");
   var arr = new Array();

   for (var i=0; i < divs.length; i++)
   {
       if (divs[i].id == id)
          {
            arr[arr.length] = divs[i];
          }
   }
   return arr;
}
	function ActivaFile(obj)
	{

	getElementsByValue(obj);
	//alert(obj);
		if($(obj).disabled)
		{ $(obj).disabled=false;


		}
		else
		{
		$(obj).disabled = true;
		}

	}


		YAHOO.namespace("example.container");
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


<?php
$enc=array(
		"",
		addslashes("<!--<div align='left' style='width:70'>Usuario<br />-s->".'<input type="hidden" size="10" name="txt_usuario[]" onclick="Foco(this)" value="'.$txt_usuario.'"<!-- /></div>-->'),
		addslashes("<div align='center' style='width:25; vertical-align:top;'>Bloqueado por</div>"),
		addslashes("<div align='center' style='width:25; vertical-align:top;'></div>"),
		addslashes("<div align='left' style='width:600px;  vertical-align:top;'>    Nombre</div>"),

		"D&iacute;as Bloqueado",
		"",
		addslashes("<div align='center' style='width:25; vertical-align:top;'>Versiones</div>"),
		addslashes("<div align='center' style='width:25; vertical-align:top;'></div>")
	);
$cue=array();
?>
<style type="text/css">
.select {
				background-color: #ccccff;
				}

			.mover {
				background-color: #F2F2F2;
				}

				.encabezadodetalle {
				font-size: 9pt;
				font-family: Arial, Helvetica, sans-serif;
				margin-left: 3px;
}

</style>

</head>

<body>

<?php

if ($handle = @opendir(addslashes($ruta)))
{
	while (($file = readdir($handle)))
	{
		if($file!=".." && $file!="." && !is_file($file))
		{
			$archivos_fechas[]=$file;
		}
    }
	closedir($handle);
}
if(@count($archivos_fechas)>0)
{
	$x=0;
	foreach($archivos_fechas as $direct)
	{
		$x++;
		$contador=$x;


		$inicio1='<div id="a'.$x.'" ondblclick="Abrir('.$x.')" onclick="highlight('.$contador.');'.$activacion.'" onmousemove="javascript:mouseovert('.$contador.');" onmouseout="javascript:mouseout('.$contador.');" >';
		$inicio2='<div id="b'.$x.'" ondblclick="Abrir('.$x.')" onclick="highlight('.$contador.');'.$activacion.'" onmousemove="javascript:mouseovert('.$contador.');" onmouseout="javascript:mouseout('.$contador.');" >';
		$inicio3='<div id="c'.$x.'" ondblclick="Abrir('.$x.')" onclick="highlight('.$contador.');'.$activacion.'" onmousemove="javascript:mouseovert('.$contador.');" onmouseout="javascript:mouseout('.$contador.');" >';
		$inicio4='<div id="'.$x.'" ondblclick="Abrir('.$x.')" onclick="highlight('.$contador.');'.$activacion.'" onmousemove="javascript:mouseovert('.$contador.');" onmouseout="javascript:mouseout('.$contador.');" >';
		$inicio5='<div id="e'.$x.'" ondblclick="Abrir('.$x.')" onclick="highlight('.$contador.');'.$activacion.'" onmousemove="javascript:mouseovert('.$contador.');" onmouseout="javascript:mouseout('.$contador.');" >';
		$inicio6='<div id="f'.$x.'" ondblclick="Abrir('.$x.')" onclick="highlight('.$contador.');'.$activacion.'" onmousemove="javascript:mouseovert('.$contador.');" onmouseout="javascript:mouseout('.$contador.');" >';
		$inicio7='<div id="g'.$x.'" ondblclick="Abrir('.$x.')" onclick="highlight('.$contador.');'.$activacion.'" onmousemove="javascript:mouseovert('.$contador.');" onmouseout="javascript:mouseout('.$contador.');" >';
		$inicio8='<div id="h'.$x.'" ondblclick="Abrir('.$x.')" onclick="highlight('.$contador.');'.$activacion.'" onmousemove="javascript:mouseovert('.$contador.');" onmouseout="javascript:mouseout('.$contador.');" >';
		$inicio9='<div id="i'.$x.'" ondblclick="Abrir('.$x.')" onclick="highlight('.$contador.');'.$activacion.'" onmousemove="javascript:mouseovert('.$contador.');" onmouseout="javascript:mouseout('.$contador.');" >';
		$Bloqueado_por=addslashes('<input type="hidden" name="" value="" /><DIV id="b'.$x.'" align="center" >&nbsp;');
		$fin='</div>';
		$edicion="F";

		$archivo=$ruta."/".$direct;
		$query="select *,DATE_FORMAT(inicio_edicion, '%d/%m/%Y') AS modificado,(DAYOFYEAR(CURDATE())- DAYOFYEAR(archivos.inicio_edicion)) as dias_editado,archivos.usuario, concat(persona.nombre, ' ') as nomb, archivos.ruta AS ruta,persona.clave from archivos inner join usuario on archivos.usuario=usuario.clave inner join persona on persona.clave=usuario.persona where archivos.usuario like '%$txt_usuario%' and concat(persona.nombre, ' ') like '%$txt_nombre%' and archivos.ruta = '$archivo' order by archivos.usuario, archivos.archivo";
//echo $txt_usuario."<br>";
if($regs=consulta_directa($Con, $query))
{
while($reg=mysqli_fetch_array($regs))
	{


	//$activacion=(($reg["usuario"]==$_SESSION["id_usr"])?('ActivaFile('."'".str_replace("-","_",str_replace(".","_",str_replace(" ","_",$reg["archivo"])))."'".')'):(""));
	$activacion="";
	$usr=$reg["usuario"];
	if($usr==$_SESSION["id_usr"])
		{$edicion="BI";}
	else
		{$edicion="BO";}

	$jiji = leeDir($reg["ruta"]);
	$Bloqueado_por=addslashes('<input type="hidden" name="" value="'.$reg["clave"].'" /><DIV id="b'.$x.'" align="center" ><img src="Imagenes/iconografia/UserIcon.gif"  title="'.$reg["nomb"].'" />');
	$dias_editado=$reg["dias_editado"];
	$idea="";
	@mysqli_free_result($reg);
	@mysqli_free_result($regs);
	}
	@mysqli_free_result($reg);
	@mysqli_free_result($regs);
}
	@mysqli_free_result($reg);
	@mysqli_free_result($regs);

		if(is_dir("$ruta/$direct"))
		{
		$Bloqueado_por=addslashes('<input type="hidden" name="" value="" /><DIV id="b'.$x.'" align="center" >&nbsp;');
		$dias_editado="&nbsp;";
		$jiji="&nbsp;";
		$jojo=B_Carpeta("$ruta/$direct");
		$imagen_archivo='<img src="Imagenes/carpeta.JPG" align="absmiddle" border="0" /><input type="hidden" name="selection'.$x.'" id="selection'.$x.'" value="noesta" /><input type="hidden" name="tipo'.$x.'" id="tipo'.$x.'" value="carpeta" /><input type="hidden" name="vacia'.$x.'" id="vacia'.$x.'" value="'.$jojo.'" /><input type="hidden" name="valor'.$x.'" id="valor'.$x.'" value="'.$ruta.'/'.$direct.'" />';

		$idea="c&nbsp;";
		}
		else if(is_file("$ruta/$direct"))
		{

			$inf=pathinfo("$ruta/$direct");
			$ruta_r= $inf['dirname'];
			$jiji = leeDir("$ruta/$direct");
			if($inf["extension"]=="txt" || $inf["extension"]=="doc"|| $inf["extension"]=="xls"|| $inf["extension"]=="ppt"|| $inf["extension"]=="pod"|| $inf["extension"]=="pps"|| $inf["extension"]=="docx" || $inf["extension"]=="ppt" || $inf["extension"]=="rar" || $inf["extension"]=="tar" || $inf["extension"]=="pps" || $inf["extension"]=="html" || $inf["extension"]=="xls" || $inf["extension"]=="pdf" || $inf["extension"]=="mht" || $inf["extension"]=="zip" || $inf["extension"]=="odp" || $inf["extension"]=="htm" || $inf["extension"]=="odt" || $inf["extension"]=="pub"|| $inf["extension"]=="xlsx"|| $inf["extension"]=="docx"|| $inf["extension"]=="pptx")
			{
			$imagen_archivo='<input  type="checkbox" name="archivo[]" value="'.$direct.'" /><img src="Imagenes/iconografia/'.$inf["extension"].'.png" align="absmiddle" border="0" /><input type="hidden" name="selection'.$x.'" id="selection'.$x.'" value="noesta" /><input type="hidden" name="tipo'.$x.'" id="tipo'.$x.'" value="archivo" /><input type="hidden" name="valor'.$x.'" id="valor'.$x.'" value="'.$ruta.'/'.$direct.'" /><input type="hidden" name="edicion'.$x.'" id="edicion'.$x.'" value="'.$edicion.'" /><input type="hidden" name="file'.$x.'" id="file'.$x.'" value="'.$direct.'" />';
		//$idea='a<input style="visibility:hidden;"  type="checkbox" name="archivo[]" value="'.$direct.'" onclick="'.$activacion.'" /><input type="hidden" name="" value="'.$reg["clave"].'" />';
			}
			else
			{
				?><img src="Imagenes/archivo.JPG" align="absmiddle" border="0" /><input type="hidden" name="tipo<?php echo $x; ?>" id="tipo<?php echo $x; ?>" value="archivo" /><input type="hidden" name="valor<?php echo $x; ?>" id="valor<?php echo $x; ?>" value="<?php echo "$ruta/$direct"; ?>" /><?php
			}
		}
		//echo $direct;
		if($Bloqueado_por=="")
		{
		$Bloqueado_por="&nbsp;";
		}
		if($dias_editado=="")
		{
		$dias_editado="&nbsp;";
		}

		$cue[]=array(
	addslashes($inicio1.'<!--1&nbsp;-->'.$fin),
	addslashes($inicio2.'<!--2&nbsp;-->'.addslashes($fin)),
	(addslashes($inicio3).$Bloqueado_por.'<!--3&nbsp;-->'.($fin)),
	addslashes($inicio4.$imagen_archivo.'<!--4&nbsp;-->'.($fin)),
	addslashes($inicio5.$direct.'<!--5&nbsp;-->'.($fin)),
	addslashes($inicio6.$dias_editado.'<!--6&nbsp;-->'.($fin)),
	addslashes($inicio7.'<!--7&nbsp;-->'.($fin)),
	addslashes($inicio8.$jiji.'<!--8&nbsp;-->'.($fin)),
	addslashes($inicio9.'<!--9&nbsp;-->'.($fin))
	);
		?></div><?php
	}

}
$tbl = new tabla();

	$tbl->set("ruta_yui","u_yui");
	$tbl->set("alto","400px");
	$tbl->set("ancho","100%");
	$tbl->set("div","Tabla_de_datos");
	$tbl->set("encabezados",$enc);
	$tbl->set("celdas",$cue);

	$tbl->show();

?>
<form name="form_arch" id="form_arch" enctype="multipart/form-data" action="_explo_archivos.php" method="post" onsubmit="return Revisa()">
<input type="hidden" name="accion" id="accion" value="" />
<input type="text" name="carpeta[]" value="" />

<div style="visibility:hidden">
			Acci&oacute;n:
			<select name="sel_accion" id="sel_accion" onclick="EjecutaAccion(this.value)">
				<option value=""></option>
				<option value="1">Enviar E-Mail</option>
				<option value="2">Desbloquear</option>
			</select></div>
<div id="Tabla_de_datos"></div>
</form>
</body>
</html>
<?php mysqli_close($Con); ?>
