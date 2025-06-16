<?php

namespace App\Controller;

use App\Model\Item;
use App\Repository\ItemRepository;

class ItemController extends BaseController {
    private $itemRepository;

    public function __construct() {
        $this->itemRepository = new ItemRepository();
    }

    public function landing() {
        return $this->render('landing', [
            'title' => 'Game Items Catalog'
        ]);
    }

    public function index() {
        if ($this->isApiRequest()) {
            return $this->apiIndex();
        }
        
        return $this->render('items/index', [
            'title' => 'Browse Items - Game Items Catalog'
        ]);
    }
    
    private function apiIndex() {
        try {
            $search = $_GET['search'] ?? '';
            $types = !empty($_GET['type']) ? explode(',', $_GET['type']) : [];
            $acts = !empty($_GET['act']) ? explode(',', $_GET['act']) : [];
            $rarities = !empty($_GET['rarity']) ? explode(',', $_GET['rarity']) : [];
            
            $filters = [
                'search' => $search,
                'types' => array_map('trim', $types),
                'acts' => array_map('trim', $acts),
                'rarities' => array_map('trim', $rarities)
            ];
            
            error_log("API Request - Filters: " . json_encode($filters));
            
            $items = $this->itemRepository->search($filters);
            error_log("Found " . count($items) . " items");
            
            $itemsArray = array_map(function($item) {
                $thumbnailId = $item->getThumbnailId();
                error_log("Item {$item->getId()} - Thumbnail ID: " . ($thumbnailId ?? 'null'));
                
                return [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'type' => $item->getType(),
                    'act' => $item->getAct(),
                    'rarity' => $item->getRarity(),
                    'description' => $item->getDescription(),
                    'notes' => $item->getNotes(),
                    'image_url' => $thumbnailId ? "/blob/" . $thumbnailId : null
                ];
            }, $items);
            
            $response = [
                'success' => true,
                'filters' => $filters,
                'count' => count($items),
                'items' => $itemsArray
            ];
            
            error_log("API Response: " . json_encode($response));
            
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } catch (\Exception $e) {
            error_log("API Error: " . $e->getMessage());
            error_log($e->getTraceAsString());
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Internal server error'
            ]);
            exit;
        }
    }
    
    private function isApiRequest() {
        return isset($_GET['api']) || strpos($_SERVER['REQUEST_URI'], '/api/') !== false;
    }

    public function show($id) {
        $item = new Item();
        $itemData = $item->getById($id);
        
        if (!$itemData) {
            $this->redirect('/items');
        }
        
        return $this->render('items/show', [
            'item' => $itemData,
            'title' => htmlspecialchars($itemData->getName()) . ' - Game Items Catalog'
        ]);
    }

    public function create() {
        if (!$this->isAdmin()) {
            $this->redirect('/items');
        }
        
        if ($this->isPost()) {
            $this->validateCSRF();
            
            $name = $this->getPost('name');
            $description = $this->getPost('description');
            $type = $this->getPost('type');
            $rarity = $this->getPost('rarity');
            $act = $this->getPost('act');
            
            $item = new Item();
            if ($item->create([
                'name' => $name,
                'description' => $description,
                'type' => $type,
                'rarity' => $rarity,
                'act' => $act
            ])) {
                $this->redirect('/items');
            }
            
            return $this->render('items/create', [
                'error' => 'Failed to create item',
                'csrf_token' => $this->generateCSRFToken(),
                'title' => 'Create Item - Game Items Catalog'
            ]);
        }
        
        return $this->render('items/create', [
            'csrf_token' => $this->generateCSRFToken(),
            'title' => 'Create Item - Game Items Catalog'
        ]);
    }

    public function edit($id) {
        if (!$this->isAdmin()) {
            $this->redirect('/items');
        }
        
        $item = new Item();
        $itemData = $item->getById($id);
        
        if (!$itemData) {
            $this->redirect('/items');
        }
        
        if ($this->isPost()) {
            $this->validateCSRF();
            
            $name = $this->getPost('name');
            $description = $this->getPost('description');
            $type = $this->getPost('type');
            $rarity = $this->getPost('rarity');
            $act = $this->getPost('act');
            
            if ($item->update($id, [
                'name' => $name,
                'description' => $description,
                'type' => $type,
                'rarity' => $rarity,
                'act' => $act
            ])) {
                $this->redirect('/items/' . $id);
            }
            
            return $this->render('items/edit', [
                'item' => $itemData,
                'error' => 'Failed to update item',
                'csrf_token' => $this->generateCSRFToken(),
                'title' => 'Edit ' . htmlspecialchars($itemData->getName()) . ' - Game Items Catalog'
            ]);
        }
        
        return $this->render('items/edit', [
            'item' => $itemData,
            'csrf_token' => $this->generateCSRFToken(),
            'title' => 'Edit ' . htmlspecialchars($itemData->getName()) . ' - Game Items Catalog'
        ]);
    }

    public function delete($id) {
        if (!$this->isAdmin()) {
            $this->redirect('/items');
        }
        
        $this->validateCSRF();
        
        $item = new Item();
        if ($item->delete($id)) {
            $this->redirect('/items');
        }
        
        $this->redirect('/items/' . $id);
    }

    public function serveBlob($id) {
        try {
            error_log("Serving blob ID: " . $id);
            
            $item = new \App\Model\Item();
            $blob = $item->getBlob($id);
            
            if (!$blob) {
                error_log("Blob not found for ID: " . $id);
                header("HTTP/1.0 404 Not Found");
                exit;
            }
            
            error_log("Blob found with mime type: " . $blob['mime_type']);
            error_log("Blob data length: " . strlen($blob['data']));
            
            header('Content-Type: ' . $blob['mime_type']);
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
            
            // Output the binary data
            echo $blob['data'];
            exit;
        } catch (\Exception $e) {
            error_log("Error serving blob: " . $e->getMessage());
            error_log($e->getTraceAsString());
            header("HTTP/1.0 500 Internal Server Error");
            exit;
        }
    }
} 