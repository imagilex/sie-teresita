<?php
session_start();
include("../../apoyo.php");
$tabla = CTabla("docto4");
$accion = Get_Vars_Helper::getPGVar("accion");
$documento = Get_Vars_Helper::getPGVar("documento");
$consecutivo = Get_Vars_Helper::getPGVar("consecutivo");
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
