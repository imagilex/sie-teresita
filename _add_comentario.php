<?php

include "apoyo.php";

$Con=Conectar();

$comentario=Get("comentario").PostString("comentario");
$id_reporte=Get("id_reporte").PostString("id_reporte");
$id_usuario=Get("id_usuario").PostString("id_usuario");

$date_actual=getdate();
$fecha=$date_actual["year"]."-".$date_actual["mon"]."-".$date_actual["mday"];

if($comentario != "" && $id_reporte != "" && $id_usuario != "")
{
	consulta_directa("insert into reporte_comentarios (id_reporte, id_usuario, fecha, comentario) values ('$id_reporte', '$id_usuario', '$fecha', '$comentario')");
}

if($id_reporte!="" && $comentarios=consulta_directa("select fecha, comentario, nombre, apaterno from reporte_comentarios, usuario where id_reporte='$id_reporte' and reporte_comentarios.id_usuario=usuario.id_usuario order by nombre, apaterno, fecha"))
{
	$nombre="";
	$primero=true;
	?>

	<?php
	while($comentario=mysqli_fetch_array($comentarios))
	{
		if($primero==true)
		{
			$nombre=$comentario["nombre"]." ".$comentario["apaterno"];
			$primero=false;
			?>
			<fieldset><legend><?php echo $nombre; ?> </legend><dl>
			<?php
		}
		else if($nombre!=($comentario["nombre"]." ".$comentario["apaterno"]))
		{
			$nombre=$comentario["nombre"]." ".$comentario["apaterno"];
			?>
			</dl></fieldset><br />
			<fieldset><legend><?php echo $nombre; ?> </legend><dl>
			<?php
		}
		?>
		<dt><?php echo substr($comentario["fecha"],8,2)."/".substr($comentario["fecha"],5,2)."/".substr($comentario["fecha"],0,4); ?>:</dt>
		<dd><?php echo str_replace("\n",'<br />',$comentario["comentario"]); ?></dd>
		<?php
	}
	?>
	</dl></fieldset><br />
	<?php
}

mysqli_close($Con);

?>
