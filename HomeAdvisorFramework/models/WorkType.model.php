<?php

/**
 * WorkType.model.php
 */

class WorkType
{
    private $_logger;
    private $_db;
    private $_id;
    private $_workType;

    public function __construct($logger, $db, $workType)
    {
        $this->_logger = $logger;
        $this->_db = $db;
        $this->_workType = mysqli_real_escape_string($this->_db, $workType);

    }

    public function saveWorkType()
    {
        $sql = "
			INSERT INTO
				workTypes
			SET
				workType = '$this->_workType'
		";

        mysqli_query($this->_db, $sql);
        $rowsAffected = mysqli_affected_rows($this->_db);

        if ($rowsAffected === 1) {
            $this->_id = mysqli_insert_id($this->_db);
            return $this->_id;
        } else {
            $errors = $this->_db->error;
            $this->_logger->error('Database error - saveWorkType: ' . $errors);
            return FALSE;
        }
    }

    public function getExistingWorkTypeId() {
        $sql = "
			SELECT
				id
			FROM
				workTypes
			WHERE
				workType = '$this->_workType'
		";

        $result = mysqli_query($this->_db, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        } else {
            return 0;
        }
    }
}