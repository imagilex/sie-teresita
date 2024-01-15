<?php

session_start();

include("apoyo.php");

$Con=Conectar();

//	$_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//	$_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]) )
{
	header("location: index.php?noCahce=".rand(0,32000));
	exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MANAIZ</title>
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
	function Inicializar()
	{
		for(x=1;x<=10;x++)
		{
			if(Navegador()=='IE')
			{
				eval("document.getElementById('reg"+x+"').style.display='none'");
			}
			else if(Navegador()=='FIREFOX10' || Navegador()=='FIREFOX15' || Navegador()=='OTRO')
			{
				eval("document.getElementById('reg"+x+"').style.visibility='hidden'");
			}
		}
	}
	function Anexar()
	{
		var anexado=0;
		for(x=1;x<=10;x++)
		{
			if(Navegador()=='IE')
			{
				if(eval("document.getElementById('reg"+x+"').style.display")=='none')
				{
					eval("document.getElementById('reg"+x+"').style.display='block'");
					anexado++;
				}
				else if(eval("document.getElementById('Descrip"+x+"').value")=="" || eval("document.getElementById('Clave"+x+"').value")=="")
				{
					alert("Debe llenar completamente el último código general agregado");
					anexado++;
				}
			}
			else if(Navegador()=='FIREFOX10' || Navegador()=='FIREFOX15' || Navegador()=='OTRO')
			{
				if(eval("document.getElementById('reg"+x+"').style.visibility")=='hidden')
				{
					eval("document.getElementById('reg"+x+"').style.visibility='visible'");
					anexado++;
				}
				else if(eval("document.getElementById('Descrip"+x+"').value")=="" || eval("document.getElementById('Clave"+x+"').value")=="")
				{
					alert("Debe llenar completamente el último código general agregado");
					anexado++;
				}
			}
			if(anexado) break;
		}
		if(!anexado)
			alert("Sólo es posible agregar hasta 10 elementos");
	}
	function Borrar()
	{
	}
	function DataValidation()
	{
		return true;
	}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
</head>

<body onload="Inicializar()">

<?php
BarraHerramientas();

$Campo=PostString("campo");
$total=intval(PostString("total"));

if($total>0 && $Campo!="")
	for($x=1;$x<=$total;$x++)
	{
		if(PostString("cambio$x")=="S")
		{
			$id=PostString("id$x");
			$desc=PostString("Des$x");
			$val=PostString("Val$x");
			$val=($val!="")?($val):($id);
			$pos=PostString("pos$x");
			$otr=PostString("otr$x");
			$estatus=PostString("estatus$x");
			consulta_directa($Con, "update codigos_generales set descripcion='$desc',valor='$val',posicion='$pos',otro='$otr',estatus='$estatus' where campo='$Campo' and valor='$id'");
		}
	}

for($x=1;$x<=10;$x++)
{
	$id=PostString("Clave$x");
	$desc=PostString("Descrip$x");
	$posi=PostString("posi$x");
	$otro=PostString("otro$x");
	$estatus=PostString("estat$x");
	if($id!="" && $desc!="")
	{
		$registros_repetidos=mysqli_fetch_array(consulta_directa($Con, "select count(*) as n from codigos_generales where campo like '$Campo' and descripcion = '$desc'"));
	if(intval($registros_repetidos["n"])==0)
		consulta_directa($Con, "insert into codigos_generales (valor,campo,descripcion,estatus,posicion,otro) values ('$id','$Campo','$desc','$estatus','$posi','$otro')");
	}
}


$altas_usr=false;
if(PostString("btnAltaUsuarios")=="Altas Usuarios")
{
	$altas_usr=true;
}
$query="select * from codigos_generales where campo='$Campo'";
$clave_b=PostString("clave_b");
$desc_b=PostString("descr_b");
$pos_b=PostString("pos_b");
$otro_b=PostString("otro_b");
$estatus_b=PostString("estatus_b");
if($clave_b!="")
	$query=$query." and valor = '$clave_b'";
if($desc_b!="")
	$query=$query." and descripcion like '%$desc_b%'";
if($pos_b!="")
	$query=$query." and posicion_b='$pos_b'";
if($otro_b!="")
	$query=$query." and otro='$otro'";
if($estatus_b!="")
	$query=$query." and estatus='$estatus_b'";
$query=$query." order by length(valor),valor,posicion";
?>
<div align="right">
	<form action="sistema.php" method="post" name="sist" style="padding:0px;">
		Secci&oacute;n:
		<select name="seccion" onchange="javascript: document.sist.submit();"><option value=""></option>
			<?php menu_items($_SESSION["tipo"],'0.4.51'); ?>
		</select>
	</form>
	<script language="javascript">
		document.sist.seccion.value=2;
	</script>
</div>
<?php
BH_Ayuda('0.4.51','2');
?>
<form name="DatosCG" action="codigos_generales.php" method="post">
	<table align="center">
		<tr><td colspan="6">
		</td></tr>
		<tr><td colspan="6">&nbsp;<input type="hidden" name="altausr" value="<?php echo (($altas_usr)?("S"):("N")); ?>" /></td></tr>
		<tr><td align="center" colspan="6">
	C&oacute;digo general:
	<select name="campo" onchange="document.DatosCG.submit();"><option></option>
	<?php
		if($Regs=consulta_directa($Con, "select distinct(campo) as campo from codigos_generales order by campo"))
			while($Reg=mysqli_fetch_array($Regs))
				echo "<option value=\"".$Reg["campo"]."\">".$Reg["campo"]."</option>";
	?>
	</select>
	<input type="button" value="Anexar" name="btnAnexar" onclick="Anexar()" class="btn_normal" />
	<input type="button" value="Borrar" name="btnBorrar" onclick="Borrar()" class="btn_normal" />
	<input type="submit" value="Guardar" name="btnGuardar" class="btn_normal" />
		</td>
	</tr>
	<tr><td colspan="6">&nbsp;</td></tr>
	<tr>
		<th align="left">Clave:</th><th align="left">Descripci&oacute;n:</th><th>Posici&oacute;n</th><th>Otros</th><th>Estatus</th><th></th>
	</tr>
	<tr>
		<th align="right"><input type="text" name="clave_b" maxlength="4" size="4" style="background-color:#CCCCCC" value="<?php echo $clave_b; ?>" /></th>
		<th><input type="text" name="descr_b" maxlength="250" size="50" style="background-color:#CCCCCC" value="<?php echo $desc_b; ?>" /></th>
		<th><input type="text" name="pos_b" maxlength="4" size="4" style="background-color:#CCCCCC" value="<?php echo $pos_b; ?>" /></th>
		<th><input type="text" name="otro_b" maxlength="10" size="4" style="background-color:#CCCCCC" value="<?php echo $otro_b; ?>" /></th>
		<th><input type="text" name="estatus_b" maxlength="4" size="4" style="background-color:#CCCCCC" value="<?php echo $estatus_b; ?>" /></th>
		<th><input type="submit" value="Buscar" class="btn_normal" />
	</tr>
	<?php
		if($Campo!="")
		{
			$x=0;
			if($Regs=consulta_directa($Con, $query))
				while($Reg=mysqli_fetch_array($Regs))
				{
				$x++
				?>
				<tr>
					<td align="right">
						<input type="checkbox" value="S" name="ckb<?php echo $x; ?>" onchange="javascript: document.DatosCG.cambio<?php echo $x; ?>.value='S';" />
						<input type="text" size="4" maxlength="4" name="Val<?php echo $x; ?>" value="<?php echo $Reg["valor"]; ?>" disabled="disabled" />
						<input type="hidden" name="id<?php echo $x; ?>" value="<?php echo $Reg["valor"]; ?>" />
						<input type="hidden" name="cambio<?php echo $x; ?>" value="" />
					</td>
					<td><input type="text" size="50" maxlength="250" name="Des<?php echo $x; ?>" value="<?php echo $Reg["descripcion"]; ?>" onchange="javascript: document.DatosCG.cambio<?php echo $x; ?>.value='S';" /></td>
					<td align="center"><input type="text" maxlength="4" size="4" name="pos<?php echo $x; ?>" value="<?php echo $Reg["posicion"]; ?>" onchange="javascript: document.DatosCG.cambio<?php echo $x; ?>.value='S';" /></td>
					<td align="center"><input type="text" maxlength="10" size="4" name="otr<?php echo $x; ?>" value="<?php echo $Reg["otro"]; ?>" onchange="javascript: document.DatosCG.cambio<?php echo $x; ?>.value='S';" /></td>
					<td align="center"><input type="text" name="<?php echo "estatus$x"; ?>" maxlength="4" size="4" value="<?php echo $Reg["estatus"]; ?>" onchange="javascript: document.DatosCG.cambio<?php echo $x; ?>.value='S';" /></td><td></td>
				</tr>
				<?php
				}
		}
	for($a=1;$a<=10;$a++)
	{
	?>
	<tr id="reg<?php echo $a; ?>" class="reg<?php echo $a; ?>">
		<td align="right"><input type="text" size="4" maxlength="4" name="Clave<?php echo $a; ?>" id="Clave<?php echo $a; ?>" value="" /></td>
		<td><input type="text" size="50" maxlength="150" name="Descrip<?php echo $a; ?>" id="Descrip<?php echo $a; ?>" value="" /></td>
		<td align="center"><input type="text" name="posi<?php echo $a; ?>" id="posi<?php echo $a; ?>" maxlength="4" size="4" value="" /></td>
		<td align="center"><input type="text" name="otro<?php echo $a; ?>" id="otro<?php echo $a; ?>" maxlength="10" size="4" value="" /></td>
		<td align="center"><input type="text" name="estat<?php echo $a; ?>" id="estat<?php echo $a; ?>" maxlength="4" size="4" value="" /></td><td></td>
	</tr>
	<?php
	}
	?>
</table>
<input type="hidden" name="total" value="<?php echo $x; ?>" />
<script language="javascript">
	document.DatosCG.campo.value="<?php echo $Campo; ?>";
</script>
</form>
</body>
</html>
<?php
mysqli_close($Con);
unset($Campo, $Con, $Reg, $Regs, $x);
?>
