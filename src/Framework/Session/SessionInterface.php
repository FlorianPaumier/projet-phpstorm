<?php

namespace Framework\Session;

interface SessionInterface
{

    /**
     * recupération d'une info de session
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Ajout d'information sur la session
     * @param string $key
     * @param $default
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * suppression d'une info de session
     * @param string $key
     * @return void
     */
    public function delete(string $key): void;
}
