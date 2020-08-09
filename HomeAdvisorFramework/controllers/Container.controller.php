<?php

/**
 * Container.controller.php
 */

class Container
{
    static protected $shared = array();

    private $_logFile;
    private $_logLevel;
    private $_properties;

    public function __construct()
    {
        $this->_properties = new HomeAdvisorProperties();
        $this->_logFile = $this->_properties->getLogFile();
        $this->_logLevel = $this->_properties->getLogLevel();
    }

    public function getLogger()
    {
        if (isset(self::$shared['logger'])) {
            return self::$shared['logger'];
        }

        $logger = new Logger($this->_logLevel, $this->_logFile);

        return self::$shared['logger'] = $logger;
    }

    public function getMySQLDBConnect()
    {
        if (isset(self::$shared['mysqlDBConnect'])) {
            return self::$shared['mysqlDBConnect'];
        }

        global $homeadvisorDBLogin;
        $mysqlDBConnect = new MySQLConnect($homeadvisorDBLogin['server'], $homeadvisorDBLogin['username'], $homeadvisorDBLogin['password'], $homeadvisorDBLogin['database']);
        $mysqlConnection = $mysqlDBConnect->db;

        return self::$shared['mysqlDBConnect'] = $mysqlConnection;
    }
}