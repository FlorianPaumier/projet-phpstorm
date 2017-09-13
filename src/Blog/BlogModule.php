<?php
    namespace App\Blog;

    use Framework\Router;
    use Psr\Http\Message\ServerRequestInterface;

    class BlogModule{

<<<<<<< HEAD
        /**
         * BlogModule constructor.
         * @param Router $router
         */
=======
>>>>>>> 81642e95df2c4b241c97cb0008030541dc97f9ba
        public function __construct(Router $router)
        {
            $router->get('/blog', [$this, 'index'], 'blog.index');
            $router->get('/blog/{slug:[a-z\-]+}', [$this, 'show'], 'blog.show');
        }

<<<<<<< HEAD
        /**
         * Fonction de gestion de l'index
         * @param ServerRequestInterface $request
         * @return string
         */
=======
>>>>>>> 81642e95df2c4b241c97cb0008030541dc97f9ba
        public function index(ServerRequestInterface $request) : string{
            return '<h1>Bienvenue sur le Blog</h1>';
        }

<<<<<<< HEAD
        /**
         * Fonction de gestion du show des articles
         * @param ServerRequestInterface $request
         * @return string
         */
=======
>>>>>>> 81642e95df2c4b241c97cb0008030541dc97f9ba
        public function show(ServerRequestInterface $request): string{

            return '<h1>Bienvenue sur l\'article '. $request->getAttribute('slug').'</h1>';
        }
    }

