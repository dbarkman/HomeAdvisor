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
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $homeadvisorAPI->testPass();
        } else {
            $homeadvisorAPI->badRequest();
        }
    } else if ($_REQUEST['noun'] === 'business') {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $parameters = array();
            if (isset($_REQUEST['businessId'])) {
                $parameters['businessId'] = $_REQUEST['businessId'];
            } else if (isset($_REQUEST['businessName'])) {
                $parameters['businessName'] = $_REQUEST['businessName'];
            }
            if (isset($_REQUEST['workType'])) {
                $parameters['workType'] = $_REQUEST['workType'];
            }
            if (isset($_REQUEST['operatingCity'])) {
                $parameters['operatingCity'] = $_REQUEST['operatingCity'];
            }
            if (isset($_REQUEST['openOn'])) {
                $parameters['openOn'] = $_REQUEST['openOn'];
            }
            if (isset($_REQUEST['openAt'])) {
                $parameters['openAt'] = $_REQUEST['openAt'];
            }
            if (isset($_REQUEST['averageRatingScore'])) {
                $parameters['averageRatingScore'] = $_REQUEST['averageRatingScore'];
            }
            if (isset($_REQUEST['order'])) {
                $parameters['order'] = $_REQUEST['order'];
            }
            if (isset($_REQUEST['direction'])) {
                $parameters['direction'] = $_REQUEST['direction'];
            }
            $homeadvisorAPI->getBusinesses($parameters);
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $homeadvisorAPI->createBusiness();
        } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            if (isset($_REQUEST['businessId'])) {
                $homeadvisorAPI->deleteBusiness($_REQUEST['businessId']);
            } else {
                $homeadvisorAPI->badRequest();
            }
            $homeadvisorAPI->createBusiness();
        } else {
            $homeadvisorAPI->badRequest();
        }
    } else {
        $homeadvisorAPI->resourceNotFound();
    }
}