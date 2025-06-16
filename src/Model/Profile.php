<?php

namespace App\Model;

class Profile extends BaseModel {
    private $id;
    private $user_id;
    private $visible_name;
    private $avatar_url;
    private $show_contact_info;
    private $created_at;
    private $updated_at;

    public function __construct(
        $id = null,
        $user_id = null,
        $visible_name = null,
        $avatar_url = null,
        $show_contact_info = false
    ) {
        parent::__construct();
        $this->id = $id;
        $this->user_id = $user_id;
        $this->visible_name = $visible_name;
        $this->avatar_url = $avatar_url;
        $this->show_contact_info = $show_contact_info;
    }

    public function create(int $userId, string $visibleName = null, bool $showContactInfo = false): ?self {
        if (!$visibleName) {
            try {
                $user = (new User())->getById($userId);
                if (!$user) {
                    echo "User not found with ID: $userId\n";
                    return null;
                }
                $visibleName = $user['email'];
            } catch (\Exception $e) {
                echo "Error getting user: " . $e->getMessage() . "\n";
                return null;
            }
        }

        try {
            $stmt = $this->db->prepare('
                INSERT INTO profiles (user_id, visible_name, show_contact_info, created_at, updated_at)
                VALUES (?, ?, ?, NOW(), NOW())
                RETURNING *
            ');

            $showContactInfoStr = $showContactInfo ? 't' : 'f';
            
            $stmt->execute([$userId, $visibleName, $showContactInfoStr]);
            $profile = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$profile) {
                echo "No profile returned after insert\n";
                return null;
            }
            
            return $this->mapFromDB($profile);
        } catch (\PDOException $e) {
            echo "Database error: " . $e->getMessage() . "\n";
            return null;
        } catch (\Exception $e) {
            echo "Error creating profile: " . $e->getMessage() . "\n";
            return null;
        }
    }

    public function getByUserId(int $userId): ?self {
        try {
            $stmt = $this->db->prepare('
                SELECT * FROM profiles WHERE user_id = ?
            ');
            $stmt->execute([$userId]);

            $profile = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($profile) {
                return $this->mapFromDB($profile);
            }

            return null;
        } catch (\Exception $e) {
            echo "Error getting profile: " . $e->getMessage() . "\n";
            return null;
        }
    }

    public function update(array $data): bool {
        if (!$this->id) {
            return false;
        }

        $updateFields = [];
        $params = [];

        if (isset($data['visible_name'])) {
            $updateFields[] = 'visible_name = ?';
            $params[] = $data['visible_name'];
            $this->visible_name = $data['visible_name'];
        }

        if (isset($data['show_contact_info'])) {
            $updateFields[] = 'show_contact_info = ?';
            $params[] = $data['show_contact_info'] ? 't' : 'f';
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
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (\Exception $e) {
            echo "Error updating profile: " . $e->getMessage() . "\n";
            return false;
        }
    }

    private function mapFromDB(array $data): self {
        return new self(
            $data['id'],
            $data['user_id'],
            $data['visible_name'],
            $data['avatar_url'],
            (bool) $data['show_contact_info']
        );
    }

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