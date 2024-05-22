<?php

namespace ApiSistemas\Libs;

use ApiSistemas\Libs\Controller;
use ApiSistemas\Models\UserModel;
use Firebase\JWT\JWT;

class Auth extends Controller
{
    private $userId;
    private $token;
    private $key;

    public function __construct()
    {
        parent::__construct();
        $this->key = '12345';
    }

    public function initialize(array $user)
    {
        $this->token = $this->generateToken($user);
        $auth = new UserModel();
        $auth->setId($user['idUsuario']);
        $auth->setToken($this->token);
        $auth->updateToken();
        $user['token'] = $this->token;
        $this->response(["ok" => true, "user" => $user]);
    }

    public function generateToken(array $user)
    {
        $time = time();
        $token = [
            'iat' => $time,
            'exp' => $time * 60 * 60,
            'data' => ['id' => $user['idUsuario'], 'name' => $user['usuario']]
        ];
        return JWT::encode($token, $this->key, 'HS256');
    }
}
