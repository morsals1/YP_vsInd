<?php
require_once 'db_connection.php';

session_start();
$userId = $_SESSION['user_id'] ?? null;

$page = $_GET['page'] ?? 'main';

$response = [];

if ($page === 'my-products') {
    if ($userId) {
        $sql = "SELECT * FROM products WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
    } else {
        echo json_encode(['error' => 'Пользователь не авторизован.']);
        exit;
    }
} else {
    $sql = "SELECT * FROM products";
    $stmt = $conn->prepare($sql);
}

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'image' => $row['image']
            ];
        }
    }
}

echo json_encode($response);
$conn->close();
?>
