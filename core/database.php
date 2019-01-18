<?php

class Database{
	private $dbhost;
	private $dbuser;
	private $dbpassword;
	private $dbname;
	private $connection;
	private $active_connection;
	private $sql;
	private $num_rows;
	private $last_insert_id;
	private $result = array();

	public function __construct() {
		$this->dbhost = 'localhost';
		$this->dbuser = 'root';
		$this->dbpassword = '';
		$this->dbname = 'pos';
		$this->active_connection = false;
		$this->connection = '';
		$this->sql = '';
		$this->num_rows = 0;
		$this->last_insert_id = null;
	}

	public function get_all_rows() {
		$query = $this->connection->query($this->sql);
		if($query) {
			// If query has 1 or more rows
			
			$this->num_rows = $query->num_rows;
			$this->result = array();
			for($i = 0; $i < $this->num_rows; $i++) {
				array_push($this->result, $query->fetch_assoc());
			}
			// If query success
			return true;
			
		}
		// If sql is wrong, query failed
		// GENERATE ERROR LOG HERE
		return false;
	} 

	public function run_query() {		
		if($this->connection->query($this->sql)) {
			// If query success
			if(strpos($this->sql, 'INSERT') !== false) {
				$this->last_insert_id = $this->connection->insert_id;
			}
			return true;
		}else{
			// If sql is wrong, query failed
			// GENERATE ERROR LOG HERE
			return false;
		}
	}

	public function connect() {
		// IF there is already a connection
		if($this->active_connection) { 
			return true;
		}

		// IF there is no connection
		$this->connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpassword, $this->dbname);

		// Connection problem
		if($this->connection->connect_errno > 0) {
			// take a log for error
			return false;
		}

		// Set active connection true
		$this->active_connection = true;

		return true;
	}

	// Escape your string
    public function escapeString($data){
        return $this->connection->real_escape_string($data);
    }

    // If row exsists
    public function is_row_exsists($table, $row_values) {
		$rows = array_keys($row_values);
		$rows = implode(', ', $rows);
		$values = ' WHERE ';
		$itr = 0;

		foreach($row_values as $key=>$value) {
			if($itr) {
				$values .= ' AND ';
			}
			$values .= $key . "='" . $value . "'";
			$itr=1;
		}

		$this->sql = "SELECT " . $rows . " FROM " . $table . ' ' . $values;

		$this->get_all_rows();

		// num rows returns matched row number
		if($this->num_rows > 0) {return true;}
		return false;
    }

    public function get_sql() {
    	return $this->sql;
    }

    public function get_last_insert_id() {
    	return $this->last_insert_id;
    }

    public function set_sql($str) {
    	$this->sql = $str;
    }

    public function get_result() {return $this->result;}

    public function get_num_rows() {return $this->num_rows;}

	public function __destruct() {
		if($this->active_connection) {
			$this->connection->close();
			$this->active_connection = false;
		}
	}
}

?>