<?php

namespace App\Model;

class User extends BaseModel {
    private $id;
    private $email;
    private $password;
    private $role;
    private $accountName;

    public function __construct($id = null, $email = null, $password = null, $role = 'user', $accountName = null) {
        parent::__construct();
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->accountName = $accountName;
    }

    public function authenticate($email, $password) {
        $stmt = $this->db->prepare('
            SELECT id, email, password_hash, role 
            FROM users 
            WHERE email = ?
        ');
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return new User(
                $user['id'],
                $user['email'],
                null,
                $user['role']
            );
        }

        return false;
    }

    public function verifyPassword($email, $password) {
        $stmt = $this->db->prepare('
            SELECT password_hash FROM users WHERE email = ?
        ');
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user && password_verify($password, $user['password_hash']);
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare('
            SELECT COUNT(*) FROM users WHERE email = ?
        ');
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function create($email, $password, $accountName = null) {
        try {
            $this->db->beginTransaction();
            
            $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
            
            $stmt = $this->db->prepare('
                INSERT INTO users (email, password_hash, role, account_name)
                VALUES (?, ?, ?, ?)
            ');
            
            $result = $stmt->execute([
                $email, 
                $passwordHash, 
                'user',
                $accountName
            ]);
            
            if ($result) {
                $this->db->commit();
                return true;
            }
            
            $this->db->rollBack();
            return false;
        } catch (\PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("User creation error: " . $e->getMessage());
            throw new \Exception("Failed to create user");
        }
    }

    public function update($id, $data) {
        // Check if account_name column exists
        try {
            $stmt = $this->db->prepare('
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = \'users\' AND column_name = \'account_name\'
            ');
            $stmt->execute();
            $hasAccountName = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($hasAccountName) {
                $stmt = $this->db->prepare('
                    UPDATE users 
                    SET email = ?, account_name = ?
                    WHERE id = ?
                ');
                return $stmt->execute([
                    $data['email'],
                    $data['account_name'] ?? null,
                    $id
                ]);
            } else {
                $stmt = $this->db->prepare('
                    UPDATE users 
                    SET email = ?
                    WHERE id = ?
                ');
                return $stmt->execute([
                    $data['email'],
                    $id
                ]);
            }
        } catch (\PDOException $e) {
            // If there's any error, just update email
            $stmt = $this->db->prepare('
                UPDATE users 
                SET email = ?
                WHERE id = ?
            ');
            return $stmt->execute([
                $data['email'],
                $id
            ]);
        }
    }

    public function updatePassword($id, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_ARGON2ID);
        
        $stmt = $this->db->prepare('
            UPDATE users 
            SET password_hash = ?
            WHERE id = ?
        ');
        
        return $stmt->execute([$passwordHash, $id]);
    }

    public function getById($id) {
        // First try with account_name
        try {
            $stmt = $this->db->prepare('
                SELECT id, email, role, account_name, created_at
                FROM users WHERE id = ?
            ');
            $stmt->execute([$id]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // If account_name doesn't exist, try without it
            $stmt = $this->db->prepare('
                SELECT id, email, role, created_at
                FROM users WHERE id = ?
            ');
            $stmt->execute([$id]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        if ($user) {
            // Add account_name if it doesn't exist
            if (!isset($user['account_name'])) {
                $user['account_name'] = null;
            }
        }
        return $user;
    }

    public function getAll() {
        try {
            $stmt = $this->db->prepare('
                SELECT id, email, role, account_name, created_at
                FROM users
                ORDER BY created_at DESC
            ');
            $stmt->execute();
            $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // If account_name doesn't exist, try without it
            $stmt = $this->db->prepare('
                SELECT id, email, role, created_at
                FROM users
                ORDER BY created_at DESC
            ');
            $stmt->execute();
            $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Add account_name field to each user
            foreach ($users as &$user) {
                $user['account_name'] = null;
            }
        }
        return $users;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }
    public function getAccountName() { return $this->accountName ?? $this->email; }
} 