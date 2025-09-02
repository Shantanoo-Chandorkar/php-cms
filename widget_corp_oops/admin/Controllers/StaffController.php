<?php

namespace Widget_Corp_Oops_Admin\Controllers;

use Widget_Corp_Oops_Admin\Services\HeaderServices;
use Widget_Corp_Oops_Admin\Services\NavigationServices;
use Widget_Corp_Oops_Helper\DBConnection;

class StaffController
{
    private DBConnection $db;
    private HeaderServices $headerServices;
    private NavigationServices $navigationServices;

    public function __construct(DBConnection $db, HeaderServices $header, NavigationServices $navigation)
    {
        $this->db         = $db;
        $this->headerServices     = $header;
        $this->navigationServices = $navigation;
    }

    public function index(): void
    {
        $users     = $this->db->get_all_users();
        $userParam = isset($_GET['user']) ? intval($_GET['user']) : null;

        // Render the view
        include __DIR__ . '/../Views/staff.php';
    }
}
