<?php
echo $referer;
session_start();

header("Expires: Mon, 22 Sep 1997 09:00:00 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");
include("apoyo.php");
$Con=Conectar();
$raiz=PostString("raiz").Get("raiz");
$proyecto=PostString("proyecto").Get("proyecto");
$ruta_expl=PostString("ruta_expl").Get("ruta_expl");
if($ruta_expl!="")
{
	$pos=strpos($ruta_expl,"Archivos_Planes");
	if($pos!==false)
		$ruta_expl=substr($ruta_expl,$pos);
}
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
				//echo "kke ".$arch_upl2;
			//echo "<br>";

			//echo $_FILES[$arch_upl]["name"];
				if(isset($_FILES[$arch_upl]["name"]) && $_FILES[$arch_upl]["name"]!="")
				{
					$datos=@mysqli_fetch_array(consulta_directa("select ruta,archivo from archivos where archivo='$arch' and usuario='".$_SESSION["id_usr"]."'"));
					$noarchivo=$datos["archivo"];
			//		echo "original= ".  $noarchivo."<br>";
			//		echo "noarchivo= ".  basename( $_FILES[$arch_upl]['name']);
					if ($noarchivo == basename( $_FILES[$arch_upl]['name']))
					{
					$original=str_replace("\\","/",$Dir)."/".$datos["ruta"];

					$Pnewfile = explode("/",$datos["ruta"]);
					$PVdir=$Dir."/".$Pnewfile[0]."/".$Pnewfile[1]."/Versiones/";
		//			echo $original."<br>";
		//			echo $PFnewfile."<br>";
		  $arch0=sizeof($Pnewfile)-1;
          $fil=$Pnewfile[$arch0];
          $filexp=explode(".",$Pnewfile[$arch0]);
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
						consulta_directa("delete from archivos where archivo='$arch' and usuario='".$_SESSION["id_usr"]."' and ruta='".$datos["ruta"]."'");
					}
				}
				else
				{
				$lolo="<script language=\"javascript\">alert('El archivo que esta publicando no coincide con el nombre o con el tipo de archivo');</script>";
				}
				}
			else
			{}
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
				consulta_directa("delete from archivos where archivo='$arch' and usuario='".$_SESSION["id_usr"]."'");
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
<link rel="stylesheet" href="libreria/layout_d.css" />
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
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
<script language="javascript">
function cambiatitulo(param)
{
<?php
     $carpeta = $_GET["carpeta"];
     $carpetaaa = $_GET["carpetaa"];
switch ($carpetaa) {
case "Definicion":
       $carpeta="Definición";
       $carpetaTi="Planes de trabajo";
        break;
    case "PT":
       $carpeta="Planes de Trabajo";
       $carpetaTi="Planes de trabajo";
        break;
    case "Planes de Trabajo":
       $carpeta="Planes de Trabajo";
       $carpetaTi="Planes de trabajo";
        break;
    case "Archivos":
       $carpeta="Archivos";
       $carpetaTi="Planes de trabajo";
        break;
    default:
      $carpeta=$carpetaaa;
      $carpetaTi=$CarpetaR;
}
		$nombre_p=@mysqli_fetch_array(consulta_directa("select nombre from docto_general where id_documento='$proyecto'"));
?>
var titulooriginal='<?php echo $carpeta ; ?>'
var titulobacko='<?php echo $carpetaTi ; ?>'
var titulo=document.getElementById('notifica').innerHTML;
var tituloback=document.getElementById('backtitle').innerHTML;
//alert(titulobacko);
if(param !='')
{
titulo=titulo+param;
titulo=param;
document.getElementById('notifica').innerHTML=titulo;
document.getElementById('backtitle').innerHTML=titulobacko;
}
else
{
var titulo=document.getElementById('notifica').innerHTML;
document.getElementById('notifica').innerHTML=titulooriginal;
document.getElementById('backtitle').innerHTML=titulobacko;
}
}
function details2(directorio,nproy)
	{

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
	}
	function NvaCap()
	{
	YAHOO.example.container.FormArchivo.hide();
	YAHOO.example.container.FormArchivo.hide();
 YAHOO.example.container.panel1.hide();
 	YAHOO.example.container.FormCarpeta.hide();
		YAHOO.example.container.FormCarpeta.center();
		YAHOO.example.container.FormCarpeta.show();
	}

	function Fnuevo()
	{
	//alert('1');
		YAHOO.example.container.FormArchivo.hide();
		//YAHOO.example.container.panel1.hide();

			<?php
if ($CarpetaR=="")
{
?>
var bleer4='<table style=\"width: 100%\"> <form id="form_arch" name="form_arch" method=\"post\"> <tr> <td style=\"width: 31px\"> <input name=\"lista\" type=\"radio\" checked style=\"width: 20px\" value=\"1\" onclick=\"javascript: CargaArch();\"/> </td> <td>Archivo</td> </tr> <tr> <td style=\"width: 31px\"><input name=\"lista\" type=\"radio\" style=\"width: 20px\" value=\"2\" onclick=\"javascript: NvaCap();\"/></td> <td> Carpeta</td> </tr> </form> </table>';
 		<?php
 		}
 		else
 		{
 		?>
 		var bleer4='<table style=\"width: 100%\"> <form id="form_arch" name="form_arch" method=\"post\"> <tr> <td style=\"width: 31px\"> <input name=\"lista\" type=\"radio\" checked style=\"width: 20px\" value=\"1\" onclick=\"javascript: CargaArch();\"/> </td> <td>Archivo</td> </tr></form> </table>';


 		<?php
 		}
 		?>
YAHOO.example.container.panel1 = new YAHOO.widget.Panel("panel1", { width:"350px", visible:false, draggable:true, close:true } );
YAHOO.example.container.panel1.setHeader("Agregar");
YAHOO.example.container.panel1.setBody(bleer4);
YAHOO.example.container.panel1.render("FormArchivo");
YAHOO.example.container.panel1.show();
YAHOO.example.container.panel1.center();
	return false;
}


	function CargaArch()
	{
	YAHOO.example.container.FormArchivo.hide();
	YAHOO.example.container.FormArchivo.hide();
 YAHOO.example.container.panel1.hide();
 	YAHOO.example.container.FormCarpeta.hide();
		YAHOO.example.container.FormArchivo.center();
		YAHOO.example.container.FormArchivo.show();
	}
	function FACancel()
	{
		YAHOO.example.container.FormArchivo.hide();
		return false;
	}
	//FormArchivo
		function FACancel2()
	{
	//alert('lolo');
YAHOO.example.container.panel1.hide();
	return false;
	}
			function FACancel2w()
	{
	YAHOO.example.container.FormArchivo.hide();
	YAHOO.example.container.FormArchivo.hide();
 YAHOO.example.container.panel1.hide();
 	YAHOO.example.container.FormCarpeta.hide();
return true;
	}
	function FCCancel()
	{
		YAHOO.example.container.FormCarpeta.hide();
		return false;
	}
	function FAOk()
	{
		var x,inputs=$('dataArchivo').getElementsByTagName('input');
		var variables=window.parent.frames["frame_archivos"].location.href.split("?")[1].split("&");
		var direccion="";
		for(x=0;x<variables.length;x++)
			if(variables[x].split('=')[0]=='ruta')
				direccion='<?php echo addslashes($Dir."/"); ?>'+variables[x].split('=')[1];
		$('ruta_ca').value=direccion;
		for(x=0;x<inputs.length;x++)
		{
			if(inputs[x].type=='hidden' && inputs[x].name=='url_retorno')
			{
				inputs[x].value=location.href;
			}
		}
		//nosesese
		$nosese=$('dataArchivo').action;
		//alert($nosese);
		$('dataArchivo').submit();
		YAHOO.example.container.FormArchivo.hide();
		return false;
	}
		function FAOk2()
	{
		var x,inputs=$('dataArchivo2').getElementsByTagName('input');
		var variables=window.parent.frames["frame_archivos"].location.href.split("?")[1].split("&");
		var direccion="";
		for(x=0;x<variables.length;x++)
			if(variables[x].split('=')[0]=='ruta')
				direccion='<?php echo addslashes($Dir."/"); ?>'+variables[x].split('=')[1];
		$('ruta_ca').value=direccion;
		for(x=0;x<inputs.length;x++)
		{
			if(inputs[x].type=='hidden' && inputs[x].name=='url_retorno')
			{
				inputs[x].value=location.href;
			}
		}
		//nosesese
		$nosese=$('dataArchivo2').action;
		//alert($nosese);
		$('dataArchivo2').submit();
		YAHOO.example.container.FormArchivo.hide();
		return false;
	}
	function FCOk()
	{
		var x,inputs=$('dataCarpeta').getElementsByTagName('input');
		var variables=window.parent.frames["frame_archivos"].location.href.split("?")[1].split("&");
		var direccion="";
		for(x=0;x<variables.length;x++)
			if(variables[x].split('=')[0]=='ruta')
				direccion='<?php echo addslashes($Dir."/"); ?>'+variables[x].split('=')[1];
		$('ruta_nc').value=direccion;
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
<script language="javascript">
	function ActivaFile(obj)
	{
		if($(obj).disabled) $(obj).disabled=false;
		else $(obj).disabled = true;
	}

	///////////////////////////////////////////////////////////////////////////////////////////
	///                     activavfile


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

function Fleer(mensaje,titulo,ancho)
	{
	//alert('<?php echo $vista;?>');
		YAHOO.example.container.FormArchivo.hide();
var bleer4=mensaje;

YAHOO.example.container.panel1 = new YAHOO.widget.Panel("panel1", { width:"150px", visible:false, draggable:true, close:true } );
YAHOO.example.container.panel1.setHeader(titulo);
YAHOO.example.container.panel1.setBody(bleer4);
YAHOO.example.container.panel1.render("FormArchivo");
YAHOO.example.container.panel1.center();
YAHOO.example.container.panel1.show();
	return false;
}

function FleerBO(mensaje,titulo,ancho)
	{
	//alert('<?php echo $vista;?>');
		YAHOO.example.container.FormArchivo.hide();
var bleer4=mensaje;

YAHOO.example.container.panel1 = new YAHOO.widget.Panel("panel1", { width:ancho, visible:false, draggable:true, close:true } );
YAHOO.example.container.panel1.setHeader(titulo);
YAHOO.example.container.panel1.setBody(bleer4);
YAHOO.example.container.panel1.render("FormArchivo");
YAHOO.example.container.panel1.center();
YAHOO.example.container.panel1.show();
	return false;
}


function HFleerBO()
{
YAHOO.example.container.panel1.hide();
}

function Error_Mail34(mensaje,titulo)
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
}

	function ActivaVFile(obj)
	{
	//alert(obj);
	/*
	var casa=0;
	var lolo=getElementsById(obj);
		if($(obj).disabled)
		{
		 $(obj).disabled = true;
		$(obj).style.visibility="hidden";
YAHOO.example.container.FormArchivo.hide();
YAHOO.example.container.panel1.hide();
		}
		else
		{
		var casa= casa+1;
		//alert(casa);
		//alert("lalala");
		$(obj).disabled=false;
		$(obj).style.visibility="visible";

		*/

		//'<form id="dataArchivo2" action="upload.php" method="post" <!--onsubmit="return FASubmit()"--> enctype="multipart/form-data"> <input type="hidden" name="accion" id="accion" value="add_files" /> <input type="text" name="archivo" id="archivo" value="<?php echo $Dir."/".$raiz; ?>" /> <input type="hidden" name="url_retorno" id="url_retorno" value="" /> <table align="center"> <tr><td align="left">Archivo: </td></tr> <tr><td align="center"><input type="file" maxlength="250" size="30" name="file" id="archivo" /></td></tr> <tr><td align="right"><input type="button" value="Aceptar" onclick="FAOk2()" /> <input type="button" value="Cancelar" onclick="FACancel()" /></td></tr> </table> </form>'

					//alert('ahora publica');

		YAHOO.example.container.FormArchivo.hide();
		parent.YAHOO.example.container.FormCarpeta.hide();
			//YAHOO.example.container.panel1.hide();

		//var bleer4='<form id="dataArchivo2" action="upload.php" method="post" onsubmit="return FASubmit()" enctype="multipart/form-data"> <input type="hidden" name="accion" id="accion" value="add_files" /> <input type="hidden" name="archivo" id="archivo" value="'+obj+'" /> <table align="center"> <tr><td align="left">Archivo: </td></tr> <tr><td align="center"><input type="file" maxlength="250" size="30" name="file" id="file" /></td></tr> <tr><td align="right"><input type="button" value="Aceptar" onclick="FAOk()" /> <input type="button" value="Cancelar" onclick="FACancel()" /></td></tr> </table> </form>';
		var bleer4='<form id="dataArchivo2"  method="post" onsubmit="return FASubmit()" enctype="multipart/form-data"> <input type="hidden" name="accion" id="accion" value="add_files" /> <input type="hidden" name="archivo" id="archivo" value="'+obj+'" /> <table align="center"> <tr><td align="left">Archivo: </td></tr> <tr><td align="center"><input type="file" maxlength="250" size="30" name="file" id="file" /></td></tr> <tr><td align="right"><input type="button" value="Aceptar" onclick="FAOk2()" /> <input type="button" value="Cancelar" onclick="FACancel2w()" /></td></tr> </table> </form>';
		//			alert(bleer4);


YAHOO.example.container.panel1 = new YAHOO.widget.Panel("panel1", { width:"350px", visible:false, draggable:true, close:true } );
//YAHOO.example.container.panel1.setHeader("Publicarq");
YAHOO.example.container.panel1.setHeader("Publicar");
YAHOO.example.container.panel1.setBody(bleer4);
YAHOO.example.container.panel1.render("FormArchivo");
YAHOO.example.container.panel1.center();
YAHOO.example.container.panel1.show();


		/*


		}
		*/



	}
	///////////////////////////////////////////////////////////////////////////////////////////
	function Foco(obj)
	{
		window.event.cancelBubble=true;
		obj.focus();
		obj.select();
	}
	function Revisa()
	{
		return true;
	}
	function EjecutaAccion(accion)
	{
		$('sel_accion').value="";
		var form=$('form_arch');
		var inputs=form.getElementsByTagName('input');
		var checks=new Array();
		var hiddens=new Array();
		var y=0,z=0;
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
			if(checks[x].checked && personas.split(',').indexOf(hiddens[x].value)==-1) personas += hiddens[x].value+",";
		}
		if(accion=="1")
		{
			//alert('accion1');
			if(personas=="to=")
			{
				alert("No hay datos seleccionados");
				return false;
			}
			open("envia_mail.php?to="+personas.substr(0,personas.length-1));
		}
		if(accion=="2")
		{
			y=0;
			for(x=0;x<checks.length;x++)
			{
				if(checks[x].checked && hiddens[x].value!="<?php echo $_SESSION["id_persona_usr"]; ?>")
				{
					alert("¡Acción incorrecta!\nNo puede desbloquear los archivos de otros usuarios");
					return false;
					y++;
				}
				if(checks[x].checked)
				{
					y++;
				}
			}
			if(y>0)
			{
				if(confirm("¿Esta seguro?"))
				{
					$('accion').value='free_files';
					$('form_arch').submit();
					return true;
				}
			}
			else
			{
				alert("No hay datos seleccionados");
				return false;
			}
		}
	}

	function Inic()
	{
		$('lista').value="<?php echo $vista; ?>";
	}
	</script>
	<SCRIPT LANGUAGE=JAVASCRIPT TYPE="TEXT/JAVASCRIPT">
	function titulo()
	{
	//alert('<?php echo $raiz; ?>');
	<?php
if ($CarpetaR=="")
{
if ($CarpetaR=="gerardo")
{
$Cadena1=$raiz;
$Reemplazar1="//";
$CadenaNueva1="/";

$CadenaMod1=preg_replace($Reemplazar1,$CadenaNueva1,$Cadena1);

}
$URL_A="http://manaiz.com/planes_t.php";
}
else
{
$Cadena=$raiz;
$Reemplazar="/".$carpetaa;
//$Reemplazar=$carpetaa;
$CadenaNueva="";
$CadenaMod=preg_replace($Reemplazar,$CadenaNueva,$Cadena);

//$RUTA_PARTa="http://teresita.com.mx/_explorador.php?raiz=Archivos_Planes/".$proyecto."/".elimina_acentos($CarpetaR);
$RUTA_PARTa="http://manaiz.com/_explorador.php?raiz=".$CadenaMod;
$RUTA_PARTb="&proyecto=".$proyecto;
$RUTA_PARTc="&carpetaa=".elimina_acentos($CarpetaR);
$URL_A=$RUTA_PARTa.$RUTA_PARTb.$RUTA_PARTc;
}
?>
	cambiatitulo('<?php echo $carpeta ;?>');
	bckuri=document.referer;
	document.getElementById('bckt').href = '<?php echo $URL_A; ?>';
	//document.getElementById('bcki').href = '<?php echo $URL_A; ?>';
	//document.getElementById('bckf').href = '<?php echo $URL_A; ?>';
	}
