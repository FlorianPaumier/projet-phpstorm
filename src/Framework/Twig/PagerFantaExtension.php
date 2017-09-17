<?php
    namespace Framework\Twig;

    use Framework\Router;
    use Pagerfanta\Pagerfanta;
    use Pagerfanta\View\DefaultView;
    use Pagerfanta\View\TwitterBootstrap4View;

class PagerFantaExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }

    /**
         * @param Pagerfanta $paginatedResults
         * @param string $route
         * @param array $QueryArgs
         * @return string
         */
    public function paginate(Pagerfanta $paginatedResults, string $route, array $QueryArgs = []): string
    {

        $view = new TwitterBootstrap4View();

        return $view->render($paginatedResults, function ($page) use ($route, $QueryArgs) {
            if ($page > 1) {
                $QueryArgs['p'] = $page;
            }
            return $this->router->generateUri($route, [], $QueryArgs);
        });
    }
}
