<?php

namespace Widget_Corp_Oops_Frontend\Services;

class FrontendHeaderServices
{
    public function getHeader(string $stylesheetName): void
    {
        $baseUrl = $this->getSiteURL();

        require_once  __DIR__ . '/../Views/partials/header.php';
    }

    private function getSiteURL(): string
    {
        $protocol   = ( ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ) ? 'https' : 'http';
        $host       = $_SERVER['HTTP_HOST'];
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        $scriptPath = rtrim(str_replace('/admin', '', $scriptPath), '/\\');
        return $protocol . '://' . $host . $scriptPath . '/';
    }
}
