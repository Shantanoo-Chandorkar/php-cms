<?php

namespace Widget_Corp_Oops_Tests\Auth;

use Widget_Corp_Oops_Tests\Database\DatabaseTestCase;
use Widget_Corp_Oops_Helper\DBConnection;
use Widget_Corp_Oops_Admin\Controllers\AuthController;
use Widget_Corp_Oops_Admin\Models\User;

class RegisterTest extends DatabaseTestCase
{
    private AuthController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Inject the shared test connection into User, then into AuthController
        $dbConnection = new DBConnection($this->conn);
        $userModel    = new User($dbConnection);

        // AuthController now gets a prepared User instance
        $this->controller = new AuthController($userModel);
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

        $result = $this->controller->handleRegisterUser($username, $password);

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
        $result = $this->controller->handleRegisterUser('', 'Password1!');

        $this->assertFalse($result['success']);
        $this->assertEquals('Please check user name or password again.', $result['message']);
    }

    /**
     * Test registration with a long username
     * Ensures that the method correctly rejects usernames that exceeds 30 characters length
     * and returns the appropriate error message.
     */
    public function testRegisterUserLongUsername()
    {
        $result = $this->controller->handleRegisterUser('THisissomethingthatistoolongfortheintendedusername@123', 'Password1!');

        $this->assertFalse($result['success']);
        $this->assertEquals('Please check user name or password again.', $result['message']);
    }
}
