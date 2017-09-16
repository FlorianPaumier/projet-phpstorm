<?php

namespace Framework\Actions;

use Psr\Http\Message\ResponseInterface;

trait RouterAwareAction
{

    /**
     * @param string $path
     * @param array $params
     * @return ResponseInterface
     */
    public function redirect(string $path, array $params = []): ResponseInterface
    {
        $redirectUri = $this->router->generateUri($path, $params);
        return (new \GuzzleHttp\Psr7\Response())
            ->withStatus(301)
            ->withHeader('Location', $redirectUri);
    }
}
