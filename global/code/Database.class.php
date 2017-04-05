<?php

/**
 * PDO database connectivity methods. Added in 2.3.0 to replace the older mysql_* methods.
 *
 * @copyright Benjamin Keen 2017
 * @author Benjamin Keen <ben.keen@gmail.com>
 * @package 2-3-x
 * @subpackage Database
 */


// -------------------------------------------------------------------------------------------------

namespace FormTools;

use PDO, PDOException;


class Database
{
    private $dbh;
    private $error;
    private $statement;
    private $table_prefix;


    public function __construct($hostname, $db_name, $port, $username, $password, $table_prefix) {
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        );

        // if required, set all queries as UTF-8 (enabled by default). N.B. we're supporting 5.3.0 so passing charset
        // in the DSN isn't sufficient, as described here: https://phpdelusions.net/pdo
        $attrInitCommands = array();
        if (version_compare(PHP_VERSION, '5.3.6', '<')) {
            $attrInitCommands[] = "Names utf8";
        }
        if (Core::shouldSetSqlMode()) {
            $attrInitCommands[] = "SQL_MODE=''";
        }
        if (!empty($attrInitCommands)) {
            $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET " . implode(",", $attrInitCommands);
        }

        try {
            $dsn = sprintf("mysql:host=%s;port=%s;dbname=%s;charset=utf8", $hostname, $port, $db_name);
            $this->dbh = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }

        $this->table_prefix = $table_prefix;
    }

    /**
     * This is a convenience wrapper for PDO's prepare method. It replaces {PREFIX} with the database
     * table prefix so you don't have to include it everywhere.
     * @param $query
     */
    public function query($query) {
        $query = str_replace('{PREFIX}', $this->table_prefix, $query);
        $this->statement = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->statement->bindValue($param, $value, $type);
    }

    public function bindAll(array $data) {
        foreach ($data as $k => $v) {
            $this->bind($k, $v);
        }
    }

    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    public function processTransaction() {
        return $this->dbh->commit();
    }

    public function rollbackTransaction() {
        return $this->dbh->rollBack();
    }

    // method execution methods
    public function execute() {
        return $this->statement->execute();
    }

    public function fetch($fetch_style = PDO::FETCH_ASSOC) {
        return $this->statement->fetch($fetch_style);
    }

    public function fetchColumn($fetch_style = PDO::FETCH_ASSOC) {
        return $this->statement->fetchColumn($fetch_style);
    }

    public function fetchAll($fetch_style = PDO::FETCH_ASSOC) {
        return $this->statement->fetchAll($fetch_style);
    }

    public function getResultsArray() {
        $info = array();
        foreach ($this->fetchAll() as $row) {
            $info[] = $row;
        }
        return $info;
    }

    public function getInsertId() {
        return $this->dbh->lastInsertId();
    }
}
