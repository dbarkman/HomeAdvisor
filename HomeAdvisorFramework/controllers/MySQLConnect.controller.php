<?php

/**
 * MySQLConnect.php
 */

class MySQLConnect
{
    private $_host;
    private $_user;
    private $_pass;
    private $_database;

    protected $result;

    public $db;

    public function __construct($host, $user, $pass, $database)
    {
        $this->_host = $host;
        $this->_user = $user;
        $this->_pass = $pass;
        $this->_database = $database;

        $this->db = new mysqli($this->_host, $this->_user, $this->_pass) or die ('Cannot connect to the database because: ' . mysqli_error($this->db));
        self::changeDatabase($this->_database);

        return $this;
    }

    public function changeDatabase($database)
    {
        mysqli_select_db($this->db, $database);
    }
}
