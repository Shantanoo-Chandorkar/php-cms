<?php

namespace Widget_Corps_Oops_Tests\Auth;

use PHPUnit\Framework\TestCase;
use Widget_Corps_Oops_Helper\Bootstrap;

class LoginTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        $bootstrap = new Bootstrap('widget_corp_test'); // test DB
        $this->db  = $bootstrap->getDB();

        // Start a transaction for test isolation
        $this->db->conn->beginTransaction();
    }

    /**
     * Test successful user login
     * Ensures that a valid username and password are authenticated correctly
     * and that the success message is returned.
     */
    public function testLoginUserSuccess()
    {
        // First, register a user to ensure they exist
        $this->db->register_user('testuser', 'Password1!');

        $result = $this->db->login_user('testuser', 'Password1!');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertEquals('Login successful!', $result['message']);
    }

    /**
     * Test login with incorrect password
     * Ensures that the method correctly rejects invalid passwords
     * and returns the appropriate error message.
     */
    public function testLoginUserIncorrectPassword()
    {
        // First, register a user to ensure they exist
        $this->db->register_user('testuser', 'Password1!');
        $result = $this->db->login_user('testuser', 'WrongPassword');
        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid username or password.', $result['message']);
    }

    protected function tearDown(): void
    {
        // Undo changes made during the test
        $this->db->conn->rollBack();
    }
}
