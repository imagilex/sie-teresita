<?php
session_start();

include("../apoyo.php");

class campo_sql
{
    private $campo;
    private $etiqueta;
    private $tabla;
    private $criterio;
    private $orden;
    private $posicion;
    private $tipo;
    public function __construct()
    {
        $this->SetParam("campo","");
        $this->SetParam("etiqueta","");
        $this->SetParam("tabla","");
        $this->SetParam("criterio","");
        $this->SetParam("orden","");
        $this->SetParam("posicion","");
    }
    public function SetParam($param,$valor) {$this->$param=$valor;}
    public function GetParam($param) {return $this->$param;}
}

function Add_From(&$arreglo, $elemento, &$from)
{
    if(!in_array($elemento,$arreglo))
    {
        array_push($arreglo,$elemento);
        $tbl=CTabla("herr_pant_tablas");
        if($elemento=="docto1")
        {
            Add_From($arreglo, "docto_general", $from);
        }
        $aux=$tbl->select("*","tabla='$elemento'");
        $from .= "\n\t".$aux[0]["join"]." ";
    }
}

function Campos_Select($herr)
{
    $hpp=CTabla("herr_pant_preferencias");
    $campos=$hpp->select("campo, etiqueta, orden, posicion","usuario='".$_SESSION["id_usr"]."' and herramienta='$herr'","posicion");
    $retorno=array();
    $x=0;
    foreach($campos as $campo)
    {
        $retorno[$x]= new campo_sql();
        $retorno[$x]->SetParam("etiqueta",$campo["etiqueta"]);
        $retorno[$x]->SetParam("orden",$campo["orden"]);
        $retorno[$x]->SetParam("posicion",$x+1);
        $reg1=$hpp->query("select campo, tipo, herr_pant_tablas.tabla from herr_pant_campos inner join herr_pant_tablas on herr_pant_campos.tabla = herr_pant_tablas.id_tabla where id_campo='".$campo["campo"]."'");
        $aux=$hpp->registro($reg1);
        $retorno[$x]->SetParam("campo",$aux["campo"]);
        $retorno[$x]->SetParam("tabla",$aux["tabla"]);
        $retorno[$x]->SetParam("tipo",$aux["tipo"]);
        $x++;
    }
    return $retorno;
}

function Campos_Filtro($herr)
{
    $x=0;
    $retorno=array();
    $hpf=CTabla("herr_pant_filtros");
    $campos=$hpf->select("campo, operador, valor","usuario='".$_SESSION["id_usr"]."' and herramienta='$herr'", "campo, operador, valor");
    foreach($campos as $campo)
    {
        $retorno[$x]=new campo_sql();
        $reg1=$hpf->query("select campo, tipo, herr_pant_tablas.tabla from herr_pant_campos inner join herr_pant_tablas on herr_pant_campos.tabla = herr_pant_tablas.id_tabla where id_campo='".$campo["campo"]."'");
        $aux=$hpf->registro($reg1);
        $retorno[$x]->SetParam("campo",$aux["campo"]);
        $retorno[$x]->SetParam("tabla",$aux["tabla"]);
        $retorno[$x]->SetParam("tipo",$aux["tipo"]);
        $retorno[$x]->SetParam("criterio",$campo["operador"]."'".$campo["valor"]."'");
        $x++;
    }
    return $retorno;
}

