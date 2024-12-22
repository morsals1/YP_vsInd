<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

require_once 'db_connection.php';

$response = ['status' => 'error', 'message' => ''];

session_start();
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    $response['message'] = 'Ошибка: пользователь не авторизован.';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['product_name'] ?? '';
    $description = $_POST['product_description'] ?? '';
    $price = $_POST['product_price'] ?? 0;
    $imageName = $_FILES['product_image']['name'] ?? '';
    $targetFilePath = '../uploads/' . basename($imageName);

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, user_id) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('ssdsi', $name, $description, $price, $imageName, $userId);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Товар успешно добавлен.';
            } else {
                $response['message'] = 'Ошибка выполнения запроса: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = 'Ошибка подготовки запроса: ' . $conn->error;
        }
    } else {
        $response['message'] = 'Ошибка загрузки файла изображения.';
    }
} else {
    $response['message'] = 'Неверный метод запроса.';
}

echo json_encode($response);
$conn->close();
