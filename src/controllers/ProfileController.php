<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';

class ProfileController extends AppController {
    private $user;
    private $profile;

    public function __construct() {
        parent::__construct();
        $this->user = new User();
        $this->profile = new Profile();
    }

    public function profile() {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }

        $userId = $_SESSION['user_id'];
        $user = $this->user->getById($userId);
        $profile = $this->profile->getByUserId($userId);

        // Create profile if it doesn't exist
        if (!$profile) {
            $profile = $this->profile->create($userId);
        }

        return $this->render('management/profile', [
            'user' => $user,
            'profile' => $profile
        ]);
    }

    public function update() {
        if (!$this->isLoggedIn() || !$this->isPost()) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid request'
            ], 400);
        }

        $userId = $_SESSION['user_id'];
        $profile = $this->profile->getByUserId($userId);

        if (!$profile) {
            return $this->json([
                'status' => 'error',
                'message' => 'Profile not found'
            ], 404);
        }

        // Validate and sanitize input
        $visibleName = trim($_POST['visible_name'] ?? '');
        $showContactInfo = isset($_POST['show_contact_info']);

        if (empty($visibleName)) {
            return $this->json([
                'status' => 'error',
                'message' => 'Name cannot be empty'
            ], 400);
        }

        // Handle avatar upload if present
        $avatarUrl = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatarUrl = $this->handleAvatarUpload($_FILES['avatar']);
            if (!$avatarUrl) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Failed to upload avatar'
                ], 500);
            }
        }

        // Update profile
        $updateData = [
            'visible_name' => $visibleName,
            'show_contact_info' => $showContactInfo
        ];

        if ($avatarUrl) {
            $updateData['avatar_url'] = $avatarUrl;
        }

        if ($profile->update($updateData)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Profile updated successfully'
            ];
            
            if ($this->isAjax()) {
                return $this->json([
                    'status' => 'success',
                    'message' => 'Profile updated successfully'
                ]);
            }
            
            $this->redirect('management/profile');
        }

        if ($this->isAjax()) {
            return $this->json([
                'status' => 'error',
                'message' => 'Failed to update profile'
            ], 500);
        }

        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Failed to update profile'
        ];
        $this->redirect('management/profile');
    }

    private function handleAvatarUpload(array $file): ?string {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        if ($file['size'] > $maxSize) {
            return null;
        }

        $uploadDir = 'public/uploads/avatars/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid('avatar_') . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return '/' . $targetPath;
        }

        return null;
    }

    private function isAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
} 