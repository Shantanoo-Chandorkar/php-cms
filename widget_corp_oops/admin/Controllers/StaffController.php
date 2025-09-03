<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Admin\Services\SessionService;
use Widget_Corp_Oops_Admin\Models\User;

class StaffController
{
    private HeaderServices $headerServices;
    private NavigationServices $navigationServices;
    private User $userModel;
    private SessionService $sessionService;

    public function __construct(
        HeaderServices $header,
        NavigationServices $navigation
    ) {
        $this->headerServices     = $header;
        $this->navigationServices = $navigation;
        $this->userModel = new User();
        $this->sessionService = new SessionService();
    }

    public function index(): void
    {
        $users     = $this->userModel->getAllUsers();
        $userParam = isset($_GET['user']) ? intval($_GET['user']) : null;

        // Render the view
        include_once __DIR__ . '/../Views/templates/staff.php';
    }
}
