<?php

namespace App\Controller;

use App\Model\User;

class AccountController extends BaseController {
    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
    }

    public function index() {
        $user = new User();
        $userData = $user->getById($_SESSION['user']['id']);

        if (!$userData) {
            $this->redirect('/login');
        }

        return $this->render('account/index', [
            'user' => $userData,
            'activeTab' => 'profile',
            'csrf_token' => $this->generateCSRFToken(),
            'title' => 'Account Settings - Game Items Catalog'
        ]);
    }

    public function updateProfile() {
        if (!$this->isPost()) {
            $this->redirect('/account');
        }

        $this->validateCSRF();

        $email = $this->getPost('email');
        $accountName = $this->getPost('account_name');

        $user = new User();
        $success = $user->update($_SESSION['user']['id'], [
            'email' => $email,
            'account_name' => $accountName
        ]);

        if ($success) {
            $_SESSION['user']['email'] = $email;
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Profile updated successfully'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to update profile'];
        }

        $this->redirect('/account');
    }

    public function updatePassword() {
        if (!$this->isPost()) {
            $this->redirect('/account');
        }

        $this->validateCSRF();

        $currentPassword = $this->getPost('previous_password');
        $newPassword = $this->getPost('new_password');
        $confirmPassword = $this->getPost('confirm_new_password');

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'New passwords do not match'];
            $this->redirect('/account');
        }

        $user = new User();
        if (!$user->verifyPassword($_SESSION['user']['email'], $currentPassword)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Current password is incorrect'];
            $this->redirect('/account');
        }

        $success = $user->updatePassword($_SESSION['user']['id'], $newPassword);

        if ($success) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Password updated successfully'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to update password'];
        }

        $this->redirect('/account');
    }
} 