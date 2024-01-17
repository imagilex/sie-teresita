<?php
session_start();
/*

	$_SESSION["id_usr"]			==>	usuario.clave
	$_SESSION["tipo"]			==> usuario.tipo_usuario
	$name=$_SESSION["id_usr"];	==> usuario.persona

*/
$namelogin=isset($_SESSION["id_usr"]) ? $_SESSION["id_usr"] : "";
include("apoyo.php");
$Con=Conectar();

$ira = Get_Vars_Helper::getPostVar("ira_cons").Get_Vars_Helper::getPostVar("ira_adm").Get_Vars_Helper::getGetVar("ir_a");

if($ira!="")
{
	if($ira=="1")
	{
		header("location: frep_diar.php?tipo_rep=RD&noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="2")
	{
		header("location: ind_crit.php?noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="3")
	{
		header("location: planes_trabajo.php");
		exit();
	}
	else if($ira=="4")
	{
		header("location: macroproc.php?noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="10")
	{
		header("location: organigrama.php?id_mapa=10");
		exit();
	}
	else if($ira=="11")
	{
		header("location: mapas.php?id_mapa=1");
		exit();
	}
	else if($ira=="12")
	{
		header("location: directorio.php");
		exit();
	}
	else if($ira=="13")
	{
		header("location: documentos.php");
		exit();
	}
	else if($ira=="14")
	{
		header("location: reportes_especiales.php");
		exit();
	}
	else if($ira=="15")
	{
		header("location: upload.php");
		exit();
	}
	else if($ira=="16")
	{
		header("location: docto3_komps.php");
		exit();
	}
	else if($ira=="17")
	{
		//
		$login = "select id_usuario from usuario where clave='".$_SESSION["id_usr"]."'";
		$idusuario1 = consulta_directa($login);
		while($rowuser = mysqli_fetch_array($idusuario1))
		{//echo '<br>'.$rowuser["nombre"].'-'.$rowuser["clave"];
		$idusuario2=$rowuser["id_usuario"];
		}
		//
		header("location: minutas/minutas.php?id_usuario=$idusuario2");
		exit();
	}
/*	else if($ira=="17")
	{
		header("location: minutas.php");
		exit();
	}*/
	else if($ira=="18")
	{
		header("location: _vista_explo_01.php");
		exit();
	}
	else if($ira=="5")
	{
		header("location: quienes_somos.php?noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="6")
	{
		header("location: catalogos_01.php?lista=1&noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="7")
	{
		header("location: frep_diar.php?tipo_rep=RF&noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="8")
	{
		header("location: change_pass.php?noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="9")
	{
		header("location: frep_diar.php?tipo_rep=EF&noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="51")
	{
		header("location: sistema.php?noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="52")
	{
		header("location: herramientas.php?noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="53")
	{
		/*header("location: secciones.php?noCache=".rand(0,32000));
		exit();*/
	}
	else if($ira=="54")
	{
		header("location: admin_plan_trab.php?noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="55")
	{
		/*header("location: configuracion_basica.php?noCache=".rand(0,32000));
		exit();*/
	}
	else if($ira=="56")
	{
		header("location: calendario.php?noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="57")
	{
		header("location: planes_t.php");
		exit();
	}
	else if($ira=="58")
	{
		header("location: informacion.php?raiz=Archivos_Planes/P9999/Archivos&proyecto=P9999&carpetaa=Archivos");
		exit();
	}
	else if($ira=="59")
	{
		header("location: frep_diar.php?tipo_rep=RB&noCache=".rand(0,32000));
		exit();
	}
	else if($ira=="60")
	{
		header("location: pedidos.php");
		exit();
	}
	else if($ira=="61")
	{
		header("location: reportes_estrategia.php");
		exit();
	}
	else if($ira=="62")
	{
		header("location: ind_dir.php");
		exit();
	}

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita</title>
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
<link rel="stylesheet" href="libreria/layout_p.css" />
<script language="javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" src="u_yui/container_core.js"></script>
<script language="javascript" src="u_yui/menu.js"></script>
<script type="text/javascript">YAHOO.util.Event.onContentReady("barra_menu", function () {var oMenuBar = new YAHOO.widget.MenuBar("barra_menu", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});YAHOO.util.Event.onContentReady("menu_opciones", function () {var oMenuBar = new YAHOO.widget.MenuBar("menu_opciones", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});</script>
<link href="style/Style_01.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.frmInicio.usr.value!="" && document.frmInicio.pass.value!="") return true;
		else
		{
			if(document.frmInicio.usr.value=="" && document.frmInicio.pass.value=="") alert("Ingresa tu usuario y contraseña");
			else if(document.frmInicio.usr.value=="") alert("Ingresa tu usuario");
			else if(document.frmInicio.pass.value=="") alert("Ingresa tu contraseña");
			return false;
		}
	}
</script>
<script type="text/javascript">
function onEnter(ev) {  if(ev==13)    { document.frmInicio.submit();javascript: return Validar();    }  }
</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
</head>
<script LANGUAGE="JavaScript">
function PantallaCompleta2(pagina) {
fullscreen = window.open(pagina, "fullscreen", 'top=0,left=0,width='+(screen.availWidth)+',height ='+(screen.availHeight)+',fullscreen=yes,toolbar=0 ,location=0,directories=0,status=0,menubar=0,resiz able=0,scrolling=0,scrollbars=0');
}
</script>
<body>
<!--<script>
var Path="c:\\WT"
function AddNewFolder(path,folderName)
{
   var fso, f, fc, nf,path;
   fso = new ActiveXObject("Scripting.FileSystemObject");
   f = fso.GetFolder(path);
   fc = f.SubFolders;
   if (folderName != "" )
      nf = fc.Add(folderName);
   else
      nf = fc.Add("New Folder");
}
var  fso, f, fc, nf;
   fso = new ActiveXObject("Scripting.FileSystemObject");
   var Dir = fso.FolderExists(Path) ;
   //document.write (Dir)
   if (Dir == false)
   {
AddNewFolder('c:\\','WT');
   }
</script>-->

<div class="wrapper">
<?php
if( !isset($_SESSION["tipo"]) &&  !isset($_SESSION["id_usr"]) )
{

	$usr = Get_Vars_Helper::getPGVar("usr");
	$pass = Get_Vars_Helper::getPGVar("pass");
	$id_usr_usr = "";
	$tipo_usr = "";

	if($usr!="" && $pass!="")
	{
		if($registro=consulta_directa("select id_persona,clave as id_usuario,tipo_usuario, persona from usuario where clave='$usr' and password='$pass' and estatus='A' and persona in (select clave from persona where estatus = 'A')"))

		{
			$dato=mysqli_fetch_array($registro);
			$id_usr_usr=$dato["id_usuario"];
			$tipo_usr=$dato["tipo_usuario"];
			$persona_usr=$dato["persona"];
			$id_persona=$dato["id_persona"];

			if($id_usr_usr!="" && $tipo_usr!="")
			{
				$_SESSION["tipo"]=$tipo_usr;
				$_SESSION["id_usr"]=$id_usr_usr; //clave del usuario
				$_SESSION["id_persona_usr"]=$persona_usr;

				$cuantos=@mysqli_fetch_array(consulta_directa("select count(*) as n from archivos where usuario='".$_SESSION["id_usr"]."'"));
				$name=@mysqli_fetch_array(consulta_directa("select nombre from persona where id_persona =".$id_persona));
				$_SESSION["nampersona"]=$name[0];

				if(intval($cuantos["n"])>0)
				{
					?>
				<!--	<script language="javascript" type="text/javascript">location.href='upload.php';</script>   -->
					<?php
				}
			}

			else
			{
				Alert("El nombre de usuario y/o contraseña son incorrectos");
			}

		}

	}

}
BarraHerramientas(!isset($_SESSION["tipo"]),4,false);
if(! isset($_SESSION["tipo"]))
{
	$img=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Entrada' and elemento='Imagen'"));
	?>
<form action="entrada.php" method="post" enctype="multipart/form-data" name="frmInicio">
	  <p>
	    <!--onSubmit="javascript: return Validar();">-->
      </p>
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <div align="center">
    <table border="0">
      <tr>
	        <td align="center">
	         <img src="Archivos_Secciones/<?php echo $img["valor"]; ?>" border="0" height="250" />
	          </td>
	        <td align="center"><table width="200" cellspacing="0">
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td align="right">Usuario:</td>
	            <td align="left"><input type="text" name="usr" maxlength="50" size="15" style="background-color:#f2f2f2; border:none" /></td>
	            <td align="left">&nbsp;</td>
              </tr>
	          <tr>
	            <td align="right">Password:</td>
	            <td align="left"><input type="password" name="pass" maxlength="50" size="15" style="background-color:#f2f2f2; border:none" onKeyUp="onEnter(event.keyCode);"/></td>
	            <td align="left"><input type="image" src="Imagenes/entrar.jpg" name="Entrar" id="Entrar" value="Entrar" /></td>
              </tr>
      </table></td>
          </tr>
      </table>
  </div>
  </form>
<?php
}
else
{
$namep = $_SESSION["nampersona"];
//echo $namep;
$conteo = consulta_directa("select count(*) as n from archivos where usuario='".$_SESSION["id_usr"]."'");
$count = mysqli_fetch_array($conteo);

//BH_Ayuda('0','4');
$arch=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Lineamientos' and elemento='Introduccion'"));
?>
<h3 align="center" style="color:#999999;"><?php MostrarArchivo($Dir."/Archivos_Secciones/".$arch["valor"]); ?></h3>
<p>
  <?php
}
?>

</p>
<!--Finaliza el cuerpo del html e Inicia el piede página-->
<!--HAY DOS SENTENCIAS, UNA QUE QUITA EL LINK RECU PASS CUANDO TE LOGEAS, LA SEGUNDA MUESTRA EN PIE EL NÚMERO DE ARCHIVOS QUE ESTAN BLOQUEADOS POR EL USUARIO LOGEADO-->
<?php
if(!isset($_SESSION["tipo"]))
{
echo"<div class='push'></div>
</div>
<div class='footer' style='background-color:f2f2f2'>
          <p><table width='100%' height='28' border='0' bgcolor='f2f2f2'>
            <tr>
              <td align='right'><a href='olvido_pass.php'>&iquest;Olvido su password?</a></td>
            </tr>
            </table></p>
</div>";
}
else
{
echo"<div class='push'></div>
</div>
<div class='footer' style='background-color:f2f2f2'>
          <p>";
		if(intval($count["n"])>0)
		 {
		  echo"<table width='100%' height='28' border='0' bgcolor='f2f2f2'>
            <tr>
              <td width='37%' align='left'>
<!--$namep tienes $count[0] archivo(s) bloqueados-->
			  </td>
              <td width='28%' align='right'>&nbsp;</td>
              <td width='35%' align='right'>&nbsp;";?><img src="Imagenes/menu/varilla.gif" alt='Gestión de Archivos' onclick='javascript:window.location.replace("upload.php")' /> <?php echo"</td>
            </tr>
            </table>";
		 }
			else
			{
			echo"<table width='100%' height='28' border='0' bgcolor='f2f2f2'>
            <tr>
              <td width='37%' align='left'>&nbsp;</td>
              <td width='28%' align='right'>&nbsp;</td>
              <td width='35%' align='right'>&nbsp;";?> <img src="Imagenes/menu/varilla.gif" alt='Gestión de Archivos' onclick='javascript:window.location.replace("upload.php")' /> <?php echo"</td>
            </tr>
            </table>";
			}
echo"		  </p>
</div>";
}
?>
<!--Finaliza piede página-->
</body>
</html>
