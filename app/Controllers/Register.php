<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Register extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $userModel = new UserModel();

        $fullname = $this->request->getVar('fullname');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $confirm_password = $this->request->getVar('confirm_password');
    }
}
