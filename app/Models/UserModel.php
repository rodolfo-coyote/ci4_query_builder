<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

use CodeIgniter\Model;
use \Firebase\JWT\JWT;

class UserModel extends Model
{
    protected $table            = 'users_jwt';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table($this->table);
        $this->session = \Config\Services::session();
    }


    public function getUserByEmail($email)
    {
        $query = $this->builder->select('id')
            ->where('email', $email)
            ->get();
        return $query->getRow();
    }

    public function verifyPassword($email, $password)
    {
        $query = $this->builder->select('password')
            ->where('email', $email)
            ->get();

        $result = $query->getRow();
        $hashedPassword = $result->password;

        return password_verify($password, $hashedPassword);
    }

    public function getUserInfo($email)
    {
        $query = $this->builder->select('id,fullname,password')
            ->where('email', $email)
            ->get();

        $result = $query->getRow();
        $userId = $result->id;
        $userName = $result->fullname;
        // $last_login = $result->last_login;

        $key = getenv('JWT_SECRET');
        $iat = time();
        $exp = $iat + 6000;

        $payload = [
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "email" => $email,
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        $userInfo = new \stdClass();

        $userInfo->id = $userId;
        $userInfo->fullname = $userName;
        $userInfo->token = $token;
        $userInfo->expires_at = $exp;
        //$userInfo->last_login = $last_login;

        return $userInfo;
    }

    public function sessionToRedis($userId, $token, $expires_at, $fullname)
    {

        $userInfo = [
            'id' => $userId,
            'token' => $token,
            'expires_at' => $expires_at,
            'fullname' => $fullname,
        ];

        $this->session->set('user_info', serialize($userInfo));

        return true;
    }

    public function verifyUserIsLogged()
    {
        if (!$this->session->has('user_info')) {
            return false;
        }

        $serializedData = $this->session->get('user_info');

        return $serializedData;
    }

    public function destroySession()
    {
        try {
            $this->session->destroy();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
