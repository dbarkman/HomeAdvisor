<?php

/**
 * index.php
 */

class HomeAdvisorAPI
{
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
//        $this->completeRequest();
    }

    public function badRequest()
    {
        http_response_code(400);
        $errorCode = 'badRequest';
        $friendlyError = 'Bad Request';
        $errors = array($friendlyError);
        $this->echoResponse($errorCode, $errors, $friendlyError, 'fail', (object)array());
        $this->completeRequest();
    }

    public function resourceNotFound()
    {
        http_response_code(404);
        $errorCode = 'resourceNotFound';
        $friendlyError = 'Resource Not Found';
        $errors = array($friendlyError);
        $this->echoResponse($errorCode, $errors, $friendlyError, 'fail', (object)array());
        $this->completeRequest();
    }

    public function resourceNotDefined()
    {
        http_response_code(400);
        $errorCode = 'resourceNotDefined';
        $friendlyError = 'Resource Not Defined';
        $errors = array($friendlyError);
        $this->echoResponse($errorCode, $errors, $friendlyError, 'fail', (object)array());
        $this->completeRequest();
    }

    ///////////////////////////////////////////////////////////////////////////////
    ////////////////////////////// CLOSING FUNCTIONS //////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    private function echoResponse($errorCode, $errors, $friendlyErrors, $result, $data)
    {
        // if a callback is set, assume jsonp and wrap the response in the callback function
        if (isset($_REQUEST['callback']) && strtolower($_REQUEST['responseType'] === 'jsonp')) {
            echo $_REQUEST['callback'] . '(';
        }

        $this->_count = count($data);
        $this->_errorCode = $errorCode;

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

        if (isset($_REQUEST['callback']) && strtolower($_REQUEST['responseType'] === 'jsonp')) {
            echo ')';
        }
    }
}