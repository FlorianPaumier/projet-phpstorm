<?php
require '../vendor/autoload.php';

    $app = new \Framework\App();

    $reponse = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

    \Http\Response\send($reponse);