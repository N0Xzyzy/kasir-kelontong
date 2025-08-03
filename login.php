<?php
session_start();
require 'koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();


        if (md5($password) === $user['password']) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: index.php?error=" . urlencode("Password salah"));
            $error = 'Password Salah';
            exit;
        }
    } else {
        header("Location: index.php?error=" . urlencode("Username Tidak Ditemukan"));
        $error = 'User Tidak Ditemukan';
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
