<?php

include("apoyo.php");

$Con=Conectar();

$Archivo=$Dir."/".Get("archivo").PostString("archivo");

if(file_exists($Archivo))
{
	$info=pathinfo($Archivo);
	if($info["extension"]=="txt")
	{
		if($Arch=@fopen($Archivo,"r"))
		{
			?><pre style="font-size:12px;"><?php
			while(!feof($Arch))
				echo fgets($Arch)."";
			fclose($Arch);
			?></pre><?php
		}
	}
	else if($info["extension"]=="jpg" || $info["extension"]=="png" || $info["extension"]=="bmp" || $info["extension"]=="wmf" || $info["extension"]=="gif")
	{
		?><img border="0" src="<?php echo Get("archivo"); ?>" /><?
	}
}

mysql_close($Con);

?>