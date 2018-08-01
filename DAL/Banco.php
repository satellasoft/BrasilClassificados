<?php

date_default_timezone_set('America/Sao_Paulo');


/**
 * PDO PHP Persistence Class
 * https://github.com/victortassinari/pdophpclass
 *
 *
 * @author Victor Tassinari - victortassinarix@gmail.com
 */
 
class Banco {

    private static $connection;
    private $debug;
    private $server;
    private $user;
    private $password;
    private $database;

    public function __construct() {
        $this->debug = true;
        $this->server = "127.0.0.1";
        $this->user = "root";
        $this->password = "";
        $this->database = "brasilclassificados";
    }

    /**
     * Create a database connection or return the connection already open using Singletion Design Patern
     * @return PDOConnection|null
     */
    public function getConnection() {
        try {
            if (self::$connection == null) {
                self::$connection = new PDO("mysql:host={$this->server};dbname={$this->database};charset=utf8", $this->user, $this->password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                self::$connection->setAttribute(PDO::ATTR_PERSISTENT, true);
            }
            return self::$connection;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "<b>Error on getConnection(): </b>" . $ex->getMessage() . "<br/>";
            }
            die();
            return null;
        }
    }

    /**
     * Unset connection
     * @return void
     */
    public function Disconnect() {
        $this->connection = null;
    }

    /**
     * Return the last id of insert statement
     * @return int
     */
    public function GetLastID() {
        return $this->getConnection()->lastInsertId();
    }

    /**
     * Start one database transaction
     * @return void
     */
    public function BeginTransaction() {
        return $this->getConnection()->beginTransaction();
    }

    /**
     * Commit changes on opened transaction
     * @return void
     */
    public function Commit() {
        return $this->getConnection()->commit();
    }

    /**
     * Roolback changes on opened transaction
     * @return void
     */
    public function Rollback() {
        return $this->getConnection()->rollBack();
    }

    /**
     * returns the result of a query (select) of only one row
     * @param string $sql the sql string
     * @param array $params the array of parameters (array(":col1" => "val1",":col2" => "val2"))
     * @return one position array for the result of query
     */
    public function ExecuteQueryOneRow($sql, $params = null) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "<b>Error on ExecuteQueryOneRow():</b> " . $ex->getMessage() . "<br />";
                echo "<br /><b>SQL: </b>" . $sql . "<br />";

                echo "<br /><b>Parameters: </b>";
                print_r($params) . "<br />";
            }
            die();
            return null;
        }
    }

    /**
     * returns the result of a query (select)
     * @param string $sql the sql string
     * @param array $params the array of parameters (array(":col1" => "val1",":col2" => "val2"))
     * @return array for the result of query
     */
    public function ExecuteQuery($sql, $params = null) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "<b>Error on ExecuteQuery():</b> " . $ex->getMessage() . "<br />";
                echo "<br /><b>SQL: </b>" . $sql . "<br />";

                echo "<br /><b>Parameters: </b>";
                print_r($params) . "<br />";
            }
            die();
            return null;
        }
    }

    /**
     * returns if the query was successful
     * @param string $sql the sql string
     * @param array $params the array of parameters (array(":col1" => "val1",":col2" => "val2"))
     * @return boolean
     */
    public function ExecuteNonQuery($sql, $params = null) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "<b>Error on ExecuteNonQuery():</b> " . $ex->getMessage() . "<br />";
                echo "<br /><b>SQL: </b>" . $sql . "<br />";

                echo "<br /><b>Parameters: </b>";
                print_r($params) . "<br />";
            }
            die();
            return false;
        }
    }

}
