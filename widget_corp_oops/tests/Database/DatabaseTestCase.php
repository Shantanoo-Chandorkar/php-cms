<?php

namespace Widget_Corp_Oops_Tests\Database;

use PDO;
use PHPUnit\Framework\TestCase;
use Widget_Corp_Oops_Helper\DBConnection;

abstract class DatabaseTestCase extends TestCase
{
    protected PDO $conn;

    protected function setUp(): void
    {
        parent::setUp();
        $db = new DBConnection('widget_corp_test');
        $this->conn = $db->conn;
        $this->conn->beginTransaction(); // start transaction
    }

    protected function tearDown(): void
    {
        $this->conn->rollBack(); // rollback to keep DB clean
        parent::tearDown();
    }
}
