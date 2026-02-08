<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return service('response')
                ->setJSON(['message' => 'Unauthorized'])
                ->setStatusCode(401);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        helper('jwt');
        $decoded = validateJWT($token);

        if (!$decoded) {
            return service('response')
                ->setJSON(['message' => 'Invalid token'])
                ->setStatusCode(401);
        }

        $request->user = $decoded;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
