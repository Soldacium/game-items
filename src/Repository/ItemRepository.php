<?php

namespace App\Repository;

use App\Model\Item;
use PDO;

class ItemRepository extends BaseRepository {
    public function getByUserId($userId) {
        $stmt = $this->db->prepare('
            SELECT id, name, type, rarity, act, description, notes, user_id, thumbnail_id
            FROM items
            WHERE user_id = ?
            ORDER BY created_at DESC
        ');
        
        $stmt->execute([$userId]);
        $items = [];
        
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $items[] = new Item(
                $data['id'],
                $data['name'],
                $data['type'],
                $data['rarity'],
                $data['act'],
                $data['description'],
                $data['notes'],
                $data['user_id'],
                $data['thumbnail_id']
            );
        }
        
        return $items;
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare('
            SELECT id, name, type, rarity, act, description, notes, user_id, thumbnail_id
            FROM items
            WHERE id = ?
        ');
        
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }
        
        return new Item(
            $data['id'],
            $data['name'],
            $data['type'],
            $data['rarity'],
            $data['act'],
            $data['description'],
            $data['notes'],
            $data['user_id'],
            $data['thumbnail_id']
        );
    }
    
    public function save(Item $item) {
        $stmt = $this->db->prepare('
            INSERT INTO items (name, type, rarity, act, description, notes, user_id, thumbnail_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            RETURNING id
        ');
        
        $stmt->execute([
            $item->getName(),
            $item->getType(),
            $item->getRarity(),
            $item->getAct(),
            $item->getDescription(),
            $item->getNotes(),
            $item->getUserId(),
            $item->getThumbnailId()
        ]);
        
        return $stmt->fetchColumn();
    }
    
    public function update(Item $item) {
        $stmt = $this->db->prepare('
            UPDATE items 
            SET name = ?, type = ?, rarity = ?, act = ?, description = ?, notes = ?, thumbnail_id = ?
            WHERE id = ? AND user_id = ?
        ');
        
        return $stmt->execute([
            $item->getName(),
            $item->getType(),
            $item->getRarity(),
            $item->getAct(),
            $item->getDescription(),
            $item->getNotes(),
            $item->getThumbnailId(),
            $item->getId(),
            $item->getUserId()
        ]);
    }
    
    public function delete(Item $item) {
        // First delete the blob if exists
        if ($item->getThumbnailId()) {
            $stmt = $this->db->prepare('DELETE FROM blobs WHERE id = ?');
            $stmt->execute([$item->getThumbnailId()]);
        }
        
        // Then delete the item
        $stmt = $this->db->prepare('
            DELETE FROM items
            WHERE id = ? AND user_id = ?
        ');
        
        return $stmt->execute([
            $item->getId(),
            $item->getUserId()
        ]);
    }
    
    private function createItemFromRow($row) {
        $item = new Item();
        $item->setId($row['id']);
        $item->setName($row['name']);
        $item->setType($row['type']);
        $item->setAct($row['act']);
        $item->setRarity($row['rarity']);
        $item->setDescription($row['description']);
        $item->setNotes($row['notes']);
        $item->setUserId($row['user_id'] ?? null);
        $item->setThumbnailId($row['thumbnail_id'] ?? null);
        $item->setCreatedAt($row['created_at'] ?? null);
        $item->setUpdatedAt($row['updated_at'] ?? null);
        
        return $item;
    }

    public function search(array $filters) {
        $conditions = [];
        $params = [];
        
        // Search by name or description
        if (!empty($filters['search'])) {
            $conditions[] = '(LOWER(name) LIKE LOWER(?) OR LOWER(description) LIKE LOWER(?))';
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Filter by types
        if (!empty($filters['types'])) {
            $placeholders = str_repeat('?,', count($filters['types']) - 1) . '?';
            $conditions[] = 'type IN (' . $placeholders . ')';
            $params = array_merge($params, $filters['types']);
        }
        
        // Filter by acts
        if (!empty($filters['acts'])) {
            $placeholders = str_repeat('?,', count($filters['acts']) - 1) . '?';
            $conditions[] = 'act IN (' . $placeholders . ')';
            $params = array_merge($params, $filters['acts']);
        }
        
        // Filter by rarities
        if (!empty($filters['rarities'])) {
            $placeholders = str_repeat('?,', count($filters['rarities']) - 1) . '?';
            $conditions[] = 'rarity IN (' . $placeholders . ')';
            $params = array_merge($params, $filters['rarities']);
        }
        
        $sql = '
            SELECT id, name, type, rarity, act, description, notes, user_id, thumbnail_id, created_at, updated_at
            FROM items
        ';
        
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY created_at DESC';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $items = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $items[] = $this->createItemFromRow($row);
        }
        
        return $items;
    }
} 