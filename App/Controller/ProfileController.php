<?php

namespace App\Controller;

class ProfileController extends BaseController
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/Public/index.php?page=login');
            exit;
        }

        $this->view('profile/index', [
            'pageTitle' => 'My Profile',
            'hideSearch' => true
        ]);
    }
}