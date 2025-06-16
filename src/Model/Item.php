<?php

namespace App\Model;

class Item extends BaseModel {
    private $id;
    private $name;
    private $type;
    private $act;
    private $rarity;
    private $description;
    private $notes;
    private $userId;
    private $imageUrl;
    private $createdAt;
    private $updatedAt;
    private $thumbnailId;

    public function __construct($id = null, $name = null, $type = null, $rarity = null, $act = null, $description = null, $notes = null, $userId = null, $thumbnailId = null) {
        parent::__construct();
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->rarity = $rarity;
        $this->act = $act;
        $this->description = $description;
        $this->notes = $notes;
        $this->userId = $userId;
        $this->thumbnailId = $thumbnailId;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getAct() {
        return $this->act;
    }

    public function setAct($act) {
        $this->act = $act;
        return $this;
    }

    public function getRarity() {
        return $this->rarity;
    }

    public function setRarity($rarity) {
        $this->rarity = $rarity;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getNotes() {
        return $this->notes;
    }

    public function setNotes($notes) {
        $this->notes = $notes;
        return $this;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }

    public function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getThumbnailId() {
        return $this->thumbnailId;
    }

    public function setThumbnailId($thumbnailId) {
        $this->thumbnailId = $thumbnailId;
        return $this;
    }

    public function saveBlob($data, $mimeType) {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO blobs (data, mime_type)
                VALUES (:data, :mime_type)
                RETURNING id
            ');
            
            $this->db->beginTransaction();
            
            // Bind the binary data directly
            $stmt->bindValue(':data', $data, \PDO::PARAM_LOB);
            $stmt->bindValue(':mime_type', $mimeType, \PDO::PARAM_STR);
            
            if (!$stmt->execute()) {
                $this->db->rollBack();
                throw new \Exception("Execute failed: " . implode(", ", $stmt->errorInfo()));
            }
            
            $id = $stmt->fetchColumn();
            $this->db->commit();
            
            return $id;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new \Exception("Failed to save blob: " . $e->getMessage());
        }
    }

    public function getBlob($id) {
        try {
            error_log("Getting blob with ID: " . $id);
            
            $stmt = $this->db->prepare('
                SELECT encode(data, \'base64\') as data, mime_type
                FROM blobs
                WHERE id = :id
            ');
            
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                error_log("Failed to execute blob query: " . implode(", ", $stmt->errorInfo()));
                throw new \Exception("Execute failed: " . implode(", ", $stmt->errorInfo()));
            }
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($result) {
                error_log("Found blob with mime type: " . $result['mime_type']);
                // Decode the base64 data back to binary
                $result['data'] = base64_decode($result['data']);
                error_log("Decoded blob data length: " . strlen($result['data']));
            } else {
                error_log("No blob found for ID: " . $id);
            }
            
            return $result;
        } catch (\Exception $e) {
            error_log("Error getting blob: " . $e->getMessage());
            error_log($e->getTraceAsString());
            throw new \Exception("Failed to get blob: " . $e->getMessage());
        }
    }

    public function deleteBlob($id) {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM blobs
                WHERE id = :id
            ');
            
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . implode(", ", $stmt->errorInfo()));
            }
            
            return true;
        } catch (\PDOException $e) {
            throw new \Exception("Failed to delete blob: " . $e->getMessage());
        }
    }
} 