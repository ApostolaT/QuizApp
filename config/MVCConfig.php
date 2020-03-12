<?php

use Framework\Router\Router;

$configuration = [
    "dispatcher" => [
        "controller_suffix" => "Controller",
        "controller_namespace" => "QuizApp\Controllers"
    ],
    "domain" =>
        "http://local.quiz.com",
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
             "view_add_quiz" => [
                Router::CONFIG_KEY_PATH => "/quiz/add",
                "controller" => "quiz",
                "action" => "goToAddQuiz",
                "method" => "GET",
                "attributes" => []
            ],
            "add_quiz" => [
                Router::CONFIG_KEY_PATH => "/quiz/add",
                "controller" => "quiz",
                "action" => "addQuiz",
                "method" => "POST",
                "attributes" => []
            ],
            "delete_quiz" => [
                Router::CONFIG_KEY_PATH => "/quiz/delete/{id}",
                "controller" => "quiz",
                "action" => "deleteQuiz",
                "method" => "GET",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "update_quiz_page" => [
                Router::CONFIG_KEY_PATH => "/quiz/add/{id}",
                "controller" => "quiz",
                "action" => "getUpdateQuizPage",
                "method" => "GET",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "update_quiz" => [
                Router::CONFIG_KEY_PATH => "/quiz/add/{id}",
                "controller" => "quiz",
                "action" => "updateQuiz",
                "method" => "POST",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "list_all_users" => [
                Router::CONFIG_KEY_PATH => "/user/{page}",
                "controller" => "user",
                "action" => "listAll",
                "method" => "GET",
                "attributes" => [
                    "page" => "\d+"
                ]
            ],
            "view_add_user" => [
                Router::CONFIG_KEY_PATH => "/user/add",
                "controller" => "user",
                "action" => "goToAddUser",
                "method" => "GET",
                "attributes" => [
                    "page" => "\d+"
                ]
            ],
            "add_user" => [
                Router::CONFIG_KEY_PATH => "/user/add",
                "controller" => "user",
                "action" => "addUser",
                "method" => "POST",
                "attributes" => []
            ],
            "delete_user" => [
                Router::CONFIG_KEY_PATH => "/user/delete/{id}",
                "controller" => "user",
                "action" => "deleteUser",
                "method" => "GET",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "update_user_page" => [
                Router::CONFIG_KEY_PATH => "/user/add/{id}",
                "controller" => "user",
                "action" => "getUpdateUserPage",
                "method" => "GET",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "update_user" => [
                Router::CONFIG_KEY_PATH => "/user/add/{id}",
                "controller" => "user",
                "action" => "updateUser",
                "method" => "POST",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "list_all_questions" => [
                Router::CONFIG_KEY_PATH => "/question/{page}",
                "controller" => "question",
                "action" => "listAll",
                "method" => "GET",
                "attributes" => [
                    "page" => "\d+"
                ]
            ],
            "view_add_question" => [
                Router::CONFIG_KEY_PATH => "/question/add",
                "controller" => "question",
                "action" => "goToAddQuestion",
                "method" => "GET",
                "attributes" => [
                    "page" => "\d+"
                ]
            ],
            "add_question" => [
                Router::CONFIG_KEY_PATH => "/question/add",
                "controller" => "question",
                "action" => "addQuestion",
                "method" => "POST",
                "attributes" => []
            ],
            "delete_question" => [
                Router::CONFIG_KEY_PATH => "/question/delete/{id}",
                "controller" => "question",
                "action" => "deleteQuestion",
                "method" => "GET",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "update_user_question" => [
                Router::CONFIG_KEY_PATH => "/question/add/{id}",
                "controller" => "question",
                "action" => "getUpdateQuestionPage",
                "method" => "GET",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "update_question" => [
                Router::CONFIG_KEY_PATH => "/question/add/{id}",
                "controller" => "question",
                "action" => "updateQuestion",
                "method" => "POST",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "get_or_create_quiz_instance" => [
                Router::CONFIG_KEY_PATH => "/quizInstance/add/{id}",
                "controller" => "quizInstance",
                "action" => "createQuizInstance",
                "method" => "GET",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "create_question_instances" => [
                Router::CONFIG_KEY_PATH => "/questionInstance/add",
                "controller" => "questionInstance",
                "action" => "createQuestionInstances",
                "method" => "GET",
                "attributes" => []
            ],
            "get_question_instance" => [
                Router::CONFIG_KEY_PATH => "/quiz/question/{offset}",
                "controller" => "questionInstance",
                "action" => "getQuestionPage",
                "method" => "GET",
                "attributes" => [
                    "offset" => '\d+'
                ]
            ],
            "set_text_instance_id" => [
                Router::CONFIG_KEY_PATH => "/quiz/text/add",
                "controller" => "questionInstance",
                "action" => "updateTextInstance",
                "method" => "POST",
                "attributes" => []
            ],
            "get_finish_page" => [
                Router::CONFIG_KEY_PATH => "/quiz/completed",
                "controller" => "questionInstance",
                "action" => "getReviewPage",
                "method" => "GET",
                "attributes" => []
            ],
            "handle_error" => [
                Router::CONFIG_KEY_PATH => "/error/{id}",
                "controller" => "error",
                "action" => "getErrorPage",
                "method" => "GET",
                "attributes" => [
                    "id" => '\d+'
                ]
            ],
            "view_result" => [
                Router::CONFIG_KEY_PATH => "/results/{offset}",
                "controller" => "result",
                "action" => "getAllUserTest",
                "method" => "GET",
                "attributes" => [
                    "offset" => '\d+'
                ]
            ],
            "view_congrats" => [
                Router::CONFIG_KEY_PATH => "/congrats",
                "controller" => "questionInstance",
                "action" => "getCongrats",
                "method" => "GET",
                "attributes" => []
            ],
            "score" => [
                Router::CONFIG_KEY_PATH => "/result/user/{userId}/quiz/{quizId}",
                "controller" => "result",
                "action" => "getScorePage",
                "method" => "GET",
                "attributes" => [
                    'userId' => '\d+',
                    'quizId' => '\d+'
                ]
            ],
        ]
    ],
    "render" =>
        ["base_path" => "/var/www/QuizApp/src/views"]
];

return $configuration;