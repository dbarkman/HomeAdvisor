<?php

/**
 * WorkTypes.model.php
 */

class WorkTypes
{
    public static function GetWorkTypes($logger, $db, $businessId)
    {
        $sql = "
            SELECT 
                wt.* 
            FROM 
                workTypes wt
            JOIN 
                businessesWorkTypes bwt ON bwt.workTypeId = wt.id
            JOIN 
                businesses b ON b.id = bwt.businessId
            WHERE 
                b.id = '$businessId'
            ORDER BY 
                wt.id
        ";
        $logger->debug('SQL: ' . preg_replace('!\s+!', ' ', $sql));

        $workTypes = array();

        $result = mysqli_query($db, $sql);
        if ($result === FALSE) {
            return $workTypes;
        } else {
            while ($row = mysqli_fetch_row($result)) {
                $workTypes[] = $row[1];
            }
        }
        return $workTypes;
    }
}
