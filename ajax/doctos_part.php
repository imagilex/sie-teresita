<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Teresita.com.mx</title>
<link href="../estilos.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
include_once("../apoyo.php");

class documento
{
    private $tbl_gral;
    private $tbl_secc;
    private $id_docto;
    public function __construct($el_id)
    {
        $tbl_gral=CTabla("docto_general");
        $tbl_secc=CTabla("docto_secciones");
        $this->id_docto=$el_id;
    }
    public function SeeAll()
    {
        $tbl_gral=CTabla("docto_general");
        $tbl_secc=CTabla("docto_secciones");
        $tbl_cg=CTabla("codigos_generales");
        list($id,$consec)=explode("-",$this->id_docto);
        $general=$tbl_gral->select("*","id_documento='$id'");
        $secciones=$tbl_secc->select("*","documento='".$general[0]["tipo_documento"]."'");
        if($general[0]["tipo_documento"]=="1")//Minuta
        {
            $tbl_doctopart=CTabla("docto_participantes");
            $tbl_docto1=CTabla("docto1");
            $tbl_docto2=CTabla("docto2");
            $tbl_docto3=CTabla("docto3");
            $tbl_docto4=CTabla("docto4");
            $tbl_docto5=CTabla("docto5");
            $doctopart=$tbl_doctopart->select("*","id_documento='".$this->id_docto."'");
            $docto1=$tbl_docto1->select("*","id_documento='$id'");
            $docto2=$tbl_docto2->select("*","id_documento='".$this->id_docto."'","consecutivo");
            $docto3=$tbl_docto3->select("*","id_documento='".$this->id_docto."'","consecutivo");
            $docto4=$tbl_docto4->select("*","id_documento='".$this->id_docto."'","consecutivo");
            $docto5=$tbl_docto5->select("*","id_documento='".$this->id_docto."'","consecutivo");
            $date_system=getdate();
            $dia=$mes=$dia_letra="";
            $dia=$date_system["mday"];
            if($date_system["wday"]=="0") $dia_letra="Domingo";
            else if($date_system["wday"]=="1") $dia_letra="Lunes";
            else if($date_system["wday"]=="2") $dia_letra="Martes";
            else if($date_system["wday"]=="3") $dia_letra="Miercoles";
            else if($date_system["wday"]=="4") $dia_letra="Jueves";
            else if($date_system["wday"]=="5") $dia_letra="Viernes";
            else if($date_system["wday"]=="6") $dia_letra="Sabado";
            if($date_system["mon"]=="1") $mes="Enero";
            else if($date_system["mon"]=="2") $mes="Febrero";
            else if($date_system["mon"]=="3") $mes="Marzo";
            else if($date_system["mon"]=="4") $mes="Abril";
            else if($date_system["mon"]=="5") $mes="Mayo";
            else if($date_system["mon"]=="6") $mes="Junio";
            else if($date_system["mon"]=="7") $mes="Julio";
            else if($date_system["mon"]=="8") $mes="Agosto";
            else if($date_system["mon"]=="9") $mes="Septiembre";
            else if($date_system["mon"]=="10") $mes="Octubre";
            else if($date_system["mon"]=="11") $mes="Noviembre";
            else if($date_system["mon"]=="12") $mes="Diciembre";
            $taux=CTabla("persona");
            $aux=$taux->select("concat(nombre) as nomb","clave='".$docto1[0]["coordinador"]."'");
            $coordinador=$aux[0]["nomb"];
            $participantes="";
            foreach($doctopart as $part)
            {
                $aux=$taux->select("concat(nombre) as nomb","clave='".$part["participante"]."'");
                $participantes .= $aux[0]["nomb"].", ";
            }
            $participantes=substr($participantes,0,strlen($participantes)-2);
            $hoy=$date_system["year"]."-".(intval($date_system["mon"])<10?"0":"").$date_system["mon"]."-".(intval($date_system["mday"])<10?"0":"").$date_system["mday"];
            $aux=$tbl_docto1->select("DATEDIFF(CURDATE(),fecha) as dias","id_documento='".$this->id_docto."'");
            $dias_act=$aux[0]["dias"];
            ?>
            <table border="0" align="center" width="680">
                <tr>
                    <td align="left"><strong>Minuta</strong></td>
                    <td align="right" width="20%"><?php echo DateConvencional($hoy,true); ?></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td colspan="2">
                        <table border="0" width="100%">
                            <tr>
                                <td align="left" width="25%">Teresita</td>
                                <td align="left" width="25%"></td>
                                <td align="right" width="25%">Fecha:</td>
                                <td align="left" width="25%"><?php echo DateConvencional($docto1[0]["fecha"],true); ?></td>
                            </tr>
                            <tr>
                                <td align="right" width="25%"></td>
                                <td align="left" width="25%"></td>
                                <td align="right" width="25%">Ubicaci&oacute;n:</td>
                                <td align="left" width="25%"><?php echo $docto1[0]["lugar"]?></td>
                            </tr>
                            <tr>
                                <td align="right" width="25%">Coordinador:</td>
                                <td align="left" width="25%"><?php echo $coordinador; ?></td>
                                <td align="right" width="25%">D&iacute;as Activa:</td>
                                <td align="left" width="25%"><?php echo $dias_act; ?></td>
                            </tr>
                            <tr>
                                <td align="right" width="25%">Participantes:</td>
                                <td align="left" colspan="3" width="75%"><?php echo $participantes; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td align="center" colspan="2"><strong><?php echo $general[0]["agrupador"]; ?></strong></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2">Palabras Clave: <?php echo $docto1[0]["palabras_clave"]; ?></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <!-- Orden del dia -->
                <tr><td colspan="2" align="left"><strong><?php
                    $nombre=$secciones[2]["nombre_seccion"];
                    if($nombre!="") echo $nombre;
                    else
                    {
                        $taux=CTabla("docto_alias");
                        $nombre=$taux->select("alias","tabla='".$secciones[2]["tabla"]."'");
                        echo $nombre[0]["alias"];
                    }
                ?></strong></td></tr>
                <tr><td>
                    <table border="0" style="table-layout:fixed">
                        <?php
                        if(@count($docto2))
                        {
                            foreach($docto2 as $elem)
                            {
                                ?>
                                <tr><td width="30" align="left"></td><td align="left" width="680"><?php echo $elem["descripcion"]; ?></td></tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <!-- Komps -->
                <tr><td colspan="2" align="left"><strong><?php
                    $nombre=$secciones[3]["nombre_seccion"];
                    if($nombre!="") echo $nombre;
                    else
                    {
                        $taux=CTabla("docto_alias");
                        $nombre=$taux->select("alias","tabla='".$secciones[3]["tabla"]."'");
                        echo $nombre[0]["alias"];
                    }
                ?></strong></td></tr>
                <tr><td>
                    <table border="0" style="table-layout:fixed">
                        <?php
                        if(@count($docto3))
                        {
                            ?>
                            <tr style="font-size:9px"><th align="left" width="30">Num</th><th align="left" width="500">Descripci&oacute;n</th><th align="left" width="40">Inicio</th><th align="left" width="40">Fin</th><th align="left" width="150">Proveedor</th><th align="left" width="150">Cliente</th><th align="left" width="75">Puntos</th><th align="left" width="75">Estatus</th></tr>
                            <?php
                            $taux=CTabla("persona");
                            foreach($docto3 as $elem)
                            {
                                $prov=$taux->select("concat(nombre) as nomb","clave='".$elem["responsable"]."'");
                                $cl=$taux->select("concat(nombre) as nomb","clave='".$elem["cliente"]."'");
                                $est=$tbl_cg->select("descripcion","campo='plan_trab_estatus' and valor='".$elem["estatus"]."'");
                                ?>
                                <tr><td align="left" widtd="30"><?php echo $elem["consecutivo"]; ?></td><td align="left" widtd="500"><?php echo $elem["descripcion"]; ?></td><td align="left" widtd="40"><?php echo DateConvencional($elem["fecha_plan_inicio"],true); ?></td><td align="left" widtd="40"><?php echo DateConvencional($elem["fecha_plan_fin"],true); ?></td><td align="left" widtd="75"><?php echo $prov[0]["nomb"]; ?></td><td align="left" widtd="75"><?php echo $cl[0]["nomb"]; ?></td><td align="left" widtd="75"><?php echo $elem["puntos"]; ?></td><td align="left" widtd="75"><?php echo $est[0]["descripcion"]; ?></td></tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <!-- Tareas -->
                <tr><td colspan="2" align="left"><strong><?php
                    $nombre=$secciones[4]["nombre_seccion"];
                    if($nombre!="") echo $nombre;
                    else
                    {
                        $taux=CTabla("docto_alias");
                        $nombre=$taux->select("alias","tabla='".$secciones[4]["tabla"]."'");
                        echo $nombre[0]["alias"];
                    }
                ?></strong></td></tr>
                <tr><td>
                    <table border="0" style="table-layout:fixed">
                        <?php
                        if(@count($docto4))
                        {
                            ?>
                            <tr style="font-size:9px"><th align="left" width="30">Num</th><th align="left" width="500">Descripci&oacute;n</th><th align="left" width="40">Inicio</th><th align="left" width="40">Fin</th><th align="left" width="150">Proveedor</th><th align="left" width="150">Cliente</th><th align="left" width="75">Puntos</th><th align="left" width="75">Estatus</th></tr>
                            <?php
                            $taux=CTabla("persona");
                            foreach($docto4 as $elem)
                            {
                                $prov=$taux->select("concat(nombre) as nomb","clave='".$elem["responsable"]."'");
                                $cl=$taux->select("concat(nombre) as nomb","clave='".$elem["cliente"]."'");
                                $est=$tbl_cg->select("descripcion","campo='plan_trab_estatus' and valor='".$elem["estatus"]."'");
                                ?>
                                <tr><td align="left" widtd="30"><?php echo $elem["consecutivo"]; ?></td><td align="left" widtd="500"><?php echo $elem["descripcion"]; ?></td><td align="left" widtd="40"><?php echo DateConvencional($elem["fehca_plan_inicio"],true); ?></td><td align="left" widtd="40"><?php echo DateConvencional($elem["fehca_plan_fin"],true); ?></td><td align="left" widtd="75"><?php echo $prov[0]["nomb"]; ?></td><td align="left" widtd="75"><?php echo $cl[0]["nomb"]; ?></td><td align="left" widtd="75"><?php echo $elem["puntos"]; ?></td><td align="left" widtd="75"><?php echo $est[0]["descripcion"]; ?></td></tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <!-- Acuerdos -->
                <tr><td colspan="2" align="left"><strong><?php
                    $nombre=$secciones[5]["nombre_seccion"];
                    if($nombre!="") echo $nombre;
                    else
                    {
                        $taux=CTabla("docto_alias");
                        $nombre=$taux->select("alias","tabla='".$secciones[5]["tabla"]."'");
                        echo $nombre[0]["alias"];
                    }
                ?></strong></td></tr>
                <tr><td>
                    <table border="0" style="table-layout:fixed">
                        <?php
                        if(@count($docto5))
                        {
                            foreach($docto5 as $elem)
                            {
                                ?>
                                <tr><td width="30" align="left"></td><td align="left" width="680"><?php echo $elem["referencia"]; ?></td></tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </td></tr>
            </table>
            <?php
        }
        else if($general[0]["tipo_documento"]=="2")//Plan de Trabajo
        {
            $tfase=CTabla("docto7");
            $tdocto6=CTabla("docto6");
            $tentregable=CTabla("docto3");
            $fases=$tfase->select("descripcion, datediff(fecha_plan_fin,fecha_plan_inicio) as duracion, fecha_plan_inicio, fecha_plan_fin, responsable, cliente, puntos, id_documento, fase, datediff(curdate(), fecha_plan_fin) as da","id_documento='".$this->id_docto."'","cast(fase as signed)");
            $taux=CTabla("persona");
            $resp1=$tdocto6->select("responsable","id_documento='".$general[0]["id_documento"]."'");
            $resp=$taux->select("nombre","clave='".$resp1[0]["responsable"]."'");
            if(@count($fases)>0)
            {
                $x=0
                ?>
                <table border="0" align="center"><tr><td>
                <table border="0" width="100%"><tr><td width="50%" align="left"><strong><?php echo $general[0]["nombre"]; ?></strong></td>
                <td align="right" width="50%">Responsable: <?php echo $resp[0]["nombre"]; ?></td>
                </tr></table>
                </td></tr><tr><td>
                <table border="1" cellpadding="0" cellspacing="0" align="center">
                    <tr style="color:#999999;"><th></th><th align="left" style="padding:5px;">Descripci&oacute;n</th><th align="left" style="padding:5px;">Duracion</th><th align="left" style="padding:5px;">Inicio</th><th align="left" style="padding:5px;">Fin</th><th align="left" style="padding:5px;">Proveedor</th><th align="left" style="padding:5px;">Cliente</th><th align="left" style="padding:5px;">Puntos</th><th align="left" style="padding:5px;">D&iacute;as atraso</th></tr>
                    <?php
                    foreach($fases as $fase)
                    {
                        $x++;
                        $cl=$taux->select("nombre","clave='".$fase["cliente"]."'");
                        $resp=$taux->select("nombre","clave='".$fase["responsable"]."'");
                        ?>
                        <tr style="font-weight:bold;">
                            <td style="padding:5px;"><?php echo $x; ?></td>
                            <td style="padding:5px;"><?php echo $fase["descripcion"]; ?></td>
                            <td style="padding:5px;"><?php echo $fase["duracion"]; ?></td>
                            <td style="padding:5px;"><?php echo DateConvencional($fase["fecha_plan_inicio"], true); ?></td>
                            <td style="padding:5px;"><?php echo DateConvencional($fase["fecha_plan_fin"], true); ?></td>
                            <td style="padding:5px;"><?php echo $resp[0]["nombre"]; ?></td>
                            <td style="padding:5px;"><?php echo $cl[0]["nombre"]; ?></td>
                            <td style="padding:5px;"><?php echo intval($fase["puntos"]); ?></td>
                            <td style="padding:5px;"><?php echo (intval($fase["da"])>0?$fase["da"]:"0"); ?></td>
                        </tr>
                        <?php
                        $entregables=$tentregable->select("descripcion, datediff(fecha_plan_fin,fecha_plan_inicio) as duracion, fecha_plan_inicio, fecha_plan_fin, responsable, cliente, puntos, id_documento, fase, datediff(curdate(), fecha_plan_fin) as da","id_documento='".$this->id_docto."' and fase='".$fase["fase"]."'","cast(fase as signed), cast(consecutivo as signed)");
                        foreach($entregables as $entregable)
                        {
                            $x++;
                            $cl=$taux->select("nombre","clave='".$entregable["cliente"]."'");
                            $resp=$taux->select("nombre","clave='".$entregable["responsable"]."'");
                            ?>
                            <tr>
                                <td style="padding:5px;"><?php echo $x; ?></td>
                                <td style="padding:5px; padding-left:15px;"><?php echo $entregable["descripcion"]; ?></td>
                                <td style="padding:5px;"><?php echo $entregable["duracion"]; ?></td>
                                <td style="padding:5px;"><?php echo DateConvencional($entregable["fecha_plan_inicio"], true); ?></td>
                                <td style="padding:5px;"><?php echo DateConvencional($entregable["fecha_plan_fin"], true); ?></td>
                                <td style="padding:5px;"><?php echo $resp[0]["nombre"]; ?></td>
                                <td style="padding:5px;"><?php echo $cl[0]["nombre"]; ?></td>
                                <td style="padding:5px;"><?php echo intval($entregable["puntos"]); ?></td>
                                <td style="padding:5px;"><?php echo (intval($entregable["da"])>0?$entregable["da"]:"0"); ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
                </td></tr></table>
                <?php
            }
        }
    }
}

$id_docto = Get_Vars_Helper::getPGVar("id_docto");
$act = Get_Vars_Helper::getPGVar("act");
if($act!="")
{
    if($act=="display_all")
    {
        $aux=new documento($id_docto);
        $aux->SeeAll();
    }
    else if($act=="")
    {

    }
}
?>
</body>
</html>
