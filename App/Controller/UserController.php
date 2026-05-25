<?php

namespace App\Controller;

use App\Service\UserService;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {

                $email = trim($this->post('email'));
                $password = trim($this->post('password'));

                $this->userService->login($email, $password);

                $this->redirect(BASE_URL . '/Public/index.php?page=home');

            } catch (\Exception $e) {

                $error = $e->getMessage();
            }
        }

        $this->view('users/login', [
            'pageTitle' => 'Login',
            'section' => '',
            'hideSearch' => true,
            'error' => $error
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */
    public function register(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {

                $name = trim($this->post('name'));
                $email = trim($this->post('email'));
                $password = trim($this->post('password'));

                $this->userService->register([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password
                ]);

                $this->redirect(BASE_URL . '/Public/index.php?page=login');

            } catch (\Exception $e) {

                $error = $e->getMessage();
            }
        }

        $this->view('users/register', [
            'pageTitle' => 'Register',
            'section' => '',
            'hideSearch' => true,
            'error' => $error
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(): void
    {
        $this->userService->logout();

        $this->redirect(BASE_URL . '/Public/index.php?page=login');
    }

    /*
    |--------------------------------------------------------------------------
    | USER LIST
    |--------------------------------------------------------------------------
    */
    public function index(): void
    {
        $users = $this->userService->getAllUsers();

        $this->view('users/index', [
            'pageTitle' => 'All Users',
            'section' => '',
            'users' => $users
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SINGLE USER
    |--------------------------------------------------------------------------
    */
    public function show(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->redirect(BASE_URL . '/Public/index.php?page=users');
        }

        $user = $this->userService->getUserById($id);

        if (!$user) {
            $this->redirect(BASE_URL . '/Public/index.php?page=users');
        }

        $this->view('users/show', [
            'pageTitle' => $user['name'],
            'section' => '',
            'user' => $user
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $this->post('name');
            $email = $this->post('email');
            $password = $this->post('password');

            $this->userService->createUser([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]);

            $this->redirect(BASE_URL . '/Public/index.php?page=users');
        }

        $this->view('users/create', [
            'pageTitle' => 'Create User',
            'section' => ''
        ]);
    }
}