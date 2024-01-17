<?php

session_start();

include "apoyo.php";

$Con=Conectar();

if(!isset($_SESSION["id_usr"])) $_SESSION["id_usr"]="0";

$lista = Get_Vars_Helper::getPGVar("lista");
$sublista = "";
$accion = Get_Vars_Helper::getPGVar("accion");
$ql = stripslashes(Get_Vars_Helper::getPGVar("ql"));
$qsl = stripslashes(Get_Vars_Helper::getPGVar("qsl"));
$actlist = Get_Vars_Helper::getPGVar("actlist");
$actsublist = Get_Vars_Helper::getPGVar("actsublist");
$pantalla = Get_Vars_Helper::getPGVar("pantalla");
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
<script language="javascript">
    function Open(sublista)
    {
        if(document.datasoft.accion.value!="") var accion = 'accion=' + document.datasoft.accion.value;
        else var accion = "";
        if(document.datasoft.pantalla.value!="") var pantalla='&pantalla=' + document.datasoft.pantalla.value;
        else var pantalla = "";
        if(document.datasoft.ql.value!="") var ql = '&ql=' + document.datasoft.ql.value;
        else var ql="";
        if(document.datasoft.actlist.value!="") var actlist = '&actlist=' + document.datasoft.actlist.value;
        else var actlist = "";
        if (document.datasoft.qsl.value!="") var qsl = '&qsl=' + document.datasoft.qsl.value;
        else var qsl="";
        if(document.datasoft.actsublist.value!="") var actsublist = '&actsublist=' + document.datasoft.actsublist.value;
        else var actsublist="";
        if(document.datasoft.lista.value!="") var lista = '&lista=' + document.datasoft.lista.value;
        else var lista="";
        var vsublista = '&sublista=' + sublista;
        var query_string = accion + ql + qsl + actlist + actsublist + lista + vsublista + pantalla;
        window.parent.location="catalogos_01.php?"+query_string;
    }
</script>
<script type="text/JavaScript" language="javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>

<?php
if($actsublist!="") $aux="select lista_nivel from lista where lista = '$actsublist'";
else $aux="select lista_nivel from lista where lista = '$lista'";
$nivel_lista=@mysqli_fetch_array(consulta_directa($aux));
$cad_nombres="";
if($nivel_lista["lista_nivel"]=="C" || $nivel_lista["lista_nivel"]=="L")
{
    if($regs=consulta_directa($qsl))
    {
        while($reg=mysqli_fetch_array($regs))
        {
            $cad_nombres .=  "'Listas/Imagen/L".$reg["lista"].".jpg',";
        }
        $cad_nombres = substr($cad_nombres,0,strlen($cad_nombres)-1);
    }
}
?>

<body onload="MM_preloadImages(<?php echo $cad_nombres;?>)">
<div>
    <form name="datasoft" method="get" action="catalogos_01_sublist.php">
        <input type="hidden" name="accion" value="mov_sublist" />
        <input type="hidden" name="ql" value="<?php echo $ql; ?>" />
        <input type="hidden" name="qsl" value="<?php echo $qsl; ?>" />
        <input type="hidden" name="actlist" value="<?php echo $lista; ?>" />
        <input type="hidden" name="actsublist" value="<?php echo $sublista; ?>" />
        <input type="hidden" name="lista" value="<?php echo $lista; ?>" />
        <input type="hidden" name="sublista" value="<?php echo $sublista; ?>" />
        <input type="hidden" name="pantalla" value="<?php echo $pantalla; ?>" />
    </form>
