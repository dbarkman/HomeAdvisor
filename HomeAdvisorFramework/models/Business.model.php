<?php

/**
 * Business.model.php
 */

class Business
{
    private $_logger;
    private $_db;
    private $_id;
    private $_businessName;
    private $_addressLine1 = '';
    private $_addressLine2 = '';
    private $_city = '';
    private $_stateAbbr = '';
    private $_postal = '';
    private $_businessHours = array();
    private $_operatingCities = array();
    private $_workTypes = array();
    private $_reviews = array();
    private $_databaseErrors = '';

    public function __construct($logger, $db, $business)
    {
        $this->_logger = $logger;
        $this->_db = $db;

        $this->_businessName = (isset($business->businessName)) ? mysqli_real_escape_string($this->_db, $business->businessName) : '';

        if (isset($business->businessAddress)) {
            $this->_addressLine1 = (isset($business->businessAddress->addressLine1)) ? mysqli_real_escape_string($this->_db, $business->businessAddress->addressLine1) : '';
            $this->_addressLine2 = (isset($business->businessAddress->addressLine2)) ? mysqli_real_escape_string($this->_db, $business->businessAddress->addressLine2) : '';
            $this->_city = (isset($business->businessAddress->city)) ? mysqli_real_escape_string($this->_db, $business->businessAddress->city) : '';
            $this->_stateAbbr = (isset($business->businessAddress->stateAbbr)) ? mysqli_real_escape_string($this->_db, $business->businessAddress->stateAbbr) : '';
            $this->_postal = (isset($business->businessAddress->postal)) ? mysqli_real_escape_string($this->_db, $business->businessAddress->postal) : '';
        }

        if (isset($business->businessHours)) {
            $this->_businessHours = $business->businessHours;
        }

        if (isset($business->operatingCities)) {
            $this->_operatingCities = $business->operatingCities;
        }

        if (isset($business->workTypes)) {
            $this->_workTypes = $business->workTypes;
        }

        if (isset($business->reviews)) {
            $this->_reviews = $business->reviews;
        }
    }

    private function saveBusinessHours() {
        foreach($this->_businessHours as $hours) {
            $businessHours = new BusinessHours($this->_logger, $this->_db, $this->_id, $hours);
            $businessHours->saveBusinessHours();
        }
    }

    private function saveReviews() {
        foreach($this->_reviews as $aReview) {
            $review = new Review($this->_logger, $this->_db, $this->_id, $aReview);
            $review->saveReview();
        }
    }

    private function saveOperatingCities() {
        foreach($this->_operatingCities as $city) {
            $operatingCity = new OperatingCity($this->_logger, $this->_db, $city);
            $operatingCityId = $operatingCity->getExistingOperatingCityId();
            if ($operatingCityId == 0) {
                $operatingCityId = $operatingCity->saveOperatingCity();
            }
            if ($operatingCityId != 0) {
                $businessOperatingCity = new BusinessOperatingCity($this->_logger, $this->_db, $this->_id, $operatingCityId);
                if ($businessOperatingCity->getExistingBusinessOperatingCity() === FALSE) {
                    $businessOperatingCity->saveBusinessOperatingCity();
                }
            }
        }
    }

    private function saveWorkTypes() {
        foreach($this->_workTypes as $aWorkType) {
            $workType = new WorkType($this->_logger, $this->_db, $aWorkType);
            $workTypeId = $workType->getExistingWorkTypeId();
            if ($workTypeId == 0) {
                $workTypeId = $workType->saveWorkType();
            }
            if ($workTypeId != 0) {
                $businessWorkType = new BusinessWorkType($this->_logger, $this->_db, $this->_id, $workTypeId);
                $businessWorkType->saveBusinessWorkType();
            }
        }
    }

    public function saveBusiness() {
        $sql = "
			INSERT INTO
				businesses
			SET
				businessName = '$this->_businessName',
				addressLine1 = '$this->_addressLine1',
				addressLine2 = '$this->_addressLine2',
				city = '$this->_city',
				stateAbbr = '$this->_stateAbbr',
				postal = '$this->_postal'
		";

        mysqli_query($this->_db, $sql);
        $rowsAffected = mysqli_affected_rows($this->_db);

        if ($rowsAffected === 1) {
            $this->_id = mysqli_insert_id($this->_db);
            $this->saveBusinessHours();
            $this->saveOperatingCities();
            $this->saveWorkTypes();
            $this->saveReviews();
            $parameters = array('businessId' => $this->_id);
            return Businesses::GetBusinesses($this->_logger, $this->_db, $parameters);
        } else {
            $this->_databaseErrors = $this->_db->error;
            $this->_logger->error('Database error - saveBusiness: ' . $this->_databaseErrors);
            return FALSE;
        }
    }

    public static function DeleteBusiness($logger, $db, $businessId)
    {
        $queryCondition = '';
        if ($businessId > 0) {
            $queryCondition = "WHERE id = '$businessId'";
        }
        $sql = "
            DELETE FROM
                businesses
            $queryCondition
        ";
        $logger->debug('SQL: ' . preg_replace('!\s+!', ' ', $sql));

        $result = mysqli_query($db, $sql);
        $rowsAffected = mysqli_affected_rows($db);
        if ($rowsAffected > 0) {
            return $rowsAffected;
        } else if ($rowsAffected == 0 && $result === TRUE) {
            return $rowsAffected;
        } else if ($result === FALSE) {
            $logger->error('Database error - deleteBusiness: ' . $db->error);
            return FALSE;
        }
    }

    public function getDatabaseErrors()
    {
        return $this->_databaseErrors;
    }
}