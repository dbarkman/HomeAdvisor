<?php

/**
 * BusinessWorkType.model.php
 */

class BusinessWorkType
{
    private $_logger;
    private $_db;
    private $_id;
    private $_businessId;
    private $_workTypeId;

    public function __construct($logger, $db, $businessId, $workTypeId)
    {
        $this->_logger = $logger;
        $this->_db = $db;
        $this->_businessId = $businessId;
        $this->_workTypeId = $workTypeId;
    }

    public function saveBusinessWorkType()
    {
        $sql = "
			INSERT INTO
				businessesWorkTypes
			SET
				businessId = '$this->_businessId',
				workTypeId = '$this->_workTypeId'
		";

        mysqli_query($this->_db, $sql);
        $rowsAffected = mysqli_affected_rows($this->_db);

        if ($rowsAffected === 1) {
            $this->_id = mysqli_insert_id($this->_db);
            return TRUE;
        } else {
            $errors = $this->_db->error;
            $this->_logger->error('Database error - saveBusinessWorkType: ' . $errors);
            return $errors;
        }
    }
}