<?php

namespace App\Controllers;

helper('url');

class Home extends BaseController
{
    public function index(): string
    {
        return view('index');
    }
}
