<?php

    namespace Framework\Route;

    use Framework\Router;

class RouteTwigExtenxion extends \Twig_Extension
{

    /**
         * @var Router
         */
    private $router;

    public function __construct(Router $router)
    {

        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('path', [$this, 'pathFor'])
        ];
    }


    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }
}
