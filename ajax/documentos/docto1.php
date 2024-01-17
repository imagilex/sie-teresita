<?php
session_start();
include("../../apoyo.php");
$tabla = CTabla("docto1");
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
            $regs=$tabla->select("*",'1=1',"id_documento, consecutivo, posicion");
        }
        else
        {
            if($consecutivo=="")
            {
                $regs=$tabla->select("*","id_documento='$documento'","consecutivo, posicion");
            }
            else
            {
                $regs=$tabla->select("*","id_documento='$documento' and consecutivo='$consecutivo'","posicion");
            }
        }
        header('Content-type: text/xml');
        echo '<?xml version="1.0" encoding="iso-8859-1"?>';
        echo "\n".'<!DOCTYPE raiz SYSTEM "documento1.dtd">';
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
            $regs=$tabla->select("*",'1=1',"id_documento, consecutivo, posicion");
        }
        else
        {
            if($consecutivo=="")
            {
                $regs=$tabla->select("*","id_documento='$documento'","consecutivo, posicion");
            }
            else
            {
                $regs=$tabla->select("*","id_documento='$documento' and consecutivo='$consecutivo'","posicion");
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
