<?php

/**
 * index.php
 */

class HomeAdvisorAPI
{
    private $_logger;
    private $_db;

    private $_start;
    private $_count;
    private $_response;

    public function __construct()
    {
        $container = new Container();
        $this->_logger = $container->getLogger();
        $this->_db = $container->getMySQLDBConnect();

        $this->_start = microtime(true);
        $this->_count = 0;

        $this->beginRequest();
    }

    private function beginRequest()
    {
        $this->logIt('info', '');
        $this->logIt('info', '--------------------------------------------------------------------------------');
        $this->logIt('info', 'API Session Started');
        $this->logIt('info', 'Query String: ' . $_SERVER['QUERY_STRING']);

        $timeStamp = (isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : 'NA');
        $ip = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'NA');
        $agent = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'NA');
        $language = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'NA');
        $method = (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'NA');

        $this->logIt('info', 'TIME: ' . $timeStamp);
        $this->logIt('info', 'IP: ' . $ip);
        $this->logIt('info', 'AGENT: ' . $agent);
        $this->logIt('info', 'LANGUAGE: ' . $language);
        $this->logIt('info', 'VERB: ' . $method);
        $this->logIt('info', 'NOUN: ' . $_REQUEST['noun']);
    }

    public function logIt($level, $message)
    {
        $this->_logger->$level($message);
    }

    ///////////////////////////////////////////////////////////////////////////////
    ////////////////////////////// RESPONSE FUNCTIONS /////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    public function testPass()
    {
        http_response_code(200);
        $this->echoResponse('none', array(), '', 'success', array());
        $this->completeRequest();
    }

    public function badRequest()
    {
        http_response_code(400);
        $errorCode = 'badRequest';
        $friendlyError = 'Bad Request';
        $errors = array($friendlyError);
        $this->echoResponse($errorCode, $errors, $friendlyError, 'fail', array());
        $this->completeRequest();
    }

    public function resourceNotFound()
    {
        http_response_code(404);
        $errorCode = 'resourceNotFound';
        $friendlyError = 'Resource Not Found';
        $errors = array($friendlyError);
        $this->echoResponse($errorCode, $errors, $friendlyError, 'fail', array());
        $this->completeRequest();
    }

    ///////////////////////////////////////////////////////////////////////////////
    ////////////////////////////// BUSINESS FUNCTIONS /////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    public function createBusiness()
    {
        $postBody = file_get_contents('php://input');
        $businessObject = json_decode($postBody);
        if (!is_array($businessObject)) {
            $businessObject = array($businessObject);
        }

        $errors = array();
        $businesses = array();
        foreach ($businessObject as $business) {
            $business = new Business($this->_logger, $this->_db, $business);
            $result = $business->saveBusiness();
            if ($result === FALSE) {
                $errors[] = $business->getDatabaseErrors();
            } else {
                $businesses[] = $result[0];
            }
        }
        if (count($businesses) > 0) {
            http_response_code(201);
            $this->echoResponse('none', array(), '', 'success', $businesses);
        } else {
            http_response_code(500);
            $errorCode = 'businessNotCreated';
            $friendlyError = 'Business could not be created.';
            array_unshift($errors, $friendlyError);
            $this->echoResponse($errorCode, $errors, $friendlyError, 'fail', array());
        }
        $this->completeRequest();
    }

    public function getBusinesses($parameters)
    {
        http_response_code(200);
        $businesses = Businesses::GetBusinesses($this->_logger, $this->_db, $parameters);
        $this->echoResponse('none', array(), '', 'success', $businesses);
        $this->completeRequest();
    }

    public function deleteBusiness($businessId)
    {
        $result = Business::DeleteBusiness($this->_logger, $this->_db, $businessId);
        if ($result > 0) {
            http_response_code(200);
            $this->_count = $result;
            $this->echoResponse('none', array(), '', 'success', array());
        } else if ($result == 0) {
            http_response_code(200);
            $errorCode = 'businessNotDeleted';
            $friendlyError = "No business found to delete.";
            $errors = array($friendlyError);
            $this->echoResponse($errorCode, $errors, $friendlyError, 'success', array());
        } else if ($result === FALSE) {
            http_response_code(500);
            $errorCode = 'businessNotDeleted';
            $friendlyError = 'Business could not be deleted.';
            $errors = array($friendlyError);
            $this->echoResponse($errorCode, $errors, $friendlyError, 'fail', array());
        }
        $this->completeRequest();
    }

    ///////////////////////////////////////////////////////////////////////////////
    ////////////////////////////// CLOSING FUNCTIONS //////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    private function echoResponse($errorCode, $errors, $friendlyErrors, $result, $data)
    {
        if ($this->_count == 0) {
            $this->_count = count($data);
        }

        $jsonResponse = array();
        $jsonResponse['httpStatus'] = http_response_code();
        $jsonResponse['noun'] = $_REQUEST['noun'];
        $jsonResponse['verb'] = $_SERVER['REQUEST_METHOD'];
        $jsonResponse['errorCode'] = $errorCode;
        $jsonResponse['errors'] = $errors;
        $jsonResponse['friendlyError'] = $friendlyErrors;
        $jsonResponse['result'] = $result;
        $jsonResponse['count'] = $this->_count;
        $jsonResponse['data'] = $data;
        foreach ($errors as $error) {
            $this->logIt('info', $error);
        }
        $this->_response = json_encode($jsonResponse);
        header('Content-type: application/json');
        echo $this->_response;
    }


    private function completeRequest()
    {
        $time = (microtime(true) - $this->_start);
        $packageSize = strlen($this->_response);
        $size = number_format($packageSize);
        $memoryUsage = number_format(memory_get_usage());

        $this->logIt('info', 'Payload Time: ' . $time);
        $this->logIt('info', 'Payload Size: ' . $size);
        $this->logIt('info', 'Count: ' . $this->_count);
        $this->logIt('info', 'Memory Usage: ' . $memoryUsage);
        $this->logIt('info', 'API Session Ended');
        $this->logIt('info', '--------------------------------------------------------------------------------');
        $this->logIt('info', '');

        exit();
    }
}