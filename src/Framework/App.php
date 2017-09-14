<?php
    namespace Framework;

    use GuzzleHttp\Psr7\Response;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;

class App
{
    /**
         * @var Router
         */
    private $router;

    /**
         * @var liste des modules
         */
    private $modules = [];

    /**
         * App constructor. On charge les modules de l'application
         * @param string[] tableau des modules à charger
         */
    public function __construct(array $modules = [], array $dependencies = [])
    {
        $this->router = new Router();

        if (array_keys_exists('renderer', $dependencies)) {
            $dependencies['renderer']->addGlobal('router', $this->router);
        }
        foreach ($modules as $module) {
            $this->modules[] = new $module($this->router, $dependencies['renderer']);
        }
    }

    /**
         * Fonction de de lancement de l'application
         * @param ServerRequestInterface $request
         * @return ResponseInterface
         * @throws \Exception
         */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if (!empty($uri) && $uri[-1] === "/") {
            return $response = (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }

        $route = $this->router->match($request);

        /**
             * si la route est null on en recréer une avec une Erreure
             */
        if (is_null($route)) {
            return new Response(404, [], '<h1>Erreur 404</h1>');
        }

        $params = $route->getParams();

        /**
             * On récupère les arguments de la requête
             */
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);

        $response = call_user_func_array($route->getCallback(), [$request]);

        /**
             * On test la réponse puis on retourne ou on crée une exception
             */
        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new \Exception('Erreur reponse != string || instance de Responseinterface');
        }
    }
}
