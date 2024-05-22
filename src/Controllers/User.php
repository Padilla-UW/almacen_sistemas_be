<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Libs\Controller;

class User extends Controller
{
    protected $id;
    protected $user;
    protected $password;

    public function __construct()
    {
        parent::__construct();
    }
}
