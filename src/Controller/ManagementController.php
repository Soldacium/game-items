<?php

namespace App\Controller;

use App\Model\User;
use App\Model\Profile;
use App\Model\Item;
use App\Repository\ItemRepository;

class ManagementController extends BaseController {
    private $itemRepository;

    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $this->itemRepository = new ItemRepository();
    }

    public function index() {
        // Redirect to profile by default
        $this->redirect('/management/profile');
    }

    public function profile() {
        $user = new User();
        $userData = $user->getById($_SESSION['user']['id']);

        if (!$userData) {
            $this->redirect('/login');
        }

        $profile = new Profile();
        $profileData = $profile->getByUserId($_SESSION['user']['id']);

        if (!$profileData) {
            // Create profile if it doesn't exist
            $profileData = $profile->create($_SESSION['user']['id'], $userData['email']);
            if (!$profileData) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to create profile'];
                $this->redirect('/management/profile');
            }
        }

        return $this->render('management/profile', [
            'user' => $userData,
            'profile' => $profileData,
            'activeTab' => 'profile',
            'csrf_token' => $this->generateCSRFToken(),
            'title' => 'Profile Management - Game Items Catalog'
        ]);
    }

    public function account() {
        $user = new User();
        $userData = $user->getById($_SESSION['user']['id']);

        if (!$userData) {
            $this->redirect('/login');
        }

        return $this->render('management/account', [
            'user' => $userData,
            'activeTab' => 'account',
            'csrf_token' => $this->generateCSRFToken(),
            'title' => 'Account Management - Game Items Catalog'
        ]);
    }

    public function activity() {
        $user = new User();
        $userData = $user->getById($_SESSION['user']['id']);

        if (!$userData) {
            $this->redirect('/login');
        }

        return $this->render('management/activity', [
            'user' => $userData,
            'activeTab' => 'activity',
            'csrf_token' => $this->generateCSRFToken(),
            'title' => 'Activity History - Game Items Catalog'
        ]);
    }

    public function updateProfile() {
        if (!$this->isPost()) {
            $this->redirect('/management/profile');
        }

        $this->validateCSRF();
        
        $profile = new Profile();
        $profileData = $profile->getByUserId($_SESSION['user']['id']);

        if (!$profileData) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Profile not found'];
            $this->redirect('/management/profile');
        }

        $success = $profileData->update([
            'visible_name' => $this->getPost('visible_name'),
            'show_contact_info' => $this->getPost('show_contact_info') === 'on'
        ]);

        if ($success) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Profile updated successfully'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to update profile'];
        }

        $this->redirect('/management/profile');
    }

    public function updateAvatar() {
        if (!$this->isPost() || !isset($_FILES['avatar'])) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'No file uploaded']);
        }

        $this->validateCSRF();

        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Invalid file type']);
        }

        if ($file['size'] > $maxSize) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'File too large']);
        }

        $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
        $fileName = uniqid('avatar_') . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Failed to upload file']);
        }

        $profile = new Profile();
        $profileData = $profile->getByUserId($_SESSION['user']['id']);

        if (!$profileData) {
            unlink($targetPath);
            return $this->jsonResponse(['status' => 'error', 'message' => 'Profile not found']);
        }

        $success = $profileData->update([
            'avatar_url' => '/public/uploads/avatars/' . $fileName
        ]);

        if (!$success) {
            unlink($targetPath);
            return $this->jsonResponse(['status' => 'error', 'message' => 'Failed to update profile']);
        }

        return $this->jsonResponse(['status' => 'success', 'url' => '/public/uploads/avatars/' . $fileName]);
    }

    public function updateAccount() {
        if (!$this->isPost()) {
            $this->redirect('/management/account');
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
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Account updated successfully'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to update account'];
        }

        $this->redirect('/management/account');
    }

    public function updatePassword() {
        if (!$this->isPost()) {
            $this->redirect('/management/account');
        }

        $this->validateCSRF();

        $currentPassword = $this->getPost('previous_password');
        $newPassword = $this->getPost('new_password');
        $confirmPassword = $this->getPost('confirm_new_password');

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'New passwords do not match'];
            $this->redirect('/management/account');
        }

        $user = new User();
        if (!$user->verifyPassword($_SESSION['user']['email'], $currentPassword)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Current password is incorrect'];
            $this->redirect('/management/account');
        }

        $success = $user->updatePassword($_SESSION['user']['id'], $newPassword);

        if ($success) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Password updated successfully'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to update password'];
        }

        $this->redirect('/management/account');
    }

    public function items()
    {
        $this->ensureAdmin();
        $userId = $_SESSION['user']['id'];
        
        $items = $this->itemRepository->getByUserId($userId);
        $csrf_token = $this->generateCsrfToken();
        
        require_once __DIR__ . '/../../views/management/items.php';
    }

    public function newItem()
    {
        $this->ensureAdmin();
        $csrf_token = $this->generateCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $item = new Item();
            $item->setName($_POST['name']);
            $item->setType($_POST['type']);
            $item->setAct($_POST['act']);
            $item->setRarity($_POST['rarity']);
            $item->setDescription($_POST['description'] ?? '');
            $item->setNotes($_POST['notes'] ?? '');
            $item->setUserId($_SESSION['user']['id']);
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image'];
                $allowedTypes = ['image/jpeg', 'image/png'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                if (!in_array($file['type'], $allowedTypes)) {
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'Invalid file type. Only JPG and PNG are allowed.'
                    ];
                    require_once __DIR__ . '/../../views/management/item-form.php';
                    return;
                }

                if ($file['size'] > $maxSize) {
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'File size must be less than 2MB.'
                    ];
                    require_once __DIR__ . '/../../views/management/item-form.php';
                    return;
                }

                $imageData = file_get_contents($file['tmp_name']);
                $thumbnailId = $item->saveBlob($imageData, $file['type']);
                
                if ($thumbnailId) {
                    $item->setThumbnailId($thumbnailId);
                } else {
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'Failed to upload image.'
                    ];
                    require_once __DIR__ . '/../../views/management/item-form.php';
                    return;
                }
            }
            
            $this->itemRepository->save($item);
            
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Item created successfully!'
            ];
            
            header('Location: /management/items');
            exit;
        }
        
        require_once __DIR__ . '/../../views/management/item-form.php';
    }

    public function editItem($id)
    {
        $this->ensureAdmin();
        $userId = $_SESSION['user']['id'];
        
        $item = $this->itemRepository->getById($id);
        if (!$item || $item->getUserId() !== $userId) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Item not found or access denied.'
            ];
            header('Location: /management/items');
            exit;
        }
        
        $csrf_token = $this->generateCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $item->setName($_POST['name']);
            $item->setType($_POST['type']);
            $item->setAct($_POST['act']);
            $item->setRarity($_POST['rarity']);
            $item->setDescription($_POST['description'] ?? '');
            $item->setNotes($_POST['notes'] ?? '');
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image'];
                $allowedTypes = ['image/jpeg', 'image/png'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                if (!in_array($file['type'], $allowedTypes)) {
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'Invalid file type. Only JPG and PNG are allowed.'
                    ];
                    require_once __DIR__ . '/../../views/management/item-form.php';
                    return;
                }

                if ($file['size'] > $maxSize) {
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'File size must be less than 2MB.'
                    ];
                    require_once __DIR__ . '/../../views/management/item-form.php';
                    return;
                }

                // Delete old blob if exists
                if ($item->getThumbnailId()) {
                    $item->deleteBlob($item->getThumbnailId());
                }

                $imageData = file_get_contents($file['tmp_name']);
                $thumbnailId = $item->saveBlob($imageData, $file['type']);
                
                if ($thumbnailId) {
                    $item->setThumbnailId($thumbnailId);
                } else {
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'Failed to upload image.'
                    ];
                    require_once __DIR__ . '/../../views/management/item-form.php';
                    return;
                }
            }
            
            $this->itemRepository->update($item);
            
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Item updated successfully!'
            ];
            
            header('Location: /management/items');
            exit;
        }
        
        require_once __DIR__ . '/../../views/management/item-form.php';
    }

    public function deleteItem($id)
    {
        $this->ensureAdmin();
        $userId = $_SESSION['user']['id'];
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /management/items');
            exit;
        }
        
        $this->validateCSRF();
        
        $item = $this->itemRepository->getById($id);
        if (!$item || $item->getUserId() !== $userId) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Item not found or access denied.'
            ];
            header('Location: /management/items');
            exit;
        }
        
        $this->itemRepository->delete($item);
        
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Item deleted successfully!'
        ];
        
        header('Location: /management/items');
        exit;
    }

    private function ensureAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Access denied. Admin privileges required.'
            ];
            header('Location: /');
            exit;
        }
    }

    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 