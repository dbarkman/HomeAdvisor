<?php

/**
 * Reviews.model.php
 */

class Reviews
{
    public static function GetReviews($logger, $db, $businessId)
    {
        $sql = "
            SELECT 
                * 
            FROM 
                reviews
            WHERE 
                businessId = '$businessId'
            ORDER BY 
                id
        ";
        $logger->debug('SQL: ' . preg_replace('!\s+!', ' ', $sql));

        $reviews = array();

        $result = mysqli_query($db, $sql);
        if ($result === FALSE) {
            return $reviews;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $reviews[] = array(
                    'ratingScore' => $row['ratingScore'],
                    'customerComment' => $row['customerComment']
                );
            }
        }
        return $reviews;
    }
}