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
                Router::CONFIG_KEY_CONTROLLER => "login",
                Router::CONFIG_KEY_ACTION => "getLoginPage",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "user_log_in" => [
                Router::CONFIG_KEY_PATH => "/login",
                Router::CONFIG_KEY_CONTROLLER => "login",
                Router::CONFIG_KEY_ACTION => "loginAction",
                Router::CONFIG_KEY_METHOD => "POST",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "user_log_out" => [
                Router::CONFIG_KEY_PATH => "/logout",
                Router::CONFIG_KEY_CONTROLLER => "login",
                Router::CONFIG_KEY_ACTION => "logoutAction",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "list_all_quizzes" => [
                Router::CONFIG_KEY_PATH => "/quiz",
                Router::CONFIG_KEY_CONTROLLER => "quiz",
                Router::CONFIG_KEY_ACTION => "listAll",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
             "view_add_quiz" => [
                Router::CONFIG_KEY_PATH => "/quiz/add",
                Router::CONFIG_KEY_CONTROLLER => "quiz",
                Router::CONFIG_KEY_ACTION => "goToAddQuiz",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "add_quiz" => [
                Router::CONFIG_KEY_PATH => "/quiz/add",
                Router::CONFIG_KEY_CONTROLLER => "quiz",
                Router::CONFIG_KEY_ACTION => "addQuiz",
                Router::CONFIG_KEY_METHOD => "POST",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "delete_quiz" => [
                Router::CONFIG_KEY_PATH => "/quiz/delete/{id}",
                Router::CONFIG_KEY_CONTROLLER => "quiz",
                Router::CONFIG_KEY_ACTION => "deleteQuiz",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "update_quiz_page" => [
                Router::CONFIG_KEY_PATH => "/quiz/add/{id}",
                Router::CONFIG_KEY_CONTROLLER => "quiz",
                Router::CONFIG_KEY_ACTION => "getUpdateQuizPage",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "update_quiz" => [
                Router::CONFIG_KEY_PATH => "/quiz/add/{id}",
                Router::CONFIG_KEY_CONTROLLER => "quiz",
                Router::CONFIG_KEY_ACTION => "updateQuiz",
                Router::CONFIG_KEY_METHOD => "POST",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "list_all_users" => [
                Router::CONFIG_KEY_PATH => "/user",
                Router::CONFIG_KEY_CONTROLLER => "user",
                Router::CONFIG_KEY_ACTION => "listAll",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "view_add_user" => [
                Router::CONFIG_KEY_PATH => "/user/add",
                Router::CONFIG_KEY_CONTROLLER => "user",
                Router::CONFIG_KEY_ACTION => "goToAddUser",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "page" => "\d+"
                ]
            ],
            "add_user" => [
                Router::CONFIG_KEY_PATH => "/user/add",
                Router::CONFIG_KEY_CONTROLLER => "user",
                Router::CONFIG_KEY_ACTION => "addUser",
                Router::CONFIG_KEY_METHOD => "POST",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "delete_user" => [
                Router::CONFIG_KEY_PATH => "/user/delete/{id}",
                Router::CONFIG_KEY_CONTROLLER => "user",
                Router::CONFIG_KEY_ACTION => "deleteUser",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "update_user_page" => [
                Router::CONFIG_KEY_PATH => "/user/add/{id}",
                Router::CONFIG_KEY_CONTROLLER => "user",
                Router::CONFIG_KEY_ACTION => "getUpdateUserPage",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "update_user" => [
                Router::CONFIG_KEY_PATH => "/user/add/{id}",
                Router::CONFIG_KEY_CONTROLLER => "user",
                Router::CONFIG_KEY_ACTION => "updateUser",
                Router::CONFIG_KEY_METHOD => "POST",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "list_all_questions" => [
                Router::CONFIG_KEY_PATH => "/question",
                Router::CONFIG_KEY_CONTROLLER => "question",
                Router::CONFIG_KEY_ACTION => "listAll",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "view_add_question" => [
                Router::CONFIG_KEY_PATH => "/question/add",
                Router::CONFIG_KEY_CONTROLLER => "question",
                Router::CONFIG_KEY_ACTION => "goToAddQuestion",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "page" => "\d+"
                ]
            ],
            "add_question" => [
                Router::CONFIG_KEY_PATH => "/question/add",
                Router::CONFIG_KEY_CONTROLLER => "question",
                Router::CONFIG_KEY_ACTION => "addQuestion",
                Router::CONFIG_KEY_METHOD => "POST",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "delete_question" => [
                Router::CONFIG_KEY_PATH => "/question/delete/{id}",
                Router::CONFIG_KEY_CONTROLLER => "question",
                Router::CONFIG_KEY_ACTION => "deleteQuestion",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "update_user_question" => [
                Router::CONFIG_KEY_PATH => "/question/add/{id}",
                Router::CONFIG_KEY_CONTROLLER => "question",
                Router::CONFIG_KEY_ACTION => "getUpdateQuestionPage",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "update_question" => [
                Router::CONFIG_KEY_PATH => "/question/add/{id}",
                Router::CONFIG_KEY_CONTROLLER => "question",
                Router::CONFIG_KEY_ACTION => "updateQuestion",
                Router::CONFIG_KEY_METHOD => "POST",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "get_or_create_quiz_instance" => [
                Router::CONFIG_KEY_PATH => "/quizInstance/add/{id}",
                Router::CONFIG_KEY_CONTROLLER => "quizInstance",
                Router::CONFIG_KEY_ACTION => "createQuizInstance",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "create_question_instances" => [
                Router::CONFIG_KEY_PATH => "/questionInstance/add",
                Router::CONFIG_KEY_CONTROLLER => "questionInstance",
                Router::CONFIG_KEY_ACTION => "createQuestionInstances",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "get_question_instance" => [
                Router::CONFIG_KEY_PATH => "/quiz/question/{offset}",
                Router::CONFIG_KEY_CONTROLLER => "questionInstance",
                Router::CONFIG_KEY_ACTION => "getQuestionPage",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "offset" => '\d+'
                ]
            ],
            "set_text_instance_id" => [
                Router::CONFIG_KEY_PATH => "/quiz/text/add",
                Router::CONFIG_KEY_CONTROLLER => "questionInstance",
                Router::CONFIG_KEY_ACTION => "updateTextInstance",
                Router::CONFIG_KEY_METHOD => "POST",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "get_user_review_page" => [
                Router::CONFIG_KEY_PATH => "/user/completed/quiz/{quizId}",
                Router::CONFIG_KEY_CONTROLLER => "result",
                Router::CONFIG_KEY_ACTION => "getUserResultPage",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'quizId' => "\d+"
                ]
            ],
            "handle_error" => [
                Router::CONFIG_KEY_PATH => "/error/{id}",
                Router::CONFIG_KEY_CONTROLLER => "error",
                Router::CONFIG_KEY_ACTION => "getErrorPage",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    "id" => '\d+'
                ]
            ],
            "view_result" => [
                Router::CONFIG_KEY_PATH => "/result",
                Router::CONFIG_KEY_CONTROLLER => "result",
                Router::CONFIG_KEY_ACTION => "getAllUserTest",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "view_congrats" => [
                Router::CONFIG_KEY_PATH => "/congrats",
                Router::CONFIG_KEY_CONTROLLER => "questionInstance",
                Router::CONFIG_KEY_ACTION => "getCongrats",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
            "view_admin_score_page" => [
                Router::CONFIG_KEY_PATH => "/result/user/{userId}/quiz/{quizId}",
                Router::CONFIG_KEY_CONTROLLER => "result",
                Router::CONFIG_KEY_ACTION => "getAdminResultPage",
                Router::CONFIG_KEY_METHOD => "GET",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'userId' => '\d+',
                    'quizId' => '\d+'
                ]
            ],
            "score_quiz_instance" => [
                Router::CONFIG_KEY_PATH => "/result/score/{quizId}",
                Router::CONFIG_KEY_CONTROLLER => "result",
                Router::CONFIG_KEY_ACTION => "scoreQuiz",
                Router::CONFIG_KEY_METHOD => "POST",
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'quizId' => '\d+'
                ]
            ]
        ]
    ],
    "render" =>
        ["base_path" => "../src/views"]
];

return $configuration;