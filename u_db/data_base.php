<?php

//2009-09-17 18:22

class data_base
{
	var $usuario;
	var $host;
	var $password;
	var $db;
	var $coneccion;
	var $conectado;
	var $sel_bd;
	var $sel_bd_status;
	public $mysqli;
	function data_base($fusr="root",$fhost="localhost",$fpass="",$fdb="")
	{
		$this->set_usuario($fusr);
		$this->set_host($fhost);
		$this->set_password($fpass);
		$this->set_db($fdb);
		$this->conectado=false;
		$this->sel_bd=false;
		$this->coneccion=false;
		$this->sel_db_status=false;
		$this->conectar();
		$this->select_bd();
	}
	function __construct($fusr="root",$fhost="localhost",$fpass="",$fdb="")
	{
		$this->set_usuario($fusr);
		$this->set_host($fhost);
		$this->set_password($fpass);
		$this->set_db($fdb);
		$this->conectado=false;
		$this->sel_bd=false;
		$this->coneccion=false;
		$this->sel_db_status=false;
		$this->conectar();
		$this->select_bd();
	}
	function get_usuario(){return $this->usuario;}
	function get_host(){return $this->host;}
	function get_password(){return $this->password;}
	function get_db(){return $this->db;}
	function get_conectado(){return $this->conectado;}
	function get_sel_db_status(){return $this->sel_db_status;}
	function set_usuario($new_value){$this->usuario=$new_value;}
	function set_host($new_value){$this->host=$new_value;}
	function set_password($new_value){$this->password=$new_value;}
	function set_db($new_value){$this->db=$new_value;}
	function conectar()
	{
		if($this->get_conectado()==false && $this->get_usuario()!="" && $this->get_host()!="")
		{
			$this->coneccion= new mysqli($this->get_host(),$this->get_usuario(),$this->get_password(), $this->get_db());
			if($this->coneccion)
			{
				$this->conectado=true;
				return $this->coneccion;
			}
			return false;
		}
		return false;
	}
	function select_bd()
	{
		if($this->get_conectado() && $this->get_db()!="")
		{
			$res=$this->coneccion->select_db($this->get_db());
			if($res)
			{
				$this->sel_db_status=true;
				return true;
			}
		}
		return false;
	}
	function no_error()
	{
		if($this->get_conectado() && $this->coneccion->errno!="0")
			return $this->coneccion->errno;
		return "";
	}
	function error($Alert=false)
	{
		if($this->no_error()!="")
		{
			if($Alert)
			{
				?>
				<script language="javascript">window.alert("<?php echo $this->coneccion->error; ?>");</script>
				<?php
			}
			return $this->coneccion->error;
		}
		return "";
	}
	function consulta($query)
	{
		return mysqli_query($this->coneccion, $query);
	}
	function get_tablas($data_base="")
	{
		if($data_base=="") $data_base=$this->get_db();
		if($this->get_conectado() && $this->get_sel_db_status())
		{
			if($regs=$this->consulta("show tables from $data_base"))
			{
				while($reg=mysqli_fetch_array($regs))
				{
					$tablas[]=$reg["Tables_in_".$data_base];
				}
				mysqli_free_result($regs);
				return $tablas;
			}
		}
		return array();
	}
	function get_bds()
	{
		if($this->get_conectado() && $regs=$this->consulta("show databases"))
		{
			while($reg=mysqli_fetch_array($regs))
			{
				$bases_de_datos[]=$reg["Database"];
			}
			mysqli_free_result($regs);
			return $bases_de_datos;
		}
		return array();
	}
	function describe_table($tabla,$bd="")
	{
		if($bd=="") $bd=$this->get_db();
		if($this->get_conectado() && $regs=$this->consulta("describe $bd.$tabla"))
		{
			while($reg=mysqli_fetch_array($regs))
			{
				$campos[]=array("field"=>$reg["Field"],"type"=>$reg["Type"],"null"=>$reg["Null"],"key"=>$reg["Key"],"default"=>$reg["Default"],"extra"=>$reg["Extra"]);
			}
			mysqli_free_result($regs);
			return $campos;
		}
		return array();
	}
	function show_create_table($tabla,$bd="")
	{
		if($bd=="") $bd=$this->get_db();
		if($this->get_conectado() && $regs=$this->consulta("show create table $bd.$tabla"))
		{
			$reg=mysqli_fetch_array($regs);
			mysqli_free_result($regs);
			return $reg["Create Table"].";\n";
		}
		return "";
	}
	function show_insert_into_table($tabla,$bd="")
	{
		if($bd=="") $bd=$this->get_db();
		if($this->get_conectado())
		{
			$cuantos=mysqli_fetch_array($this->consulta("select count(*) as n from $bd.$tabla"));
			$tamano=200;
			$cadena="";
			for($x=0;$x<intval($cuantos["n"]);$x+=$tamano)
			{
				$cadena.="insert into $bd.$tabla values ";
				if($regs=$this->consulta("select * from $bd.$tabla limit $x,$tamano"))
				{
					while($reg=mysqli_fetch_array($regs))
					{
						$cadena.="\n(";
						for($y=0;$y<count($reg)/2;$y++)
							$cadena.=" '".$reg[$y]."',";
						$cadena=substr($cadena,0,strlen($cadena)-1);
						$cadena.="),";
					}
					$cadena=substr($cadena,0,strlen($cadena)-1);
				}
				$cadena.=";\n";
			}
			return $cadena;
		}
		return "";
	}
}


?>
