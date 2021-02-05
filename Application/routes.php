<?php

use System\Router;

Router::get('/', Application\Controllers\ApplicationController::class, 'index');
Router::get('/send-mail', Application\Controllers\ApplicationController::class, 'sendMail');
Router::post('/get-ads', Application\Controllers\ApplicationController::class, 'getAds');