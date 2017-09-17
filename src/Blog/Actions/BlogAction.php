<?php

namespace App\Blog\Actions {

    use App\Blog\Table\PostTable;
    use Framework\Actions\RouterAwareAction;
    use Framework\Renderer\RendererInterface;
    use Framework\Router;
    use Psr\Http\Message\ServerRequestInterface;

    class BlogAction
    {

        private $renderer;
        /**
         * @var Router
         */
        private $router;
        /**
         * @var PostTable
         */
        private $postTable;

        use RouterAwareAction;

        public function __construct(RendererInterface $renderer, PostTable $postTable, Router $router)
        {
            $this->renderer = $renderer;
            $this->router = $router;
            $this->postTable = $postTable;
        }


        public function __invoke(ServerRequestInterface $request)
        {


            if ($request->getAttribute('id')) {
                return $this->show($request);
            }
                return $this->index($request);
        }
        /**
         * Fonction de gestion de l'index
         * @param ServerRequestInterface $request
         * @return string
         */

        public function index(ServerRequestInterface $request) : string
        {
            $params = $request->getQueryParams();


            $posts = $this->postTable->findPaginated(12, $params['p'] ?? 1);

            return $this->renderer->render('@blog/index', compact('posts'));
        }


        /**
         * Fonction de gestion du show des articles
         * @param ServerRequestInterface $request
         * @return string
         */

        /**
         * @param \GuzzleHttp\Psr7\Request $request
         * @return \GuzzleHttp\Psr7\MessageTrait|string|static
         */
        public function show(ServerRequestInterface $request)
        {
            $slug = $request->getAttribute('slug');

            $post = $this->postTable->find($request->getAttribute('id'));
            if ($post->slug !== $slug) {
                return $this->redirect('blog.show', [
                    'slug' => $post->slug,
                    'id' => $post->id
                ]);
            }
            return $this->renderer->render('@blog/show', [
                'post' => $post
            ]);
        }
    }
}
