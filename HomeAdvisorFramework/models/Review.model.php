<?php

/**
 * Review.model.php
 */

class Review
{
    private $_logger;
    private $_db;
    private $_id;
    private $_businessId;
    private $_ratingScore;
    private $_customerComment;

    public function __construct($logger, $db, $businessId, $review)
    {
        $this->_logger = $logger;
        $this->_db = $db;
        $this->_businessId = $businessId;
        $this->_ratingScore = (isset($review->ratingScore)) ? $review->ratingScore : null;
        $this->_customerComment = (isset($review->customerComment)) ? mysqli_real_escape_string($this->_db, $review->customerComment) : '';
        if ($this->_ratingScore > 5) $this->_ratingScore = 5;
    }

    public function saveReview()
    {
        $sql = "
			INSERT INTO
				reviews
			SET
				businessId = '$this->_businessId',
				ratingScore = '$this->_ratingScore',
				customerComment = '$this->_customerComment'
		";

        mysqli_query($this->_db, $sql);
        $rowsAffected = mysqli_affected_rows($this->_db);

        if ($rowsAffected === 1) {
            $this->_id = mysqli_insert_id($this->_db);
            return TRUE;
        } else {
            $errors = $this->_db->error;
            $this->_logger->error('Database error - saveReview: ' . $errors);
            return $errors;
        }
    }
}