</script>
</head>
<body onload="titulo();">
<a id="bckt" style="cursor:default" href="">
<div style="background-color:fuchsia; filter:alpha(opacity=0); margin:0px auto;position: absolute; width: 150px; height: 28px; z-index: 1; left: 53px; top: 5px" id="layer1">
</div></a>


<?php
function elimina_acentos($cadena){
	$tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	return(strtr($cadena,$tofind,$replac));
}
?>

<div class="wrapper">
<?php //BarraHerramientas(); ?>
<div style="visibility:hidden; font-size:12px;">
<div id="FormCarpeta" class="div_panel">
	<div class="hd">Agregar Carpeta</div>
	<div class="bd">
		<form id="dataCarpeta" action="ajax/archivos.php" method="post" onsubmit="return FCSubmit()">
			<input type="hidden" name="accion" id="accion" value="crea_carpteta" />
			<input type="hidden" name="ruta" id="ruta_nc" value="<?php echo $Dir."/".$raiz; ?>" />
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
	<div class="hd">Agregar Archivo</div>
	<div class="bd">
		<form id="dataArchivo" action="ajax/archivos.php" method="post" onsubmit="return FASubmit()" enctype="multipart/form-data">
			<input type="hidden" name="accion" id="accion" value="carga_archivo" />
			<input type="hidden" name="ruta" id="ruta_ca" value="<?php echo $Dir."/".$raiz; ?>" />
			<input type="hidden" name="url_retorno" id="url_retorno" value="" />
			<table align="center">
				<tr><td align="left">Archivo: </td></tr>
				<tr><td align="center"><input type="file" maxlength="250" size="30" name="archivo" id="archivo" /></td></tr>
				<tr><td align="right"><input type="button" value="Aceptar" onclick="FAOk()" /> <input type="button" value="Cancelar" onclick="FACancel();" /></td></tr>
			</table>
		</form>
	</div>
