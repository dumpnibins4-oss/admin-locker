<?php
    session_start();
    include '../Connections/conn.php';
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username and password are required']);
            exit;
        }

        try {
            $stmt = $conn->prepare("SELECT * FROM [LRNPH_OJT].[dbo].[lrnph_users] WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if($user) {
                $stmt = $conn->prepare("SELECT * FROM [dbo].[admin_locker_restrictions] WHERE biometrics_id = ?");
                $stmt->execute([$user['username']]);
                $restriction = $stmt->fetch();
                if (password_verify($password, $user['password'])) {
                    if ($restriction) {
                        $stmt = $conn->prepare("SELECT * FROM [LRNPH_OJT].[dbo].[lrn_master_list] WHERE BiometricsID = ?");
                        $stmt->execute([$user['username']]);
                        $lrn = $stmt->fetch();
                        
                        if ($lrn) {
                            $_SESSION['lrn_master_list'] = $lrn;
                            $_SESSION['user'] = $user;
                            $_SESSION['restriction'] = $restriction;
                        }
                        
                        http_response_code(200);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Signed in successfully'
                        ]);
                    }
                    else {
                        http_response_code(401);
                        echo json_encode(['success' => false, 'message' => 'You do not have access to this system']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['success' => false, 'message' => 'Invalid password']);
                }
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        }
        catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Internal server error']);
        }

    }
?>