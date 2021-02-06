<?php

use System\Router;

Router::get('/', Application\Controllers\ApplicationController::class, 'index');
Router::post('/send-mail', Application\Controllers\ApplicationController::class, 'sendMail');
Router::post('/get-ads', Application\Controllers\ApplicationController::class, 'getAds');