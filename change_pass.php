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

$newp = Get_Vars_Helper::getPGVar("newp");
if($newp!="")
{
    $query="update usuario set password = '$newp' where clave = '".$_SESSION["id_usr"]."' and persona = '".$_SESSION["id_persona_usr"]."'";
    consulta_directa($query);
}

$actual=@mysqli_fetch_array(consulta_directa("select password from usuario where clave = '".$_SESSION["id_usr"]."' and persona = '".$_SESSION["id_persona_usr"]."'"));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita</title>
<link rel="stylesheet" type="text/css" href="u_yui/menu.css" />
<script language="javascript" src="u_yui/yahoo-dom-event.js"></script>
<script language="javascript" src="u_yui/container_core.js"></script>
<script language="javascript" src="u_yui/menu.js"></script>
<script type="text/javascript">YAHOO.util.Event.onContentReady("barra_menu", function () {var oMenuBar = new YAHOO.widget.MenuBar("barra_menu", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});YAHOO.util.Event.onContentReady("menu_opciones", function () {var oMenuBar = new YAHOO.widget.MenuBar("menu_opciones", {autosubmenudisplay: true,hidedelay: 5000,lazyload: true });oMenuBar.render();});</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="apoyo_js.js"></script>
<script language="javascript" src="prototype.js"></script>
<script language="javascript">
    function evaluacion()
    {
        if($('newp').value=="<?php echo $actual["password"]; ?>")
        {
            alert("Debe ingresar una contraseña diferente a la actual");
            return false;
        }
        else if($('newp').value=="")
        {
            alert("Debe ingresar una contraseña");
            return false;
        }
        else if($('newp').value!=$('confirm').value)
        {
            alert("La contraseña y su confirmacion no coinciden");
            return false;
        }
        return true
    }
</script>
</head>

<body>
<?php

B_reportes();

?>
<?php
BH_Ayuda('','');
?>
<form method="post" action="change_pass.php" name="formulario" onsubmit="return evaluacion();">
<table border="0" align="center">
    <tr>
        <td align="right">Nueva contrase&ntilde;a:</td>
        <td><input name="newp" id="newp" type="password" maxlength="250" size="30" /></td>
    </tr>
    <tr>
        <td align="right">Confirmar Contrase&ntilde;a:</td>
        <td><input name="confirm" id="confirm" type="password" maxlength="250" size="30" /></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input type="submit" value="Aceptar" class="btn_normal" />
        </td>
    </tr>
</table>
</form>
</body>
</html>
