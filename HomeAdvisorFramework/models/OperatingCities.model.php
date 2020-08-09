<?php

/**
 * OperatingCities.model.php
 */

class OperatingCities
{
    public static function GetOperatingCities($logger, $db, $businessId)
    {
        $sql = "
            SELECT 
                oc.* 
            FROM 
                operatingCities oc
            JOIN 
                businessesOperatingCities boc ON boc.operatingCityId = oc.id
            JOIN 
                businesses b ON b.id = boc.businessId
            WHERE 
                b.id = '$businessId'
            ORDER BY 
                oc.id
        ";
        $logger->debug('SQL: ' . preg_replace('!\s+!', ' ', $sql));

        $operatingCities = array();

        $result = mysqli_query($db, $sql);
        if ($result === FALSE) {
            return $operatingCities;
        } else {
            while ($row = mysqli_fetch_row($result)) {
                $operatingCities[] = $row[1];
            }
        }
        return $operatingCities;
    }
}