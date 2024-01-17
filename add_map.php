<?php
include_once("apoyo.php");
include_once("u_db/data_base.php");
include_once("u_mapa/mapa.php");
$db = new data_base("root","localhost","pass","mapas");
$accion = Get_Vars_Helper::getPGVar("accion");
if($accion=="add_map"){
    $nombre = Get_Vars_Helper::getPostVar("nombre");
    $comentarios = Get_Vars_Helper::getPostVar("comentarios");
    $tipo = Get_Vars_Helper::getPostVar("tipo");
    $contenido = Get_Vars_Helper::getPostVar("contenido");
    $db->consulta("insert into mapa (nombre,comentarios,tipo,contenido) values ('$nombre','$comentarios','$tipo','$contenido')");
}else if($accion=="add_rel"){
    $mapa_padre = Get_Vars_Helper::getPostVar("mapa_padre");
    $mapa_hijo = Get_Vars_Helper::getPostVar("mapa_hijo");
    $figura = Get_Vars_Helper::getPostVar("figura");
    $coordenadas = Get_Vars_Helper::getPostVar("coordenadas");
    $posicion = Get_Vars_Helper::getPostVar("posicion");
    $preposicion = Get_Vars_Helper::getPostVar("preposicion");
    for($x=0;$x<@count($mapa_hijo);$x++){
        if($mapa_hijo[$x]!="" && ($figura[$x]!="" || $coordenadas[$x]!="" || $posicion[$x]!="" || $preposicion[$x]!="")){
            $query="insert into mapa_submapa (mapa_padre,mapa_hijo,figura,coordenadas,posicion,preposicion) values ('$mapa_padre','".$mapa_hijo[$x]."','".$figura[$x]."','".$coordenadas[$x]."','".$posicion[$x]."','".$preposicion[$x]."')";
            $db->consulta($query);
            $db->error(true);
        }
    }
}else if($accion=="add_docto"){
    $id_mapa = Get_Vars_Helper::getPostVar("id_mapa");
    $nombre_documento = Get_Vars_Helper::getPostVar("nombre_documento");
    $contenido = Get_Vars_Helper::getPostVar("contenido");
    $tipo_documento = Get_Vars_Helper::getPostVar("tipo_documento");
    for($x=0;$x<@count($nombre);$x++){
        if($nombre_documento[$x]!=""){
            $db->consulta("insert into mapa_documento (nombre_documento,contenido,tipo_documento,fecha) values ('".$nombre_documento[$x]."','".$contenido[$x]."','".$tipo_documento[$x]."',currdate())");
            $id_docto=@mysqli_fetch_array($db->consulta("select id_documento from mapa_documento where nombre='".$nombre_documento[$x]."' and contenido='".$contenido[$x]."' and tipo_documento='".$tipo_documento[$x]."' and fecha=currdate()"));
            $db->consulta("insert into mapa_doc_cont (id_mapa,id_documento,fecha) values ('$id_mapa','".$id_docto["id_documento"]."',currdate())");
        }
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Agregar Mapas - Hijos</title>
<style type="text/css">
form, table {padding:0px; margin:0px;}
body {font-family:Calibri, Arial; font-size:small;}
input, textarea, select {font-size:small; font-family:Arial, Helvetica, sans-serif;}
</style>
</head>
<body>
<form action="add_map.php" name="add_map" id="add_map" method="post"><input type="hidden" name="accion" value="add_map" />
    <fieldset>
        <legend>Agregar Mapa a la Base de Datos: </legend>
        <table border="0" align="center">
            <tr><td align="right">Nombre: </td><td align="left"><input type="text" maxlength="250" size="50" name="nombre" /></td>
            <tr><td align="right">Comentarios: </td><td align="left"><input type="text" maxlength="250" size="50" name="comentarios" /></td>
            <tr><td align="right">Tipo: </td><td align="left"><select name="tipo"><?php echo CboCG("tipo_mapa"); ?></select></td>
            <tr><td align="right">Contenido: </td><td align="left"><input type="text" maxlength="250" size="50" name="contenido" /></td>
            <tr><td align="right" colspan="2"><input type="submit" value="Aceptar" /></td></tr>
        </table>
    </fieldset>
</form>
<form action="add_map.php" name="add_rel" id="add_rel" method="post"><input type="hidden" name="accion" value="add_rel" />
    <fieldset>
        <legend>Relacion Mapa Padre - Hijo: </legend>
        <table border="0" align="center"><tr><td><div style="overflow:auto; width:800px; height:500px;"><table border="0" align="center">
            <tr><td align="left">Padre: <select name="mapa_padre"><?php
            if($regs=$db->consulta("select id_mapa, nombre from mapa order by nombre, id_mapa"))
                while($reg=mysqli_fetch_array($regs))
                    echo '<option value="'.$reg["id_mapa"].'">'.$reg["nombre"].'</option>';
            ?></select></td></tr>
            <?php
            if($regs=$db->consulta("select id_mapa, nombre from mapa order by nombre, id_mapa"))
                while($reg=mysqli_fetch_array($regs))
                {
                    ?>
                    <tr><td align="left"><table border="0" align="center">
                        <tr><td align="right"><input type="hidden" name="mapa_hijo[]" value="<?php echo $reg["id_mapa"]; ?>" /></td><td align="left"><?php echo $reg["nombre"]; ?></td></tr>
                        <tr><td align="right">Figura: </td><td align="left"><input type="text" maxlength="250" size="50" name="figura[]" /></td></tr>
                        <tr><td align="right">Coordenadas: </td><td align="left"><input type="text" maxlength="250" size="50" name="coordenadas[]" /></td></tr>
                        <tr><td align="right">Posición: </td><td align="left"><input type="text" maxlength="250" size="50" name="posicion[]" /></td></tr>
                        <tr><td align="right">Preposición: </td><td align="left"><input type="text" maxlength="250" size="50" name="preposicion[]" /></td></tr>
                    </table></td></tr>
                    <?php
                }
            ?>
            <tr><td align="right"><input type="submit" value="Aceptar" /></td></tr>
        </table></div></td></tr></table>
    </fieldset>
</form>
<form action="add_map.php" name="add_docto" id="add_docto" method="post"><input type="hidden" name="accion" value="add_docto" />
    <fieldset>
        <legend>Relacion Mapa - Documento: </legend>
        <table border="0" align="center"><tr><td><div style="overflow:auto; width:800px; height:350px;"><table border="0" align="center">
            <tr><td align="left">Mapa: <select name="id_mapa"><?php
            if($regs=$db->consulta("select id_mapa, nombre from mapa where tipo = '4' order by nombre, id_mapa"))
                while($reg=mysqli_fetch_array($regs))
                    echo '<option value="'.$reg["id_mapa"].'">'.$reg["nombre"].'</option>';
            ?></select></td></tr>
            <tr><td><table border="0" align="center"><tr><th>Nombre</th><th>Contenido</th><th>Tipo</th></tr>
            <?php
            for($x=1;$x<=5;$x++) echo '<tr><td valign="top"><input type="text" maxlength="250" size="50" name="nombre_documento[]" /></td><td valign="top"><textarea rows="5" cols="40" name="contenido[]"></textarea></td><td valign="top"><select name="tipo_documento[]">'.CboCG("contenido_tipo").'</select></td></tr>';
            ?>
            </table></td></tr>
            <tr><td align="right"><input type="submit" value="Aceptar" /></td></tr>
        </table></div></td></tr></table>
    </fieldset>
</form>
</body>
</html>
