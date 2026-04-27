<?php
    include('../Connections/conn.php');
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    try {
        $sql = "SELECT id, locker_number, phase, gender, classification, employment_type, 
                       status, pos_x, pos_y, locker_length, locker_width, locker_facing,
                       created_at, updated_at
                FROM admin_lockers 
                ORDER BY pos_y, pos_x";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $lockers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get counts by status
        $countSql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'Occupied' THEN 1 ELSE 0 END) as occupied,
                        SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available,
                        SUM(CASE WHEN status = 'Reserved' THEN 1 ELSE 0 END) as reserved,
                        SUM(CASE WHEN status = 'Maintenance' THEN 1 ELSE 0 END) as maintenance
                     FROM admin_lockers";
        $countStmt = $conn->prepare($countSql);
        $countStmt->execute();
        $counts = $countStmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'lockers' => $lockers,
            'counts' => $counts
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
    }
?>
