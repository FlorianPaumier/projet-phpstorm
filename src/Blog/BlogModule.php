<?php
    namespace App\Blog;

    use Framework\Router;
    use Psr\Http\Message\ServerRequestInterface;

    class BlogModule{

        /**
         * BlogModule constructor.
         * @param Router $router
         */
        public function __construct(Router $router)
        {
            $router->get('/blog', [$this, 'index'], 'blog.index');
            $router->get('/blog/{slug:[a-z\-]+}', [$this, 'show'], 'blog.show');
        }

        /**
         * Fonction de gestion de l'index
         * @param ServerRequestInterface $request
         * @return string
         */
        public function index(ServerRequestInterface $request) : string{
            return '<h1>Bienvenue sur le Blog</h1>';
        }

        /**
         * Fonction de gestion du show des articles
         * @param ServerRequestInterface $request
         * @return string
         */
        public function show(ServerRequestInterface $request): string{

            return '<h1>Bienvenue sur l\'article '. $request->getAttribute('slug').'</h1>';
        }
    }

