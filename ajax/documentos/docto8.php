<?php
session_start();
include("../../apoyo.php");
$tabla=CTabla("docto8");

$accion=PostString("accion").Get("accion");
$documento=PostString("documento").Get("documento");
$consecutivo=PostString("consecutivo").Get("consecutivo");
if($accion!="")
{
	if($accion=="inserta")
	{
	
	}
	else if($accion=="actualiza")
	{
	
	}
	else if($accion=="elimina")
	{
	
	}	
	else if($accion=="get_xml")
	{	
		if($documento=="")
		{
			$regs=$tabla->select("*",'1=1',"posicion");
		}
		else
		{
			if($consecutivo=="")
			{
				$regs=$tabla->select("*","id_documento='$documento'","posicion");
			}
			else
			{
				$regs=$tabla->select("*","id_documento='$documento' and consecutivo='$consecutivo'","posicion");
			}
		}	
		header('Content-type: text/xml');
		echo '<?xml version="1.0" encoding="iso-8859-1"?>';
		echo "\n".'<!DOCTYPE raiz SYSTEM "documento2.dtd">';
		echo "\n".'<raiz>';
		foreach($regs as $reg)
		{
			echo "\n\t<documento>";
			foreach ($reg as $key=>$val)
			{
				echo "\n\t\t".'<'.($key).'>'.($val).'</'.($key).'>';
			}
			echo "\n\t</documento>";
		}
		echo "\n".'</raiz>';
	}
	else if($accion=="get_JSON")
	{
		if($documento=="")
		{
		
		}
		else
		{
			if($consecutivo=="")
			{
			
			}
			else
			{
			
			}
		}	
		echo '[';
		$aux="";
		foreach($regs as $reg)
		{
			$aux .= "\n\t{";
			$cad="";
			foreach ($reg as $key=>$val)
			{
				$cad .= "\n\t\t".'"'.htmlentities($key).'":"'.htmlentities($val).'",';
			}
			$aux .= substr($cad,0,strlen($cad)-1)."\n\t},";
		}
		echo substr($aux,0,strlen($aux)-1)."\n".']';
	}
}
?>