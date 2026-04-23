<?php
    include '../Connections/conn.php';
    header('Content-Type: application/json');

    try {
        $stmt = $conn->prepare("
            SELECT 
                EmployeeID, BiometricsID, LastName, FirstName, MiddleName,
                Gender, Company, Location, Department, Section,
                DateHired, EmploymentStatus, Classification,
                PositionTitle, ContactNumber, Email, IsActive
            FROM [LRNPH_OJT].[dbo].[lrn_master_list]
            WHERE IsActive = '1'
            ORDER BY LastName, FirstName
        ");
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($employees);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
?>