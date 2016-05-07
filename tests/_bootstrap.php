<?php
// This is global bootstrap for autoloading
date_default_timezone_set('Europe/Paris');

// ensure we get report on all possible php errors
error_reporting(E_ALL);
define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);
$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

define('SENDGRID_FROM', 'reply@sweelix.io');
define('SENDGRID_TOKEN', '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');
define('SENDGRID_TO', 'pgaultier@sweelix.net');
define('SENDGRID_TEMPLATE', 'd3a17530-1b94-419d-b773-f996a700c5f0');

define('SENDGRID_TEST_SEND', true);


Yii::setAlias('@tests/unit', __DIR__ . '/unit');
Yii::setAlias('@sweelix/sendgrid', dirname(__DIR__) .'/src');