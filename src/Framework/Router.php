<?php
    namespace Framework;

    use Framework\Route\Route;
    use Psr\Http\Message\ServerRequestInterface;
    use Zend\Expressive\Router\FastRouteRouter;
    use Zend\Expressive\Router\Route as ZenRoute;

    /**
     * Class Router
     * @package Framework
     * Register and match route
     */
class Router
{

    /**
         * @var FastRouteRouter
         */
    private $router;

    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
         * @param string $path
         * @param string | callable $callable
         * @param string $name
         */
    public function get(string $path, $callable, string $name)
    {
        $this->router->addRoute(new ZenRoute($path, $callable, ['GET'], $name));
    }
    /**
         *
         * @param ServerRequestInterface $request
         * Return Route|null
         */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);

        if ($result->isSuccess()) {
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
        }

        return null;
    }

    /**
         * On génère une url selon un patern
         * @param string $name
         * @param array $Params
         * @return null|string
         */
    public function generateUri(string $name, array $Params = [], array $queryArgs = []) : ?string
    {
        $uri =$this->router->generateUri($name, $Params);
        if (!empty($queryArgs)) {
            return $uri . '?' . http_build_query($queryArgs);
        }
        return $uri;
    }
}
