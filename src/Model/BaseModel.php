<?php

namespace App\Model;

use App\Config\Database;

abstract class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function fetchAll($result) {
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function fetch($result) {
        return $result->fetch(\PDO::FETCH_ASSOC);
    }
} 