<?php

/**
 * BusinessHours.model.php
 */

class BusinessHours
{
    private $_logger;
    private $_db;
    private $_id;
    private $_businessId;
    private $_dayOfWeek;
    private $_open;
    private $_close;

    public function __construct($logger, $db, $businessId, $businessHour)
    {
        $this->_logger = $logger;
        $this->_db = $db;
        $this->_businessId = $businessId;
        $this->_dayOfWeek = mysqli_real_escape_string($this->_db, $businessHour->dayOfWeek);
        $this->_open = $businessHour->open;
        $this->_close = $businessHour->close;
    }

    public function saveBusinessHours()
    {
        $sql = "
			INSERT INTO
				businessHours
			SET
				businessId = '$this->_businessId',
				dayOfWeek = '$this->_dayOfWeek',
				open = '$this->_open',
				close = '$this->_close'
		";

        mysqli_query($this->_db, $sql);
        $rowsAffected = mysqli_affected_rows($this->_db);

        if ($rowsAffected === 1) {
            $this->_id = mysqli_insert_id($this->_db);
            return TRUE;
        } else {
            $errors = $this->_db->error;
            $this->_logger->error('Database error - saveBusinessHours: ' . $errors);
            return $errors;
        }
    }
}