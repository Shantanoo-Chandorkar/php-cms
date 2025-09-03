<?php

namespace Widget_Corp_Oops_Tests\Auth;

use Widget_Corp_Oops_Tests\Database\DatabaseTestCase;
use Widget_Corp_Oops_Helper\DBConnection;
use Widget_Corp_Oops_Admin\Controllers\AuthController;
use Widget_Corp_Oops_Admin\Models\User;

class LoginTest extends DatabaseTestCase
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
     * Test successful user login
     */
    public function testLoginUserSuccess(): void
    {
        $this->controller->handleRegisterUser('testuser', 'Password1!');

        $result = $this->controller->handleLoginUser('testuser', 'Password1!');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertEquals('Login successful!', $result['message']);
    }

    /**
     * Test login with incorrect password
     */
    public function testLoginUserIncorrectPassword(): void
    {
        $this->controller->handleRegisterUser('testuser', 'Password1!');

        $result = $this->controller->handleLoginUser('testuser', 'WrongPassword');

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid username or password.', $result['message']);
    }
}