function Forma_Select($campos, &$tablas_from, &$from, &$virtual, &$order)
{
    $select="";
    foreach($campos as $campo)
    {
        Add_From($tablas_from,$campo->GetParam("tabla"),$from);
        $tvirtual="tbl_virtual_".($virtual++);
        if($campo->GetParam("tipo")=="cg")
        {
            if($campo->GetParam("campo")=="origen" && $campo->GetParam("tabla")=="docto_general")
            {
                $select .= "\n\t$tvirtual.descripcion as '".$campo->GetParam("etiqueta")."', ";
                $from .= "\n\t\tLEFT JOIN codigos_generales as $tvirtual ON $tvirtual.campo='documento_origen' and $tvirtual.valor=".$campo->GetParam("tabla").".".$campo->GetParam("campo")." ";
            }
            else if($campo->GetParam("campo")=="fuente" && $campo->GetParam("tabla")=="docto_general")
            {
                $select .= "\n\t$tvirtual.descripcion as '".$campo->GetParam("etiqueta")."', ";
                $from .= "\n\t\tLEFT JOIN codigos_generales as $tvirtual ON $tvirtual.campo='documento_fuente' and $tvirtual.valor=".$campo->GetParam("tabla").".".$campo->GetParam("campo")." ";
            }
            else if($campo->GetParam("campo")=="estatus" && $campo->GetParam("tabla")=="docto1")
            {
                $select .= "\n\tconcat('_STATUS_STAR_=', $tvirtual.otro) as '".$campo->GetParam("etiqueta")."', ";
                $from .= "\n\t\tLEFT JOIN codigos_generales as $tvirtual ON $tvirtual.campo='minuta_estatus' and $tvirtual.valor=".$campo->GetParam("tabla").".".$campo->GetParam("campo")." ";
            }
        }
        else if($campo->GetParam("tipo")=="persona")
        {
            $select .= "\n\tconcat($tvirtual.nombre) as '".$campo->GetParam("etiqueta")."', ";
            $from .= "\n\t\tLEFT JOIN persona as $tvirtual ON $tvirtual.clave=".$campo->GetParam("tabla").".".$campo->GetParam("campo")." ";
        }
        else if($campo->GetParam("tipo")=="fecha")
        {
            $select .= "\n\tDATE_FORMAT(".$campo->GetParam("tabla").".".$campo->GetParam("campo").", '%d-%m-%Y') as '".$campo->GetParam("etiqueta")."', ";
        }
        else
        {
            $select .= "\n\t".$campo->GetParam("tabla").".".$campo->GetParam("campo")." as '".$campo->GetParam("etiqueta")."', ";
        }
    $order .= "\n\t".$campo->GetParam("tabla").".".$campo->GetParam("campo")." ".$campo->GetParam("orden").", ";
    }
    return substr($select,0,strlen($select)-2)." ";
}

function Forma_Where($campos, &$tablas_from, &$from)
{
    $where="";
    $campo_actual="";
    $open=false;
    foreach($campos as $campo)
    {
        Add_From($tablas_from,$campo->GetParam("tabla"),$from);
        if($campo_actual==$campo->GetParam("campo") && $campo->GetParam("campo")!="")
        {
            if($campo->GetParam("tipo")=="fecha") $operador=" AND ";
            else $operador=" OR ";
            $where .= $operador.$campo->GetParam("tabla").".".$campo->GetParam("campo").$campo->GetParam("criterio");
        }
        else
        {
            if($campo_actual!="")
            {
                $where .= ") \n\tAND (";
            }
            else
            {
                $where .= " \n\tAND (";
            }
            $campo_actual=$campo->GetParam("campo");
            $where .= $campo->GetParam("tabla").".".$campo->GetParam("campo").$campo->GetParam("criterio");
        $open=true;
        }
    }
    if($open)
    {
        $where .= ")";
    }
    return $where.= " ";
}

