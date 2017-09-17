<?php
    namespace App\Blog;

    use App\Blog\Actions\AdminBlogAction;
    use App\Blog\Actions\BlogAction;
    use Framework\Module;
    use Framework\Renderer\RendererInterface;
    use Framework\Router;
    use Psr\Container\ContainerInterface;

class BlogModule extends Module
{

    const DEFENITIONS = __DIR__ . '/config.php';

    const MIGRATIONS = __DIR__ . '/db/migrations';

    const SEEDS = __DIR__ . '/db/seeds';

    private $renderer;

    /**
         * BlogModule constructor.
         * @param Router $router
         */

    public function __construct(ContainerInterface $container)
    {
        $router =  $container->get(Router::class);

        $container->get(RendererInterface::class)->addPath('blog', __DIR__ . '/views');
        $router->get($container->get('blog.prefix'), BlogAction::class, 'blog.index');
        $router->get($container->get('blog.prefix').'/{slug:[a-z\-0-9]+}-{id: [0-9]+}', BlogAction::class, 'blog.show');

        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->crud("$prefix/posts", AdminBlogAction::class, 'blog.admin');
        };
    }
}
