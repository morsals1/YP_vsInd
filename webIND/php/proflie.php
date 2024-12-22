<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не авторизован']);
    exit;
}

$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "personal_yp";

$conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка подключения к базе данных']);
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT surname, name, date, login, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success',
        'surname' => $user['surname'],
        'name' => $user['name'],
        'date' => $user['date'],
        'login' => $user['login'],
        'email' => $user['email']
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
}

mysqli_close($conn);
?>
