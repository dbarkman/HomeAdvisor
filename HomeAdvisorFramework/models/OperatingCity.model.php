<?php

/**
 * OperatingCity.model.php
 */

class OperatingCity
{
    private $_logger;
    private $_db;
    private $_id;
    private $_city;

    public function __construct($logger, $db, $operatingCity)
    {
        $this->_logger = $logger;
        $this->_db = $db;
        $this->_city = mysqli_real_escape_string($this->_db, $operatingCity);
    }

    public function saveOperatingCity()
    {
        $sql = "
			INSERT INTO
				operatingCities
			SET
				city = '$this->_city'
		";

        mysqli_query($this->_db, $sql);
        $rowsAffected = mysqli_affected_rows($this->_db);

        if ($rowsAffected === 1) {
            $this->_id = mysqli_insert_id($this->_db);
            return $this->_id;
        } else {
            $errors = $this->_db->error;
            $this->_logger->error('Database error - saveOperatingCity: ' . $errors);
            return FALSE;
        }
    }

    public function getExistingOperatingCityId() {
        $sql = "
			SELECT
				id
			FROM
				operatingCities
			WHERE
				city = '$this->_city'
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