</div>
</div>
<!--INICIA TABLA PRINCIPAL-->
<form name="otro" id="form_arch" enctype="multipart/form-data" action="_explorador.php" method="post" onsubmit="return Revisa()">
<input type="hidden" name="accion" id="accion" value="" />
<table width='100%' height="40" border='0' cellpadding="0" cellspacing="0" bgcolor='F2F2F2'>
  <tr>
    <th width="10%" scope='col'><table border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td><img src="Imagenes/menu/varilla.gif" width="14" height="1" /></td>
        <td><img src="Imagenes/menu/home.png" title="Inicio" alt="Inicio" onclick="javascript: /*window.close()*/location.href='entrada.php';" /></td>
        <td><img src="Imagenes/menu/varilla.gif" width="14" height="1" /></td>
        <td style="WIDTH: 150px;">
        <!--atras gerardo -->
          <table style="WIDTH: 150px; VERTICAL-ALIGN: middle;height: 24px; BACKGROUND-REPEAT: no-repeat; background-image: url('http://www.teresita.com.mx/Imagenes/back/boton_location.png');" cellspacing="0" cellpadding="0" class="style1"  >
<tr>
<td style="TEXT-ALIGN: left;  BACKGROUND-REPEAT: no-repeat; HEIGHT: 28px; FONT-SIZE: 9px; VERTICAL-ALIGN: middle;">
</td>
<td style="cursor:default;TEXT-ALIGN: center; WIDTH: 150px; BACKGROUND-REPEAT: no-repeat; HEIGHT: 28px; FONT-SIZE: 9px; VERTICAL-ALIGN: middle;">
<span id="backtitle" style="cursor:default; font-family:Arial, Helvetica, sans-serif; color:#000; font-size:inherit; cursor:default">
<?php
 if ($CarpetaR ="")
 {
 echo "Planes de Trabajo";
 }
 else
 {echo $CarpetaR;
 }
 ?>
