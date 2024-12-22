<?php
header('Content-Type: application/json; charset=utf-8');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "personal_yp";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$connect) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка подключения к базе данных: ' . mysqli_connect_error()]);
    exit;
}
else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $is_employee = $isEmployee = isset($_POST['is_employee']) ? (int)$_POST['is_employee'] : 0;
        $surname = trim(strip_tags($_POST['surname']));
        $name = trim(strip_tags($_POST['name']));
        $date = trim(strip_tags($_POST['date']));
        $login = trim(strip_tags($_POST['login']));
        $email = trim(strip_tags($_POST['email']));
        $password = password_hash(trim(strip_tags($_POST['password'])), PASSWORD_DEFAULT);

        $check_login_sql = "SELECT * FROM users WHERE login = '$login'";
        $result = mysqli_query($connect, $check_login_sql);

        if (mysqli_num_rows($result) > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Логин уже занят']);
            exit;
        }
        else {
            $sql = "INSERT INTO users (is_employee, surname, name, date, login, email, password) 
            VALUES ('$is_employee', '$surname', '$name', '$date', '$login', '$email', '$password')";
            
            if (mysqli_query($connect, $sql)) {
                echo json_encode(['status' =>'success','message' => 'Регистрация успешна']);
            }
            else {
                echo json_encode(['status' => 'error', 'message' => 'Ошибка регистрации']);
            }
        }
    }
}

mysqli_close($connect);
?>