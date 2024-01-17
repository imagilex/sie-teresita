<?php

header("Expires: Mon, 22 Sep 1997 09:00:00 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");

include("../apoyo.php");

$id_documento = Get_Vars_Helper::getPGVar("id_documento");
$fase = Get_Vars_Helper::getPGVar("fase");
$consecutivo = Get_Vars_Helper::getPGVar("consecutivo");

$tbl_comps = CTabla("docto3");
$tbl_parts = CTabla("docto_participantes");
$tbl_gral = CTabla("docto_general");
$tbl_cg = CTabla("codigos_generales");
$tbl_pers = CTabla("persona");

$data_comps = $tbl_comps->select("id_documento, fase, consecutivo, descripcion, responsable, cliente, fecha_plan_inicio, fecha_plan_fin, fecha_real_inicio, fecha_real_fin, estatus, fecha_act_inicio, fecha_act_fin, puntos, fecha_captura, datediff(fecha_plan_fin,curdate()) as dias_venc, datediff(curdate(),fecha_plan_inicio) as dias_act","id_documento='$id_documento' and fase='$fase' and consecutivo='$consecutivo'");
$data_parts = $tbl_parts->select("participante","id_documento='$id_documento'");
$data_gral = $tbl_gral->select("*","id_documento='$id_documento'");

$origen = $tbl_cg->select("descripcion","campo='docto_origen' and valor='".$data_gral[0]["origen"]."'");
$tipo = $tbl_cg->select("descripcion","campo='docto_origen' and valor='".$data_gral[0]["tipo_documento"]."'");

$docto = explode("-",$id_documento);
$id_docto_p1 = $docto[0];
if(isset($docto[1])) $id_docto_p2=$docto[1];
else $id_docto_p2="";
$estatus = $tbl_cg->select("descripcion","campo='docto_comps_estatus' and valor='".$data_comps[0]["estatus"]."'");;
$resp = $tbl_pers->select("nombre","clave='".$data_comps[0]["responsable"]."'");
$parts = "";
foreach($data_parts as $reg)
{
    $tmp=$tbl_pers->select("nombre", "clave='".$reg["participante"]."'");
    $parts.=" ".$tmp[0]["nombre"].",";
}
$parts=substr($parts,0,strlen($parts)-1);
?>
<table border="0" align="center">
    <tr><td>Komp <?php echo $id_documento; ?>-<?php echo $fase; ?>-<?php echo $consecutivo; ?></td><td align="right" rowspan="2"><input type="button" value="Documentos" onclick="AbreDocto('<?php echo $id_documento; ?>')" /></td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td><strong style="color:#0000CC;"><?php echo $origen[0]["descripcion"]; ?></strong></td>
        <td rowspan="3" style="padding-right:30px;" align="right" valign="top">
            <table border="0">
                <tr><td align="right">Captura:</td><td align="left"><?php echo DateConvencional($data_comps[0]["fecha_captura"],true); ?></td>
                </tr>
                <tr><td align="right">Inicio:</td><td align="left"><?php echo DateConvencional($data_comps[0]["fecha_plan_inicio"],true); ?></td></tr>
                <tr><td align="right">Fin:</td><td align="left"><?php echo DateConvencional($data_comps[0]["fecha_plan_fin"],true); ?></td></tr>
            </table>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td align="center">
        <table border="0">
            <tr><td align="right">Proyecto:</td><td align="left"><?php echo htmlentities($data_gral[0]["agrupador"]); ?></td></tr>
            <tr><td align="right"><?php echo htmlentities(($data_gral[0]["tipo_documento"]=="2"?"Plan de Trabajo":"Minuta")); ?>:</td><td align="left"><?php echo htmlentities($data_gral[0]["nombre"]); ?></td></tr>
            <tr><td align="center" colspan="2"><?php echo htmlentities(($id_docto_p2!=""?"Minuta numero $id_docto_p2":"")); ?></td></tr>
            <tr><td align="center" colspan="2"><strong><?php echo htmlentities($data_comps[0]["descripcion"]); ?></strong></td>
            </tr>
        </table>
    </td></tr>
    <tr>
        <td>&nbsp;</td>
        <td align="right" rowspan="2" style="padding-right:30px;" valign="top">
            <table border="0">
                <tr><td align="right">D&iacute;as para vencimiento:</td><td align="left"><?php echo htmlentities($data_comps[0]["dias_venc"]); ?></td></tr>
                <tr><td align="right">D&iacute;as activado:</td><td align="left"><?php echo htmlentities($data_comps[0]["dias_act"]); ?></td></tr>
            </table>
        </td>
    </tr>
    <tr><td>
        <table border="0">
            <tr><td align="left"><strong><font color="<?php echo (($data_comps[0]["estatus"]=="1")?("#006600"):(($data_comps[0]["estatus"]=="")?("#990000"):("#000000"))); ?>"><?php echo htmlentities($estatus[0]["descripcion"]); ?></font></strong></td></tr>
            <tr><td align="left" style="text-transform:capitalize;">Responsable: <?php echo htmlentities($resp[0]["nombre"]); ?></td></tr>
            <tr><td align="left" style="text-transform:capitalize;">Participantes: <?php echo htmlentities($parts); ?></td></tr>
        </table>
    </td></tr>
</table>
