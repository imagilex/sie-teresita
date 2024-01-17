<?php
include "apoyo.php";

$Con=Conectar();

$id_reporte = Get_Vars_Helper::getPostVar("reporte");

if($id_reporte!="" && $comentarios=consulta_directa("select fecha, comentario, concat(nombre,' ', apaterno) as nombre from reporte_comentarios, usuario where id_reporte='$id_reporte' and reporte_comentarios.id_usuario=usuario.id_usuario order by nombre,fecha"))
{
    ?>
    <dl>
    <?php
    while($comentario=mysqli_fetch_array($comentarios))
    {
        ?>
        <dt><?php echo $comentario["nombre"]; ?> (<?php echo substr($comentario["fecha"],8,2)."/".substr($comentario["fecha"],5,2)."/".substr($comentario["fecha"],0,4)."/"; ?>)</dt>
        <dd><?php echo $comentario["comentario"]; ?></dd>
        <?php
    }
    ?>
    </dl>
    <?php
}
?>
