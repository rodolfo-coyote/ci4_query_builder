<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class isLoggedIn implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if ($request->getMethod() !== 'GET') {
            return redirect()->to(site_url('/api/login'));;
        }

        if (!$this->session->has('user_info')) {
            return redirect()->to(site_url('/api/login'));;
        }

        $serializedData = $this->session->get('user_info');
        if ($serializedData)
            $sessionData = unserialize($serializedData);

        $key = getenv('JWT_SECRET');
        $token = $sessionData['token'];

        try {
            $validateToken = JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return redirect()->to(site_url('/api/login'));;
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
