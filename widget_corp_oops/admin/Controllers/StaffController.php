<?php
namespace Widget_Corps_Oops_Admin\Controllers;

use Widget_Corps_Oops_Admin\Services\HeaderServices;
use Widget_Corps_Oops_Admin\Services\NavigationServices;
use Widget_Corps_Oops_Helper\DBConnection;

class StaffController
{
    private DBConnection $_db;
    private HeaderServices $_header;
    private NavigationServices $_navigation;

    public function __construct(DBConnection $db, HeaderServices $header, NavigationServices $navigation)
    {
        $this->_db = $db;
        $this->_header = $header;
        $this->_navigation = $navigation;
    }

    public function index(): void
    {
        $users = $this->_db->get_all_users();
        $userParam = isset($_GET['user']) ? intval($_GET['user']) : null;

        $header = $this->_header;
        $navigation = $this->_navigation;

        // Render the view
        include __DIR__ . '/../Views/staff.php';
    }
}
