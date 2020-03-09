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
            ],
            "user_log_out" => [
                Router::CONFIG_KEY_PATH => "/logout",
                "controller" => "login",
                "action" => "logoutAction",
                "method" => "GET",
                "attributes" => []
            ],
            "list_all_quizzes" => [
                Router::CONFIG_KEY_PATH => "/quiz/{page}",
                "controller" => "quiz",
                "action" => "listAll",
                "method" => "GET",
                "attributes" => [
                    "page" => "\d+"
                ]
            ],
             "add_quizz" => [
                Router::CONFIG_KEY_PATH => "/quiz/add",
                "controller" => "quiz",
                "action" => "goToAddPage",
                "method" => "GET",
                "attributes" => [
                "page" => "\d+"
                ]
            ],
            "list_all_users" => [
                Router::CONFIG_KEY_PATH => "/user",
                "controller" => "user",
                "action" => "listAll",
                "method" => "GET",
                "attributes" => []
            ],
            "list_all_questions" => [
                Router::CONFIG_KEY_PATH => "/question",
                "controller" => "question",
                "action" => "listAll",
                "method" => "GET",
                "attributes" => []
            ]
        ]
    ],
    "render" =>
        ["base_path" => "/var/www/QuizApp/src/views"]
];

return $configuration;