<?php

class Profile {
    private $id;
    private $user_id;
    private $visible_name;
    private $avatar_url;
    private $show_contact_info;
    private $created_at;
    private $updated_at;

    public function __construct(
        private ?PDO $database = null
    ) {
        $this->database = Database::getInstance();
    }

    public function create(int $userId, string $visibleName = null, bool $showContactInfo = false): ?self {
        if (!$visibleName) {
            // Default visible name to user's email if not provided
            $user = (new User())->getById($userId);
            $visibleName = $user ? $user->getEmail() : 'User' . $userId;
        }

        $stmt = $this->database->prepare('
            INSERT INTO profiles (user_id, visible_name, show_contact_info, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), NOW())
        ');

        try {
            $stmt->execute([$userId, $visibleName, $showContactInfo]);
            return $this->getByUserId($userId);
        } catch (PDOException $e) {
            // Log error
            return null;
        }
    }

    public function getByUserId(int $userId): ?self {
        $stmt = $this->database->prepare('
            SELECT * FROM profiles WHERE user_id = ?
        ');
        $stmt->execute([$userId]);

        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($profile) {
            return $this->mapFromDB($profile);
        }

        return null;
    }

    public function update(array $data): bool {
        if (!$this->id) {
            return false;
        }

        $updateFields = [];
        $params = [];

        // Only update fields that are provided
        if (isset($data['visible_name'])) {
            $updateFields[] = 'visible_name = ?';
            $params[] = $data['visible_name'];
            $this->visible_name = $data['visible_name'];
        }

        if (isset($data['show_contact_info'])) {
            $updateFields[] = 'show_contact_info = ?';
            $params[] = $data['show_contact_info'];
            $this->show_contact_info = $data['show_contact_info'];
        }

        if (isset($data['avatar_url'])) {
            $updateFields[] = 'avatar_url = ?';
            $params[] = $data['avatar_url'];
            $this->avatar_url = $data['avatar_url'];
        }

        if (empty($updateFields)) {
            return false;
        }

        $updateFields[] = 'updated_at = NOW()';
        $params[] = $this->id;

        $sql = 'UPDATE profiles SET ' . implode(', ', $updateFields) . ' WHERE id = ? RETURNING *';
        
        try {
            $stmt = $this->database->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            // Log error
            return false;
        }
    }

    private function mapFromDB(array $data): self {
        $this->id = $data['id'];
        $this->user_id = $data['user_id'];
        $this->visible_name = $data['visible_name'];
        $this->avatar_url = $data['avatar_url'];
        $this->show_contact_info = (bool) $data['show_contact_info'];
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
        return $this;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getUserId(): ?int {
        return $this->user_id;
    }

    public function getVisibleName(): ?string {
        return $this->visible_name;
    }

    public function getAvatarUrl(): ?string {
        return $this->avatar_url;
    }

    public function getShowContactInfo(): bool {
        return $this->show_contact_info;
    }

    public function getCreatedAt(): ?string {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string {
        return $this->updated_at;
    }
} 