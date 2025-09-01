<?php

use PHPUnit\Framework\TestCase;
use Widget_Corps_Oops_Helper\Bootstrap;

class RegisterTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        $bootstrap = new Bootstrap('widget_corp_test'); // test DB
        $this->db = $bootstrap->getDB();

        // Remove the test user if it exists to ensure a clean test environment
        $this->db->conn->prepare('DELETE FROM users WHERE username = :username')
                  ->execute([':username' => 'testuser123']);
    }

    /**
     * Test successful user registration
     * Ensures that a valid username and password are inserted correctly
     * and that the success message is returned.
     */
    public function testRegisterUserSuccess()
    {
        $username = 'testuser123';
        $password = 'Password1!';

        $result = $this->db->register_user($username, $password);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertEquals('Registration successful!', $result['message']);
    }

    /**
     * Test registration with an empty username
     * Ensures that the method correctly rejects empty usernames
     * and returns the appropriate error message.
     */
    public function testRegisterUserEmptyUsername()
    {
        $result = $this->db->register_user('', 'Password1!');

        $this->assertFalse($result['success']);
        $this->assertEquals('Username or password cannot be empty.', $result['message']);
    }
}
