<?php

namespace Framework\Session;

class PHPSession implements SessionInterface
{

    /**
     * recupération d'une info de session
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->SessionStarted();
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * Ajout d'information sur la session
     * @param string $key
     * @param $default
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->SessionStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * suppression d'une info de session
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        $this->SessionStarted();
        unset($_SESSION[$key]);
    }

    /**
     * Fonction pour assurer de démarrer une session
     */
    private function SessionStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
