<?php

session_start();

include "apoyo.php";

$Con=Conectar();

//    $_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//    $_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]) )
{
    header("location: index.php?noCahce=".rand(0,32000));
    exit();
}

$id_seccion=$elemento="";

$datos = Get_Vars_Helper::getPostVar("datos");
if($datos!="")
{
    list($id_seccion,$elemento)=explode(" ",$datos);
}

    $tipo_contenido = Get_Vars_Helper::getPostVar("tipo_contenido");
    if($tipo_contenido=="archivo de texto")
    {
        if(isset($_FILES["valor"]["name"]) && $_FILES["valor"]["name"]!="")
        {
            //Eliminar el archivo anterior
            $query="select valor from seccion where id_seccion='$id_seccion' and elemento='$elemento'";
            if($Regs=consulta_directa($query))
            {
                $Reg=mysqli_fetch_array($Regs);
                $photo=$Dir."/Archivos_Secciones/".$Reg["valor"];
                if($Reg["valor"]!="" && file_exists($photo))
                    unlink($photo);
            }
            //Subir el archivo nuevo
            $photo=pathinfo($_FILES["valor"]["name"]);
            $foto=$Dir."/Archivos_Secciones/".$photo["basename"];
            move_uploaded_file($_FILES["valor"]["tmp_name"],$foto);
            $fotografia=basename($foto);
            consulta_directa("update seccion set valor='$fotografia' where id_seccion='$id_seccion' and elemento='$elemento'");
        }
    }
    else if($tipo_contenido=="imagen")
    {
        if(isset($_FILES["valor"]["name"]) && $_FILES["valor"]["name"]!="")
        {
            //Eliminar la imagen anterior
            $query="select valor from seccion where id_seccion='$id_seccion' and elemento='$elemento'";
            if($Regs=consulta_directa($query))
            {
                $Reg=mysqli_fetch_array($Regs);
                $photo=$Dir."/Archivos_Secciones/".$Reg["valor"];
                if($Reg["valor"]!="" && file_exists($photo))
                    unlink($photo);
            }
            //Subir la imagen nueva
            $photo=pathinfo($_FILES["valor"]["name"]);
            $foto=$Dir."/Archivos_Secciones/".$photo["basename"];
            move_uploaded_file($_FILES["valor"]["tmp_name"],$foto);
            $fotografia=basename($foto);
            consulta_directa("update seccion set valor='$fotografia' where id_seccion='$id_seccion' and elemento='$elemento'");
        }
    }
    else if($tipo_contenido=="texto")
    {
        if(Get_Vars_Helper::getPostVar("valor")!="")
            consulta_directa("update seccion set valor='".Get_Vars_Helper::getPostVar("valor")."' where id_seccion='$id_seccion' and elemento='$elemento'");
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MANAIZ</title>
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
<script language="javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" src="u_yui/container_core.js"></script>
<script language="javascript" src="u_yui/menu.js"></script>
<script type="text/javascript">YAHOO.util.Event.onContentReady("barra_menu", function () {var oMenuBar = new YAHOO.widget.MenuBar("barra_menu", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});YAHOO.util.Event.onContentReady("menu_opciones", function () {var oMenuBar = new YAHOO.widget.MenuBar("menu_opciones", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
</head>

<body>
<?php

BarraHerramientas();

?>
<div align="right">
    <form action="sistema.php" method="post" name="sist" style="padding:0px;">
        Secci&oacute;n:
        <select name="seccion" onchange="javascript: document.sist.submit();"><option value=""></option>
            <?php menu_items($_SESSION["tipo"],'0.4.51'); ?>
        </select>
    </form>
    <script language="javascript">
        document.sist.seccion.value=3;
    </script>
</div>
<?php
BH_Ayuda('0.4.51','3');
?>
<form method="post" action="configuracion_basica.php" enctype="multipart/form-data" name="DataConfig">
<table border="0" align="center">
    <tr>
        <td valign="top">Secciones:<br />
            <select name="datos" size="9" onchange="javascript: document.DataConfig.submit();">
                <?php
                if($secciones=consulta_directa("select distinct(id_seccion) as secc from seccion order by id_seccion"))
                {
                    while($seccion_actual=mysqli_fetch_array($secciones))
                    {
                        ?>
                        <optgroup label="<?php echo $seccion_actual["secc"]; ?>">
                        <?php
                        if($elementos=consulta_directa("select elemento, tipo, valor from seccion where id_seccion='".$seccion_actual["secc"]."'"))
                        {
                            while($elemento_actual=mysqli_fetch_array($elementos))
                            {
                                ?>
                                <option value="<?php echo $seccion_actual["secc"]." ".$elemento_actual["elemento"]?>"><?php echo $elemento_actual["elemento"]?></option>
                                <?php
                            }
                        }
                        ?>
                        </optgroup>
                        <?php
                    }
                }
                ?>
            </select>
            <?php
            if($datos!="")
            {
                ?>
                <script language="javascript">
                    document.DataConfig.datos.value="<?php echo $datos; ?>";
                </script>
                <?php
            }
            ?>
        </td>
        <td>
            <table border="0" style="table-layout:fixed;" cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td style="width:3px; height:62px; background-image:url(Archivos_Secciones/win/11.PNG);"></td>
                    <td style="width:222px; height:62px; background-image:url(Archivos_Secciones/win/12.PNG);"></td>
                    <td style="width:275px; height:62px; background-image:url(Archivos_Secciones/win/13.PNG);"></td>
                    <td style="width:57px; height:62px; background-image:url(Archivos_Secciones/win/14.PNG);"></td>
                    <td style="width:3px; height:62px; background-image:url(Archivos_Secciones/win/15.PNG);"></td>
                </tr>
                <tr>
                    <td style="height:100px; background-image:url(Archivos_Secciones/win/21.PNG);"></td>
                    <td colspan="3" style="height:100px; padding:15px;" valign="middle" align="center"><?php
                        $tipo_elem="";
                        $valor="";
                        if($id_seccion!="" && $elemento!="")
                        {
                            $Reg=mysqli_fetch_array(consulta_directa("select tipo,valor from seccion where id_seccion='$id_seccion' and elemento='$elemento'"));
                            $tipo_elem=$Reg["tipo"];
                            $valor=$Reg["valor"];
                            if($tipo_elem=="texto")
                            {
                                echo "<center>$valor</center>";
                            }
                            else if($tipo_elem=="archivo de texto")
                            {
                                echo "<div align=\"left\">";
                                $Arch_n=addslashes($Dir)."/Archivos_Secciones/".$valor;
                                if($valor!="")
                                    MostrarArchivo($Arch_n);
                                echo "</div>";
                            }
                            else if($tipo_elem=="imagen")
                            {
                                echo "<img name=\"imagen\" id=\"imagen\" src=\"Archivos_Secciones/".$valor."\" />";
                            }
                        }
                    ?></td>
                    <td style="height:100px; background-image:url(Archivos_Secciones/win/25.PNG);"></td>
                </tr>
                <tr>
                    <td style="height:22px; background-image:url(Archivos_Secciones/win/31.PNG);"></td>
                    <td style="height:22px; background-image:url(Archivos_Secciones/win/32.PNG);"></td>
                    <td style="height:22px; background-image:url(Archivos_Secciones/win/33.PNG);"></td>
                    <td style="height:22px; background-image:url(Archivos_Secciones/win/34.PNG);"></td>
                    <td style="height:22px; background-image:url(Archivos_Secciones/win/35.PNG);"></td>
                </tr>
                <tr>
                    <td align="center" colspan="4">
                        <?php
                            if($id_seccion!="" && $elemento!="")
                            {
                                echo $tipo_elem.": "
                                ?>
                                <input type="hidden" name="tipo_contenido" value="<?php echo $tipo_elem; ?>" />
                                <?php
                                if($tipo_elem=="archivo de texto")
                                {
                                    ?>
                                    <input type="file" name="valor" onchange="javascript: document.DataConfig.submit();" style="border-width:1px;" />
                                    <?php
                                }
                                else if($tipo_elem=="imagen")
                                {
                                    ?>
                                    <input type="file" name="valor" onchange="javascript: document.DataConfig.submit();" />
                                    <?php
                                }
                                else if($tipo_elem=="texto")
                                {
                                    ?>
                                    <input type="text" name="valor" size="50" maxlength="250" onchange="javascript: document.DataConfig.submit();" />
                                    <?php
                                }
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</form>
</body>
</html>
