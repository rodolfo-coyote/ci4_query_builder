<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RedisAuthModel;
use CodeIgniter\API\ResponseTrait;

class RedisAuth extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $RedisAuthModel = new RedisAuthModel();


        if (!$RedisAuthModel->searchToken()) {
            return $this->respond(['status' => 'token is not stored'], 401);
        }

        return $this->respond(['status' => 'Token is already stored'], 200);
    }
}
