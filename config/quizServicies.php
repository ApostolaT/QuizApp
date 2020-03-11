<?php

use ReallyOrm\Test\Hydrator\Hydrator;
use ReallyOrm\Test\Repository\RepositoryManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$MVCconfig = require "MVCConfig.php";
$suffix = $MVCconfig["dispatcher"]["controller_suffix"];
$controllerName = $MVCconfig["dispatcher"]["controller_namespace"];
$baseViewPath = $MVCconfig["render"]["base_path"];

$dbConfig = require "db_config.php";
$dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['db']};charset={$dbConfig['charset']}";
$dbUser = $dbConfig['user'];
$dbPassword = $dbConfig['pass'];
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$container = new ContainerBuilder();
$container->setParameter("MVCconfig", $MVCconfig);
$container->setParameter("suffix", $suffix);
$container->setParameter("controllerName", $controllerName);
$container->setParameter("baseViewPath", $baseViewPath);
$container->setParameter("dsn", $dsn);
$container->setParameter("dbUser", $dbUser);
$container->setParameter("dbPassword", $dbPassword);
$container->setParameter("options", $options);

$session = $container
    ->register(\Framework\Session\Session::class, \Framework\Session\Session::class);

$container
    ->register(Framework\Contracts\RouterInterface::class, Framework\Router\Router::class)
    ->addArgument('%MVCconfig%');
$container
    ->register(Framework\Contracts\RendererInterface::class, Framework\Render\Renderer::class)
    ->addArgument('%baseViewPath%');

$container
    ->register(PDO::class, PDO::class)
    ->addArgument('%dsn%')
    ->addArgument('%dbUser%')
    ->addArgument('%dbPassword%')
    ->addArgument('%options%');

$loginService = $container
    ->register(\QuizApp\Services\LoginService::class, \QuizApp\Services\LoginService::class)
    ->addTag('service');
$quizService = $container
    ->register(\QuizApp\Services\QuizService::class, \QuizApp\Services\QuizService::class)
    ->addTag('service');
$userService = $container
    ->register(\QuizApp\Services\UserService::class, \QuizApp\Services\UserService::class)
    ->addTag('service');
$questionService = $container
    ->register(\QuizApp\Services\QuestionService::class, \QuizApp\Services\QuestionService::class)
    ->addTag('service');

$container
    ->register(QuizApp\Entities\User::class, QuizApp\Entities\User::class);
$container
    -> register(\QuizApp\Entities\QuizType::class, \QuizApp\Entities\QuizType::class);

$repositoryManager = $container
    ->register(RepositoryManager::class, RepositoryManager::class);

$container
    ->register(Hydrator::class, Hydrator::class)
    ->addArgument(new Reference(RepositoryManager::class));

$container
    ->register(QuizApp\Repositories\UserRepository::class, QuizApp\Repositories\UserRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizApp\Entities\User::class)
    ->addArgument(new Reference(Hydrator::class))
    ->addTag("repository");
$container
    ->register(QuizApp\Repositories\QuizTypeRepository::class, QuizApp\Repositories\QuizTypeRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizApp\Entities\QuizType::class)
    ->addArgument(new Reference(Hydrator::class))
    ->addTag("repository");
$container
    ->register(QuizApp\Repositories\QuizRepository::class, QuizApp\Repositories\QuizRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizApp\Entities\QuizTemplate::class)
    ->addArgument(new Reference(Hydrator::class))
    ->addTag("repository");
$container
    ->register(QuizApp\Repositories\QuestionRepository::class, QuizApp\Repositories\QuestionRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizApp\Entities\QuestionTemplate::class)
    ->addArgument(new Reference(Hydrator::class))
    ->addTag("repository");
$container
    ->register(QuizApp\Repositories\TextRepository::class, QuizApp\Repositories\TextRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizApp\Entities\TextTemplate::class)
    ->addArgument(new Reference(Hydrator::class))
    ->addTag("repository");
$container
    ->register(QuizApp\Repositories\QuizQuestionTemplateRepository::class, QuizApp\Repositories\QuizQuestionTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizApp\Entities\QuizQuestionTemplate::class)
    ->addArgument(new Reference(Hydrator::class))
    ->addTag("repository");

$userController = $container
    ->register(QuizApp\Controllers\ErrorController::class, QuizApp\Controllers\ErrorController::class)
    ->addArgument(new Reference(Framework\Contracts\RendererInterface::class))
    ->addTag('controller');
$userController = $container
    ->register(QuizApp\Controllers\UserController::class, QuizApp\Controllers\UserController::class)
    ->addArgument(new Reference(Framework\Contracts\RendererInterface::class))
    ->addTag('controller');
$quizController = $container
    ->register(QuizApp\Controllers\QuizController::class, QuizApp\Controllers\QuizController::class)
    ->addArgument(new Reference(Framework\Contracts\RendererInterface::class))
    ->addTag('controller');
$loginController = $container
    ->register(QuizApp\Controllers\LoginController::class, QuizApp\Controllers\LoginController::class)
    ->addArgument(new Reference(Framework\Contracts\RendererInterface::class))
    ->addTag('controller');
$questionController = $container
    ->register(QuizApp\Controllers\QuestionController::class, QuizApp\Controllers\QuestionController::class)
    ->addArgument(new Reference(Framework\Contracts\RendererInterface::class))
    ->addTag('controller');

$dispatcher = $container
    ->register(Framework\Contracts\DispatcherInterface::class, Framework\Dispatcher\Dispatcher::class)
    ->addArgument('%controllerName%')
    ->addArgument('%suffix%');

foreach ($container->findTaggedServiceIds('repository') as $id => $tags) {
    $repository = $container->getDefinition($id);
    $repositoryManager->addMethodCall("addRepository", [$repository]);
}

foreach ($container->findTaggedServiceIds('service') as $id => $tags) {
    $service = $container->getDefinition($id);
    $service->addMethodCall('setRepositoryManager', [$repositoryManager]);
}

foreach ($container->findTaggedServiceIds('controller') as $id => $tags) {
    $controller = $container->getDefinition($id);
    $controller->addMethodCall('setSession', [$session]);
    $dispatcher->addMethodCall("addController", [$controller]);
}

$loginController->addMethodCall('setService', [$loginService]);
$quizController->addMethodCall('setQuizService', [$quizService]);
$quizController->addMethodCall('setQuestionService', [$questionService]);
$userController->addMethodCall('setService', [$userService]);
$questionController->addMethodCall('setService', [$questionService]);

return new \Framework\DependencyInjection\SymfonyContainer($container);