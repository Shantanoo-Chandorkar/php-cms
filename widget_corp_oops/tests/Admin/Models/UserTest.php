<?php

namespace Widget_Corp_Oops_Tests\Admin\Models;

use Widget_Corp_Oops_Admin\Models\User;
use Widget_Corp_Oops_Tests\Database\DatabaseTestCase;
use Widget_Corp_Oops_Helper\DBConnection;

class UserTest extends DatabaseTestCase
{
    private function makeUser(): User
    {
        $db = new DBConnection('widget_corp_test', $this->conn);
        return new User($db);
    }

    public function testRegisterUserSuccess(): void
    {
        $user = $this->makeUser();
        $result = $user->registerUser('test_user', 'password123');

        $this->assertTrue($result['success']);
        $this->assertSame('Registration successful!', $result['message']);

        $fetched = $user->getUserByUserName('test_user');
        $this->assertNotNull($fetched);
        $this->assertSame('test_user', $fetched['username']);
    }

    public function testRegisterUserFailsIfEmpty(): void
    {
        $user = $this->makeUser();
        $result = $user->registerUser('', '');

        $this->assertFalse($result['success']);
        $this->assertSame('Username or password cannot be empty.', $result['message']);
    }

    public function testRegisterUserFailsIfDuplicate(): void
    {
        $user = $this->makeUser();
        $user->registerUser('dupe_user', 'abc123');
        $result = $user->registerUser('dupe_user', 'xyz456');

        $this->assertFalse($result['success']);
        $this->assertSame('Username already taken', $result['message']);
    }

    public function testLoginUserSuccess(): void
    {
        $user = $this->makeUser();
        $user->registerUser('login_test', 'secret');

        $result = $user->loginUser('login_test', 'secret');

        $this->assertTrue($result['success']);
        $this->assertSame('Login successful!', $result['message']);
        $this->assertEquals('login_test', $_SESSION['username']);
    }

    public function testLoginUserFailsWithWrongPassword(): void
    {
        $user = $this->makeUser();
        $user->registerUser('wrong_pw_test', 'rightpass');

        $result = $user->loginUser('wrong_pw_test', 'wrongpass');

        $this->assertFalse($result['success']);
        $this->assertSame('Invalid username or password.', $result['message']);
    }

    public function testCreateNewUserAndFetchById(): void
    {
        $user = $this->makeUser();
        $id = $user->createNewUser('custom_user', password_hash('123', PASSWORD_DEFAULT), 'admin');

        $this->assertIsNumeric($id);

        $fetched = $user->getUserById((int) $id);
        $this->assertSame('custom_user', $fetched['username']);
        $this->assertSame('admin', $fetched['role']);
    }

    public function testGetAllUsers(): void
    {
        $user = $this->makeUser();
        $user->createNewUser('alpha', password_hash('123', PASSWORD_DEFAULT), 'editor');
        $user->createNewUser('beta', password_hash('456', PASSWORD_DEFAULT), 'viewer');

        $all = $user->getAllUsers();

        $this->assertIsArray($all);
        $this->assertGreaterThanOrEqual(2, count($all));
        $this->assertInstanceOf(User::class, $all[0]);
    }

    public function testUpdateUserUsername(): void
    {
        $user = $this->makeUser();
        $id = $user->createNewUser('old_name', password_hash('123', PASSWORD_DEFAULT), 'viewer');

        $result = $user->updateUser((int) $id, username: 'new_name');

        $this->assertTrue($result);

        $fetched = $user->getUserById((int) $id);
        $this->assertSame('new_name', $fetched['username']);
    }

    public function testUpdateUserFailsIfNoFields(): void
    {
        $user = $this->makeUser();
        $id = $user->createNewUser('some_user', password_hash('123', PASSWORD_DEFAULT), 'viewer');

        $result = $user->updateUser((int) $id);

        $this->assertFalse($result);
    }

    public function testDeleteUserById(): void
    {
        $user = $this->makeUser();
        $id = $user->createNewUser('delete_me', password_hash('123', PASSWORD_DEFAULT), 'viewer');

        $result = $user->deleteUserById((string) $id);

        $this->assertTrue($result);

        $fetched = $user->getUserById((int) $id);
        $this->assertNull($fetched);
    }
}
