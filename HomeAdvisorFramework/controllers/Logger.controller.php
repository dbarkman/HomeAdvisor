<?php

/**
 * Logger.controller.php
 */

class Logger
{
    const DEBUG = 0x01;
    const INFO = 0x02;
    const WARN = 0x04;
    const ERROR = 0x08;
    const FATAL = 0x16;

    public static $levels = array(1 => "DEBUG", 2 => "INFO", 4 => "WARN", 8 => "ERROR", 16 => "FATAL");

    private $logPath;
    private $logFile;
    private $logLevel;

    public function __construct($level, $file)
    {
        $this->logLevel = $level;
        $this->logFile = $file;
        $this->logPath = '../HomeAdvisorFramework/logs';
    }

    public static function getLevelInt($string)
    {
        foreach (self::$levels as $key => $val)
        {
            if ($val == $string) {
                return $key;
            }
        }

        return NULL;
    }

    private function log($level, $message)
    {
        try {
            if ($level >= $this->logLevel) {
                $log = $this->logPath . DIRECTORY_SEPARATOR . $this->logFile;
                $trace = debug_backtrace();
                $file = explode('/', $trace[1]['file']);
                $file = array_pop($file);
                $now = date("m/d/Y H:i:s", time());
                $msg = sprintf("%-14s | %-5s - %s", $now, self::$levels[$level], session_id() . " - (" . $file . ") - " . $message);
                file_put_contents($log, "$msg\n", FILE_APPEND);
            }
        } catch (Exception $e) {
            //could not create a log entry
        }
    }

    public function debug($message)
    {
        $this->log(self::DEBUG, $message);
    }

    public function info($message)
    {
        $this->log(self::INFO, $message);
    }

    public function warn($message)
    {
        $this->log(self::WARN, $message);
    }

    public function error($message)
    {
        $this->log(self::ERROR, $message);
    }

    public function fatal($message)
    {
        $this->log(self::FATAL, $message);
    }
}
