<?php

/**
 * HomeAdvisorProperties.class.php
 */

class HomeAdvisorProperties extends Properties
{
    private $_propertiesFile;
    const PROP_LOGFILE = "homeadvisor.log.file";
    const PROP_LOGLEVEL = "homeadvisor.log.level";

    public function __construct()
    {
        $this->_propertiesFile = dirname(__FILE__) . '/../config/homeadvisor.properties';
        $this->load($this->_propertiesFile);
    }

    public function load($file)
    {
        parent::load($file);
    }

    public function save($file)
    {
        parent::save($file);
    }

    public function getLogFile()
    {
        return parent::getProperty(self::PROP_LOGFILE);
    }

    public function getLogLevel()
    {
        $string = $this->getLogLevelString();
        return Logger::getLevelInt($string);
    }

    public function getLogLevelString()
    {
        $string = $this->getProperty(self::PROP_LOGLEVEL);
        $level = Logger::getLevelInt($string);
        if ($level != NULL) {
            return $string;
        }
        return "INFO";
    }
}
