<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

use CodeIgniter\Model;

class RedisAuthModel extends Model
{
    public function searchToken()
    {
        $redis = \Config\Services::redis();

        $token = $redis->hget('stored_token2', 'token');
        //$expiresAt = $redis->hget('stored_token', 'expires_at');

        return $token;
    }
}
