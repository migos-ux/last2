<?php
class Product extends Model {
    protected $table = 'products';
    
    public function findByCategory($categoryId, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = ? AND status = 'active'";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }
    
    public function search($query, $categoryId = null, $minPrice = null, $maxPrice = null) {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active'";
        $params = [];
        
        if ($query) {
            $sql .= " AND (name LIKE ? OR description LIKE ?)";
            $params[] = "%{$query}%";
            $params[] = "%{$query}%";
        }
        
        if ($categoryId) {
            $sql .= " AND category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($minPrice) {
            $sql .= " AND price >= ?";
            $params[] = $minPrice;
        }
        
        if ($maxPrice) {
            $sql .= " AND price <= ?";
            $params[] = $maxPrice;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getFeatured($limit = 8) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE featured = 1 AND status = 'active' ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    public function getWithCategory($id) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getTopSelling($limit = 10) {
        $sql = "SELECT p.*, COALESCE(SUM(ti.quantity), 0) as total_sold
                FROM {$this->table} p
                LEFT JOIN transaction_items ti ON p.id = ti.product_id
                LEFT JOIN transactions t ON ti.transaction_id = t.id
                WHERE p.status = 'active' AND (t.status = 'completed' OR t.status IS NULL)
                GROUP BY p.id
                ORDER BY total_sold DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
?>