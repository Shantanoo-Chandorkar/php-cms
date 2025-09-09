<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Admin\Models\User;
use Widget_Corp_Oops_Admin\Services\ValidationServices;
use Widget_Corp_Oops_Admin\Services\SessionService;
use Widget_Corp_Oops_Admin\Services\RedirectService;

class AuthController
{
    private User $userModel;
    private ValidationServices $validationServices;
    private SessionService $sessionService;
    private RedirectService $redirectService;

    public function __construct(?User $userModel = null)
    {
        $this->userModel = $userModel ?? new User();
        $this->validationServices = new ValidationServices();
        $this->sessionService = new SessionService();
        $this->redirectService = new RedirectService();
    }

    public function handleLoginUser(string $username, string $password): array
    {
        return $this->userModel->loginUser($username, $password);
    }

    public function handleRegisterUser(string $username, string $password): array
    {
        $data = ['username' => $username, 'password' => $password];

        $errors          = array();
        $required_fields = array( 'username', 'password' );
        $errors          = $this->validationServices->validateRequiredFields($required_fields, $data);

        $field_lengths = array( 'username' => 50 );
        $errors        = array_merge($errors, $this->validationServices->validateMaxLengths($field_lengths, $data));

        if (! empty($errors)) {
            $this->sessionService->set('errors', $errors);
            return array(
                'success' => false,
                'message' => 'Please check user name or password again.'
            );
        }

        return $this->userModel->registerUser($username, $password);
    }
}
