<?php

namespace App\Controller;

use App\Service\UserService;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;


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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1. Validate request
            $data = $this->validateRequest(LoginRequest::class);

            // 2. Business logic
            $this->userService->login($data);

            // 3. Redirect on success
            $this->redirect(BASE_URL . '/Public/index.php?page=home');
        }

        $this->view('users/login', [
            'pageTitle' => 'Login',
            'section' => '',
            'hideSearch' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */


    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1. Validate request (throws exception automatically if invalid)
            $data = $this->validateRequest(UserRequest::class);


            // 2. Business logic
            $this->userService->register($data);

            // 3. Success redirect
            $this->redirect(BASE_URL . '/Public/index.php?page=login');
        }

        // 4. Show view (GET request OR after error redirect)
        $this->view('users/register', [
            'pageTitle' => 'Register',
            'section' => '',
            'hideSearch' => true,
            'error' => $_SESSION['error'] ?? []
        ]);

        // clear flash error after showing
        unset($_SESSION['error']);
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