<?php
class Database {

	protected $configDB;
	
	function __construct() {
		$this->configDB = parse_ini_file("db.ini.php");
		$this->OpenConnect();
	}

	function OpenConnect() {
		$dsn = "mysql:dbname=" . $this->configDB["dbname"] . ";host=" . $this->configDB["host"] . ";port=" . $this->configDB["port"];

		try {
			$this->pdo = new PDO($dsn, $this->configDB["user"], $this->configDB["password"], array(
			    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
			    PDO::ATTR_EMULATE_PREPARES => false,
			    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
			));

		}
		catch (PDOException $e) {
			echo "Exception captured" . $e->getMessage() . "\n";
			die();
		}
	}

	function CloseConnect() {
		// Objeto PDO a null para cerrar la conexión
		$this->pdo = null;
    }


    function Insert($table, $array_data) {

    	$fields = implode(",",array_keys($array_data));

    	if( isset($array_data) && count($array_data)>0 ) {
    		foreach($array_data as $key => $value)
    			$array_bind_data[":" . $key] = $value;
    	
	    	$values = implode(",",array_keys($array_bind_data));

	    	$query = "INSERT IGNORE INTO " . $table . " ( ".$fields." ) VALUES ( ".$values." )";

	    	$exec = $this->pdo->prepare($query);
	    	//$stmt -> bindParam();
	    	$exec->execute( $array_bind_data );

	    	return $this->pdo->lastInsertId();
	    }
    }

    function Update($table, $update, $where, $symbols) {

    	if( isset($update) && count($update)>0 ) {

    		if( isset($where) && count($where) ) {

    			$j=0;
    			foreach($where as $key => $value) {
					if( is_numeric($value) )
						$array_where[] = $key . $symbols[$j] . $value;
					else
						$array_where[] = $key . $symbols[$j] . "\"" . $value . "\"";
					$j++;
				}

				$str_where = " WHERE " . implode(" & ",$array_where);
    		} else
    			$str_where = "";
  

			foreach($update as $key => $value) {
				if( is_numeric($value) )
					$array_update[] = $key . "=" . $value;
				else
					$array_update[] = $key . "=" . "\"" . $value . "\"";
			}
    		

	    	$query = "UPDATE " . $table . " SET " . implode(",",$array_update) . $str_where ;

	    	$exec = $this->pdo->prepare($query);
	    	//$stmt -> bindParam();
	    	$exec->execute();

	    	return true;
	    }
    }


    function Delete($table, $where, $symbols) {

    	if( isset($where) && count($where)>0 ) {
    		$i=0;
    		foreach($where as $key => $value) {
    			if( is_numeric($value) )
    				$array_where[] = $key . $symbols[$i] . $value;
    			else
    				$array_where[] = $key . $symbols[$i] . "\"". $value . "\"";
    			$i++;
    		}

	    	$query = "DELETE FROM " . $table . " WHERE " . implode(" & ",$array_where);

	    	$exec = $this->pdo->prepare($query);
	    	$exec->execute();

	    	return true;
	    }

    	return $result;
    }


    function truncate($table) {
    	$query = "TRUNCATE " . $table;
    	
    	$exec = $this->pdo->prepare($query);
	    $exec->execute();

	    return true;
    }


    function get_results($query, $array_data="", $fetchmode = PDO::FETCH_ASSOC) {

    	$exec = $this->pdo->prepare($query);

    	if( isset($array_data) && count($array_data) > 0 ) {
    		foreach($array_data as $key => $value)
    			$exec->bindParam($key,$value);
    	}

    	$exec->execute();
    	$results = $exec->fetchAll($fetchmode);

    	if( $exec->rowCount() > 0 )
    		$result = $results;
    	else
    		$result = array();

    	return $result;
    }


    function get_row($table, $fields, $where, $symbols, $fetchmode = PDO::FETCH_ASSOC) {

    	$result = array();

    	if( isset($fields) && count($fields)>0 && isset($where) && count($where)>0 ) {

    		$i=0;
    		$str_where = array();
    		foreach($where as $key => $value) {
    			if( is_numeric($value) )
    				$str_where[] = $key . $symbols[$i] . $value;
    			else
    				$str_where[] = $key . $symbols[$i] . "\"" . $value . "\"";

    			$i++;
    		}

    		$query = "SELECT " . implode(",",$fields) . " FROM " . $table . " WHERE " . implode(" & ",$str_where) . " LIMIT 1";

    		$exec = $this->pdo->prepare($query);
    		$exec->execute();
    		$results = $exec->fetchAll($fetchmode);

    		if( $exec->rowCount() > 0 )
    			$result = $results[0];
    	}

    	return $result;
    }
}
?>