<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Customer extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        if (!$this->session->has('user_info')) {
            //? Por qué no puedo usar "/" como parámetro de la vista?
            return view('index');
        }

        $serializedData = $this->session->get('user_info');

        if ($serializedData)
            $sessionData = unserialize($serializedData);

        return view('customer/index', $sessionData);
    }
}