$accion = Get_Vars_Helper::getPGVar("accion");
$id_documento = Get_Vars_Helper::getPGVar("docto");
$new_val = Get_Vars_Helper::getPGVar("new_val");
$includes_id = Get_Vars_Helper::getPGVar("includes");
if($includes_id!="") $includes_id="'".str_replace(",","','",$includes_id)."'";
if($accion!="")
{
    $tbl = CTabla("docto_general");
    if($accion=="ver_regs_minuta_inicio")
    {
        // id_herramienta = 1
        $tables_from=array();
        $select="select distinct docto_general.id_documento as 'id_documento', ";
        $from="from ";
        $where= "where docto_general.tipo_documento='1' ";
        if($includes_id!="") $where .= "and docto_general.id_documento in ($includes_id)";
        $order= "order by  ";
        $x=0;
        $virtual=0;
        $tbl_cont=CTabla("docto1");
        Add_From($tables_from,"docto_general",$from);
        $select .= Forma_Select(Campos_Select("1"), $tables_from, $from, $virtual, $order);
        $where .= Forma_Where(Campos_Filtro("1"), $tables_from, $from);
        //Ajuste de lineas del query
        $from = substr($from,0,strlen($from)-1)." ";
        $order = substr($order,0,strlen($order)-2)." ";
        $query_sql=$select."\n".$from."\n".$where."\n".$order."\n";
        $id_docto="";
        if($regs=$tbl->query($query_sql))
        {
            $x=0;
            $y=0;
            ?><table align="left" border="0" cellspacing="5"><?php
                while($reg=$tbl->registro($regs))
                {
                    $x++;
                    $clave_documento=$reg["id_documento"];
                    ?><tr onmousemove="javascript: this.style.backgroundColor='#EBF1DE';" onmouseout="javascript: this.style.backgroundColor='#FFFFFF';" ondblclick="seeMinViva('<?php echo $clave_documento; ?>')"><?php
                        if($x==1)
                        {
                            $lin="";
                            $y=0;
                            foreach($reg as $key => $val)
                            {
                                if(count(explode("=",$val))==2)
                                {
                                    list($parametro,$valor)=explode("=",$val);
                                    if($parametro=="_STATUS_STAR_")
                                        $val='<img src="Imagenes/iconografia/status_star.png" height="15" style="background-color:#'.$valor.'" onclick="CambioEstatusMinuta('."'".$id_docto."'".','."'".$valor."'".');" />';
                                }
                                else $val=htmlentities($val);
                                if($y==0)
                                {
                                    $id_docto = $val;
                                    $cuantos=$tbl_cont->select("count(*) as n","id_documento='$id_docto'");
                                    echo "<th><th></th></th>";
                                }
                                if($y>0)
                                {
                                    ?><th align="left" style="font-size:.75em;"><?php echo htmlentities($key); ?></th><?php
                                }
                                $lin .= "<td onclick='CheckRow($x)'>".($y==0?'<input class="ckb" type="checkbox" id="ckb_'.$x.'" value="'.$val.'" /><img src="Imagenes/iconografia/selecb.png" id="img_ckb_'.$x.'" height="15"> <img src="Imagenes/iconografia/plus.png" id="img_docto_'.$id_docto.'" onclick="Expande('.$x.','."'".$id_docto."'".')"><td align="center" valign="middle" width="15" style="background-image:url(Imagenes/iconografia/circle.png); background-repeat:no-repeat;"><font size="-1">'.$cuantos[0]["n"].'</font></td>':$val)."</td>";
                                $y++;
                            }
                            echo "</tr><tr onmousemove=\"javascript: this.style.backgroundColor='#EBF1DE';\" onmouseout=\"javascript: this.style.backgroundColor='#FFFFFF';\"  ondblclick=\"seeMinViva('$clave_documento;')\">$lin";
                        }
                        else
                        {
                            $y=0;
                            foreach($reg as $val)
                            {
                                if(count(explode("=",$val))==2)
                                {
                                    list($parametro,$valor)=explode("=",$val);
                                    if($parametro=="_STATUS_STAR_")
                                        $val='<img src="Imagenes/iconografia/status_star.png" height="15" style="background-color:#'.$valor.'" onclick="CambioEstatusMinuta('."'".$id_docto."'".','."'".$valor."'".');" />';
                                }
                                else $val=htmlentities($val);
                                if($y==0) {$id_docto = $val; $cuantos=$tbl_cont->select("count(*) as n","id_documento='$id_docto'");}
                                ?><td onClick="CheckRow(<?php echo $x; ?>)"><?php echo ($y==0?'<input class="ckb" type="checkbox" id="ckb_'.$x.'" value="'.$val.'" /><img src="Imagenes/iconografia/selecb.png" id="img_ckb_'.$x.'" height="15"> <img src="Imagenes/iconografia/plus.png" id="img_docto_'.$id_docto.'" onclick="Expande('.$x.','."'".$id_docto."'".')"><td align="center" valign="middle" width="15" style="background-image:url(Imagenes/iconografia/circle.png); background-repeat:no-repeat;"><font size="-1">'.$cuantos[0]["n"].'</font></td>':$val); ?></td><?php
                                $y++;
                            }
                        }
                    ?></tr><tr><td colspan="<?php echo $y+5; ?>"><div id="subminutas<?php echo $x; ?>"></div></td></tr><?php
                }
                ?></table><?php
        }
    }
    else if($accion=="ver_regs_minuta_subcontenido")
    {
        // id_herramienta = 2
        $campos_where=array();
        $tables_from=array();
        $select="select distinct concat(docto1.id_documento, '-', consecutivo) as 'id_documento ', ";
        $from="from ";
        $where="where 1=1 ";
        if($includes_id!="") $where .= "and docto1.id_documento in ($includes_id)";
        $order= "order by  ";
        $virtual=0;
        $tbl_cont=CTabla("docto1");
        Add_From($tables_from,"docto1",$from);
        $select .= Forma_Select(Campos_Select("2"),$tables_from,$from,$virtual,$order);
        $where .= Forma_Where(Campos_Filtro("2"), $tables_from, $from);
        //Ajuste de lineas del query
        $from = substr($from,0,strlen($from)-1)." ";
        $order = substr($order,0,strlen($order)-2)." ";
        $query_sql=$select."\n".$from."\n".$where."\n".$order;
        $id_docto="";
        if($regs=$tbl->query($query_sql))
        {
            $x=0;
            ?><table align="left" border="0" cellspacing="5"><?php
                while($reg=$tbl->registro($regs))
                {
                    $x++;
                    $clave_documento=$reg["id_documento"];
                    ?><tr onmousemove="javascript: this.style.backgroundColor='#EBF1DE';" onmouseout="javascript: this.style.backgroundColor='#FFFFFF';" ondblclick="seeMinuta('<?php echo $clave_documento; ?>')"><?php
                        if($x==1)
                        {
                            $lin="";
                            $y=0;
                            foreach($reg as $key => $val)
                            {
                                if(count(explode("=",$val))==2)
                                {
                                    list($parametro,$valor)=explode("=",$val);
                                    if($parametro=="_STATUS_STAR_")
                                        $val='<img src="Imagenes/iconografia/status_star.png" height="15" style="background-color:#'.$valor.'" onclick="CambioEstatusMinuta('."'".$id_docto."'".','."'".$valor."'".');" />';
                                }
                                else $val=htmlentities($val);
                                if($y==0)
                                {
                                    $id_docto = $val;
                                    $cuantos=$tbl_cont->select("count(*) as n","id_documento='$id_docto'");
                                    echo "<th></th>";
                                }
                                if($y>0)
                                {
                                    ?><th align="left" style="font-size:.75em;"><?php echo htmlentities($key); ?></th><?php
                                }
                                $lin .= "<td onclick='CheckRow($x)'>".($y==0?'<input class="ckb" type="checkbox" id="ckb_'.$x.'" value="'.$val.'" /><img src="Imagenes/iconografia/selecb.png" id="img_ckb_'.$x.'" height="15">':$val)."</td>";

                                $y++;
                            }
                            echo "</tr><tr onmousemove=\"javascript: this.style.backgroundColor='#EBF1DE';\" onmouseout=\"javascript: this.style.backgroundColor='#FFFFFF';\"  ondblclick=\"seeMinuta('$clave_documento;')\">$lin";
                        }
                        else
                        {
                            $y=0;
                            foreach($reg as $val)
                            {
                                if(count(explode("=",$val))==2)
                                {
                                    list($parametro,$valor)=explode("=",$val);
                                    if($parametro=="_STATUS_STAR_")
                                        $val='<img src="Imagenes/iconografia/status_star.png" height="15" style="background-color:#'.$valor.'" onclick="CambioEstatusMinuta('."'".$id_docto."'".','."'".$valor."'".');" />';
                                }
                                else $val=htmlentities($val);
                                if($y==0) {$id_docto = $val; $cuantos=$tbl_cont->select("count(*) as n","id_documento='$id_docto'");}
                                ?><td onClick="CheckRow(<?php echo $x; ?>)"><?php echo ($y==0?'<input class="ckb" type="checkbox" id="ckb_'.$x.'" value="'.$val.'" /><img src="Imagenes/iconografia/selecb.png" id="img_ckb_'.$x.'" height="15">':$val); ?></td><?php
                                $y++;
                            }
                        }
                    ?></tr><?php
                }
                ?></table><?php
        }
    }
    else if($accion=="ver_regs_minuta_subcontenido_todo")
    {
        // id_herramienta = 3
    }
    else if($accion=="ver_regs_minuta_subcontenido_generales")
    {
        // id_herramienta = 4
    }
    else if($accion=="ver_regs_minuta_subcontenido_komps")
    {
        // id_herramienta = 5
    }
    else if($accion=="ver_regs_minuta_subcontenido_tareas")
    {
        // id_herramienta = 6
    }
    else if($accion=="ver_regs_minuta_subcontenido_acuerdos")
    {
        // id_herramienta = 7
    }
    else if($accion=="ver_regs_minuta_preview_generales")
    {
        // id_herramienta = 8
    }
    else if($accion=="ver_regs_minuta_preview_komps")
    {
        // id_herramienta = 9
    }
    else if($accion=="ver_regs_minuta_preview_tareas")
    {
        // id_herramienta = 10
    }
    else if($accion=="ver_regs_minuta_preview_todo_viva")
    {
        // id_herramienta = 11
    }
    else if($accion=="ver_regs_minuta_preview_todo")
    {
        // id_herramienta = 12
    }
    else if($accion=="ver_minuta_preview_todo_viva" && $id_documento!="")
    {
        $tdg=CTabla("docto_general");
        $td1=CTabla("docto1");
        $td3=Ctabla("docto3");
        $td4=Ctabla("docto4");
        $tp=CTabla("persona");
        $tds=CTabla("docto_secciones");
        $ddg=$tdg->select("*","id_documento='$id_documento'");
        $dd1=$td1->select("*","id_documento='$id_documento'");
        $coord=$tp->select("concat(nombre) as nomb","clave='".$dd1[0]["coordinador"]."'");
        $dias_act=$td1->select("datediff(curdate(), fecha) as da","id_documento='$id_documento'");
        $comps_title=$tds->select("nombre_seccion","documento='1' and posicion='4'");
        $tareas_title=$tds->select("nombre_seccion","documento='1' and posicion='5'");
        $dd3=$td3->select("id_documento, consecutivo, descripcion, responsable as proveedor, cliente, puntos, fecha_plan_inicio, fecha_plan_fin","id_documento like '$id_documento-%'","id_documento, consecutivo");
        $dd4=$td4->select("id_documento, consecutivo, descripcion, responsable as proveedor, cliente, puntos, fecha_plan_inicio, fecha_plan_fin","id_documento like '$id_documento-%'","id_documento, consecutivo");
        ?>
        <table border="0" align="center">
            <tr><td align="right">
                <!--<img height="15" src="../Imagenes/iconografia/02.png" />
                <img height="15" src="../Imagenes/iconografia/15.png" onClick="javascript: print();" />-->
            </td></tr>
            <tr><td>
                <table border="0" align="center">
                    <tr><td>
                        <table border="0" align="center" style="table-layout:fixed;">
                            <tr>
                                <td align="left" width="300" colspan="2">Teresita</td>
                                <td align="right" width="150">Fecha:</td>
                                <td align="left" width="150"><?php echo htmlentities(DateConvencional($dd1[0]["fecha"],true));?></td>
                            </tr>
                            <tr>
                                <td align="left" width="300" colspan="2">&nbsp;</td>
                                <td align="right" width="150">Ubicaci&oacute;n:</td>
                                <td align="left" width="150"><?php echo htmlentities($dd1[0]["lugar"]);?></td>
                            </tr>
                            <tr>
                                <td align="right" width="150">Coordinador:</td>
                                <td align="left" width="150"><?php echo htmlentities($coord[0]["nomb"]); ?></td>
                                <td align="right" width="150">D&iacute;as Activa:</td>
                                <td align="left" width="150"><?php echo htmlentities($dias_act[0]["da"]); ?></td>
                            </tr>
                        </table>
                    </td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td align="center">
                        <strong><?php echo htmlentities($ddg[0]["agrupador"]);?></strong>
                    </td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>
                        <table border="0" align="center" style="table-layout:fixed;">
                            <tr><td colspan="7" width="600"><strong><?php echo htmlentities($comps_title[0]["nombre_seccion"]); ?></strong></td></tr>
                            <tr style="font-size:.85em;"><th align="left">Num</th><th align="left">Descripci&oacute;n</th><th align="left">Inicio</th><th align="left">Fin</th><th align="left">Proveedor</th><th align="left">Cliente</th><th align="left">Puntos</th></tr>
                            <?php
                            foreach($dd3 as $reg)
                            {
                                $aux=explode("-",$reg["id_documento"]);
                                $pr=$tp->select("concat(nombre) as nomb","clave='".$reg["proveedor"]."'");
                                $cl=$tp->select("concat(nombre) as nomb","clave='".$reg["cliente"]."'");
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($aux[1]."-".$reg["consecutivo"]); ?></td>
                                    <td><?php echo htmlentities($reg["descripcion"]); ?></td>
                                    <td><?php echo htmlentities(DateConvencional($reg["fecha_plan_inicio"],true)); ?></td>
                                    <td><?php echo htmlentities(DateConvencional($reg["fecha_plan_fin"],true)); ?></td>
                                    <td><?php echo htmlentities($pr[0]["nomb"]); ?></td>
                                    <td><?php echo htmlentities($cl[0]["nomb"]); ?></td>
                                    <td><?php echo htmlentities(intval($reg["puntos"])); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>
                        <table border="0" align="center" style="table-layout:fixed;">
                            <tr><td colspan="7" width="600"><strong><?php echo htmlentities($tareas_title[0]["nombre_seccion"]); ?></strong></td></tr>
                            <tr style="font-size:.85em;"><th align="left">Num</th><th align="left">Descripci&oacute;n</th><th align="left">Inicio</th><th align="left">Fin</th><th align="left">Proveedor</th><th align="left">Cliente</th><th align="left">Puntos</th></tr>
                            <?php
                            foreach($dd4 as $reg)
                            {
                                $aux=explode("-",$reg["id_documento"]);
                                $pr=$tp->select("concat(nombre) as nomb","clave='".$reg["proveedor"]."'");
                                $cl=$tp->select("concat(nombre) as nomb","clave='".$reg["cliente"]."'");
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($aux[1]."-".$reg["consecutivo"]); ?></td>
                                    <td><?php echo htmlentities($reg["descripcion"]); ?></td>
                                    <td><?php echo htmlentities(DateConvencional($reg["fecha_plan_inicio"],true)); ?></td>
                                    <td><?php echo htmlentities(DateConvencional($reg["fecha_plan_fin"],true)); ?></td>
                                    <td><?php echo htmlentities($pr[0]["nomb"]); ?></td>
                                    <td><?php echo htmlentities($cl[0]["nomb"]); ?></td>
                                    <td><?php echo htmlentities(intval($reg["puntos"])); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </td></tr>
                </table>
            </td></tr>
        </table>
        <?php
    }
    else if($accion=="eliminar_minuta" && $id_documento!="")
    {
        $aux=CTabla("docto_general");
        $aux->delete("id_documento='$id_documento'");
        $aux=CTabla("docto1");
        $aux->delete("id_documento='$id_documento'");
        $aux=CTabla("docto2");
        $aux->delete("id_documento like '$id_documento-%'");
        $aux=CTabla("docto3");
        $aux->delete("id_documento like '$id_documento-%'");
        $aux=CTabla("docto4");
        $aux->delete("id_documento like '$id_documento-%'");
        $aux=CTabla("docto5");
        $aux->delete("id_documento like '$id_documento-%'");
    }
    else if($accion=="cambio_estatus" && $id_documento!="" && $new_val!="")
    {
        $aux=CTabla("docto1");
        $aux->update(array('estatus'=>$new_val),"id_documento like '$id_documento'");
    }
    else if($accion=="ver_columnas_minuta_inicio" && $id_documento!="")
    {
    }
    else if($accion=="ver_filtros_minuta_inicio" && $id_documento!="")
    {
    }
    else if($accion=="actualiza_columnas_minuta_inicio" && $id_documento!="")
    {
    }
    else if($accion=="actualiza_filtros_minuta_inicio" && $id_documento!="")
    {
    }
    else if($accion=="ver_captura_minuta_inicio")
    {
        ?>
        <form id="form_minuta_nivel0">
        <table border="0" align="center">
            <tr><td align="right" colspan="4"><img src="Imagenes/iconografia/10.png" alt="Guardar" title="Guardar" onclick="SalvaMinuta_nivel0()" /></td></tr>
            <tr>
                <td width="123" align="right">Organizaci&oacute;n:</td>
                <td colspan="3" align="left"><input type="text" size="50" maxlength="250" id="organizacion" name="organizacion" readonly="readonly" /> <input type="button" value="Teresita" style="border-style:solid; background-color:#DCE6F2; border-width:1px; height:25px; width:75px;" onclick="javascript: $('organizacion').value=this.value;" /></td>
            </tr>
            <tr>
                <td align="right">Fecha:</td>
                <td colspan="3" align="left"><?php
                $aux=CTabla("persona");
                $hoy=$aux->select("curdate() as hoy");
                $manana=$aux->select("adddate(curdate(),1) as manana");
                ?><input type="text" size="10" maxlength="250" id="fecha" name="fecha" readonly="readonly" /> <input type="button" value="Hoy" style="border-style:solid; background-color:#DCE6F2; border-width:1px; height:25px; width:75px;" onclick="javascript: $('fecha').value='<?php echo $hoy[0]["hoy"]; ?>';" /> <input type="button" value="Ma&ntilde;ana" style="border-style:solid; background-color:#DCE6F2; border-width:1px; height:25px; width:75px;" onclick="javascript: $('fecha').value='<?php echo $manana[0]["manana"]; ?>';" /> <button id="fechador" type="button" style="background-color:#FFFFFF; border-style:none; margin:0px; padding:0px;"><img id="img_cal" src="Imagenes/iconografia/16.png" /></button></td>
            </tr>
            <tr><td colspan="4">&nbsp;</td></tr>
            <tr><td align="right">Coordinador:</td><td width="102"><select name="coordinador" id="coordinador"><?php
                $aux=CTabla("persona");
                $regs=$aux->select("concat(nombre) as nomb, clave","clave in (select persona from herr_pant_responsables)","concat(nombre)");
                foreach($regs as $reg) echo '<option value="'.$reg["clave"].'">'.htmlentities($reg["nomb"]).'</option>';
            ?></select></td>
            <td width="184" align="right">D&iacute;as Activa:</td>
            <td width="79"><input type="text" size="5" maxlength="3" value="90" name="dias_activa" id="dias_activa" /></td></tr>
            <tr><td colspan="4">&nbsp;</td></tr>
            <tr>
                <td align="right" valign="top">Participantes:</td>
                <td colspan="3" align="left"><?php
                    $regs=$aux->select("concat(nombre) as nomb, clave","clave in (select persona from herr_pant_responsables)","concat(nombre)");
                    foreach($regs as $reg) echo '<label><input type="checkbox" value="'.$reg["clave"].'" name="participante[]" id="participante[]" /> '.htmlentities($reg["nomb"]).'</label><br />';
                ?></td>
            </tr>
            <tr><td align="center" colspan="4">&nbsp;</td></tr>
            <tr><td align="center" colspan="4">T&iacute;tulo: <input type="text" maxlength="250" size="50" id="agrupador" name="agrupador" /></td></tr>
        </table>
        </form>
        <?php
    }
    else if($accion=="agrega_minuta_inicio")
    {
        $organizacion = Get_Vars_Helper::getPGVar("organizacion");
        $fecha = Get_Vars_Helper::getPGVar("fecha");
        $dias_activa = Get_Vars_Helper::getPGVar("dias_activa");
        $coordinador = Get_Vars_Helper::getPGVar("coordinador");
        $participante = Get_Vars_Helper::getPostVar("participante") != "" ? Get_Vars_Helper::getPostVar("participante") : Get_Vars_Helper::getGetVar("participante");
        $agrupador = Get_Vars_Helper::getPGVar("agrupador");
        if($fecha!="" && $coordinador!="" && $agrupador!="")
        {
            $aux=CTabla("docto_general");
            $cuantos=$aux->select("count(*)+1 as n","id_documento like 'M%' and tipo_documento='1'");
            $id="M".$cuantos[0]["n"];
            $aux->insert(array("id_documento"=>$id, "nombre"=>"Minuta", "nombre_corto"=>"Minuta", "tipo_documento"=>"1", "agrupador"=>$agrupador, "origen"=>'1', "fuente"=>"1"));
            $aux=CTabla("docto1");
            $cuantos=$aux->select("count(*)+1 as n","id_documento='$id'");
            $consec=$cuantos[0]["n"];
            $aux->insert(array("id_documento"=>$id, "consecutivo"=>$consec, "posicion"=>$consec, "coordinador"=>$coordinador, "estatus"=>"1", "fecha"=>"fecha", "dias_activa"=>$dias_activa));
            echo "El documento ha sido agregado";
        }
        else
        {
            echo "Es necesario llenar todos los campos";
        }
    }
}
?>
