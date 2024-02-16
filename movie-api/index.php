<?php

use Api\Controllers\ApiController;

require ("vendor/autoload.php");

define('Api', dirname(__FILE__));

$uri = $_SERVER;

$api = new ApiController();
echo $api->process();