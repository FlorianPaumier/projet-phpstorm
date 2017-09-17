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
    public function get(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZenRoute($path, $callable, ['GET'], $name));
    }

    public function post(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZenRoute($path, $callable, ['POST'], $name));
    }

    public function delete(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZenRoute($path, $callable, ['DELETE'], $name));
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

    public function crud(string $prefixpath, $callable, string $prefixname)
    {

        $this->get("$prefixpath", $callable, $prefixname.'.index');
        $this->get("$prefixpath/new", $callable, $prefixname.'.create');
        $this->post("$prefixpath/new", $callable);
        $this->get("$prefixpath/{id:\d+}", $callable, $prefixname.'.edit');
        $this->post("$prefixpath/{id:\d+}", $callable);
        $this->delete("$prefixpath/{id:\d+}", $callable, $prefixname.'.delete');
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