</span>
</td>
</tr>
</table>


          <!--atras gerardo--></td>
      </tr>
    </table></th>
    <th width="73%" scope='col'>
      <?php echo $nombre_p[0] . "..."; ?>
      <span id="notifica"/>
      <span id="notifica"/>
      <?php
      if ($CarpetaR=="")
      {
      $D_espacio="";
      }
      else
      {
      $D_espacio=", ";
      }
      ?>
      <?php echo $CarpetaR .$D_espacio. $carpeta ; ?></span>
      </span>
    </th>
    <th width="17%" scope='col'><table width="169" border='0' align='right' cellpadding="0" cellspacing="0">
      <tr>
        <th width="14" scope='col'><img src="Imagenes/menu/varilla.gif" alt="Borrar" width="14" height="1" title="Borrar" onclick="" /></th>
        <th width="20" scope='col'><img src="Imagenes/menu/add_a.png" onclick="return Fnuevo()" title="Agregar" alt="Agregar" /></th>
        <th width="14" scope='col'>&nbsp;<img src="Imagenes/menu/varilla.gif" alt="Borrar" width="14" height="1" title="Borrar" onclick="" /></th>
        <th width="20" scope='col'><img src="Imagenes/menu/add_b.png" onclick="window.frames.frame_archivos.haz('borra'); window.frames.frame_archivos.haz('elimina_carpeta')" title="Borrar" alt="Borrar" /></th>
        <th width="14" scope='col'><img src="Imagenes/menu/varilla.gif" alt="Borrar" width="14" height="1" title="Borrar" onclick="" /></th>
        <th width="40" scope='col'><span onclick="window.frames.frame_archivos.haz('envia_mail')"><img title='Mail' alt='Mail' src='Imagenes/iconografia/02.png' /></span></th>
        <th width="14" scope='col'><img src="Imagenes/menu/varilla.gif" alt="Borrar" width="14" height="1" title="Borrar" onclick="" /></th>
        <th width="14" scope='col'><!--<span onclick="window.frames.frame_archivos.haz('desbloquea')"><img src="Imagenes/menu/Open_lock.png" alt="Desbloquear" width="28" height="28" /></span>--></th>
        <th width="14" scope='col'><img src="Imagenes/menu/varilla.gif" alt="Borrar" width="14" height="1" title="Borrar" onclick="" /></th>
        <th width="29" scope='col'><img src="Archivos_Secciones/Btn_salir.png" alt="" onclick="javascript: /*window.close()*/location.href='index.php';" /></th>
        </tr>
    </table></th>
  </tr>
