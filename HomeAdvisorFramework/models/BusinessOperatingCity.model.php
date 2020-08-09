<?php

/**
 * BusinessOperatingCity.model.php
 */

class BusinessOperatingCity
{
    private $_logger;
    private $_db;
    private $_id;
    private $_businessId;
    private $_operatingCityId;

    public function __construct($logger, $db, $businessId, $operatingCityId)
    {
        $this->_logger = $logger;
        $this->_db = $db;
        $this->_businessId = $businessId;
        $this->_operatingCityId = $operatingCityId;
    }

    public function saveBusinessOperatingCity()
    {
        $sql = "
			INSERT INTO
				businessesOperatingCities
			SET
				businessId = '$this->_businessId',
				operatingCityId = '$this->_operatingCityId'
		";

        mysqli_query($this->_db, $sql);
        $rowsAffected = mysqli_affected_rows($this->_db);

        if ($rowsAffected === 1) {
            $this->_id = mysqli_insert_id($this->_db);
            return TRUE;
        } else {
            $errors = $this->_db->error;
            $this->_logger->error('Database error - saveBusinessOperatingCity: ' . $errors);
            return $errors;
        }
    }

    public function getExistingBusinessOperatingCity() {
        $sql = "
			SELECT
				*
			FROM
				businessesOperatingCities
			WHERE
			    businessId = $this->_businessId
			    AND 
			    operatingCityId = $this->_operatingCityId
		";

        $result = mysqli_query($this->_db, $sql);
        $rows = mysqli_num_rows($result);

        if ($rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}