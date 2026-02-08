<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return $this->response->setJSON([
            'message' => 'Protected route accessed',
            'user' => $this->request->user
        ]);
    }
}