</div>
<?php
if($nivel_lista["lista_nivel"]=="C" || $nivel_lista["lista_nivel"]=="L")
{
?>
<div>
    <table border="0" align="left">
        <tr>
            <td align="center" bgcolor="#DDDDDD" style="color:777777;">
                Nombre: <br />
                <input type="text" value="" name="nomb_compl" id="comb_compl" maxlength="250" size="25" disabled="disabled" />
            </td>
            <td rowspan="2">
                <img src="Listas/Imagen/L1.jpg" name="Image1" border="0" id="Image1" />
            </td>
        </tr>
        <tr>
            <td>
                <div style="overflow:auto; width:200px; height:300px;">
                    <table border="0" align="center">
                        <?php
                        if($regs=consulta_directa($qsl))
                        {
                            while($reg=mysqli_fetch_array($regs))
                            {
                                ?>
                                <tr>
                                    <td width="195" onmousemove="javascript: this.style.background='999999';" onmouseout="javascript: this.style.background='FFFFFF'; MM_swapImgRestore();" onclick="Open('<?php echo $reg["lista"]; ?>')" onmouseover="MM_swapImage('Image1','','Listas/Imagen/L<?php echo $reg["lista"]; ?>.jpg',1)">
                                        <?php echo $reg["nombre"]; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>
<?php
}
else if($nivel_lista["lista_nivel"]=="A")
{
    if($regs=consulta_directa("SELECT concat(mid(producto, 6), '-', mid(valor_atributo, 8)) as img, mid(producto, 6) as producto, valor_atributo FROM lista_atributo where lista='$actsublist' order by producto, valor_atributo"))
    {
        ?>
        <form name="dataset" method="post">
        <table border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <table class="clase_tabla" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="20">&nbsp;</td>
                            <td class="encabezado_tabla_reporte" width="200" valign="middle" style="vertical-align:middle;">Imagen</td>
                            <td class="encabezado_tabla_reporte" width="140">Producto<br /><input type="text" maxlength="250" value="" size="15" /></td>
                            <td class="encabezado_tabla_reporte" width="140">Descripción<br /><input type="text" maxlength="250" value="" size="15" /></td>
                            <td class="encabezado_tabla_reporte" width="140">Color<br /><input type="text" maxlength="250" value="" size="15" /></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="height:350px; overflow:auto;">
                    <table class="clase_tabla" cellpadding="0" cellspacing="0">
                    <?php
                    $x=0;
                    while($reg=mysqli_fetch_array($regs))
                    {
                        if($pantalla=="Códigos")
                        {
                            $cad_sql = "select concat(nombre,'&nbsp;') as nombre, concat(producto.descripcion,'&nbsp;') as descripcion, imagen, concat(codigos_generales.descripcion,'&nbsp;') as color, mid(imagen,1,5) as prod, mid(imagen,7,3) as val from producto inner join codigos_generales on valor = '".substr($reg["img"],6)."' and campo='color' where clave='".$reg["producto"]."'";
                        }
                        else
                        {
                            $cad_sql = "select clave, concat(mid(clave,6),'&nbsp;') as nombre, concat(producto.descripcion,'&nbsp;') as descripcion, imagen, concat(codigos_generales.descripcion,'&nbsp;') as color, mid(imagen,1,5) as prod, mid(imagen,7,3) as val from producto inner join codigos_generales on valor = '".substr($reg["img"],6)."' and campo='color' where clave='".$reg["producto"]."'";
                        }
                        //echo $cad_sql;
                        if($info_db=consulta_directa($cad_sql))
                        {
                            $info=mysqli_fetch_array($info_db);
                            $x++;
                            ?>
                            <tr>
                                <td class="cuerpo_tabla_reporte_ckb" width="20"><input type="checkbox" name="Registro_<?php echo $x; ?>" id="Registro_<?php echo $x; ?>" value="<?php echo $reg["producto"]." ".$reg["valor_atributo"]; ?>" /></td>
                                <td align="center" valign="middle" width="200" class="cuerpo_tabla_reporte"><img src="Listas/Productos/<?php echo $reg["producto"]; ?>/<?php echo /*$info["imagen"]*/$reg["producto"]."-".substr($reg["valor_atributo"],7); ?>.jpg" width="175" height="50" /></td>
                                <td align="center" valign="middle" width="140" class="cuerpo_tabla_reporte"><?php echo $info["nombre"]; ?>&nbsp;</td>
                                <td align="center" valign="middle" width="140" class="cuerpo_tabla_reporte"><?php echo $info["descripcion"]; ?>&nbsp;</td>
                                <td align="center" valign="middle" width="140" class="cuerpo_tabla_reporte"><?php echo $info["color"]; ?>&nbsp;</td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </table>
                    </div>
                </td>
            </tr>
        </table>
        <input type="hidden" name="total_registros" id="total_registros" value="<?php echo $x; ?>" />
        </form>
        <?php
    }
    ErrorMySQLAlert($Con);
}
else if($nivel_lista["lista_nivel"]=="P")
{
    $aux="select producto from lista_producto where lista='$actsublist' order by posicion";
    if($regs=consulta_directa($aux))
    {
        $ones="";
        ?>
            <form name="dataset" method="post">
            <table border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <table class="clase_tabla" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="20">&nbsp;</td>
                                <td class="encabezado_tabla_reporte" width="200" valign="middle" style="vertical-align:middle;">Imagen</td>
                                <td class="encabezado_tabla_reporte" width="140">Producto<br /><input type="text" maxlength="250" value="" size="15" /></td>
                                <td class="encabezado_tabla_reporte" width="140">Descripción<br /><input type="text" maxlength="250" value="" size="15" /></td>
                                <td class="encabezado_tabla_reporte" width="140">Color<br /><input type="text" maxlength="250" value="" size="15" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="height:350px; overflow:auto;">
                        <table class="clase_tabla" cellpadding="0" cellspacing="0">
                            <?php
                            $x=0;
                            while($reg=mysqli_fetch_array($regs))
                            {
                                if($pantalla=="Códigos")
                                {
                                    $cad_sql = "select concat(nombre,'&nbsp;') as nombre, concat(producto.descripcion,' ') as descripcion, concat(codigos_generales.descripcion,' ') as color, producto_atributo.imagen as imagen, mid(producto_atributo.valor, 8, 3), producto_atributo.producto as pap, producto_atributo.valor as pav from producto inner join producto_atributo on producto.clave = producto_atributo.producto and producto_atributo.atributo = 1 and producto_atributo.estatus = 'L' inner join codigos_generales on codigos_generales.valor = mid(producto_atributo.valor, 8, 3) and campo='color' where clave='".$reg["producto"]."'";
                                }
                                else
                                {
                                    $cad_sql = "select concat(mid(clave,6),'&nbsp;') as nombre, concat(producto.descripcion,' ') as descripcion, concat(codigos_generales.descripcion,' ') as color, producto_atributo.imagen as imagen, mid(producto_atributo.valor, 8, 3), producto_atributo.producto as pap, producto_atributo.valor as pav from producto inner join producto_atributo on producto.clave = producto_atributo.producto and producto_atributo.atributo = 1 and producto_atributo.estatus = 'L' inner join codigos_generales on codigos_generales.valor = mid(producto_atributo.valor, 8, 3) and campo='color' where clave='".$reg["producto"]."'";
                                }
                                //echo $cad_sql;
                                $ones .=  "<br />union <br />$cad_sql";
                                if($info_db=consulta_directa($cad_sql))
                                {
                                    while($info=mysqli_fetch_array($info_db))
                                    {
                                        $x++;
                                        ?>
                                        <tr>
                                            <td class="cuerpo_tabla_reporte_ckb" width="20"><input type="checkbox" name="Registro_<?php echo $x; ?>" id="Registro_<?php echo $x; ?>" value="<?php echo $info["pap"]." ".$info["pav"] ?>" /></td>
                                            <td align="center" valign="middle" width="200" class="cuerpo_tabla_reporte"><img src="Listas/Productos/<?php echo substr($info["imagen"],0,5)?>/<?php echo $info["imagen"]; ?>.jpg" width="175" height="50" /></td>
                                            <td align="center" valign="middle" width="140" class="cuerpo_tabla_reporte"><?php echo $info["nombre"]; ?>&nbsp;</td>
                                            <td align="center" valign="middle" width="140" class="cuerpo_tabla_reporte"><?php echo $info["descripcion"]; ?>&nbsp;</td>
                                            <td align="center" valign="middle" width="140" class="cuerpo_tabla_reporte"><?php echo $info["color"]; ?>&nbsp;</td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </table>
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="total_registros" id="total_registros" value="<?php echo $x; ?>" />
            </form>
            <?php
        $cad_nombres = substr($cad_nombres,0,strlen($cad_nombres)-1);
    }
    ErrorMySQLAlert($Con);
}
?>
</body>
</html>
