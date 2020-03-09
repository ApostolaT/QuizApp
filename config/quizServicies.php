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
$container
    ->register(QuizApp\Entities\User::class, QuizApp\Entities\User::class);
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
$userController = $container
    ->register(QuizApp\Controllers\UserController::class, QuizApp\Controllers\UserController::class)
    ->addArgument(new Reference(Framework\Contracts\RendererInterface::class))
    ->addTag('controller');
$loginController = $container
    ->register(QuizApp\Controllers\LoginController::class, QuizApp\Controllers\LoginController::class)
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
    $dispatcher->addMethodCall("addController", [$controller]);
}

$loginController->addMethodCall('setService', [$loginService]);

return new \Framework\DependencyInjection\SymfonyContainer($container);