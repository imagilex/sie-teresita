<?php

class directorio
{
	private $ruta;
	public function __construct($truta)
	{
		$this->ruta=$truta;
	}
	public function JSON_archivos($orden="Alfabetico",$inverso=false,$pattern="")
	{
		$obj_dir = dir($this->ruta);
		$cad="[";
		$datos=array();
		while(($entrada=$obj_dir->read())!==false)
		{
			if(is_file($this->ruta."/".$entrada))
				$datos[] = $entrada;
		}
		if($orden=="Alfabetico")
		{
			sort($datos);
			if($inverso)
			{
				rsort($datos);
			}
		}
		else if($orden=="Extension")
		{
			$base=array();
			$exts=array();
			foreach($datos as $dato)
			{
				$partes=explode(".",$dato);
				if(count($partes)==1)
				{
					$partes[1]==" ";
				}
				$base[]=$partes[0];
				$exts[]=$partes[1];
			}
			$all=array($base,$exts);
			if(!$inverso)
			{
				array_multisort($all[1], SORT_ASC, SORT_STRING, $all[0], SORT_ASC, SORT_STRING);
			}
			else
			{
				array_multisort($all[1], SORT_DESC, SORT_STRING, $all[0], SORT_ASC, SORT_STRING);
			}
			$base=$all[0];
			$exts=$all[1];
			foreach($base as $x=>$val)
			{
				$datos[$x]=implode(".",array($val,$exts[$x]));
			}
		}
		foreach($datos as $val) 
		{
			if($pattern!="")
			{
				if(stristr($val,$pattern)!==false)
					$cad.='"'.$val.'"'.", ";
			}
			else
			{
				$cad.='"'.$val.'"'.", ";
			}
		}
		$obj_dir->close();
		return substr($cad,0,strlen($cad)-2)."]";
	}
	public function JSON_carpetas($inverso=false)
	{
		$obj_dir = dir($this->ruta);
		$cad="[";
		$datos=array();
		while(($entrada=$obj_dir->read())!==false)
		{
			if(is_dir($this->ruta."/".$entrada))
				$datos[] = $entrada;
		}
		sort($datos);
		if($inverso)
		{
			rsort($datos);
		}
		foreach($datos as $val) $cad.='"'.$val.'"'.", ";
		$obj_dir->close();
		return substr($cad,0,strlen($cad)-2)."]";
	}
}

?>