<?php

define("DB_DISPLAY_QUERIES", false);
define("DB_INSERT_SIZE", 200);

if(!isset($__DB_MODULE__)) {

    $__DB_MODULE__ = true;

    class DB_Database {

        public readonly string $usr;
		public readonly string $pass;
		public readonly string $host;
		public readonly string $bd;
		private $mysqli;

		public function __construct(
            string $host, string $usr, string $pass, string $bd) {
			$this->usr = $usr;
			$this->pass = $pass;
			$this->host = $host;
			$this->bd = $bd;
			$this->conectar();
		}

        public function __destruct() {
            $this->desconectar();
        }

		public function conectar() {
			if($this->mysqli)
				return $this->mysqli;
			if($this->usr && $this->pass && $this->host && $this->bd)
			{
				$this->mysqli = new mysqli(
                    $this->host, $this->usr, $this->pass, $this->bd);
				if($this->mysqli)
					return $this->mysqli;
			}
			throw new Exception(
                "Error al establecer conexion con la base de datos: " .
                $this->error());
		}

		public function desconectar() {
            if($this->mysqli && isset($this->mysqli->server_info)) {
                return $this->mysqli->close();
            }
			return null;
		}

        public function query(string $query, int $modo=MYSQLI_STORE_RESULT) {
			$this->conectar();
            if(DB_DISPLAY_QUERIES) {
                $excep = new Exception();
	            trigger_error(
                    "CONSULTA SQL: \n$query;\n en \n" .
                    $excep->getTraceAsString());
            }
			$ret = $this->mysqli->query($query, $modo);
            if($this->mysqli->errno) {
                throw new Exception(
                    "Error al establecer conexion con la base de datos: " .
                    $this->error());
            }
			return $ret;
		}

        static public function registro(
            mysqli_result $mysqli_result, int $resulttype=MYSQLI_ASSOC) {
			return $mysqli_result->fetch_array($resulttype);
		}

        static public function registros(
            mysqli_result $mysqli_result, int $resulttype=MYSQLI_ASSOC) {
            $registros = array();
            while($registro = DB_Database::registro(
                $mysqli_result, $resulttype)) {
                $registros[] = $registro;
            }
            mysqli_free_result($mysqli_result);
            return $registros;
        }

        public function select(
            string $campos, string $tabla, string $where_cond="",
            string $order="", int $resulttype=MYSQLI_ASSOC) {
            $result = $this->query(
                "SELECT $campos FROM $tabla " .
                ($where_cond != "" ? "WHERE $where_cond " : "") .
                ($order != "" ? "ORDER BY $order" : ""));
            return DB_Database::registros($result, $resulttype);
		}

		public function update(
            string $tabla, array $vals, string $where_cond="") {
			// $vals => array('campo1' => 'val1', 'campo2' =>'val2', ...)
            $campos = array();
            foreach($vals as $k => $v) {
                $campos[] = "$k = '$v'";
            }
            $query_set = implode(", ", $campos);
			return $this->query(
                "UPDATE $tabla SET $query_set " .
                ($where_cond != "" ? "WHERE $where_cond " : ""));
		}

        public function insert(string $tabla, array $vals) {
			// $vals => array('campo1' => 'val1', 'campo2' =>'val2', ...)
            $campos = array();
            $valores = array();
            foreach($vals as $k => $v) {
                $campos[] = $k;
                $valores[] = "'$v'";
            }
			$campos = implode(",", $campos);
			$valores = implode(", ", $valores);
			return $this->query(
                "INSERT INTO $tabla ($campos) VALUES ($valores)");
		}

        public function delete(string $tabla,string $where_cond="") {
            return $this->query("DELETE FROM $tabla " .
                ($where_cond != "" ? "WHERE $where_cond ":""));
        }

        public function truncate(string $tabla) {
            return $this->query("TRUNCATE $tabla");
        }

        public function drop_table(string $tabla) {
            return $this->query("DROP TABLE $tabla");
        }

        public function drop_data_base() {
            return $this->query("DROP DATABASE ".$this->bd);
        }

        public function error() {
            if($this->mysqli && $this->mysqli->errno) {
                return $this->mysqli->errno . " - " . $this->mysqli->error;
            }
            return null;
        }

    }

    class DB_Table {

        public readonly DB_Database $db;
        public readonly string $tabla;

        public function __construct(
            string $host, string $usr, string $pass,string  $bd,
            string $tabla) {
            $this->db = new DB_Database($host, $usr, $pass, $bd);
			$this->tabla = $tabla;
		}

        public function select(
            string $campos, string $where_cond="", string $order="") {
            return $this->db->select(
                $campos, $this->tabla, $where_cond, $order);
        }

        public function update(array $vals, string $where_cond="") {
			// $vals => array('campo1' => 'val1', 'campo2' =>'val2', ...)
			return $this->db->update($this->tabla, $vals, $where_cond);
		}

        public function insert(array $vals) {
			// $vals => array('campo1' => 'val1', 'campo2' =>'val2', ...)
			return $this->db->insert($this->tabla, $vals);
		}

        public function delete(string $where_cond="") {
            return $this->db->delete($this->tabla, $where_cond);
        }

        public function truncate() {
            return $this->db->truncate($this->tabla);
        }

        public function drop_table() {
            return $this->db->drop_table($this->tabla);
        }
    }

    class DB_Descriptor {

        public readonly DB_Database $db;

        public function __construct(
            string $host, string $usr, string $pass,string  $bd) {
            $this->db = new DB_Database($host, $usr, $pass, $bd);
		}

        public function tables() {
            $regs = DB_Database::registros(
                $this->db->query("SHOW TABLES FROM " . $this->db->bd));
            $res = array();
            foreach($regs as $reg) {
                $res[] = $reg["Tables_in_" . $this->db->bd];
            }
            return $res;
        }

        public function databases() {
            $regs = DB_Database::registros(
                $this->db->query("SHOW DATABASES"));
            $res = array();
            foreach($regs as $reg) {
                $res[] = $reg["Database"];
            }
            return $res;
        }

        public function describe(string $tabla) {
            $regs = DB_Database::registros(
                $this->db->query("DESCRIBE $tabla"));
            $res = array();
            foreach($regs as $reg) {
                $res[] = array(
                    "field" => $reg["Field"],
                    "type" => $reg["Type"],
                    "null" => $reg["Null"],
                    "key" => $reg["Key"],
                    "default" => $reg["Default"],
                    "extra" => $reg["Extra"]
                );
            }
            return $res;
        }

        public function show_create(string $tabla) {
            $regs = DB_Database::registros(
                $this->db->query("SHOW CREATE TABLE $tabla"));
            return $regs[0]["Create Table"];
        }

        public function show_insert(string $tabla) {
            $total_regs = intval(DB_Database::registros(
                $this->db->query("SELECT COUNT(*) AS n FROM $tabla"))[0]["n"]);
            $sql = "";
            for($x = 0; $x < $total_regs; $x += DB_INSERT_SIZE) {
                $sql .= "INSERT INTO $tabla VALUES";
                if(count(
                    $regs = DB_Database::registros($this->db->query(
                        "SELECT * FROM $tabla LIMIT $x, " .
                        strval(DB_INSERT_SIZE))))) {

                }
            }
        }

    }

}

?>
