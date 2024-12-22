<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "personal_yp";

$conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка подключения к базе данных: ' . mysqli_connect_error()]);
    exit;
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $login = trim(strip_tags($_POST['login']));
        $password = trim(strip_tags($_POST['password']));

        $stmt = $conn->prepare("SELECT id, password, is_employee, name, surname, email, login, date FROM users WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();        

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_login'] = $login;
                $_SESSION['is_employee'] = $row['is_employee'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_surname'] = $row['surname'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_nickname'] = $row['login'];
                $_SESSION['user_registration_date'] = $row['date'];

                $is_employee = $row['is_employee'];
                if ($is_employee) {
                    echo json_encode(['status' => 'success', 'message' => 'Успешный вход', 'redirect' => 'html/main_employee.html']);
                } else {
                    echo json_encode(['status' => 'success', 'message' => 'Успешный вход', 'redirect' => 'html/main_byuer.html']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Неверный пароль']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
        }
        $stmt->close();
    }
}

mysqli_close($conn);
?>
