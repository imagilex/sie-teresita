<?php
session_start();

/*
<script language="javascript">
    function ExpandeFiltro(filtro)
    {
        $('filtro_enc_'+filtro).className='filtro_enc_sel_'+filtro;
        $('filtro_body_'+filtro).className=''+filtro;
        $('filtro_body_'+filtro).innerHTML='Contenido';
    }
    function DesexpandeTodo(filtros)
    {
        var x;
        for(x=1;x<=filtros;x++)
        {
            $('filtro_enc_'+x).className='filtro_enc_desel_'+x;
            $('filtro_body_'+x).className='filtro_body_empty';
            $('filtro_body_'+x).innerHTML='';
        }
    }
</script>


<table border="0" align="center">
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_1" class="filtro_enc_desel_1" onclick="DesexpandeTodo(10); ExpandeFiltro(1)">1</td></tr>
        <tr><td id="filtro_body_1" class="filtro_body_empty"></td></tr>
    </table></td></tr>
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_2" class="filtro_enc_desel_2" onclick="DesexpandeTodo(10); ExpandeFiltro(2)">2</td></tr>
        <tr><td id="filtro_body_2" class="filtro_body_empty"></td></tr>
    </table></td></tr>
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_3" class="filtro_enc_desel_3" onclick="DesexpandeTodo(10); ExpandeFiltro(3)">3</td></tr>
        <tr><td id="filtro_body_3" class="filtro_body_empty"></td></tr>
    </table></td></tr>
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_4" class="filtro_enc_desel_4" onclick="DesexpandeTodo(10); ExpandeFiltro(4)">4</td></tr>
        <tr><td id="filtro_body_4" class="filtro_body_empty"></td></tr>
    </table></td></tr>
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_5" class="filtro_enc_desel_5" onclick="DesexpandeTodo(10); ExpandeFiltro(5)">5</td></tr>
        <tr><td id="filtro_body_5" class="filtro_body_empty"></td></tr>
    </table></td></tr>
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_6" class="filtro_enc_desel_6" onclick="DesexpandeTodo(10); ExpandeFiltro(6)">6</td></tr>
        <tr><td id="filtro_body_6" class="filtro_body_empty"></td></tr>
    </table></td></tr>
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_7" class="filtro_enc_desel_7" onclick="DesexpandeTodo(10); ExpandeFiltro(7)">7</td></tr>
        <tr><td id="filtro_body_7" class="filtro_body_empty"></td></tr>
    </table></td></tr>
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_8" class="filtro_enc_desel_8" onclick="DesexpandeTodo(10); ExpandeFiltro(8)">8</td></tr>
        <tr><td id="filtro_body_8" class="filtro_body_empty"></td></tr>
    </table></td></tr>
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_9" class="filtro_enc_desel_9" onclick="DesexpandeTodo(10); ExpandeFiltro(9)">9</td></tr>
        <tr><td id="filtro_body_9" class="filtro_body_empty"></td></tr>
    </table></td></tr>
    <tr><td><table border="0" cellpadding="0" cellspacing="0">
        <tr><td id="filtro_enc_10" class="filtro_enc_desel_10" onclick="DesexpandeTodo(10); ExpandeFiltro(10)">10</td></tr>
        <tr><td id="filtro_body_10" class="filtro_body_empty"></td></tr>
    </table></td></tr>
</table>

*/
include("../apoyo.php");
$schema = Get_Vars_Helper::getPGVar("schema");
$type_inf = Get_Vars_Helper::getPGVar("type_inf");
$campo_filtro = Get_Vars_Helper::getPGVar("campo_filtro");
$extra_data = Get_Vars_Helper::getPGVar("extra_data");
$herramienta = Get_Vars_Helper::getPGVar("herramienta");
$campo_save = Get_Vars_Helper::getPGVar("campo_save");
if($schema!="")
{
    if($schema=="minuta_incio")
    {
        ?>
        <table border="0" align="center">
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_1" class="filtro_enc_desel_1" onclick="DesexpandeTodo(6); ExpandeFiltro(1)">Origen</td></tr>
                <tr><td id="filtro_body_1" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_2" class="filtro_enc_desel_2" onclick="DesexpandeTodo(6); ExpandeFiltro(2)">Agrupador</td></tr>
                <tr><td id="filtro_body_2" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_3" class="filtro_enc_desel_3" onclick="DesexpandeTodo(6); ExpandeFiltro(3)">Fuente</td></tr>
                <tr><td id="filtro_body_3" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_4" class="filtro_enc_desel_4" onclick="DesexpandeTodo(6); ExpandeFiltro(4)">Coordinador</td></tr>
            <tr><td id="filtro_body_4" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_5" class="filtro_enc_desel_5" onclick="DesexpandeTodo(6); ExpandeFiltro(5)">Estatus</td></tr>
                <tr><td id="filtro_body_5" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_6" class="filtro_enc_desel_6" onclick="DesexpandeTodo(6); ExpandeFiltro(6)">Fecha</td></tr>
                <tr><td id="filtro_body_6" class="filtro_body_empty"></td></tr>
            </table></td></tr>
        </table>
        <?php
    }
    else if($schema=="minuta_subcontenido")
    {
        ?>
        <table border="0" align="center">
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_1" class="filtro_enc_desel_1" onclick="DesexpandeTodo_ms(3); ExpandeFiltro_ms(1)">Coordinador</td></tr>
                <tr><td id="filtro_body_1" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_2" class="filtro_enc_desel_2" onclick="DesexpandeTodo_ms(3); ExpandeFiltro_ms(2)">Estatus</td></tr>
                <tr><td id="filtro_body_2" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_3" class="filtro_enc_desel_3" onclick="DesexpandeTodo_ms(3); ExpandeFiltro_ms(3)">Fecha</td></tr>
                <tr><td id="filtro_body_3" class="filtro_body_empty"></td></tr>
            </table></td></tr>
        </table>
        <?php
    }
    else if($schema=="minuta_subcontenido_komps")
    {
        ?>
        <table border="0" align="center">
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_1" class="filtro_enc_desel_1" onclick="DesexpandeTodo(9); ExpandeFiltro(1)">Proveedor</td></tr>
                <tr><td id="filtro_body_1" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_2" class="filtro_enc_desel_2" onclick="DesexpandeTodo(9); ExpandeFiltro(2)">Cliente</td></tr>
                <tr><td id="filtro_body_2" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_3" class="filtro_enc_desel_3" onclick="DesexpandeTodo(9); ExpandeFiltro(3)">Fecha Planeada (Inicio)</td></tr>
                <tr><td id="filtro_body_3" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_4" class="filtro_enc_desel_4" onclick="DesexpandeTodo(9); ExpandeFiltro(4)">Fecha Planeada (Fin)</td></tr>
            <tr><td id="filtro_body_4" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_5" class="filtro_enc_desel_5" onclick="DesexpandeTodo(9); ExpandeFiltro(5)">Fecha Real (Inicio)</td></tr>
                <tr><td id="filtro_body_5" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_6" class="filtro_enc_desel_6" onclick="DesexpandeTodo(9); ExpandeFiltro(6)">Fecha Real (Fin)</td></tr>
                <tr><td id="filtro_body_6" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_7" class="filtro_enc_desel_7" onclick="DesexpandeTodo(9); ExpandeFiltro(7)">Fecha de Activaci&oacute;n (Inicio)</td></tr>
                <tr><td id="filtro_body_7" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_8" class="filtro_enc_desel_8" onclick="DesexpandeTodo(9); ExpandeFiltro(8)">Fecha de Activaci&oacute;n (Fin)</td></tr>
                <tr><td id="filtro_body_8" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_9" class="filtro_enc_desel_9" onclick="DesexpandeTodo(9); ExpandeFiltro(9)">Estatus</td></tr>
                <tr><td id="filtro_body_9" class="filtro_body_empty"></td></tr>
            </table></td></tr>
        </table>
        <?php
    }
    else if($schema=="minuta_subcontenido_tareas")
    {
        ?>
        <table border="0" align="center">
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_1" class="filtro_enc_desel_1" onclick="DesexpandeTodo(5); ExpandeFiltro(1)">Proveedor</td></tr>
                <tr><td id="filtro_body_1" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_2" class="filtro_enc_desel_2" onclick="DesexpandeTodo(5); ExpandeFiltro(2)">Cliente</td></tr>
                <tr><td id="filtro_body_2" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_3" class="filtro_enc_desel_3" onclick="DesexpandeTodo(5); ExpandeFiltro(3)">Estatus</td></tr>
                <tr><td id="filtro_body_3" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_4" class="filtro_enc_desel_4" onclick="DesexpandeTodo(5); ExpandeFiltro(4)">Fecha Planeada (Inicio)</td></tr>
            <tr><td id="filtro_body_4" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_5" class="filtro_enc_desel_5" onclick="DesexpandeTodo(5); ExpandeFiltro(5)">Fecha Planeada (Fin)</td></tr>
                <tr><td id="filtro_body_5" class="filtro_body_empty"></td></tr>
            </table></td></tr>
        </table>
        <?php
    }
    else if($schema=="minuta_subcontenido_acuerdos")
    {
        ?>
        <table border="0" align="center">
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_1" class="filtro_enc_desel_1" onclick="DesexpandeTodo(); ExpandeFiltro(1)"></td></tr>
                <tr><td id="filtro_body_1" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_2" class="filtro_enc_desel_2" onclick="DesexpandeTodo(); ExpandeFiltro(2)"></td></tr>
                <tr><td id="filtro_body_2" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_3" class="filtro_enc_desel_3" onclick="DesexpandeTodo(); ExpandeFiltro(3)"></td></tr>
                <tr><td id="filtro_body_3" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_4" class="filtro_enc_desel_4" onclick="DesexpandeTodo(); ExpandeFiltro(4)"></td></tr>
            <tr><td id="filtro_body_4" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_5" class="filtro_enc_desel_5" onclick="DesexpandeTodo(); ExpandeFiltro(5)"></td></tr>
                <tr><td id="filtro_body_5" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_6" class="filtro_enc_desel_6" onclick="DesexpandeTodo(); ExpandeFiltro(6)"></td></tr>
                <tr><td id="filtro_body_6" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_7" class="filtro_enc_desel_7" onclick="DesexpandeTodo(); ExpandeFiltro(7)"></td></tr>
                <tr><td id="filtro_body_7" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_8" class="filtro_enc_desel_8" onclick="DesexpandeTodo(); ExpandeFiltro(8)"></td></tr>
                <tr><td id="filtro_body_8" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_9" class="filtro_enc_desel_9" onclick="DesexpandeTodo(); ExpandeFiltro(9)"></td></tr>
                <tr><td id="filtro_body_9" class="filtro_body_empty"></td></tr>
            </table></td></tr>
            <tr><td><table border="0" cellpadding="0" cellspacing="0">
                <tr><td id="filtro_enc_10" class="filtro_enc_desel_10" onclick="DesexpandeTodo(); ExpandeFiltro(10)"></td></tr>
                <tr><td id="filtro_body_10" class="filtro_body_empty"></td></tr>
            </table></td></tr>
        </table>
        <?php
    }
}
if($type_inf!="" && $campo_filtro!="" && $herramienta!="")
{
    $aux=CTabla("herr_pant_filtros");
    $usua=$aux->select("operador, valor","campo='$campo_filtro' and usuario='".$_SESSION["id_usr"]."' and herramienta='$herramienta'");
    ?><form name="data_filtro" id="data_filtro">
    <input type="hidden" name="campo_save" id="campo_save" value="<?php echo $campo_filtro; ?>" />
    <input type="hidden" name="herramienta" id="herramienta" value="<?php echo $herramienta; ?>" />
    <input type="hidden" name="type_inf" id="type_inf" value="<?php echo $type_inf; ?>" />
    <table border="0" align="center"><tr><td valign="top" width="100" align="center"><?php
    if($type_inf=="cg" && $extra_data!="")
    {
        $aux=CTabla("codigos_generales");
        $regs=$aux->select("valor,descripcion","campo='$extra_data'",'posicion');
        ?>
        <table border="0" align="center">
            <?php
            foreach($regs as $reg)
            {
                ?>
                <tr><td><input type="checkbox" <?php echo ((IsCheck($usua,$reg["valor"]))?('checked="checked"'):(''))?> value="<?php echo htmlentities($reg["valor"]); ?>" name="filtrado[]" id="filtrado[]" /></td><td align="left"><?php echo htmlentities($reg["descripcion"]); ?></td></tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    else if($type_inf=="texto")
    {
        $datos_campo=$aux->registro($aux->query("select campo, tabla from herr_pant_campos where id_campo='$campo_filtro'"));
        $datos_tabla=$aux->registro($aux->query("select tabla from herr_pant_tablas where id_tabla = '".$datos_campo["tabla"]."'"));
        $aux=CTabla($datos_tabla["tabla"]);
        if($datos_tabla["tabla"]!="docto_general")
        {
            $regs=$aux->select("distinct ".$datos_campo["campo"]." as dato");
        }
        else
        {
            $regs=$aux->select("distinct ".$datos_campo["campo"]." as dato","tipo_documento='1'");
        }
        ?>
        <table border="0" align="center">
            <?php
            foreach($regs as $reg)
            {
                ?>
                <tr><td><input type="checkbox" <?php echo ((IsCheck($usua,$reg["dato"]))?('checked="checked"'):(''))?> value="<?php echo htmlentities($reg["dato"]); ?>" name="filtrado[]" id="filtrado[]" /></td><td align="left"><?php echo htmlentities($reg["dato"]); ?></td></tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    else if($type_inf=="persona")
    {
        $aux=CTabla("persona");
        $regs=$aux->select("concat(nombre) as nomb, clave","clave in (select persona from herr_pant_responsables)","concat(nombre)");
        ?>
        <table border="0" align="center">
            <?php
            foreach($regs as $reg)
            {
                ?>
                <tr><td><input type="checkbox" <?php echo ((IsCheck($usua,$reg["clave"]))?('checked="checked"'):(''))?> value="<?php echo htmlentities($reg["clave"]); ?>" name="filtrado[]" id="filtrado[]" /></td><td align="left"><?php echo htmlentities($reg["nomb"]); ?></td></tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    else if($type_inf=="fecha")
    {
        $usua_ini=$aux->select("valor","operador='>' and campo='$campo_filtro' and usuario='".$_SESSION["id_usr"]."'");
        $usua_fin=$aux->select("valor","operador='<' and campo='$campo_filtro' and usuario='".$_SESSION["id_usr"]."'");
        ?>
        <table border="0" align="center">
            <tr><td align="right">Desde:</td><td><input type="text" maxlength="25" size="10" id="filtro_desde" name="filtro_desde" value="<?php echo htmlentities($usua_ini[0]["valor"]); ?>" /></td></tr>
            <tr><td align="right">Hasta:</td><td><input type="text" maxlength="25" size="10" id="filtro_hasta" name="filtro_hasta" value="<?php echo htmlentities($usua_fin[0]["valor"]); ?>" /></td></tr>
        </table>
        <?php
    }
    ?></td><td valign="top" width="50" align="right"><img align="Guardar Cambios" title="Guardar Cambios" src="Imagenes/iconografia/10.png" onclick="SaveFilter()"></td></tr></table></form><?php
}
if($campo_save!="" && $herramienta!="" && $type_inf!="")
{
    $datos = Get_Vars_Helper::getPostVar("filtrado") != "" ? Get_Vars_Helper::getPostVar("filtrado") : Get_Vars_Helper::getGetVar("filtrado");
    if($type_inf=="cg")
    {
        $aux = CTabla("herr_pant_filtros");
        $aux->delete("herramienta='$herramienta' and usuario='".$_SESSION["id_usr"]."' and campo='$campo_save'");
        if($datos!="" && count($datos>0))
        {
            foreach($datos as $dato)
            {
                $aux->insert(array("campo"=>$campo_save, "operador"=>"=", "valor"=>$dato, "usuario"=>$_SESSION["id_usr"], "herramienta"=>$herramienta));
            }
        }
    }
    else if($type_inf=="texto")
    {
        $aux = CTabla("herr_pant_filtros");
        $aux->delete("herramienta='$herramienta' and usuario='".$_SESSION["id_usr"]."' and campo='$campo_save'");
        if($datos!="" && count($datos>0))
        {
            foreach($datos as $dato)
            {
                $aux->insert(array("campo"=>$campo_save, "operador"=>"=", "valor"=>$dato, "usuario"=>$_SESSION["id_usr"], "herramienta"=>$herramienta));
            }
        }
    }
    else if($type_inf=="persona")
    {
        $aux = CTabla("herr_pant_filtros");
        $aux->delete("herramienta='$herramienta' and usuario='".$_SESSION["id_usr"]."' and campo='$campo_save'");
        if($datos!="" && count($datos>0))
        {
            foreach($datos as $dato)
            {
                $aux->insert(array("campo"=>$campo_save, "operador"=>"=", "valor"=>$dato, "usuario"=>$_SESSION["id_usr"], "herramienta"=>$herramienta));
            }
        }
    }
    else if($type_inf=="fecha")
    {
        $aux = CTabla("herr_pant_filtros");
        $aux->delete("herramienta='$herramienta' and usuario='".$_SESSION["id_usr"]."' and campo='$campo_save'");
        $filtro_desde = Get_Vars_Helper::getPGVar("filtro_desde");
        $filtro_hasta = Get_Vars_Helper::getPGVar("filtro_hasta");
        if($filtro_desde!="") $aux->insert(array("campo"=>$campo_save, "operador"=>">", "valor"=>$filtro_desde, "usuario"=>$_SESSION["id_usr"], "herramienta"=>$herramienta));
        if($filtro_hasta) $aux->insert(array("campo"=>$campo_save, "operador"=>"<", "valor"=>$filtro_hasta, "usuario"=>$_SESSION["id_usr"], "herramienta"=>$herramienta));
    }
}
function IsCheck($registros, $verificador)
{
    for($x=0;$x<@count($registros);$x++)
        if($registros[$x]["valor"]==$verificador)
            return true;
    return false;
}
?>
