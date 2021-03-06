<?php

    namespace Tests\Framework;

    use Framework\Renderer\PHPRenderer;
    use PHPUnit\Framework\TestCase;

    class RendererTest extends TestCase{

        private $renderer;

        public function setUp()
        {
            $this->renderer = new PHPRenderer();
            $this->renderer->addPath(__DIR__.'/views');
        }

        public function testRenderTheRightPast(){
            $this->renderer->addPath('blog', __DIR__. '\views');
            $content = $this->renderer->render('@blog/demo');
            $this->assertEquals('Salut', $content);
        }

        public function testTheRenderTheDefaultPath(){
            $this->renderer->addPath( __DIR__. '\views');
            $content = $this->renderer->render('demo');
            $this->assertEquals('Salut', $content);
        }

        public function testTheRenderWithParams(){
            $content = $this->renderer->render('demoparams', ['nom' => 'Marc']);
            $this->assertEquals('Salut Marc', $content);
        }

        public  function testGlobalParams(){
            $this->renderer->addGlobal('nom', 'Marc');
            $content = $this->renderer->render('demoparams');
            $this->assertEquals('Salut Marc', $content);
        }
    }