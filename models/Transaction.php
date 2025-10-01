<?php
class Transaction extends Model {
    protected $table = 'transactions';
    
    public function createWithItems($transactionData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Créer la transaction
            $transactionId = $this->create($transactionData);
            
            // Ajouter les items
            foreach ($items as $item) {
                $item['transaction_id'] = $transactionId;
                $this->createTransactionItem($item);
            }
            
            $this->db->commit();
            return $transactionId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    private function createTransactionItem($data) {
        $sql = "INSERT INTO transaction_items (transaction_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['transaction_id'],
            $data['product_id'],
            $data['quantity'],
            $data['price']
        ]);
    }
    
    public function getWithItems($id) {
        // Récupérer la transaction
        $transaction = $this->findById($id);
        if (!$transaction) return null;
        
        // Récupérer les items
        $sql = "SELECT ti.*, p.name as product_name, p.image 
                FROM transaction_items ti 
                JOIN products p ON ti.product_id = p.id 
                WHERE ti.transaction_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $transaction['items'] = $stmt->fetchAll();
        
        return $transaction;
    }
    
    public function getRecentTransactions($limit = 50) {
        $sql = "SELECT t.*, u.email as customer_email 
                FROM {$this->table} t 
                LEFT JOIN users u ON t.user_id = u.id 
                ORDER BY t.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    public function getAnalytics($startDate = null, $endDate = null) {
        $whereClause = "WHERE 1=1";
        $params = [];
        
        if ($startDate) {
            $whereClause .= " AND DATE(created_at) >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $whereClause .= " AND DATE(created_at) <= ?";
            $params[] = $endDate;
        }
        
        // Statistiques générales
        $sql = "SELECT 
                    COUNT(*) as total_transactions,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_transactions,
                    SUM(CASE WHEN status = 'completed' THEN total_amount ELSE 0 END) as total_revenue,
                    AVG(CASE WHEN status = 'completed' THEN total_amount ELSE NULL END) as avg_order_value
                FROM {$this->table} {$whereClause}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $analytics = $stmt->fetch();
        
        // Conversion rate
        $analytics['conversion_rate'] = $analytics['total_transactions'] > 0 
            ? ($analytics['completed_transactions'] / $analytics['total_transactions']) * 100 
            : 0;
        
        return $analytics;
    }
    
    public function getChannelStats($startDate = null, $endDate = null) {
        $whereClause = "WHERE 1=1";
        $params = [];
        
        if ($startDate) {
            $whereClause .= " AND DATE(created_at) >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $whereClause .= " AND DATE(created_at) <= ?";
            $params[] = $endDate;
        }
        
        $sql = "SELECT 
                    contact_channel,
                    COUNT(*) as total_contacts,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_sales,
                    SUM(CASE WHEN status = 'completed' THEN total_amount ELSE 0 END) as revenue
                FROM {$this->table} {$whereClause}
                GROUP BY contact_channel
                ORDER BY total_contacts DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getDailyStats($days = 30) {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as transactions,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'completed' THEN total_amount ELSE 0 END) as revenue
                FROM {$this->table} 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }
}
?>