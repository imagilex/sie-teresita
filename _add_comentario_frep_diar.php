<?php

session_start();

include "apoyo.php";

$esta = false;

$Con = Conectar();

$comentario = Get_Vars_Helper::getPGVar("comentario");
$id_usuario = Get_Vars_Helper::getPGVar("id_usuario");
$id_reporte = Get_Vars_Helper::getPGVar("id_reporte");
$fecha = Get_Vars_Helper::getPGVar("fecha");
$fecha_reporte = Get_Vars_Helper::getPGVar("fecha_reporte");

if($comentario != "" && $id_reporte != "" && $id_usuario != "" && $fecha_reporte!="")
{
	$usr=mysqli_fetch_array(consulta_directa("select clave as `usuario` from usuario where clave='$id_usuario'"));
	$usuario=$usr["usuario"];
	if($fecha=="")
		consulta_directa("insert into reporte_comentario (id_reporte, usuario, comentario, fecha, fecha_reporte) values ('$id_reporte', '$usuario', '$comentario', curdate(), '$fecha_reporte')");
	else
		consulta_directa("insert into reporte_comentario (id_reporte, usuario, comentario, fecha, fecha_reporte) values ('$id_reporte', '$usuario', '$comentario', '$fecha', '$fecha_reporte')");
}

if($id_reporte != "")
{
	if($usuarios=consulta_directa("select nombre, clave from persona where clave in (select distinct(persona) from usuario where clave in (select distinct(usuario) from reporte_comentario where id_reporte='$id_reporte' and fecha_reporte='$fecha_reporte'))"))
	{
		while($usr=mysqli_fetch_array($usuarios))
		{
			?>
				<fieldset>
					<legend>
						<?php echo NoAcute($usr["nombre"]); ?>:
						<?php
							if($data_usr=mysqli_fetch_array(consulta_directa("select count(*) as n from persona where clave='".$usr["clave"]."' and clave in (select persona from usuario where clave = '".$_SESSION["id_usr"]."')")))
								if(intval($data_usr["n"])>0)
								{
									$esta=true;
									?>
										<input type="button" name="btnAddCommentSpace" id="btnAddCommentSpace" value="Anexar Comentario" class="btn_extra_1" onclick="AddCommentSpace('hoy');" />
								 	<?php
								}
						?>
					</legend>
					<ul type="circle">
						<?php
						if($comentarios=consulta_directa("select comentario, fecha, usuario from reporte_comentario where id_reporte='$id_reporte' and fecha_reporte='$fecha_reporte' and usuario in (select clave from usuario where persona = '".$usr["clave"]."') order by fecha"))
						{
							while($coment=mysqli_fetch_array($comentarios))
							{
								if($coment["fecha"]!="") $fecha=substr($coment["fecha"],8,2)."/".substr($coment["fecha"],5,2)."/".substr($coment["fecha"],0,4).": ";
								else $fecha="";
								?>
								<li><?php echo $fecha; ?>
									<div id="<?php echo $coment["fecha"]; ?>" <?php
									if(intval($data_usr["n"])>0)
									{
										if($coment["usuario"]==$_SESSION["id_usr"])
											echo 'ondblclick="AddCommentSpace('."'".$coment["fecha"]."'".')"';
									}
									?>><?php echo NoAcute(str_replace("\n","<br />",$coment["comentario"])); ?></div>
								</li>
								<?php
							}
						}
						?>
					</ul>
					<?php
						if(intval($data_usr["n"])>0)
						{
							?>
							<div id="elComentario"></div>
							<?php
						}
					?>
				</fieldset><br />
			<?php
		}
	}
}

if(! $esta)
{
	?>
			<input type="button" name="btnAddCommentSpace" id="btnAddCommentSpace" value="Anexar Comentario" class="btn_extra_1" onclick="AddCommentSpace('hoy');" />
		<div id="elComentario"></div>
	<?php
}
?>
