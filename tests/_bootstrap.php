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

define('SENDGRID_FROM', '<FROM>');
define('SENDGRID_TOKEN', '<SENDGRID_TOKEN>');
define('SENDGRID_TO', '<TO>');
define('SENDGRID_TEMPLATE', '<SENDGRID_TEST_TEMPLATE_ID>');

define('SENDGRID_TEST_SEND', false);


Yii::setAlias('@tests/unit', __DIR__ . '/unit');
Yii::setAlias('@sweelix/sendgrid', dirname(__DIR__) .'/src');