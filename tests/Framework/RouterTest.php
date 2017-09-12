<?php
    namespace tests\Framework;

    use Framework\Router;
    use GuzzleHttp\Psr7\ServerRequest;
    use PHPUnit\Framework\TestCase;

    class RouterTest extends TestCase{

        /**
         * @var instace du router
         */
        private $router;


        public function setUp(){
            $this->router = new Router();
        }

        public function testGetMethod(){
            $request = new ServerRequest('GET', '/blog');
            $this->router->get('/blog', function(){ return 'hello'; } , 'blog');
            $route = $this->router->match($request);
            $this->assertEquals('blog', $route->getName());
            $this->assertEquals('hello', call_user_func_array($route->getCallback(), [$request]));
        }

        public function testGetMethodIfUrlDoesNotExist(){
            $request = new ServerRequest('GET', '/blog');
            $this->router->get('/blogaze', function(){ return 'hello'; } , 'blog');
            $route = $this->router->match($request);
            $this->assertEquals(null, $route);
        }

        public function testGetMethodWithParams(){
            $request = new ServerRequest('GET', '/blog/mon-slug-8');
            $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function(){ return 'hello'; } , 'post.show');
            $this->router->get('/blog', function(){ return 'azaea'; } , 'posts');
            $route = $this->router->match($request);
            $this->assertEquals('post.show', $route->getName());
            $this->assertEquals('hello', call_user_func_array($route->getCallback(), [$request]));
            $this->assertEquals(['slug' => 'mon-slug', 'id' => '8'], $route->getParams());

            //test invalid url

            $route = $this->router->match(new ServerRequest('GET', '/blog/mon_slug-8'));
            $this->assertEquals( null, $route);
        }

        public function testGenerateUri(){

            $this->router->get('/blog', function (){ return 'azeza';}, 'posts');
            $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function(){ return 'hello'; } , 'post.show');
            $uri = $this->router->generateUri('post.show', ['slug' => 'mon-article', 'id' => 18]);
            $this->assertEquals('/blog/mon-article-18', $uri);

        }
    }