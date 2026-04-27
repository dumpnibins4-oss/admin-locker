<?php
    include('../Connections/conn.php');
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    try {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id']) || !isset($data['pos_x']) || !isset($data['pos_y'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing required fields: id, pos_x, pos_y']);
            exit;
        }

        $id    = intval($data['id']);
        $pos_x = floatval($data['pos_x']);
        $pos_y = floatval($data['pos_y']);

        $sql = "UPDATE admin_lockers 
                SET pos_x = :pos_x, pos_y = :pos_y, updated_at = GETDATE() 
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':pos_x' => $pos_x,
            ':pos_y' => $pos_y,
            ':id'    => $id,
        ]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['message' => 'Locker not found']);
            exit;
        }

        echo json_encode([
            'message' => 'Position updated',
            'id'      => $id,
            'pos_x'   => $pos_x,
            'pos_y'   => $pos_y,
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
    }
?>
