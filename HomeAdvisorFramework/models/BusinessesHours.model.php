<?php

/**
 * BusinessesHours.model.php
 */

class BusinessesHours
{
    public static function GetBusinessesHours($logger, $db, $businessId)
    {
        $sql = "
            SELECT 
                * 
            FROM 
                businessHours
            WHERE 
                businessId = '$businessId'
            ORDER BY 
                id
        ";
        $logger->debug('SQL: ' . preg_replace('!\s+!', ' ', $sql));

        $businessesHours = array();

        $result = mysqli_query($db, $sql);
        if ($result === FALSE) {
            return $businessesHours;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $businessesHours[] = array(
                    'dayOfWeek' => $row['dayOfWeek'],
                    'open' => $row['open'],
                    'close' => $row['close']
                );
            }
        }
        return $businessesHours;
    }
}