<?php

use \Framework\Renderer\RendererInterface;
use \Framework\Renderer\TwigRendererFactory;

    return[
        'database.host' => 'localhost',
        'database.username' => 'root',
        'database.password' => '',
        'database.name' => 'site',
        RendererInterface::class => \DI\factory(TwigRendererFactory::class),
        \Framework\Router::class => \DI\object(),
        'views.path' => dirname(__DIR__) . '\views',
        'twig.extensions' => [
            \DI\get(\Framework\Route\RouteTwigExtenxion::class)
        ]
    ];