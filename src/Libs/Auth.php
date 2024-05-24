<?php

namespace ApiSistemas\Libs;

use ApiSistemas\Libs\Controller;
use ApiSistemas\Models\UserModel;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;

class Auth extends Controller
{
    private $userId;
    private $token;
    private $key;

    public function __construct()
    {
        parent::__construct();
        $this->key = '12345';
        $this->validateToken();
    }

    public function validateToken()
    {
        if ($_GET['url'] != 'login') {
            $headers = apache_request_headers();
            if (!isset($headers['Authorization'])) {
                $this->response(['ok' => false, "msj" => 'Token requerido']);
            }
            $token = str_replace("Bearer ", "", $headers['Authorization']);
            $this->verifyToken($token);
        }
    }

    public function verifyToken($token)
    {
        try {
            $decode = JWT::decode($token, new Key($this->key, 'HS256'));
            $auth = new UserModel();
            if ($auth->existsToken($token)) {
                $this->userId = $decode->data->id;
                return true;
            }

            $this->response(['ok' => false, "msj" => 'Token no existe']);
        } catch (Exception $e) {
            error_log('verifyToken::() -> ' . $e->getMessage());
            $this->response(['ok' => false, "msj" => 'Token invalido']);
        }
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
