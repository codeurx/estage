<?php

use \PDO;

class DB
{
    private $database;
    private $username;
    private $password;
    private $pdo;
    private $sQuery;
    private $settings;
	private $bConnected = false;
	private $parameters;

	public function __construct($hostname, $database, $username, $password)
    {
        $this->Connect($hostname, $database, $username, $password);
        $this->parameters = array();
	}
	
    private function Connect($hostname, $database, $username, $password)
    {
        global $settings;
        $dsn = 'mysql:dbname=' . $database . ';host=' . $hostname;
        try {
            $this->pdo = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
			$this->bConnected = true;
        } catch (PDOException $e) {
            echo $this->ExceptionLog($e->getMessage());
            die();
        }
	}
	
    public function CloseConnection()
    {
        $this->pdo = null;
	}

	private function Init($query, $parameters = "")
    {
        if (!$this->bConnected) {$this->Connect();}
        try {
			$this->sQuery = $this->pdo->prepare($query);
			$this->bindMore($parameters);
            if (!empty($this->parameters)) {
                foreach ($this->parameters as $param) {
                    $parameters = explode("\x7F", $param);
                    $this->sQuery->bindParam($parameters[0], $parameters[1]);
                }
            }
            $this->success = $this->sQuery->execute();
        } catch (PDOException $e) {
            $this->ExceptionLog($e->getMessage(), $query);
        }
        $this->parameters = array();
    }

    public function bind($para, $value)
    {
        $this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . utf8_encode($value);
    }
	
	public function bindMore($parray)
    {
        if (empty($this->parameters) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
                $this->bind($column, $parray[$column]);
            }
        }
    }
	
	public function query($query, $params = null, $fetchmode = PDO::FETCH_ASSOC)
    {
        $query = trim($query);
        $this->Init($query, $params);
		$rawStatement = explode(" ", $query);
		$statement = strtolower($rawStatement[0]);
		if ($statement === 'select' || $statement === 'show') {
            return $this->sQuery->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            return $this->sQuery->rowCount();
        } else {
            return null;
        }
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function column($query, $params = null)
    {
        $this->Init($query, $params);
        $Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);

        $column = null;
        foreach ($Columns as $cells) {
            $column[] = $cells[0];
        }
        return $column;

    }
	
	public function row($query, $params = null, $fetchmode = PDO::FETCH_ASSOC)
    {
        $this->Init($query, $params);
        return $this->sQuery->fetch($fetchmode);
    }
	
	public function single($query, $params = null)
    {
        $this->Init($query, $params);
        return $this->sQuery->fetchColumn();
    }
	
	private function ExceptionLog($message, $sql = "")
    {
        $exception = 'Unhandled Exception. <br />';
        $exception .= $message;
        $exception .= "<br /> You can find the error back in the log.";
        if (!empty($sql)) {
            $message .= "\r\nRaw SQL : " . $sql;
        }
        $this->log->write($message);
		throw new Exception($message);
	}
}
