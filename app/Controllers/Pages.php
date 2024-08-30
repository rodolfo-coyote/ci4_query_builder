<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class Pages extends BaseController
{
    public function index()
    {
        return view('pages/index');
    }

    public function custom_view(string $page = 'home')
    {
        //If the page that user is trying to access, doesnt exist on our Views...
        if (!is_file(APPPATH . 'Views/pages/' . $page . '.php')) {
            //To use PageNotFoundException, we must use CodeIgniter\Exceptions\PageNotFoundException;
            throw new PageNotFoundException($page);
        }

        $data['title'] = ucfirst($page);

        return view('templates/header', $data) .
            view('pages/' . $page) .
            view('templates/footer');
    }
}
