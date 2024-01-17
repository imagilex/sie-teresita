<?php

include_once "includes/loader.php";

function consulta_directa(string $query, int $modo = MYSQLI_STORE_RESULT) {
    return MAIN_DB->query($query, $modo);
}

function Conectar()
{
    try {
        return MAIN_DB->conectar();
    } catch (Exception $e) {
        Alert($e->getMessage());
    }
}

$Dir = dirname(__FILE__);
include_once("util_base_datos/mysql/mysql_data_base.php");

function CTabla($tabla)
{
    $aux = new Table(
        MAIN_DB->host, MAIN_DB->usr, MAIN_DB->pass, MAIN_DB->bd, $tabla);
    $aux->query("optimize table $tabla");
    $aux->query("repair table $tabla");
    return $aux;
}
function BarraHerramientas($barra_menu=false,$elem=0,$favoritos=true)
{
    $mysqli = Conectar();
    $regs=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Principal' and elemento='Btn_salir'"));
    ErrorMySQLAlert($mysqli);
    $boton_salir = isset($regs["valor"]) ? $regs["valor"] : "";
    $regs=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Principal' and elemento='Logo'"));
    ErrorMySQLAlert($mysqli);
    $Logo = isset($regs["valor"]) ? $regs["valor"] : "";
    $regs=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Principal' and elemento='Linea1'"));
    ErrorMySQLAlert($mysqli);
    $linea1 = isset($regs["valor"]) ? $regs["valor"] : "";
    $regs=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Principal' and elemento='Linea2'"));
    ErrorMySQLAlert($mysqli);
    $linea2 = isset($regs["valor"]) ? $regs["valor"] : "";
    ?>
<div id="div_menu" style=" border:top:3px; left:92%; width:336px; height:28px; position:absolute; z-index:200; vertical-align:middle; text-align:center; margin-left:-330px; visibility:hidden;" ><?php
        $prefijo_actual='0.4';
        $tipo_usuario_actual = isset($_SESSION["tipo"]) ? $_SESSION["tipo"] : "";
        $tmenu=CTabla("menu");
        $itemsq=$tmenu->query("select menu.opcion, menu.descripcion, menu_agrupador.agrupador, menu_agrupador.icono from menu inner join menu_agrupador on menu.agrupador=menu_agrupador.id_agrupador where menu.prefijo_menu='$prefijo_actual' and concat(prefijo_menu,'.',opcion) in (select concat(prefijo_menu,'.',opcion) from funcion_menu where funcion in (select funcion from tipo_usuario_funcion where tipo_usuario='$tipo_usuario_actual')) order by menu_agrupador.posicion, menu.posicion");

//echo "select menu.opcion, menu.descripcion, menu_agrupador.agrupador, menu_agrupador.icono from menu inner join menu_agrupador on menu.agrupador=menu_agrupador.id_agrupador where menu.prefijo_menu='$prefijo_actual' and concat(prefijo_menu,'.',opcion) in (select concat(prefijo_menu,'.',opcion) from funcion_menu where funcion in (select funcion from tipo_usuario_funcion where tipo_usuario='$tipo_usuario_actual')) order by menu_agrupador.posicion, menu.posicion";

        $items=array();
        while($itq=$tmenu->registro($itemsq))
            $items[]=$itq;
        $agrupador="";
        //print_r($items);
        if(count($items)>0)
        {
            $x=0;
            $y=0;
            ?>
            <div id="barra_menu" style="text-align:center;">
                <div class="bd">
                    <ul class="yui-overlay">
                        <?php
                        foreach($items as $item)
                        {
                            if($agrupador!=$item["agrupador"])
                            {
                                if($agrupador!="")
                                {
                                    ?>
                  </ul></div></div></li>
                                    <?php
                                }
                                $agrupador=$item["agrupador"];
                                if($x==0)
                                {
                                    ?>
                                    <li class="yui-overlay" style="margin-left:28px; vertical-align:bottom;"><img src="Imagenes/menu/<?php echo $item["icono"]; ?>"  border="0" align="bottom" />
                                        <div id="<?php echo $item["agrupador"]; ?>_menu">
                                            <div class="bd">
                                            <ul>
                                                <li><a class="item_menu" href="entrada.php?ir_a=<?php echo $item["opcion"]; ?>" onmousemove="javascript: this.className='item_menu_over';" onmouseout="javascript: this.className='item_menu';"><?php echo $item["descripcion"]; ?></a></li>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <li style="margin-left:28px; vertical-align:bottom; background-color:#F2F2F2;"><img src="Imagenes/menu/<?php echo $item["icono"]; ?>" border="0" align="bottom" />
                                        <div class="yui-overlay" id="<?php echo $item["agrupador"]; ?>_menu">
                                            <div class="bd">
                                            <ul>
                                                <li><a class="item_menu" href="entrada.php?ir_a=<?php echo $item["opcion"]; ?>" onmousemove="javascript: this.className='item_menu_over';" onmouseout="javascript: this.className='item_menu';"><?php echo $item["descripcion"]; ?></a></li>
                                    <?php
                                }
                            }
                            else
                            {
                                ?>
                                <li><a class="item_menu" href="entrada.php?ir_a=<?php echo $item["opcion"]; ?>" onmousemove="javascript: this.className='item_menu_over';" onmouseout="javascript: this.className='item_menu';"><?php echo $item["descripcion"]; ?></a></li>
                                <?php
                            }
                            $x++;
                        }
                        if($agrupador!="")
                        {
                            ?>
                            </ul></div></div></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php
        }
    ?></div>
    <!--AQUI SE DECLARA EL COLOR DE FONDO DE LA BOTONERA-->
    <table width="100%" bgcolor="f2f2f2" height="31">
        <tr>
            <td align="left" valign="top">
                <table border="0" width="100%">
                <?php
                if($barra_menu)
                {
                    ?><tr><td><div style=""><!--<a href="index.php"><img src="Archivos_Secciones/<?php echo $Logo; ?>" border="0" /></a>--></div></td></tr><?php
                }
                ?>
                <?php
        if($favoritos)
        {
            ?>
            <script language="javascript">
                function trim (myString)
                {
                    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
                }
                function MuestraFav()
                {
                    var elDiv=$('div_fav');
                    if(elDiv.style.visibility)
                        elDiv.style.visibility='visible';
                    else if(elDiv.style.display)
                        elDiv.style.display='block';
                    else elDiv.style.visibility='visible';
                }
                function OcultaFav()
                {
                    var elDiv=$('div_fav');
                    if(elDiv.style.visibility)
                        elDiv.style.visibility='hidden';
                    else if(elDiv.style.display)
                        elDiv.style.display='none';
                    else elDiv.style.visibility='hidden';
                }
                function AddFav()
                {
                    var nombre = (prompt("Nombre del vínculo")) || "favorito";
                    if(nombre=="favorito")
                        return false;
                    var url_request = location.pathname;
                    var parametros = location.search.substring(1);
                    var cadena = "_add_to_fav.php?nombre="+nombre+"&urlrequest="+url_request+"&parametros='"+parametros+"'";
                    window.open(cadena,"addFav");
                }
                function DelFav()
                {
                    var checks=document.getElementsByTagName('input');
                    var cads="", valor,x ,y, url="_del_to_fav.php?usr=<?php echo $_SESSION["id_usr"]; ?>";
                    for(x=0;x<checks.length;x++)
                    {
                        if(checks[x].type=="checkbox" && checks[x].checked)
                        {
                            nomb="";
                            valor=checks[x].value.split('*');
                            if(valor.length>=2 && valor[0]=="fav")
                            {
                                for(y=1;y<valor.length;y++)
                                {
                                    nomb += valor[y]+" ";
                                }
                            }
                            nomb=trim(nomb);
                            url += "&nombre"+x+"="+nomb;
                        }
                    }
                    window.open(url,"delFav");
                }
            </script>
            <!--COMIENZA LA BOTONERA FAVORITOS-->
            <tr><td colspan="2" align="left">
                <?php
                if (isset($_SESSION["id_usr"]) && $_SESSION["id_usr"]!="0")
                {
                ?>
                <!--
                <img src="Imagenes/fav.bmp" border="0" onmousemove="MuestraFav();" />
                <img src="Imagenes/fav_add.bmp" border="0" onclick="AddFav()" />
                -->
                <?php
                }
                ?>
            </td></tr>
            <tr><td colspan="2" valign="top">
                <div id="div_fav" style="width:250px; height:200px; position:absolute; z-index:1; background-color:#EEEEEE; visibility:hidden;" onmouseout="OcultaFav();">
                    <table border="0" align="left" width="100%" onmousemove="MuestraFav()">
                        <tr><td align="right"><input type="button" value="Eliminar" style="width:75px; height:25px;" onclick="DelFav();" /></td></tr>
                        <?php
                        if($favs=consulta_directa("select nombre, url_request, parametros_url from favoritos where usuario = '".$_SESSION["id_usr"]."' order by nombre, url_request"))
                        {
                            while($fav=mysqli_fetch_array($favs))
                            {
                                ?>
                                <tr onmousemove="MuestraFav()"><td onmousemove="javascript: this.style.background='#FFFFFF'; MuestraFav();" onmouseout="javascript: this.style.background='#EEEEEE';" ondblclick="javascript: location.href='<?php echo $fav["url_request"]."?".$fav["parametros_url"]; ?>'">
                                    <label><input type="checkbox" value="fav*<?php echo $fav["nombre"]; ?>" /><?php echo $fav["nombre"]; ?></label>
                                </td></tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>
            </td></tr>
            <?php
        }
        ?>
                </table>            </td>
            <td valign="top" align="right">
                <div align="right" style="vertical-align:top; padding:0px; margin:0px;">
                    <form name="Administracion" action="entrada.php" method="post" style="padding:0px; margin:0px;">
                    <?php
                    if($barra_menu)
                    {
                        ?>
                        <!--<a href="index.php?ira=1"><?php if ($elem==1) echo '<font color="#999999">'; ?>&iquest;Qui&eacute;nes somos?<?php if ($elem==1) echo '</font>'; ?></a> |-->
                        <a href="index.php?ira=2"><?php if ($elem==2) echo '<font color="#999999">'; ?>Nuestros Productos<?php if ($elem==2) echo '</font>'; ?></a> |
                        <!--<a href="index.php?ira=3"><?php if ($elem==3) echo '<font color="#999999">'; ?>Cont&aacute;ctenos<?php if ($elem==3) echo '</font>'; ?></a> |-->
                    <a href="javascript:void(0);" onClick="PantallaCompleta('entrada.php');"><?php if ($elem==4) echo '<font color="#999999">'; ?>S I E<?php if ($elem==4) echo '</font>'; ?></a> |
                    <?php
                    }
                    ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                      <img src="Archivos_Secciones/<?php echo $boton_salir; ?>" onclick="javascript: /*window.close()*/location.href='index.php';" />
                    </form>
                    </div>
                    </td>
        </tr>
    </table>
    <?php
}
function MostrarArchivo($Archivo,$fin="<br />")
{
    $maximo=-32000;
    if(file_exists($Archivo))
    {
        if($Arch=@fopen($Archivo,"r"))
        {
            while(!feof($Arch))
            {
                $cad=fgets($Arch);
                echo $cad.$fin;
                if($maximo<strlen($cad))
                    $maximo=strlen($cad);
            }
            fclose($Arch);
        }
        else
        {
            echo "";
            trigger_error("Archivo no encontrado: $Archivo");
        }
    }
    else
    {
        echo "";
        trigger_error("Archivo no encontrado: $Archivo");
    }
    unset($Arch);
    return $maximo;
}
function CboCG($Campo)
{
    $mysqli = Conectar();
    $Cad="";
    $qry = "select valor,descripcion from codigos_generales where campo='$Campo' order by posicion,descripcion";
    if($Regs=consulta_directa($qry))
        while($Reg=mysqli_fetch_array($Regs))
        {
            // $descr=preg_replace('á',"&aacute;",$Reg["descripcion"]);
            // $descr=preg_replace('é','&eacute;',$descr);
            // $descr=preg_replace('í','&iacute;',$descr);
            // $descr=preg_replace('ó','&oacute;',$descr);
            // $descr=preg_replace('ú','&uacute;',$descr);
            // $descr=preg_replace('Á','&Aacute;',$descr);
            // $descr=preg_replace('É','&Eacute;',$descr);
            // $descr=preg_replace('Í','&Iacute;',$descr);
            // $descr=preg_replace('Ó','&Oacute;',$descr);
            // $descr=preg_replace('Ú','&Uacute;',$descr);
            // $descr=preg_replace('Ñ','&Ntilde;',$descr);
            // $descr=preg_replace('ñ','&ntilde;',$descr);
            $descr = $Reg["descripcion"];
            $Cad=$Cad."\n<option value=\"".$Reg["valor"]."\">".$descr."</option>";
        }
    return $Cad;
}
function Alert($Mensaje)
{
    ?>
    <script language="javascript">
        alert("<?php echo $Mensaje; ?>");
    </script>
    <?php
}
function ErrorMySQL($cnn)
{
    if($cnn && $cnn->errno)
        return $cnn->errno.": ".$cnn->error;
    return "";
}
function ErrorMySQLAlert($cnn)
{
    if(ErrorMySQL($cnn)!="")
        Alert(ErrorMySQL($cnn));
}
function FormFecha($Variable)
{
    $Dia=FormComboNum($Variable."_d",1,31,1);
    $Mes=FormComboNum($Variable."_m",1,12,1);
    $Mes="<select name=\"$Variable"."_m\" id=\"$Variable\"><option value=\"0\"></option>";
    $Mes=$Mes."<option value=\"1\">Ene</option>";
    $Mes=$Mes."<option value=\"2\">Feb</option>";
    $Mes=$Mes."<option value=\"3\">Mar</option>";
    $Mes=$Mes."<option value=\"4\">Abr</option>";
    $Mes=$Mes."<option value=\"5\">May</option>";
    $Mes=$Mes."<option value=\"6\">Jun</option>";
    $Mes=$Mes."<option value=\"7\">Jul</option>";
    $Mes=$Mes."<option value=\"8\">Ago</option>";
    $Mes=$Mes."<option value=\"9\">Sep</option>";
    $Mes=$Mes."<option value=\"10\">Oct</option>";
    $Mes=$Mes."<option value=\"11\">Nov</option>";
    $Mes=$Mes."<option value=\"12\">Dic</option>";
    $Mes=$Mes."</select>";
    $Anio="<input type=\"text\" maxlength=\"4\" size=\"4\" name=\"$Variable"."_a\" onblur=\"javascript: if(document.getElementById('$Variable"."_a').value.length!=4) alert('El año debe ser de cuatro digitos');\" />";
    return $Dia." / ".$Mes." / ".$Anio;
}
function FormComboNum($Variable,$Inicio,$Fin,$Incremento)
{
    $Cad="";
    for($x=$Inicio;$x<=$Fin;$x+=$Incremento)
        $Cad=$Cad."<option value=\"$x\">$x</option>";
    return '<select name="'.$Variable.'"><option></option>'.$Cad.'</select>';
}
function NoAcute($cad)
{
    $cadena=$cad;
    $cadena=str_replace("á","&aacute;",$cadena);
    $cadena=str_replace("é","&eacute;",$cadena);
    $cadena=str_replace("í","&iacute;",$cadena);
    $cadena=str_replace("ó","&oacute;",$cadena);
    $cadena=str_replace("ú","&uacute;",$cadena);
    $cadena=str_replace("Á","&Aacute;",$cadena);
    $cadena=str_replace("É","&Eacute;",$cadena);
    $cadena=str_replace("í","&Iacute;",$cadena);
    $cadena=str_replace("Ó","&Oacute;",$cadena);
    $cadena=str_replace("Ú","&Uacute;",$cadena);
    $cadena=str_replace("ñ","&ntilde;",$cadena);
    $cadena=str_replace("Ñ","&Ntilde;",$cadena);
    return $cadena;
}
function menu_items($tipo_usuario_actual, $prefijo_actual)
{
    $mysqli = Conectar();
    $cuantos=mysqli_fetch_array(consulta_directa("select count(distinct(agrupador)) as n from menu where prefijo_menu='$prefijo_actual'"));
    $cad="";
    if(intval($cuantos["n"])>1)
    {
        if($agrupadores=consulta_directa("select distinct(agrupador) as gpo from menu where prefijo_menu='$prefijo_actual'"))
        {
            while($agrupador=mysqli_fetch_array($agrupadores))
            {
                $contador=mysqli_fetch_array(consulta_directa("select count(*) as n from (select descripcion, opcion from menu where concat( prefijo_menu, '.', opcion) in (select concat( prefijo_menu, '.', opcion) from funcion_menu where funcion in (select funcion from tipo_usuario_funcion where tipo_usuario='$tipo_usuario_actual')) and prefijo_menu='$prefijo_actual' and agrupador='".$agrupador["gpo"]."' order by posicion) as tbl01"));
                if(intval($contador["n"])>0)
                {
                    $cad.='<optgroup label="'.$agrupador["gpo"].'">';
                }
                if($items=consulta_directa("select descripcion, opcion from menu where concat( prefijo_menu, '.', opcion) in (select concat( prefijo_menu, '.', opcion) from funcion_menu where funcion in (select funcion from tipo_usuario_funcion where tipo_usuario='$tipo_usuario_actual')) and prefijo_menu='$prefijo_actual' and agrupador='".$agrupador["gpo"]."' order by posicion"))
                {
                    while($item=mysqli_fetch_array($items))
                    {
                        $cad.='<option value="'.$item["opcion"].'">'.NoAcute($item["descripcion"]).'</option>';
                    }
                }
                if(intval($contador["n"])>0)
                {
                    $cad.='</optgroup>';
                }
            }
        }
    }
    else
    {
        if($items=consulta_directa("select descripcion, opcion from menu where concat( prefijo_menu, '.', opcion) in (select concat( prefijo_menu, '.', opcion) from funcion_menu where funcion in (select funcion from tipo_usuario_funcion where tipo_usuario='$tipo_usuario_actual')) and prefijo_menu='$prefijo_actual' order by posicion"))
        {
            while($item=mysqli_fetch_array($items))
            {
                $cad.='<option value="'.$item["opcion"].'">'.NoAcute($item["descripcion"]).'</option>';
            }
        }
    }
    echo $cad;
}
function BH_Ayuda($prefijo,$opcion)
{
    $mysqli = Conectar();
    $query="select descripcion, archivo from pantalla_ayuda inner join ayuda on pantalla_ayuda.ayuda = ayuda.ayuda where prefijo_menu='$prefijo' and opcion = '$opcion' order by posicion, descripcion";
    $cuantos=mysqli_fetch_array(consulta_directa("select count(*) as n from ($query) as tbl_01"));
    if(intval($cuantos["n"])>0 && $items=consulta_directa($query))
    {
        ?>
        <script language="javascript">
            function Ayuda_en_BH(archivo)
            {
                var url="help.php?file="+archivo;
                if(archivo!="")
                    window.open(url, "ayuda");
                document.getElementById('ayuda_bh').value="";
            }
        </script>
        <table border="0" width="100%">
            <tr>
                <td align="right">
                    Ayuda:
                    <select name="ayuda_bh" id="ayuda_bh" onchange="Ayuda_en_BH(this.value)">
                        <option value=""></option>
                        <?php
                        while($item=mysqli_fetch_array($items))
                        {
                            ?>
                            <option value="<?php echo $item["archivo"]; ?>">
                                <?php echo $item["descripcion"]; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
}
function DateMySQL($Variable)
{
    if(strlen($Variable)==10 && substr($Variable,2,1)=="/" && substr($Variable,5,1)=="/")
        return substr($Variable,6,4)."-".substr($Variable,3,2)."-".substr($Variable,0,2);
    return $Variable;
}
function DateConvencional($Variable,$as_text=false)
{
    if(strlen($Variable)==10 && substr($Variable,4,1)=="-" && substr($Variable,7,1)=="-")
    {
        if(!$as_text)
            return substr($Variable,8,2)."/".substr($Variable,5,2)."/".substr($Variable,0,4);
        else
        {
            $mes="";
            switch(substr($Variable,5,2))
            {
                case "01": $mes="Enero"; break;
                case "02": $mes="Febrero"; break;
                case "03": $mes="Marzo"; break;
                case "04": $mes="Abril"; break;
                case "05": $mes="Mayo"; break;
                case "06": $mes="Junio"; break;
                case "07": $mes="Julio"; break;
                case "08": $mes="Agosto"; break;
                case "09": $mes="Septiembre"; break;
                case "10": $mes="Octubre"; break;
                case "11": $mes="Noviembre"; break;
                case "12": $mes="Diciembre"; break;
            }
            $fecha=getdate();
            if($mes!="") return $mes." ".substr($Variable,8,2).(substr($Variable,0,4)!=$fecha["year"]?", ".substr($Variable,0,4):"");
        }
    }
    return "";
}
function Esta_en($array,$elemento)
{
    foreach($array as $elem)
        if($elem==$elemento)
            return true;
    return false;
}
function DisplaySQLD3($lista,$usuar)
{
    $Con=Conectar();
    $columnas=array();
    if($regs=consulta_directa("select docto3_columnas.columna, docto3_columnas_alias.tabla, docto3_columnas.orden, docto3_columnas.etiqueta from docto3_columnas inner join docto3_columnas_alias on docto3_columnas.columna=docto3_columnas_alias.columna where docto3_columnas.usuario='$usuar' order by docto3_columnas.posicion"))
    {
        while($reg=mysqli_fetch_array($regs,MYSQLI_ASSOC))
        {
            $columnas[]=$reg;
        }
    }
    if(@count($columnas))
    {
        $cols=array();
        $tablas=array();
        foreach($columnas as $columna)
        {
            if(!Esta_en($cols,$columna["columna"])) $cols[]=array("columna"=>$columna["columna"],"orden"=>$columna["orden"],"etiqueta"=>$columna["etiqueta"]);
            if(!Esta_en($tablas,$columna["tabla"])) $tablas[]=$columna["tabla"];
        }
        $select="select id_documento as id_documento_x, fase as fase_x, consecutivo as consecutivo_x, ";
        $from=" from ";
        $where=" where concat(docto3.id_documento,'-',docto3.fase,'-',docto3.consecutivo) in (select concat(lista_comps.id_documento,'-',lista_comps.fase,'-',lista_comps.consecutivo) from lista_comps where id_lista='$lista') ";
        $orden=" order by ";
        foreach($cols as $col)
        {
            $select .= $col["columna"]." as '".$col["etiqueta"]."',";
            $orden .= $col["columna"]." ".$col["orden"].",";
        }
        foreach($tablas as $tabla)
        {
            $from .= $tabla.",";
        }
        $select = substr($select,0,strlen($select)-1);
        $orden = substr($orden,0,strlen($orden)-1);
        $from = substr($from,0,strlen($from)-1);
        return "$select $from $where $orden";
    }
    return "";
}
function Komp($id_documento,$fase,$consecutivo)
{
    $tbl_comps=CTabla("docto3");
    $tbl_parts=CTabla("docto_participantes");
    $tbl_gral=CTabla("docto_general");
    $tbl_cg=CTabla("codigos_generales");
    $tbl_pers=CTabla("persona");

    $data_comps=$tbl_comps->select("id_documento, fase, consecutivo, descripcion, responsable, cliente, fecha_plan_inicio, fecha_plan_fin, fecha_real_inicio, fecha_real_fin, estatus, fecha_act_inicio, fecha_act_fin, puntos, fecha_captura, datediff(fecha_plan_fin,curdate()) as dias_venc, datediff(curdate(),fecha_plan_inicio) as dias_act","id_documento='$id_documento' and fase='$fase' and consecutivo='$consecutivo'");
    $data_parts=$tbl_parts->select("participante","id_documento='$id_documento'");
    $data_gral=$tbl_gral->select("*","id_documento='$id_documento'");

    $origen=$tbl_cg->select("descripcion","campo='docto_origen' and valor='".$data_gral[0]["origen"]."'");
    $tipo=$tbl_cg->select("descripcion","campo='docto_origen' and valor='".$data_gral[0]["tipo_documento"]."'");

    $docto=explode("-",$id_documento);
    $id_docto_p1=$docto[0];
    if(isset($docto[1])) $id_docto_p2=$docto[1];
    else $id_docto_p2="";
    $estatus=$tbl_cg->select("descripcion","campo='docto_comps_estatus' and valor='".$data_comps[0]["estatus"]."'");;
    $resp=$tbl_pers->select("nombre","clave='".$data_comps[0]["responsable"]."'");
    $parts="";
    foreach($data_parts as $reg)
    {
        $tmp=$tbl_pers->select("nombre", "clave='".$reg["participante"]."'");
        $parts.=" ".$tmp[0]["nombre"].",";
    }
    $parts=substr($parts,0,strlen($parts)-1);
    $cad="";
    $cad='
        <table border="0" align="center">
            <tr>
                <td>
                    Komp '.$id_documento.'-'.$fase.'-'.$consecutivo.'
                </td>
                <td align="right" rowspan="2">
                    <input type="button" value="Documentos" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <strong style="color:#0000CC;">
                        '.$origen[0]["descripcion"].'
                    </strong>
                </td>
                <td rowspan="3" style="padding-right:30px;" align="right" valign="top">
                    <table border="0">
                        <tr>
                            <td align="right">
                                Captura:
                            </td>
                            <td align="left">
                                '.DateConvencional($data_comps[0]["fecha_captura"]).'
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                Inicio:
                            </td>
                            <td align="left">
                                '.DateConvencional($data_comps[0]["fecha_plan_inicio"]).'
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                Fin:
                            </td>
                            <td align="left">
                                '.DateConvencional($data_comps[0]["fecha_plan_fin"]).'
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="center">
                    <table border="0">
                        <tr>
                            <td align="right">
                                Proyecto:
                            </td>
                            <td align="left">
                                '.htmlentities($data_gral[0]["agrupador"]).'
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                '.htmlentities(($data_gral[0]["tipo_documento"]=="2"?"Plan de Trabajo":"Minuta")).':
                            </td>
                            <td align="left">
                                '.htmlentities($data_gral[0]["nombre"]).'
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">
                                '.htmlentities(($id_docto_p2!=""?"Minuta numero $id_docto_p2":"")).'
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">
                                <strong>
                                    '.htmlentities($data_comps[0]["descripcion"]).'
                                </strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td align="right" rowspan="2" style="padding-right:30px;" valign="top">
                    <table border="0">
                        <tr>
                            <td align="right">
                                D&iacute;as para vencimiento:
                            </td>
                            <td align="left">
                                '.htmlentities($data_comps[0]["dias_venc"]).'
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                D&iacute;as activado:
                            </td>
                            <td align="left">
                                '.htmlentities($data_comps[0]["dias_act"]).'
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="0">
                        <tr>
                            <td align="left">
                                <strong>
                                    <font color="'.(($data_comps[0]["estatus"]=="1")?("#006600"):(($data_comps[0]["estatus"]=="")?("#990000"):("#000000"))).'">
                                        '.htmlentities($estatus[0]["descripcion"]).'
                                    </font>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="text-transform:capitalize;">
                                Responsable: '.htmlentities($resp[0]["nombre"]).'
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="text-transform:capitalize;">
                                Participantes: '.htmlentities($parts).'
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>';
    return $cad;
}
function Parametro($menu,$elem,$caract)
{
    $aux=CTabla("estandares");
    $daux=$aux->select("id_estandar, valor_default", "elemento='$elem' and menu='$menu' and caracteristica='$caract'");
    $aux2=CTabla("estandares_usuario");
    $daux2=$aux2->select("valor","id_estandar='".$daux[0]["id_estandar"]."' and usuario='".$_SESSION["id_usr"]."'");
    if($daux2[0]["valor"]!="") return $daux2[0]["valor"];
    else return $daux[0]["valor_default"];
}

function B_reportes()
{
    echo "
        <table bgcolor='f2f2f2' width='100%' height='40' border='0' align='center' cellpadding='0' cellspacing='0'>
            <tr>
                <td>
                    <table border='0' align='left' cellpadding='0' cellspacing='0'>
                        <tr>
                            <td>
                                <img src='Imagenes/menu/varilla.gif' width='14' height='1'/>
                            </td>
                            <td>";
                                ?>
                                <img src="Imagenes/menu/home.png" alt="Inicio" title="Inicio" onclick="javascript: /*window.close()*/location.href='entrada.php';" />
                                <?php echo "
                            </td>
                            <td>
                                <img src='Imagenes/menu/varilla.gif' width='14' height='1'>
                            </td>
                            <td>";
                                ?>
                                <?php echo "
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <div align='center'>
                        $titulo
                    </div>
                </td>
                <td>
                    <table border='0' align='right' cellpadding='0' cellspacing='0'>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>";
                                ?>
                                <img src="Imagenes/Btn_salir.png" onclick="javascript: /*window.close()*/location.href='index.php';" />
                                <?php echo "
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    ";
}

function BH_Plan()
{
    echo"<table width='100%' border='0' bgcolor='F2F2F2'>
    <table width='100%' border='0' bgcolor='F2F2F2'>
    <tr>
        <th scope='col'><table border='0' align='left' cellpadding='0' cellspacing='0'><tr><td style='background-image:url(Imagenes/iconografia/go.png); background-repeat:no-repeat; width:88px; height:20px; vertical-align:middle; text-align:center; font-size:9px; font-weight:bold;' onclick='javascript: location.href='entrada.php''>Inicio</td></tr></table></th>
        <th scope='col'>&nbsp;</th>
        <th scope='col'><table border='0' align='right'>
        <tr>
            <th scope='col'>&nbsp;</th>
            <th scope='col'><img height='15' title='Editar' alt='Editar' src='Imagenes/iconografia/11.png' onclick='EjecutaAccion_nivel0(2)' /></th>
            <th scope='col'><img height='15' title='Agregar' alt='Agregar' src='Imagenes/iconografia/07.png' onclick='EjecutaAccion_nivel0(2)' /></th>
            <th scope='col'><a href='index.php'><img height='15' title='Salir' alt='Salir' src='Archivos_Secciones/Btn_salir.png' /></a></th>
        </tr>
        </table></th>
    </tr>
    </table>";
}
function BH2()
{
    $mysqli = Conectar();
    $regs=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Principal' and elemento='Btn_salir'"));
    ErrorMySQLAlert($mysqli);
    $boton_salir=$regs["valor"];
    $regs=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Principal' and elemento='Logo'"));
    ErrorMySQLAlert($mysqli);
    $Logo=$regs["valor"];
    $regs=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Principal' and elemento='Linea1'"));
    ErrorMySQLAlert($mysqli);
    $linea1=$regs["valor"];
    $regs=mysqli_fetch_array(consulta_directa("select valor from seccion where id_seccion='Principal' and elemento='Linea2'"));
    ErrorMySQLAlert($mysqli);
    $linea2=$regs["valor"];
    $favoritos=true;
    ?>
      <table width="100%">
        <tr>
            <td align="left" valign="top">
                <table border="0" width="100%">

                    <?php
                    if($favoritos)
                    {
                        ?>
                        <script language="javascript">
                            function trim (myString)
                            {
                                return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
                            }
                            function MuestraFav()
                            {
                                var elDiv=$('div_fav');
                                if(elDiv.style.visibility)
                                    elDiv.style.visibility='visible';
                                else if(elDiv.style.display)
                                    elDiv.style.display='block';
                                else elDiv.style.visibility='visible';
                            }
                            function OcultaFav()
                            {
                                var elDiv=$('div_fav');
                                if(elDiv.style.visibility)
                                    elDiv.style.visibility='hidden';
                                else if(elDiv.style.display)
                                    elDiv.style.display='none';
                                else elDiv.style.visibility='hidden';
                            }
                            function AddFav()
                            {
                                var nombre = (prompt("Nombre del vínculo")) || "favorito";
                                if(nombre=="favorito")
                                    return false;
                                var url_request = location.pathname;
                                var parametros = location.search.substring(1);
                                var cadena = "_add_to_fav.php?nombre="+nombre+"&urlrequest="+url_request+"&parametros='"+parametros+"'";
                                window.open(cadena,"addFav");
                            }
                            function DelFav()
                            {
                                var checks=document.getElementsByTagName('input');
                                var cads="", valor,x ,y, url="_del_to_fav.php?usr=<?php echo $_SESSION["id_usr"]; ?>";
                                for(x=0;x<checks.length;x++)
                                {
                                    if(checks[x].type=="checkbox" && checks[x].checked)
                                    {
                                        nomb="";
                                        valor=checks[x].value.split('*');
                                        if(valor.length>=2 && valor[0]=="fav")
                                        {
                                            for(y=1;y<valor.length;y++)
                                            {
                                                nomb += valor[y]+" ";
                                            }
                                        }
                                        nomb=trim(nomb);
                                        url += "&nombre"+x+"="+nomb;
                                    }
                                }
                                window.open(url,"delFav");
                            }
                        </script>
                        <tr>
                            <td colspan="2" align="left">
                                <?php
                                if (isset($_SESSION["id_usr"]) && $_SESSION["id_usr"]!="0")
                                {
                                    ?>
                                    <img src="Imagenes/fav.bmp" border="0" onmousemove="MuestraFav();" />
                                    <img src="Imagenes/fav_add.bmp" border="0" onclick="AddFav()" />
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">
                                <div id="div_fav" style="width:250px; height:200px; position:absolute; z-index:1; background-color:#EEEEEE; visibility:hidden;" onmouseout="OcultaFav();">
                                    <table border="0" align="left" width="100%" onmousemove="MuestraFav()">
                                        <tr>
                                            <td align="right">
                                                <input type="button" value="Eliminar" style="width:75px; height:25px;" onclick="DelFav();" />
                                            </td>
                                        </tr>
                                        <?php
                                        if($favs=consulta_directa("select nombre, url_request, parametros_url from favoritos where usuario = '".$_SESSION["id_usr"]."' order by nombre, url_request"))
                                        {
                                            while($fav=mysqli_fetch_array($favs))
                                            {
                                                $url_fav = $fav["url_request"]."?".$fav["parametros_url"];
                                                ?>
                                                <tr onmousemove="MuestraFav()">
                                                    <td
                                                            onmousemove="javascript: this.style.background='#FFFFFF'; MuestraFav();"
                                                            onmouseout="javascript: this.style.background='#EEEEEE';"
                                                            ondblclick="javascript: location.href='<?php echo $url_fav; ?>'">
                                                        <label>
                                                            <input type="checkbox" value="fav*<?php echo $fav["nombre"]; ?>" /><?php echo $fav["nombre"]; ?>
                                                        </label>
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
                        <?php
                    }
                    ?>

                </table>
            </td>
            <td valign="top" align="right" width="10">
                <img src="Archivos_Secciones/<?php echo $boton_salir; ?>" onclick="javascript: location.href='index.php';" />
            </td>
        </tr>
    </table>
    <?php
}
?>
