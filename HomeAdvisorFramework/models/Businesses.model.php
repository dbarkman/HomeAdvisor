<?php

/**
 * Businesses.model.php
 */

class Businesses
{
    public static function GetBusinesses($logger, $db, $parameters)
    {
        $businessId = (array_key_exists('businessId', $parameters)) ? $parameters['businessId'] : null;
        $businessName = (array_key_exists('businessName', $parameters)) ? mysqli_real_escape_string($db, $parameters['businessName']) : '';
        $workType = (array_key_exists('workType', $parameters)) ? mysqli_real_escape_string($db, $parameters['workType']) : '';
        $operatingCity = (array_key_exists('operatingCity', $parameters)) ? mysqli_real_escape_string($db, $parameters['operatingCity']) : '';
        $openOn = (array_key_exists('openOn', $parameters)) ? $parameters['openOn'] : '';
        $openAt = (array_key_exists('openAt', $parameters)) ? $parameters['openAt'] : 12;
        $averageRatingScore = (array_key_exists('averageRatingScore', $parameters)) ? $parameters['averageRatingScore'] : 0;
        $order = (array_key_exists('order', $parameters)) ? mysqli_real_escape_string($db, $parameters['order']) : 'businessName';
        $direction = (array_key_exists('direction', $parameters)) ? mysqli_real_escape_string($db, $parameters['direction']) : 'ASC';

        $queryCondition = '';
        if ($businessId != null) {
            $queryCondition = "WHERE b.id = $businessId";
        } else if (!empty($businessName)) {
            $queryCondition = "WHERE b.businessName LIKE '%$businessName%'";
        }

        $joinCondition = '';
        if (!empty($workType)) {
            $joinCondition .= "
                JOIN businessesWorkTypes bwt ON bwt.businessId = b.id
                JOIN workTypes wt ON wt.id = bwt.workTypeId
            ";
            $queryCondition .= (empty($queryCondition)) ? 'WHERE' : ' AND ';
            $queryCondition .= " wt.workType = '$workType'";
        }
        if (!empty($operatingCity)) {
            $joinCondition .= "
                JOIN businessesOperatingCities boc ON boc.businessId = b.id
                JOIN operatingCities oc ON oc.id = boc.operatingCityId
            ";
            $queryCondition .= (empty($queryCondition)) ? 'WHERE' : ' AND ';
            $queryCondition .= " oc.city = '$operatingCity'";
        }
        if (!empty($openOn)) {
            $joinCondition .= "
                JOIN businessHours bh ON bh.businessId = b.id
            ";
            $queryCondition .= (empty($queryCondition)) ? 'WHERE' : ' AND ';
            $queryCondition .= " bh.dayOfWeek in ('$openOn')";
            $queryCondition .= " AND $openAt BETWEEN bh.open AND bh.close + 12 - 1";
        }

        $businesses = array();
        $sql = "
            SELECT 
                b.*, ROUND(AVG(r.ratingScore), 2) AS averageRatingScore
            FROM
                businesses b
            $joinCondition
            JOIN
                reviews r ON r.businessId = b.id
            $queryCondition
            GROUP BY 
                b.businessName
            ORDER BY
                $order $direction
        ";
        $logger->debug('SQL: ' . preg_replace('!\s+!', ' ', $sql));

        $result = mysqli_query($db, $sql);
        if ($result === FALSE) {
            return $businesses;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
                $businessesHours = BusinessesHours::GetBusinessesHours($logger, $db, $id);
                $operatingCities = OperatingCities::GetOperatingCities($logger, $db, $id);
                $worktypes = WorkTypes::GetWorkTypes($logger, $db, $id);
                $reviews = Reviews::GetReviews($logger, $db, $id);
                if ($averageRatingScore > 0 && self::getAverageRatingScore($reviews) < $averageRatingScore) continue;
                $business = array(
                    'businessName' => $row['businessName'],
                    'businessHours' => $businessesHours,
                    'businessAddress' => array(
                        'addressLine1' => $row['addressLine1'],
                        'addressLine2' => $row['addressLine2'],
                        'city' => $row['city'],
                        'stateAbbr' => $row['stateAbbr'],
                        'postal' => $row['postal']
                    ),
                    'operatingCities' => $operatingCities,
                    'workTypes' => $worktypes,
                    'reviews' => $reviews
                );
                if ($businessId != null) {
                    $business = array('id' => $row['id']) + $business;
                }
                $businesses[] = $business;
            }
        }
        return $businesses;
    }

    private static function getAverageRatingScore($reviews)
    {
        $count = 0;
        $score = 0;
        foreach($reviews as $review) {
            $count++;
            $score += $review['ratingScore'];
        }
        return $score / $count;
    }
}
