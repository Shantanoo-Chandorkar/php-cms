<?php

namespace Widget_Corp_Oops_Admin\Services;

class SessionService
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set(string $key, $value): void
    {
        $_SESSION[ $key ] = $value;
    }

    public function get(string $key, $default = null)
    {
        return $_SESSION[ $key ] ?? $default;
    }

    public function unset(string $key): void
    {
        unset($_SESSION[ $key ]);
    }

    public function destroy(): void
    {
        $_SESSION = array();
        session_destroy();
    }
}
