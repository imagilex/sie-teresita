<?php

/*

Se requieren archivos de YUI

*/



class tabla
{
	public $celdas;
	public $encabezados;
	public $style_tabla;
	public $style_encabezado;
	public $style_celdas;
	public $alto;
	public $ancho;
	public $ruta_yui;
	public $div;
	function tabla()
	{
		$this->celdas=array();
		$this->encabezados=array();
		$this->style_tabla="";
		$this->style_encabezado="";
		$this->style_celdas="";
		$this->alto="";
		$this->ancho="";
		$this->ruta_yui="";
		$this->div="";
	}
	function set($param, $val)
	{
		if($param=="celdas")
		{
			$this->celdas=$val;
		}
		else if($param=="encabezados")
		{
			$this->encabezados=$val;
		}	
		else if($param=="style_tabla")
		{
			$this->style_tabla=$val;
		}	
		else if($param=="style_encabezado")
		{
			$this->style_encabezado=$val;
		}
		else if($param=="style_celdas")
		{
			$this->style_celdas=$val;
		}
		else if($param=="alto")
		{
			$this->alto=$val;
		}
		else if($param=="ancho")
		{
			$this->ancho=$val;
		}
		else if($param=="ruta_yui")
		{
			$this->ruta_yui=$val;
		}
		else if($param=="div")
		{
			$this->div=$val;
		}
	}
	function get($param)
	{
		if($param=="celdas")
		{
			return $this->celdas;
		}
		else if($param=="encabezados")
		{
			return $this->encabezados;
		}	
		else if($param=="style_tabla")
		{
			return $this->style_tabla;
		}	
		else if($param=="style_encabezado")
		{
			return $this->style_encabezado;
		}
		else if($param=="style_celdas")
		{
			return $this->style_celdas;
		}
		else if($param=="alto")
		{
			return $this->alto;
		}
		else if($param=="ancho")
		{
			return $this->ancho;
		}
		else if($param=="ruta_yui")
		{
			return $this->ruta_yui;
		}
		else if($param=="div")
		{
			return $this->div;
		}
		return false;
	}
	function show()
	{
		if($this->ruta_yui!="" && @count($this->encabezados)>0 && @count($this->celdas)>0 && count($this->celdas[0])==count($this->encabezados))
		{
			?>
<link rel="stylesheet" type="text/css" href="u_tabla/datatable.css" />
<script type="text/javascript" src="<?php echo $this->ruta_yui; ?>/yahoo-dom-event.js"></script>
<script type="text/javascript" src="<?php echo $this->ruta_yui; ?>/element.js"></script>
<script type="text/javascript" src="<?php echo $this->ruta_yui; ?>/datasource.js">
</script><script type="text/javascript" src="<?php echo $this->ruta_yui; ?>/datatable.js"></script>			
<script type="text/javascript">
YAHOO.example.Data = {regs: {registros: [<?
$registros="";
foreach($this->celdas as $x => $val)
{
	$linea="\n{";
	foreach($this->encabezados as $y => $val2)
	{
		$cadena=$this->celdas[$x][$y];
		if($this->isDateMySQL($cadena))
		{
			$pos1=intval(strpos($cadena,"-").strpos($cadena,"/"));
			$pos2=intval(strpos($cadena,"-",$pos1+1).strpos($cadena,"/",$pos1+1));
			$cadena="new Date(".substr($cadena,0,4).", ".substr($cadena,5,$pos2-$pos1-1).", ".substr($cadena,8,$pos2+1).")";
		}
		else if($this->isDateConv($cadena))
		{
			$pos1=intval(strpos($cadena,"-").strpos($cadena,"/"));
			$pos2=intval(strpos($cadena,"-",$pos1+1).strpos($cadena,"/",$pos1+1));
			$cadena="new Date(".substr($cadena,$pos2+1,4).", ".substr($cadena,$pos1+1,$pos2-$pos1-1).", ".substr($cadena,0,$pos1).")";
		}
		else if($this->isNum($cadena))
		{
			$cadena=$cadena;
		}		
		else if($cadena=='""')
		{
			$cadena=$cadena;
		}
		else if($cadena!="")
		{
			$cadena="\"$cadena\"";
		}
		else
		{
			$cadena="\"\"";
		}
		$linea.="campo$y: $cadena,";
	}
	$registros.=substr($linea,0,strlen($linea)-1)."},";	
}
echo substr($registros,0,strlen($registros)-1);
?>]}};YAHOO.util.Event.addListener(window, "load", function() {YAHOO.example.Scrolling = function() {var myColumnDefs = [<?php
$linea="";
foreach($this->encabezados as $y=> $val)
{
	$cadena=', formatter:"date"';
	$linea.="\n".'{key:"campo'.($y).'", label:"'.$this->encabezados[$y].'", sortable:true'.$cadena.'},';
}
echo substr($linea,0,strlen($linea)-1);	
?>];var myDataSource = new YAHOO.util.DataSource(YAHOO.example.Data.regs);myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;myDataSource.responseSchema = {resultsList: "registros",campos: [<?php
$linea="";
foreach($this->encabezados as $y=> $val)
{
	$cadena=', formatter:"date"';
	$linea.="\n".'{key:"campo'.($y).'"'.$cadena.'},';
}
echo substr($linea,0,strlen($linea)-1);	
?>]};var myDataTableXY = new YAHOO.widget.ScrollingDataTable("<?php echo $this->div; ?>", myColumnDefs, myDataSource, {width:"<?php echo $this->ancho; ?>", height:"<?php echo $this->alto; ?>"});return {oDS: myDataSource, oDTXY: myDataTableXY};}();});</script><?php
		}
	}
	function isDateMySQL($cadena)
	{
		$estado = 0;
		for($x=0;$x<=strlen($cadena) && $estado!=99; $x++)
		{
			$car=substr($cadena,$x,1);
			switch($estado)
			{
				case 0:
					if($car=="1" || $car=="2") $estado=1;
					else $estado=99;
					break;
				case 1:
					if($car=="0" || $car=="9") $estado=2;
					else $estado=99;
					break;
				case 2:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=3;
					else $estado=99;
					break;
				case 3:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=4;
					else $estado=99;
					break;
				case 4:
					if($car=="-" || $car=="/") $estado=5;
					else $estado=99;
					break;
				case 5:
					if($car=="0") $estado=6;
					else if($car=="1") $estado=7;
					else if($car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=8;
					else $estado=99;
					break;
				case 6:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=8;
					else $estado=99;
					break;
				case 7:
					if($car=="0" || $car=="1" || $car=="2") $estado=8;
					else if($car=="-" || $car=="/") $estado=9;
					else $estado=99;
					break;
				case 8:
					if($car=="-" || $car=="/") $estado=9;
					else $estado=99;
					break;
				case 9:
					if($car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=11;
					else if($car=="0") $estado=10;
					else if($car=="1" || $car=="2") $estado=12;
					else if($car=="3") $estado=13;
					else $estado=99;
					break;
				case 10:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=11;
					else $estado=99;
					break;
				case 12:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=11;
					else $estado=99;
					break;
				case 13:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=11;
					else $estado=99;
					break;
			}
		}
		if($estado==11 || $estado==12 ||$estado==13)
		{
			return true;
		}
		return false;
	}
	function isDateConv($cadena)
	{
		$estado = 0;
		for($x=0;$x<=strlen($cadena) && $estado!=99; $x++)
		{
			$car=substr($cadena,$x,1);
			switch($estado)
			{
				case 0:
					if($car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=2;
					else if($car=="0") $estado=1;
					else if($car=="1" || $car=="2") $estado=3;
					else if($car=="3") $estado=4;
					else $estado=99;
					break;
				case 1:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=2;
					else $estado=99;
					break;
				case 2:
					if($car=="-" || $car=="/") $estado=5;
					else $estado=99;
					break;
				case 3:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=2;
					else if($car=="-" || $car=="/") $estado=5;
					else $estado=99;
					break;
				case 4:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=2;
					else if($car=="-" || $car=="/") $estado=5;
					else $estado=99;
					break;
				case 5:
					if($car=="0") $estado=6;
					else if($car=="1") $estado=7;
					else if($car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=8;
					else $estado=99;
					break;
				case 6:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=8;
					else $estado=99;
					break;
				case 7:
					if($car=="0" || $car=="1" || $car=="2") $estado=8;
					else if($car=="-" || $car=="/") $estado=9;
					else $estado=99;
					break;
				case 8:
					if($car=="-" || $car=="/") $estado=9;
					else $estado=99;
					break;
				case 9:
					if($car=="1" || $car=="2") $estado=10;
					else $estado=99;
					break;
				case 10:
					if($car=="0" || $car=="9") $estado=11;
					else $estado=99;
					break;
				case 11:					
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=12;
					else $estado=99;
					break;
				case 12:
					if($car=="0" || $car=="1" || $car=="2" || $car=="3" || $car=="4" || $car=="5" || $car=="6" || $car=="7" || $car=="8" || $car=="9") $estado=13;
					else $estado=99;
					break;
			}
		}
		if($estado==13)
		{
			return true;
		}
		return false;
	}
	function isNum($cadena){for($x=0;$x<=strlen($cadena);$x++){$caracter=substr($cadena,$x,1);if($caracter!='1' && $caracter!='2' && $caracter!='3' && $caracter!='4' && $caracter!='5' && $caracter!='6' && $caracter!='7' && $caracter!='8' && $caracter!='9' && $caracter!='0' && $caracter!='.' && $caracter!='+' && $caracter!='-'){return false;}}return true;}
}

?>