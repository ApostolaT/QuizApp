<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$config = require "MVCConfig.php";
$suffix = $config["dispatcher"]["controller_suffix"];
$controllerName = $config["dispatcher"]["controller_namespace"];
$baseViewPath = $config["render"]["base_path"];

$container = new ContainerBuilder();
$container->setParameter("config", $config);
$container->setParameter("suffix", $suffix);
$container->setParameter("controllerName", $controllerName);
$container->setParameter("baseViewPath", $baseViewPath);

$container
    ->register(Framework\Contracts\RouterInterface::class, Framework\Router\Router::class)
    ->addArgument('%config%');
$container
    ->register(Framework\Contracts\RendererInterface::class, Framework\Render\Renderer::class)
    ->addArgument('%baseViewPath%');
$container
    ->register(QuizApp\Controllers\UserController::class, QuizApp\Controllers\UserController::class)
    ->addArgument(new Reference(Framework\Contracts\RendererInterface::class))
    ->addTag('controller');
$dispatcher = $container
    ->register(Framework\Contracts\DispatcherInterface::class, Framework\Dispatcher\Dispatcher::class)
    ->addArgument('%controllerName%')
    ->addArgument('%suffix%');

foreach ($container->findTaggedServiceIds('controller') as $id => $tags) {
    $controller = $container->getDefinition($id);
    $dispatcher->addMethodCall("addController", [$controller]);
}

return new \Framework\DependencyInjection\SymfonyContainer($container);