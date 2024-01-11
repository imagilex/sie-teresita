<?php 

include("apoyo.php"); 

$Con=Conectar();

$informacion=pathinfo($archivo=$Dir.Get("archivo"));

if($archivo!="" && file_exists($archivo))
{
	if($informacion["extension"]=="" || $informacion["extension"]=="txt")
	{
		if($Arch=@fopen($archivo,"r"))
		{
			?><pre><?php
			while(!feof($Arch))
				echo fgets($Arch)."";
			fclose($Arch);
			?></pre><?php
		}
	}
	else if($informacion["extension"]=="jpg" || $informacion["extension"]=="png" || $informacion["extension"]=="bmp" || $informacion["extension"]=="gif")
	{
		?>
		<img src="<?php echo substr($archivo, strlen($Dir)+1); ?>" border="0" />
		<?php
	}
}

mysql_close($Con);

?>