<?php

use Framework\Router\Router;

$configuration = [
    "dispatcher" => [
        "controller_suffix" => "Controller",
        "controller_namespace" => "QuizApp\Controllers"
    ],
    "routing" => [
        "routes" => [
            "log_in_page" => [
                Router::CONFIG_KEY_PATH => "/",
                "controller" => "login",
                "action" => "getLoginPage",
                "method" => "GET",
                "attributes" => []
            ],
            "user_log_in" => [
                Router::CONFIG_KEY_PATH => "/login",
                "controller" => "login",
                "action" => "loginAction",
                "method" => "POST",
                "attributes" => []
            ]
        ]
    ],
    "render" =>
        ["base_path" => "/var/www/QuizApp/src/views"]
];

return $configuration;