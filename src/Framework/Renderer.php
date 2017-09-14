<?php

    namespace Framework;

class Renderer
{

    private $Paths = [];
    private $Globals = [];
    const default_namespace = '__MAIN';

    public function addPath(string $namespace, ?string $path = null): void
    {

        if (is_null($path)) {
            $this->Paths[self::default_namespace] = $namespace;
        } else {
            $this->Paths[$namespace] = $path;
        }
    }

    public function render(string $view, array $params = []): string
    {
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNamespace($view).'.php';
        } else {
            $path = $this->Paths[self::default_namespace] . DIRECTORY_SEPARATOR . $view . '.php';
        }

        ob_start();
        $renderer = $this;
        extract($this->Globals);
        extract($params);
        require $path;

        return ob_get_clean();
    }

    public function addGlobal(string $key, $valule): void
    {
        $this->Globals[$key] = $valule;
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
