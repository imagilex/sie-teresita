<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<script language="javascript">
	var mov=20;
	function go_left()
	{
		scrollBy(-1*mov,0);
	}
	function go_right()
	{
		scrollBy(mov,0);
	}
	function go_top()
	{
		scrollBy(0,-1*mov);
	}
	function go_bottom()
	{
		scrollBy(0,mov);
	}
</script>
<body>
<?php

include("apoyo.php");

$Con=Conectar();

$informacion=pathinfo($archivo=$Dir.Get("archivo"));

if($archivo!="" && file_exists($archivo) && isset($informacion["extension"]))
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
		else {trigger_error("Archivo no encontrado: $archivo");}
	}
	else if($informacion["extension"]=="jpg" || $informacion["extension"]=="png" || $informacion["extension"]=="bmp" || $informacion["extension"]=="gif")
	{
		?>
		<img src="<?php echo substr($archivo, strlen($Dir)+1); ?>" border="0" />
		<?php
	}
}
else {trigger_error("Archivo no encontrado: $archivo");}

mysqli_close($Con);

?>
</body>
</html>
