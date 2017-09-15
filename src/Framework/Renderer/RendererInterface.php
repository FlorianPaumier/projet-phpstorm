<?php
/**
 * Created by PhpStorm.
 * User: Ordi_Florian
 * Date: 14/09/2017
 * Time: 14:50
 */

namespace Framework\Renderer;

interface RendererInterface
{
    /**
     * @param string $namespace
     * @param null|string $path
     */
    public function addPath(string $namespace, ?string $path = null): void;

    public function render(string $view, array $params = []): string;

    public function addGlobal(string $key, $value): void;
}
