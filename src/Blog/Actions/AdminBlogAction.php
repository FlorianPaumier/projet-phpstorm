<?php

namespace App\Blog\Actions {

    use App\Blog\Table\PostTable;
    use Framework\Actions\RouterAwareAction;
    use Framework\Renderer\RendererInterface;
    use Framework\Router;
    use Framework\Session\FlashService;
    use Framework\Session\SessionInterface;
    use Framework\Validator;
    use Psr\Http\Message\ServerRequestInterface;

    class AdminBlogAction
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
        /**
         * @var SessionInterface
         */
        private $session;
        /**
         * @var FlashService
         */
        private $flash;

        use RouterAwareAction;

        public function __construct(
            RendererInterface $renderer,
            PostTable $postTable,
            Router $router,
            FlashService $flash
        ) {
        
            $this->renderer = $renderer;
            $this->router = $router;
            $this->postTable = $postTable;
            $this->flash = $flash;
        }


        public function __invoke(ServerRequestInterface $request)
        {

            if ($request->getMethod() === 'DELETE') {
                return $this->delete($request);
            };
            if (substr((string)$request->getUri(), -3) === 'new') {
                return $this->create($request);
            };
            if ($request->getAttribute('id')) {
                return $this->edit($request);
            };
            return $this->index($request);
        }
        /**
         * Fonction de gestion de l'index
         * @param ServerRequestInterface $request
         * @return string
         */

        public function index(ServerRequestInterface $request)
        {
            $params = $request->getQueryParams();


            $items = $this->postTable->findPaginated(12, $params['p'] ?? 1);

            return $this->renderer->render('@blog/admin/index', compact('items', 'session'));
        }

        public function edit(ServerRequestInterface $request)
        {

            $item = $this->postTable->find($request->getAttribute('id'));

            if ($request->getMethod() === 'POST') {
                $params = $this->getParams($request);

                $validator = $this->getValidator($request);

                if ($validator->isValid()) {
                    $this->postTable->update($item->id, $params);
                    $this->flash->success('L\'article a bien été modifié');
                    return $this->redirect('blog.admin.index');
                }
                $errors = $validator->getErrors();

                $params['id'] = $item->id;
                $item = $params;
            }
            return $this->renderer->render('@blog/admin/edit', compact('item', 'errors'));
        }

        public function create(ServerRequestInterface $request)
        {

            if ($request->getMethod() === 'POST') {
                $params = $this->getParams($request);

                $validator = $this->getValidator($request);

                if ($validator->isValid()) {
                    $this->postTable->insert($params);
                    $this->flash->success('L\'article a bien été créé');
                    return $this->redirect('blog.admin.index');
                }
                $errors = $validator->getErrors();
                var_dump($errors);
                die();
                $item = $params;
            }
            return $this->renderer->render('@blog/admin/create', compact('item', 'errors'));
        }

        public function delete(ServerRequestInterface $request)
        {

            $query = $this->postTable->delete($request->getAttribute('id'));

            return $this->redirect('blog.admin.index');
        }
        private function getParams(ServerRequestInterface $request)
        {
            $params = array_filter($request->getParsedBody(), function ($key) {
                return in_array($key, ['name', 'slug', 'content', 'created_at']);
            }, ARRAY_FILTER_USE_KEY);


            return array_merge($params, [
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        /**
         * @param ServerRequestInterface $request
         * @return Validator
         */
        private function getValidator(ServerRequestInterface $request)
        {
            return (new Validator($request->getParsedBody()))
                ->required('content', 'name', 'slug')
                ->length('content', 10)
                ->length('name', 2, 250)
                ->length('slug', 2, 50)
                ->slug('slug');
        }
    }
}
