<?php

namespace App\Models;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Config\Services;

require_once __DIR__ . '/../../vendor/autoload.php';

use CodeIgniter\Model;

class RedisAuthModel extends Model
{
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
    }
}
