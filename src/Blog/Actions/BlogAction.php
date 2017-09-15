<?php

namespace App\Blog\Actions {

    use Framework\Renderer\RendererInterface;
    use Psr\Http\Message\ServerRequestInterface;

    class BlogAction
    {

        private $renderer;

        public function __construct(RendererInterface $renderer)
        {
            $this->renderer = $renderer;
        }


        public function __invoke(ServerRequestInterface $request)
        {
            $slug = $request->getAttribute('slug');

            if ($slug) {
                return $this->show($slug);
            }
                return $this->index();
        }
        /**
         * Fonction de gestion de l'index
         * @param ServerRequestInterface $request
         * @return string
         */

        public function index() : string
        {
            return $this->renderer->render('@blog/index');
        }


        /**
         * Fonction de gestion du show des articles
         * @param ServerRequestInterface $request
         * @return string
         */

        public function show(string $slug): string
        {
            return $this->renderer->render('@blog/show', [
                'slug' => $slug
            ]);
        }
    }
}
