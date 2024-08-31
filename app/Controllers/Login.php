<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $userModel = new userModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $findUser = $userModel->getUserByEmail($email);

        if (is_null($findUser)) {
            return $this->respond(['error' => 'Email is not registered'], 401);
        }


        if (!$userModel->verifyPassword($email, $password)) {
            return $this->respond(['error' => 'Password doesnt match'], 401);
        }

        $token = $userModel->getUserJwt($email);

        if (!$token) {
            return $this->respond(['error' => 'An error occurred while generating your JWT token '], 401);
        }

        if (!$userModel->tokenToRedis($token)) {
            return $this->respond(['error' => 'Unable to storage the token in Redis'], 401);
        }

        $response = [
            'message' => 'User authenticated',
            'token' => $token,
            'stored_token2' => true
        ];

        return $this->respond($response, 200);
    }
}
