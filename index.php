<?php

define('ROOT', __DIR__);
define('SYSTEM', ROOT . DIRECTORY_SEPARATOR . 'System');
define('APPLICATION', ROOT . DIRECTORY_SEPARATOR . 'Application');
define('CONFIG', APPLICATION . DIRECTORY_SEPARATOR . 'config');
define('RESOURCES', APPLICATION . DIRECTORY_SEPARATOR . 'resources');
define('VIEWS', RESOURCES . DIRECTORY_SEPARATOR . 'views');

require ROOT . DIRECTORY_SEPARATOR . 'autoload.php';

$kernel = new System\Kernel();
$kernel->run();