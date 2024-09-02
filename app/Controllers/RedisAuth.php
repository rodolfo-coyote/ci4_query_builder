<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RedisAuthModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class RedisAuth extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $userModel = new UserModel();

        $userInfo = $userModel->verifyUserIsLogged();

        if (!$userInfo) {
            return $this->respond(['error' => 'Session doesnt exist. You arent logged'], 401);
        }
        return $this->respond(print_r($userInfo), 200);
    }

    public function logout()
    {
        $userModel = new UserModel();

        $userInfo = $userModel->verifyUserIsLogged();

        if (!$userInfo) {
            return $this->respond(['error' => 'You arent logged so you cant logout. Refresh the page'], 401);
        }

        $userLogout = $userModel->destroySession();

        if (!$userLogout) {
            return $this->respond(['error' => 'Unable to logout. Contact admin'], 401);
        }

        return $this->respondDeleted("User logged out successfully");
    }
}
