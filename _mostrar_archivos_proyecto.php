<?php
include "apoyo.php";
$Con=Conectar();

$proyecto=PostString("proyecto").Get("proyecto");
$accion=PostString("accion").Get("accion");

$arch=false;

if($accion=="ver" && $archs_db=consulta_directa($Con, "select persona, archivo, tipo from proyecto_archivo where proyecto = '$proyecto' order by posicion, archivo"))
{
	$x=-1;
	while($archdb=mysqli_fetch_array($archs_db))
	{
		$x++;
		$archivos[$x]=$archdb;
		$arch=true;
	}
}
else if($accion=="borrar" && $archs_db=consulta_directa($Con, "select persona, archivo, tipo from proyecto_archivo where proyecto = '$proyecto' order by posicion, archivo"))
{
	$x=-1;
	while($archdb=mysqli_fetch_array($archs_db))
	{
		$x++;
		$archivos[$x]=$archdb;
		$arch=true;
	}
}

if($arch)
{
	echo "opciones[0]= {persona: \"\" , archivo: \"\" , tipo: \"\" };";
	for($x=0;$x<count($archivos);$x++)
	{
		echo "opciones[".($x+1)."]= {persona: \"".$archivos[$x]["persona"]."\" , archivo: \"".$archivos[$x]["archivo"]."\" , tipo: \"".$archivos[$x]["tipo"]."\" };";
	}
}
else
{
	echo "{};";
}

mysqli_close($Con);
?>
