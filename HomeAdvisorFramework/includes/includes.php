<?php

/**
 * includes.php
 */

require_once 'HomeAdvisorAPI.php';
require_once dirname(__FILE__) . '/../config/credentials.php';

function autoloadModels($className) {
    $filename = dirname(__FILE__) . '/../models/' . $className . '.model.php';
    if (is_readable($filename)) {
        require_once $filename;
    }
}
spl_autoload_register('autoloadModels');

function autoloadControllers($className) {
    $filename = dirname(__FILE__) . '/../controllers/' . $className . '.controller.php';
    if (is_readable($filename)) {
        require_once $filename;
    }
}
spl_autoload_register('autoloadControllers');
