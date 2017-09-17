<?php

namespace Framework\Twig;

use Framework\Session\FlashService;

class FlashExtension extends \Twig_Extension
{


    /**
     * @var FlashService
     */
    private $flash;

    public function __construct(FlashService $flash)
    {
        $this->flash = $flash;
    }

    /**
     * @param string $type
     */
    public function getFunctions(): array
    {

        return [
            new \Twig_SimpleFunction('flash', [$this, 'getflash'])
        ];
    }

    public function getflash($type): ?string
    {
        return $this->flash->get($type);
    }
}
