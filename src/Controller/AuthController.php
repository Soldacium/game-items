<?php

namespace App\Controller;

use App\Model\User;

class AuthController extends BaseController {
    public function login() {
        if (!isset($_SESSION['previous_page']) && 
            !in_array($_SERVER['REQUEST_URI'], ['/login', '/register'])) {
            $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
        }

        if ($this->isPost()) {
            $this->validateCSRF();
            
            $email = $this->getPost('email');
            $password = $this->getPost('password');
            
            $user = new User();
            $authenticatedUser = $user->authenticate($email, $password);
            
            if ($authenticatedUser) {
                $_SESSION['user'] = [
                    'id' => $authenticatedUser->getId(),
                    'email' => $authenticatedUser->getEmail(),
                    'role' => $authenticatedUser->getRole()
                ];

                $redirectTo = isset($_SESSION['previous_page']) ? $_SESSION['previous_page'] : '/';
                unset($_SESSION['previous_page']);
                $this->redirect($redirectTo);
            }
            
            return $this->render('auth/login', [
                'error' => 'Invalid email or password',
                'csrf_token' => $this->generateCSRFToken(),
                'title' => 'Login - Game Items Catalog'
            ]);
        }
        
        return $this->render('auth/login', [
            'csrf_token' => $this->generateCSRFToken(),
            'title' => 'Login - Game Items Catalog'
        ]);
    }

    public function register() {
        if ($this->isPost()) {
            $this->validateCSRF();
            
            $email = trim($this->getPost('email'));
            $accountName = trim($this->getPost('account_name'));
            $password = $this->getPost('password');
            $confirmPassword = $this->getPost('confirm_password');
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->render('auth/register', [
                    'error' => 'Invalid email address',
                    'csrf_token' => $this->generateCSRFToken(),
                    'title' => 'Register - Game Items Catalog'
                ]);
            }
            
            // Validate account name
            if (strlen($accountName) < 3 || strlen($accountName) > 50) {
                return $this->render('auth/register', [
                    'error' => 'Account name must be between 3 and 50 characters',
                    'csrf_token' => $this->generateCSRFToken(),
                    'title' => 'Register - Game Items Catalog'
                ]);
            }
            
            // Validate password
            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
                return $this->render('auth/register', [
                    'error' => 'Password must be at least 8 characters long and include both letters and numbers',
                    'csrf_token' => $this->generateCSRFToken(),
                    'title' => 'Register - Game Items Catalog'
                ]);
            }
            
            if ($password !== $confirmPassword) {
                return $this->render('auth/register', [
                    'error' => 'Passwords do not match',
                    'csrf_token' => $this->generateCSRFToken(),
                    'title' => 'Register - Game Items Catalog'
                ]);
            }
            
            try {
                $user = new User();
                if ($user->emailExists($email)) {
                    return $this->render('auth/register', [
                        'error' => 'Email already registered',
                        'csrf_token' => $this->generateCSRFToken(),
                        'title' => 'Register - Game Items Catalog'
                    ]);
                }
                
                if ($user->create($email, $password, $accountName)) {
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Registration successful! Please log in.'
                    ];
                    $this->redirect('/login');
                }
                
                return $this->render('auth/register', [
                    'error' => 'Registration failed',
                    'csrf_token' => $this->generateCSRFToken(),
                    'title' => 'Register - Game Items Catalog'
                ]);
            } catch (\Exception $e) {
                error_log("Registration error: " . $e->getMessage());
                return $this->render('auth/register', [
                    'error' => 'An error occurred during registration',
                    'csrf_token' => $this->generateCSRFToken(),
                    'title' => 'Register - Game Items Catalog'
                ]);
            }
        }
        
        return $this->render('auth/register', [
            'csrf_token' => $this->generateCSRFToken(),
            'title' => 'Register - Game Items Catalog'
        ]);
    }

    public function profile() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $user = new User();
        $userData = $user->getById($_SESSION['user']['id']);

        if (!$userData) {
            $this->redirect('/');
        }

        return $this->render('auth/profile', [
            'user' => $userData,
            'csrf_token' => $this->generateCSRFToken(),
            'title' => 'Profile - Game Items Catalog'
        ]);
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
} 