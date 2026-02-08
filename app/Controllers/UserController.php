<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
public function create()
{
    $data = $this->request->getJSON(true);

    if (!$data) {
        return $this->response->setJSON([
            'error' => 'Invalid JSON'
        ])->setStatusCode(400);
    }

    $model = new \App\Models\UserModel();

    $model->insert([
        'name'     => $data['name'],     
        'email'    => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_DEFAULT),
    ]);

    return $this->response->setJSON([
        'status' => true,
        'message' => 'User created successfully'
    ]);
}
public function login()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['email'], $data['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request'
            ])->setStatusCode(400);
        }

        $model = new UserModel();
        $user = $model->where('email', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid credentials'
            ])->setStatusCode(401);
        }

        helper('jwt');
        $token = generateJWT($user);

        return $this->response->setJSON([
            'status' => true,
            'token' => $token
        ]);
    }
    public function loginPage()
{
    return view('Login_page');
}


}
