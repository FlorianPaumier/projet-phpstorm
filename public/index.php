<?php

use App\Blog\BlogModule;
use App\Admin\AdminModule;
use GuzzleHttp\Psr7\ServerRequest;

require dirname(__DIR__) . '/vendor/autoload.php';

$modules = [

    AdminModule::class,
    BlogModule::class
];
$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__). '/config/config.php');

foreach ($modules as $module) {
    if ($module::DEFENITIONS) {
        $builder->addDefinitions($module::DEFENITIONS);
    }
}

$builder->addDefinitions(dirname(__DIR__). '/config.php');

$container = $builder->build();


    $app = new \Framework\App($container, $modules);

if (php_sapi_name() !== 'cli') {
    $response = $app->run(ServerRequest::fromGlobals());

    \Http\Response\send($response);
}
