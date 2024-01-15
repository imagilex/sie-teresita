<?php

session_start();

include "apoyo.php";

$esta=false;

$Con=Conectar();

$comentario=Get("comentario").PostString("comentario");
$id_usuario=Get("id_usuario").PostString("id_usuario");
$indicador=Get("indicador").PostString("indicador");
$id_indicador_nivel=Get("nivel").PostString("nivel");
$anio=Get("anio").PostString("anio");
$mes=Get("mes").PostString("mes");
$fecha=Get("fecha").PostString("fecha");

if($comentario != "" && $indicador != "" && $id_indicador_nivel != "" && $id_usuario != "")
{
	$usr=mysqli_fetch_array(consulta_directa($Con, "select clave as `usuario` from usuario where clave='$id_usuario'"));
	$usuario=$usr["usuario"];
	$niv=mysqli_fetch_array(consulta_directa($Con, "select nivel from indicador_nivel where id_indicador_nivel='$id_indicador_nivel'"));
	$nivel=$niv["nivel"];
	$posicion=mysqli_fetch_array(consulta_directa($Con, "select count(*) as n from indicador_comentario where indicador='$indicador' and nivel='$nivel' and anio = '$anio' and mes='$mes'"));
	$pos=intval($posicion["n"])+1;
	if($fecha=="")
		consulta_directa($Con, "insert into indicador_comentario (anio, mes, indicador, pos, usuario, comentario, nivel, fecha) values ('$anio', '$mes', '$indicador', '$pos', '$usuario', '$comentario', '$nivel', curdate())");
	else
		consulta_directa($Con, "insert into indicador_comentario (anio, mes, indicador, pos, usuario, comentario, nivel, fecha) values ('$anio', '$mes', '$indicador', '$pos', '$usuario', '$comentario', '$nivel', '$fecha')");
}

if($indicador != "" && $id_indicador_nivel != "")
{
	$niv=mysqli_fetch_array(consulta_directa($Con, "select nivel from indicador_nivel where id_indicador_nivel='$id_indicador_nivel'"));
	$nivel=$niv["nivel"];
	if($usuarios=consulta_directa($Con, "select nombre, clave from persona where clave in (select distinct(persona) from usuario where clave in (select distinct(usuario) from indicador_comentario where indicador='$indicador' and nivel='$nivel' and anio = '$anio' and mes='$mes'))"))
	{
		while($usr=mysqli_fetch_array($usuarios))
		{
			?>
				<fieldset>
					<legend>
						<?php echo NoAcute($usr["nombre"]); ?>:
						<?php
							if($data_usr=mysqli_fetch_array(consulta_directa($Con, "select count(*) as n from persona where clave='".$usr["clave"]."' and clave in (select persona from usuario where clave = '".$_SESSION["id_usr"]."')")))
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
						if($comentarios=consulta_directa($Con, "select comentario, fecha, usuario from indicador_comentario where indicador='$indicador' and nivel='$nivel' and anio = '$anio' and mes='$mes' and usuario in (select clave from usuario where persona = '".$usr["clave"]."') order by fecha"))
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

mysqli_close($Con);
?>