<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Libs\Auth;
use ApiSistemas\Models\UserModel;

class Login extends Auth
{
    public function __construct()
    {
        parent::__construct();
    }

    public function auth()
    {
        $this->exists(['user', 'password']);
        $user = UserModel::login($this->data['password'], $this->data['user']);
        if ($user != null) {
            $this->initialize($user);
        }
        $this->response(['ok' => false, "msj" => "Datos incorrectos"]);
    }
}
