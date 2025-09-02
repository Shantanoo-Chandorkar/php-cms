<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Admin\Models\User;

class AuthController
{
    private User $userModel;

    public function __construct(?User $userModel = null)
    {
        $this->userModel = $userModel ?? new User();
    }

    public function handleLoginUser(string $username, string $password): array
    {
        return $this->userModel->loginUser($username, $password);
    }

    public function handleRegisterUser(string $username, string $password): array
    {
        return $this->userModel->registerUser($username, $password);
    }
}
