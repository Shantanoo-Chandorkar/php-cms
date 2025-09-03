<?php

namespace Widget_Corp_Oops_Admin\Services;

class HeaderServices
{
    public function getHeader(string $stylesheetName, SessionService $sessionService = null): void
    {
        $baseUrl = $this->getSiteURL();

        include_once __DIR__ . '/../Views/partials/header.php';
    }

    private function getSiteURL(): string
    {
        $protocol = ( ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ) ? 'https' : 'http';
        $host     = $_SERVER['HTTP_HOST'];

        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        $scriptPath = str_replace('/admin', '', $scriptPath); // remove '/admin'
        $scriptPath = rtrim($scriptPath, '/\\');

        return $protocol . '://' . $host . $scriptPath . '/';
    }
}
