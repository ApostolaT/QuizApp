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
                "controller" => "user",
                "action" => "getLoginPage",
                "method" => "GET",
                "attributes" => []
            ],
            "user_log_in" => [
                Router::CONFIG_KEY_PATH => "/user",
                "controller" => "user",
                "action" => "logIn",
                "method" => "POST",
                "attributes" => []
            ]
        ]
    ],
    "render" =>
        ["base_path" => "/var/www/QuizApp/src/views"]
];

return $configuration;