</table>
<table border="0" align="center" style="width: 100%">
<tr>
		<td valign="top"><!--<iframe width="250" height="200" src="_explo_carpetas.php?raiz=<?php echo $raiz; ?>" id="frame_carpetas" frameborder="1" marginheight="5" marginwidth="5"></iframe>-->
		<iframe width="100%" height="500" src="_explo_archivos.php?ruta=<?php echo (($ruta_expl!="")?($ruta_expl):($raiz)); ?>&proyecto=<?php echo $proyecto;?>&CarpetaR=<?php echo  $carpeta ?>" id="frame_archivos" frameborder="0" marginheight="0" marginwidth="0" allowtransparency="true"></iframe>
		</td>
	</tr>
</table>

  <!--Finaliza el cuerpo del html e Inicia el piede página-->
</div>
<div class="push"></div>
<div class="footer" style="background-color:f2f2f2">
          <p><table width="100%" height="28" border="0" bgcolor="f2f2f2">
            <tr>
              <td width="86%"></td>
              <td width="6%">&nbsp;</td>
              <td width="3%"><!--<span onclick="window.frames.frame_archivos.haz('elimina_carpeta')"><img src="Imagenes/menu/delcarpeta.png" title="Borrar Carpeta" alt="Borrar Carpeta" width="28" height="28" /></span>--></td>
              <td width="0%">&nbsp;</td>
              <td width="3%"><!--<img src="Imagenes/menu/adcarpeta.png" onclick="NvaCap()" title="Agregar Carpeta" alt="Agregar Carpeta" />--></td>
              <td width="2%"></td>
            </tr>
            </table></p>
</div>
</form>
			<!--  INICIA DIV OCULTA -->
			<div style="visibility:hidden">
			Acci&oacute;n:
			<select name="sel_accion" id="sel_accion" onchange="EjecutaAccion(this.value)">
				<option value=""></option>
				<option value="1">Enviar E-Mail</option>
				<option value="2">Desbloquear</option>
			</select></div>
			<!--  TERMINA DIV OCULTA -->

</body>
</html>
<?php
mysqli_close($Con);
?>
