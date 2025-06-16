<?php

namespace App\Controller;

use App\Model\Item;

class BlobController extends BaseController {
    private $item;
    
    public function __construct() {
        parent::__construct();
        $this->item = new Item();
    }
    
    public function serve($id) {
        try {
            $blob = $this->item->getBlob($id);
            
            if (!$blob) {
                header("HTTP/1.0 404 Not Found");
                exit;
            }
            
            header('Content-Type: ' . $blob['mime_type']);
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
            
            echo $blob['data'];
            exit;
        } catch (\Exception $e) {
            header("HTTP/1.0 500 Internal Server Error");
            exit;
        }
    }
} 