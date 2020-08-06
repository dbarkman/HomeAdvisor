<?php

/**
 * Properties.class.php
 */

class Properties
{
    protected $data = array();

    public function load($file)
    {
        if (file_exists($file)) {
            $contents = file_get_contents($file);
            $lines = explode("\n", $contents);

            foreach ($lines as $line)
            {
                $keyvals = explode("=", $line);
                if (count($keyvals) == 2) {
                    $this->data[$keyvals[0]] = rtrim($keyvals[1]);
                }
            }
        }
    }

    public function save($file)
    {
        if (isset($file)) {
            $output = "";
            foreach ($this->data as $key => $val)
            {
                if ($key != NULL && $val != NULL) {
                    $output = $output . "$key=$val\n";
                }
            }
            file_put_contents($file, $output);
        }
    }

    protected function getProperty($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return NULL;
    }

    protected function setProperty($name, $val)
    {
        $this->data[$name] = $val;
    }
}
