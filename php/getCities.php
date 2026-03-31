<?php
header('Content-Type: application/json');
require '../database.php';

try {
    $query = "SELECT Name FROM City ORDER BY Name ASC";
    $stmt = $pdo->query($query);
    $cities = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($cities);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>