<?php

/**
 * index.php
 */

session_start();
session_regenerate_id();

require_once dirname(__FILE__) . '/../HomeAdvisorFramework/includes/includes.php';

$homeadvisorAPI = new HomeAdvisorAPI();

if (isset($_REQUEST['noun'])) {
    if ($_REQUEST['noun'] === 'testPass') {
        $homeadvisorAPI->testPass();

    } else if ($_REQUEST['noun'] === 'earthquakes') {
    }
}