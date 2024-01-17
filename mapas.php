<?php

session_start();

include "apoyo.php";

include_once("u_db/data_base.php");

$db=new data_base(MAIN_DB->usr, MAIN_DB->host, MAIN_DB->pass, MAIN_DB->bd);

include_once("u_mapa/mapa.php");

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
<title>Teresita</title>
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
<table bgcolor='f2f2f2' width='100%' height='40' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
    <td width="35%"><table border='0' align='left' cellpadding='0' cellspacing='0'>
      <tr>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td><img src="Imagenes/menu/home.png" alt="Inicio" title="Inicio" onclick="javascript: /*window.close()*/location.href='entrada.php';" /></td>
        <td><img src='Imagenes/menu/varilla.gif' width='14' height='1'></td>
        <td></td>
      </tr>
    </table></td>
    <td width="31%"><div align='center'>Procesos</div></td>
    <td width="34%"><table border='0' align='right' cellpadding='0' cellspacing='0'>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><img src="Imagenes/Btn_salir.png" onclick="javascript: /*window.close()*/location.href='index.php';" /></td>
        </tr>
    </table></td>
  </tr>
</table>
<?php
//B_reportes();
BH_Ayuda('','');

$id_mapa = Get_Vars_Helper::getPostVar("id_mapa");
$docto = Get_Vars_Helper::getPostVar("docto");

$superiores= array();
$inferiores="";

if($regs=consulta_directa("select id_mapa, nombre from mapa inner join mapa_submapa on id_mapa = mapa_hijo and mapa_padre='$id_mapa' order by posicion, nombre"))
{
    $inferiores='<optgroup label="Inferior">';
    while($reg=mysqli_fetch_array($regs))
    {
        $inferiores.='<option value="'.$reg["id_mapa"].'">'.$reg["nombre"].'</option>';
    }
    $inferiores.='</optgroup>';
}

?>
<table border="0" align="left" width="100%">
<tr><td align="center">
<?php

if($id_mapa=="") $id_mapa="10";

$cont=new mapa($id_mapa);

$cont->print_mapa("mapas.php?",'950px','760px',$docto);
?>
</td></tr></table>
</body>
</html>
