<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

//Не имеет отношения к структуре фреймворка.
// Просто задаю глобальную константу.
define('SiteName','Общегородская служба записи на МРТ и КТ исследования');
define('FromMail','mail@f.mrimaster.ru');

Yii::createWebApplication($config)->run();
