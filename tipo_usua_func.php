<?php

session_start();

include "apoyo.php";

$Con=Conectar();

//    $_SESSION["tipo"]=0 --> Usuario tipo ADMINISTRADOR
//    $_SESSION["tipo"]=1 --> Usuario tipo CONSULTA

if(!isset($_SESSION["tipo"]))
{
    header("location: index.php?noCache=".rand(0,32000));
    exit();
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
<style type="text/css">
    ul
    {
        list-style-type: none;
    }
</style>
</head>

<body>
<?php

BarraHerramientas();

?>
<div align="right">
    <form action="sistema.php" method="post" name="sist">
        Secci&oacute;n:
        <select name="seccion" onchange="javascript: document.sist.submit();"><option value=""></option>
            <?php menu_items($_SESSION["tipo"],'0.4.51'); ?>
        </select>
    </form>
    <script language="javascript">
        document.sist.seccion.value=1;
    </script>
</div>
<div align="right">
    <form action="seguridad.php" method="post" name="seg">
        Acci&oacute;n:
        <select name="accion" onchange="javascript: document.seg.submit();"><option value=""></option>
            <?php menu_items($_SESSION["tipo"],'0.4.51.1'); ?>
        </select>
    </form>
    <script language="javascript">
        document.seg.accion.value="3";
    </script>
</div>
<?php

BH_Ayuda('0.4.51.1','3');
$tipo_usr = Get_Vars_Helper::getPostVar("tipo_usr");
if($tipo_usr!="" && Get_Vars_Helper::getPostVar("sincambios")!='yes')
{
    $total=intval(Get_Vars_Helper::getPostVar("total"));
    if($total>0)
        consulta_directa("delete from tipo_usuario_funcion where tipo_usuario='$tipo_usr'");
    for($x=1;$x<=$total;$x++)
    {
        $dato = Get_Vars_Helper::getPostVar("Reg$x");
        if($dato!="")
            consulta_directa("insert into tipo_usuario_funcion (tipo_usuario, funcion) values ('$tipo_usr', '$dato')");
    }
}
?>
<form method="post" action="tipo_usua_func.php" name="tipo_opcion">
<input type="hidden" name="sincambios" value="" />
    <table border="0" align="center">
        <tr>
            <td align="left">
                Tipo de usuario:
                <select name="tipo_usr" onchange="javascript: document.tipo_opcion.sincambios.value='yes';document.tipo_opcion.submit();"><option value=""></option>
                    <?php
                    echo CboCG("tipo_usuario");
                    ?>
                </select>
                <script language="javascript">
                    document.tipo_opcion.tipo_usr.value="<?php echo $tipo_usr; ?>";
                </script>
            </td>
            <td align="right">
                <input type="submit" value="Guardar" class="btn_normal" />
            </td>
        </tr>
        <?php
        if($funciones=consulta_directa("select valor,descripcion from codigos_generales where campo='funcion' order by posicion"))
        {
            $x=0;
            while($func=mysqli_fetch_array($funciones))
            {
                $x++;
                $cuantos=mysqli_fetch_array(consulta_directa("select count(*) as n from tipo_usuario_funcion where tipo_usuario='$tipo_usr' and funcion='".$func["valor"]."'"));
                if(intval($cuantos["n"])>0)
                    $chec=" checked='checked'";
                else
                    $chec="";
                ?>
                <tr>
                    <td colspan="2">
                        <label><input type="checkbox" name="Reg<?php echo $x; ?>" value="<?php echo $func["valor"]; ?>"<?php echo $chec; ?> /> <?php echo $func["descripcion"]; ?></label>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
<input type="hidden" name="total" value="<?php echo $x?>" />
</form>
</body>
</html>
