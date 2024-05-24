<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Model;
use PDO;
use PDOException;

class UserModel extends Model
{
    protected $id;
    protected $user;
    protected $password;
    protected $token;
    public function __construct()
    {
        parent::__construct();
    }

    public function updateToken()
    {
        try {
            $query = $this->prepare("UPDATE usuario SET token = :token WHERE idUsuario =:id");
            return $query->execute([":token" => $this->token, 'id' => $this->id]);
        } catch (PDOException $e) {
            error_log('UserModel::UpdateToken() -> ' . $e->getMessage());
            echo $e->getMessage();
            return false;
        }
    }

    public function existsToken($token)
    {
        try {
            $query = $this->prepare("SELECT token FROM  usuario WHERE token = :token");
            $query->execute(['token' => $token]);
            return ($query->rowCount() > 0) ? true : false;
        } catch (PDOException $e) {
            error_log('UserModel::existsToken() ->' . $e->getMessage());
            return false;
        }
    }

    public static function login($password, $user)
    {
        try {
            $pdo = new Model();
            $query = $pdo->prepare("SELECT * FROM usuario WHERE usuario = :usuario AND password = :password");
            $query->execute(['usuario' => $user, 'password' => $password]);
            if ($query->rowCount() == 1) {
                return $query->fetch(PDO::FETCH_ASSOC);
            }
            return null;
        } catch (PDOException $e) {
            error_log('UserNodel::login()->' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}
