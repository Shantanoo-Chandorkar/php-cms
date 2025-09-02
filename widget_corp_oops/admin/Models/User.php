<?php

namespace Widget_Corps_Oops_Admin\Models;

class User
{
    public int $id;
    public string $username;
    public string $role;

    public function __construct(int $id, string $username, string $role)
    {
        $this->id       = $id;
        $this->username = $username;
        $this->role     = $role;
    }
}
