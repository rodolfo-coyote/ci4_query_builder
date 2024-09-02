<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    use ResponseTrait;

    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        if (!$this->session->has('user_info')) {
            //? Por qué no puedo usar "/" como parámetro de la vista?
            return view('/index');
        }

        return redirect()->to(site_url('/customer/login'));;
    }

    public function login()
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

        $userInfo = $userModel->getUserInfo($email);
        $token = $userInfo->token;

        if (!$token) {
            return $this->respond(['error' => 'An error occurred while generating your JWT token'], 401);
        }

        if (!$userModel->sessionToRedis($userInfo->id, $token, $userInfo->expires_at, $userInfo->fullname)) {
            return $this->respond(['error' => 'Unable to storage the session in Redis'], 401);
        }

        $response = [
            'message' => 'User authenticated successfully',
            'user_id' => $userInfo->id,
            'token' => $token,
            'expires_at' => $userInfo->expires_at,
            'fullname' => $userInfo->fullname
        ];

        return $this->respond($response, 200);
    }
}
