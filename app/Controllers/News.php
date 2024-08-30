<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class News extends BaseController
{
    public function index()
    {
        $model = model(NewsModel::class);
        $data = [
            'all_news' => $model->getNews(),
            'title' => 'Latest News'
        ];

        return view('templates/header', $data) .
            view('news/index') .
            view('templates/footer');
    }

    public function add()
    {
        helper('form');
        $data = [
            'title' => 'Add a new article'
        ];

        return view('templates/header', $data) .
            view('news/add') .
            view('templates/footer');
    }

    public function upload_new()
    {
        helper('form');

        $data = $this->request->getPost(['title', 'body']);

        // Validation rules.
        if (! $this->validateData($data, [
            'title' => 'required|max_length[255]|min_length[3]',
            'body'  => 'required|max_length[5000]|min_length[10]',
        ])) {
            // Return to add Method
            return $this->add();
        }

        $article = $this->validator->getValidated();

        $model = model(NewsModel::class);
        $model->save([
            'title' => $article['title'],
            'slug' => url_title($article['title'], '-', true),
            'body' => $article['body']
        ]);

        return view('templates/header', $data) .
            view('news/success') .
            view('templates/footer');
    }
    public function single_new(?string $slug = null)
    {
        $model = model(NewsModel::class);
        $data['new_info'] = $model->getNews($slug);

        if ($data['new_info'] === null) {
            throw new PageNotFoundException('New ' . $slug . ' not found :/');
        }

        $data['title'] = $data['new_info']['title'];

        return view('templates/header', $data) .
            view('news/single') .
            view('templates/footer');
    }
}
