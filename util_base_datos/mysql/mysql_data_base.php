<?php



if(!isset($__BD__))

{

	$__BD__ = true;

	

	class DataBase

	{

		private $usr;

		private $pass;

		private $host;

		private $bd;

		public function __construct($thost,$tusr,$tpass,$tbd)

		{

			$this->usr = $tusr;

			$this->pass = $tpass;

			$this->host = $thost;

			$this->bd = $tbd;

		}

		private function conectar()

		{

			if($this->usr!="" && $this->pass!="" && $this->host!="" && $this->bd!="")

			{

				$con = mysql_connect($this->host, $this->usr, $this->pass);

				if(mysql_select_db($this->bd,$con)) { return $con; }

			}

			return false;

		}

		private function desconectar($conexion)

		{

			return mysql_close($conexion);

		}

		public function get_usr(){return $this->usr;}

		public function set_usr($nuevo){$this->usr = $nuevo; return $this->usr;}

		public function get_pass(){return $this->pass;}

		public function set_pass($nuevo){$this->pass = $nuevo; return $this->pass;}

		public function get_host(){return $this->host;}

		public function set_host($nuevo){$this->host = $nuevo; return $this->host;}

		public function get_bd(){return $this->bd;}

		public function set_bd($nuevo){$this->bd = $nuevo; return $this->bd;}

		public function query($str)

		{

			if($con=$this->conectar())

			{

				$ret = mysql_query($str);

				//$this->desconectar($con);

				try { if(mysql_errno()!=0) { throw new Exception(mysql_errno()." - ".mysql_error()); } }

				catch (Exception $e) { echo 'Excepcion en mysql_data_base: ',  $e->getMessage(), "\n"; exit(); }

				return $ret;

			}

			return false;

		}

		public function registro($regs_mysql)

		{

			$ret=@mysql_fetch_array($regs_mysql,MYSQL_ASSOC);

			return $ret;

		}

		public function select($campos, $tabla, $where_cond="", $order="")

		{

			$result=array();

			if($camps = $this->query("select $campos from $tabla ".($where_cond!=""?"where $where_cond ":"").($order!=""?"order by $order":"")) )

			{

				while($camp = mysql_fetch_array($camps))

				{

					$result[] = $camp;

				}

			}

			return $result;

		}

		public function update($tabla, $vals,$where_cond="")

		{

			// $vals => array('campo1' => 'val1', 'campo2' =>'val2', ...)

			$query_set = "";

			foreach($vals as $key => $val) { $query_set .= "$key = '$val',"; }

			$query_set = substr($query_set,0,strlen($query_set)-1);

			return $this->query("update $tabla set $query_set ".($where_cond!=""?"where $where_cond ":""));

		}

		public function insert($tabla, $vals)

		{

			// $vals => array('campo1' => 'val1', 'campo2' =>'val2', ...)

			$campos = "(";

			$valores = "(";

			foreach($vals as $key => $val) { $campos .= "$key,"; $valores .= "'$val',"; }

			$campos = substr($campos,0,strlen($campos)-1).")";

			$valores = substr($valores,0,strlen($valores)-1).")";

			return $this->query("insert into $tabla $campos values $valores");

		}

		public function delete($tabla, $where_cond="") { return $this->query("delete from $tabla ".($where_cond!=""?"where $where_cond ":"")); }

		public function truncate($tabla) { return $this->query("truncate $tabla"); }

		public function drop_table($tabla) { return $this->query("drop table $tabla"); }

		public function drop_data_base() { return $this->query("drop database ".$this->get_bd()); }

	}

	

	class Table extends DataBase

	{

		private $tabla;

		public function __construct($thost,$tusr,$tpass,$tbd,$ttabla)

		{

			parent::__construct($thost,$tusr,$tpass,$tbd);

			$this->tabla = $ttabla;

		}

		public function select($campos, $where_cond="", $order="") { return parent::select($campos, $this->tabla, $where_cond, $order); }

		public function update($vals,$where_cond="")

		{

			// $vals => array('campo1' => 'val1', 'campo2' =>'val2', ...)

			return parent::update($this->tabla, $vals, $where_cond);

		}

		public function insert($vals)

		{

			// $vals => array('campo1' => 'val1', 'campo2' =>'val2', ...)

			return parent::insert($this->tabla, $vals);

		}

		public function delete($where_cond="") { return parent::delete($this->tabla, $where_cond); }

		public function truncate() { return parent::truncate($this->tabla); }

		public function drop_table() { return parent::drop_table($this->tabla); }

	}

}



?>