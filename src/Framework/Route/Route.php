<?php
    namespace Framework\Route;

    /**
     * Class Route
     * @package Framework\Route
     * Represented a matched route
     */
class Route
{

    /**
         * @var string
         */
    private $name;
    /**
         * @var callable
         */
    private $callback;
    /**
         * @var array
         */
    private $params;

    /**
         * Initialisation des paramertes de la route
         * Route constructor.
         * @param string $name
         * @param string | callable $callback
         * @param array $params
         */
    public function __construct(string $name, $callback, array  $params)
    {

        $this->name = $name;
        $this->callback = $callback;
        $this->params = $params;
    }

    /**
         * @return string
         */
    public function getName(): string
    {
        return $this->name;
    }

    /**
         * @return callable
         */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
         * Retoure les paramètres de l'url
         * @return string[]
         */
    public function getParams() : array
    {
        return $this->params;
    }
}
