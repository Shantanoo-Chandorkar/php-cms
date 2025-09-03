<?php

namespace Widget_Corp_Oops_Admin\Services;

use Widget_Corp_Oops_Admin\Services\SessionService;

class RedirectService
{
    public function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    private function isUserLoggedIn(SessionService $sessionService): bool
    {
        if (null === $sessionService->get('username')) {
            return false;
        }
        return true;
    }

    public function redirectGueststoLogin(SessionService $sessionService): void
    {
        if (!$this->isUserLoggedIn($sessionService)) {
            $this->redirect('../auth/login.php');
        }
    }
}
