<?php

include("apoyo.php");

$con=Conectar();

$correo=PostString("correo");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" href="libreria/layout_p2.css" />
	<title>Teresita</title>
	<link href="style/Style_01.css" rel="stylesheet" type="text/css" />
	<script language="javascript">
		function Validar_Email(Cadena)
		{
			var Punto = Cadena.substring(Cadena.lastIndexOf('.') + 1, Cadena.length);
			var Dominio = Cadena.substring(Cadena.lastIndexOf('@') + 1, Cadena.lastIndexOf('.'));
			var Usuario = Cadena.substring(0, Cadena.lastIndexOf('@'));
			var Reserv = "@/�\"\'+*{}\\<>?�[]�����#��!^*;,:";
			var valido = true;
			//Punto no debe tener caracteres especiales
			for (var Cont=0; Cont<Punto.length; Cont++)
			{
				X = Punto.substring(Cont,Cont+1);
				if (Reserv.indexOf(X)!=-1)
					valido = false;
			}
			//Dominio no debe tener caracteres especiales
			for (var Cont=0; Cont<Dominio.length; Cont++)
			{
				X = Dominio.substring(Cont,Cont+1);
				if (Reserv.indexOf(X)!=-1)
					valido = false;
			}
			//Usuario no debe tener caracteres especiales
			for (var Cont=0; Cont<Usuario.length; Cont++)
			{
				X = Usuario.substring(Cont,Cont+1);
				if (Reserv.indexOf(X)!=-1)
					valido = false;
			}
			//Verificacion de sintaxis b�sica
			if (Punto.length<2 || Dominio.length <1 || Cadena.lastIndexOf('.')<0 || Cadena.lastIndexOf('@')<0 || Usuario.length<1)
			{
				valido = false;
			}
			return valido;
		}
		function DataValidation()
		{
			if(Validar_Email(document.formulario.correo.value))
				return true;
			else
				alert("Debe ingrasar una direcci�n de correo electr�nico v�lida");
			return false;
		}
	</script>
	<style type="text/css">
		<!--
		a:link {
			text-decoration: none;
			color: #FFF;
		}
		a:visited {
			text-decoration: none;
			color: #FFF;
		}
		a:hover {
			text-decoration: none;
			color: #FFF;
		}
		a:active {
			text-decoration: none;
			color: #FFF;
		}
		-->
	</style>
</head>

<body>
<div class="wrapper">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="30">
  <tr>
    <td width="28%">&nbsp;</td>
    <td width="61%"><a href="../site"></a></td>
    <td width="11%" align="center"><a href="entrada.php"><img src="Imagenes/iconoHome.png" title="SIE" border="0" /></a></td>
  </tr>
</table>
<?php

//BarraHerramientas();

?>
<p>&nbsp;</p>
<?php
if($correo=="")
{
	?>
	<form name="formulario" action="olvido_pass.php" method="post" onsubmit="return DataValidation();">
<table border="0" align="center" cellpadding="4" cellspacing="4">
		<tr>
		  <td colspan="2" align="right" height="100">&nbsp;</td>
	  </tr>
		<tr>
			<td colspan="2" align="right">
				<h2>Recordar acceso:</h2>
			</td>
		</tr>
		<tr>
		  <td colspan="2">&nbsp;

		    </td>
		  </tr>
		<tr>
		  <td>Correo</td>
		  <td>
		    <input type="text" name="correo" size="50" maxlength="250" />
		    </td>
		  </tr>
		<tr>
			<td colspan="2" align="right">
				<input type="image" src="Imagenes/btn_enviar.jpg" style="border-width:1px;" />
			</td>
		</tr>
	</table>

  </form>
	<?php
}
else
{
	$usuario=@mysqli_fetch_array(mysqli_query($con, "select clave as usuario, password as contrasenia from usuario where persona in (select distinct(clave) from persona where email='$correo')"));
	if($usuario["usuario"]!="" && $usuario["contrasenia"]!="")
	{
		$email_cuerpo="";
		$Arch=mysqli_fetch_array(mysqli_query($con, "select valor  from seccion where id_seccion='mail' and elemento='olvido_pass'"));
		if(file_exists($Dir."/Archivos_Secciones/".$Arch["valor"]))
		{
			if($Arch["valor"]!="" && $Archivo=@fopen($Dir."/Archivos_Secciones/".$Arch["valor"],"r"))
			{
				$Contenido="<html><head><title>Teresita.com.mx >> Usuario y Contrase�a</title></head><body style=\"font-family:Arial, Helvetica, sans-serif; color:#333333\"><p>&nbsp;</p>";
				while(!feof($Archivo))
					$Contenido=$Contenido.fgets($Archivo)."<br />";
				fclose($Archivo);
				$Contenido=str_replace("_USUARIO_",$usuario["usuario"],$Contenido);
				$Contenido=str_replace("_PASSWORD_",$usuario["contrasenia"],$Contenido);
				$email_cuerpo=$Contenido."</body></html>";
			}
		}
		@mail($correo,"Teresita.com.mx >> Usuario y Contrase�a",$email_cuerpo,'MIME-Version: 1.0' . "\r\n".'Content-type: text/html; charset=iso-8859-1'."\r\n")
		?>
		<table border="0" align="center">
			<tr>
				<td>
					Recibir&aacute; en su correo electr&oacute;nico su usuario y contrase&ntilde;a
				</td>
			</tr>
		</table>
		<?php
	}
	else
	{
		?>
		<form name="formulario" action="olvido_pass.php" method="post" onsubmit="return DataValidation();">
<table border="0" align="center" cellpadding="4" cellspacing="4">
			<tr>
			  <td colspan="2" height="100">&nbsp;</td>
	  </tr>
			<tr>
				<td colspan="2">
					No se ha encontrado la direcci&oacute;n de correo electr&oacute;nico ingresada
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;

				</td>
			</tr>
			<tr>
				<td>Correo</td>
				<td>
					<input type="text" name="correo" size="50" maxlength="250" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="image" src="Imagenes/btn_enviar.jpg" style="border-width:1px;" />
				</td>
			</tr>
		</table>
  </form>
		<?php
	}
}
?>
<!--Finaliza el cuerpo del html e Inicia el piede p�gina-->
<!--HAY DOS SENTENCIAS, UNA QUE QUITA EL LINK RECU PASS CUANDO TE LOGEAS, LA SEGUNDA MUESTRA EN PIE EL N�MERO DE ARCHIVOS QUE ESTAN BLOQUEADOS POR EL USUARIO LOGEADO-->
<div class='push'></div>
</div>
<div class='footer' style='background-color:#009ee0'>
          <p><table width='100%' height='28' border='0' bgcolor='#009ee0'>
            <tr>
              <td width='24%' align='right'>&nbsp;</td>
              <td width='60%' align='center'>&nbsp;</td>
              <td width='16%' align='center'>&nbsp;</td>
            </tr>
            </table></p>
</div>
<!--Finaliza piede p�gina-->
</body>
</html>
<?php
mysqli_close($con);
?>
