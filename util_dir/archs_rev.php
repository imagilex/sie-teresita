<?php

include("directorio.php");

function input($var)
{
    if(isset($_POST[$var]) && $_POST[$var]!="")
        return $_POST[$var];
    else if(isset($_GET[$var]) && $_GET[$var]!="")
        return $_GET[$var];
    return "";
}

$d=new directorio(input("directorio"));
$cad=$d->JSON_archivos("Alfabetico",false,input("pattern"));
$archs=explode(",",substr($cad,1,strlen($cad)-1));
for($x=0;$x<count($archs);$x++)
{
    $archivo=substr($archs[$x],1,strlen($arch[$x])-1);
    $info=pathinfo($archivo);
    $ant=input("directorio")."/".$archivo;
    $nvo=input("directorio")."/".$info["filename"]."_rev.".$info["extension"];
    if(strpos($archivo,"_rev")===false)
        rename($ant,$nvo);
}
?>
