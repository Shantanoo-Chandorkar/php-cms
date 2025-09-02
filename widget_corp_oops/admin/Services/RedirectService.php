<?php

namespace Widget_Corp_Oops_Admin\Services;

class RedirectService
{
    public function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
