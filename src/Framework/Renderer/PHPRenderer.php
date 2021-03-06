<?php

    namespace Framework\Renderer;

class PHPRenderer implements RendererInterface
{

    private $Paths = [];
    private $Globals = [];
    const DEFAULT_NAMESPACE = '__MAIN';

    public function __construct(?string $defaultpath = null)
    {
        if (!is_null($defaultpath)) {
            $this->addPath($defaultpath);
        }
    }

    public function addPath(string $namespace, ?string $path = null): void
    {

        if (is_null($path)) {
            $this->Paths[self::DEFAULT_NAMESPACE] = $namespace;
        } else {
            $this->Paths[$namespace] = $path;
        }
    }

    public function render(string $view, array $params = []): string
    {
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNamespace($view).'.twig';
        } else {
            $path = $this->Paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $view . '.php';
        }

        ob_start();
        $renderer = $this;
        extract($this->Globals);
        extract($params);
        require $path;

        return ob_get_clean();
    }

    public function addGlobal(string $key, $value): void
    {
        $this->Globals[$key] = $value;
    }

    private function hasNamespace(string $view): bool
    {
        return $view[0] === '@';
    }

    private function getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') -1);
    }

    private function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamespace($view);
        return str_replace('@'.$namespace, $this->Paths[$namespace], $view);
    }
}
