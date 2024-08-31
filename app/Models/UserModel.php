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


    protected function getBuilder()
    {
        return $this->db->table($this->table);
    }

    public function getUserByEmail($email)
    {
        $builder = $this->getBuilder();
        $query = $builder->select('id')
            ->where('email', $email)
            ->get();
        return $query->getRow();
    }

    public function verifyPassword($email, $password)
    {
        $builder = $this->getBuilder();
        $query = $builder->select('password')
            ->where('email', $email)
            ->get();

        $result = $query->getRow();
        $hashedPassword = $result->password;

        return password_verify($password, $hashedPassword);
    }

    public function getUserJwt($email)
    {
        $key = getenv('JWT_SECRET');
        $iat = time();
        $exp = $iat + 60;

        $payload = [
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "email" => $email,
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $token;
    }

    public function tokenToRedis($token)
    {
        $redis = \Config\Services::redis();

        if (!$redis->hexists('stored_token2', 'token')) {
            // Almacenar datos en el hash
            $redis->hset('stored_token2', 'token', $token);
            $redis->hset('stored_token2', 'expires_at', time() + 60);
        }

        return true;
    }
}
