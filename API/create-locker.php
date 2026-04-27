<?php
    include('../Connections/conn.php');
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    try {
        // Required fields
        $required = ['locker_number', 'phase', 'gender', 'classification', 'employment_type', 'status'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                http_response_code(400);
                echo json_encode(['message' => "Missing required field: $field"]);
                exit;
            }
        }

        $locker_number   = $_POST['locker_number'];
        $phase           = $_POST['phase'];
        $gender          = $_POST['gender'];
        $classification  = $_POST['classification'];
        $employment_type = $_POST['employment_type'];
        $status          = $_POST['status'];
        $pos_x           = floatval($_POST['pos_x'] ?? 0);
        $pos_y           = floatval($_POST['pos_y'] ?? 0);
        $locker_length   = intval($_POST['locker_length'] ?? 18);
        $locker_width    = intval($_POST['locker_width'] ?? 18);
        $locker_facing   = intval($_POST['locker_facing'] ?? 0);

        $sql = "INSERT INTO admin_lockers 
                (locker_number, phase, gender, classification, employment_type, status, pos_x, pos_y, locker_length, locker_width, locker_facing)
                VALUES 
                (:locker_number, :phase, :gender, :classification, :employment_type, :status, :pos_x, :pos_y, :locker_length, :locker_width, :locker_facing)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':locker_number'   => $locker_number,
            ':phase'           => $phase,
            ':gender'          => $gender,
            ':classification'  => $classification,
            ':employment_type' => $employment_type,
            ':status'          => $status,
            ':pos_x'           => $pos_x,
            ':pos_y'           => $pos_y,
            ':locker_length'   => $locker_length,
            ':locker_width'    => $locker_width,
            ':locker_facing'   => $locker_facing,
        ]);

        $lockerId = $conn->lastInsertId();

        http_response_code(201);
        echo json_encode([
            'message' => 'Locker created successfully',
            'id' => $lockerId
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
    }
?>