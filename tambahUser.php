<?php
session_start();
include 'Config/koneksi.php';

// Cek hak akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    die("Akses ditolak. Anda bukan owner.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password_plain = trim($_POST['password']);
    $role = $_POST['role'];

    // Validasi input kosong
    if (empty($username) || empty($password_plain) || empty($role)) {
        $error = "Semua kolom harus diisi!";
    } elseif (!in_array($role, ['owner', 'operator', 'kasir'])) {
        $error = "Role tidak valid!";
    } else {
        // Cek username sudah ada atau belum
        $check_stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Hash password pakai MD5
            $password = md5($password_plain);

            // Insert user baru
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $username, $password, $role);
                if ($stmt->execute()) {
                    header("Location: kelola_user.php?msg=User+berhasil+ditambahkan");
                    exit();
                } else {
                    $error = "Gagal menambahkan user: " . $stmt->error;
                }
            } else {
                $error = "Query gagal: " . $conn->error;
            }
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah User</title>
    <link rel="stylesheet" href="pageNTable.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-content">
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($_GET['msg'])) echo "<p style='color:green;'>".htmlspecialchars($_GET['msg'])."</p>"; ?>

    <div class="form-wrapper">
        <h2>Tambah User</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Role:</label>
                <select name="role">
                    <option value="owner">Owner</option>
                    <option value="operator">Operator</option>
                    <option value="kasir">Kasir</option>
                </select>
            </div>
            <button type="submit">+ Tambah User</button>
        </form>
    </div>
</body>
</html>
