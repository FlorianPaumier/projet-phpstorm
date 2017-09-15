<?php
    namespace App\Blog;

    use Framework\Renderer\RendererInterface;
    use Framework\Router;
    use Psr\Http\Message\ServerRequestInterface;

class BlogModule
{


    private $renderer;

    /**
         * BlogModule constructor.
         * @param Router $router
         */

    public function __construct(Router $router, RendererInterface $renderer)
    {
        $this->renderer = $renderer;
        $this->renderer->addPath('blog', __DIR__ . '/views');
        $router->get('/blog', [$this, 'index'], 'blog.index');
        $router->get('/blog/{slug:[a-z\-]+}', [$this, 'show'], 'blog.show');
    }


    /**
         * Fonction de gestion de l'index
         * @param ServerRequestInterface $request
         * @return string
         */

    public function index(ServerRequestInterface $request) : string
    {
        return $this->renderer->render('@blog/index');
    }


    /**
         * Fonction de gestion du show des articles
         * @param ServerRequestInterface $request
         * @return string
         */

    public function show(ServerRequestInterface $request): string
    {
        return $this->renderer->render('@blog/show', [
            'slug' => $request->getAttribute('slug')
        ]);
    }
}
