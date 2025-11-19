<?php
session_start();
include '../Config/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'owner') {
    die("Akses ditolak. Anda bukan owner.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password_plain = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($username) || empty($password_plain) || empty($role)) {
        $error = "Semua kolom harus diisi!";
    } elseif (!in_array($role, ['owner', 'operator', 'kasir'])) {
        $error = "Role tidak valid!";
    } else {
        $check_stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ?");
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $password = md5($password_plain);

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

include '../Layout/sidebar.php';
include '../Layout/footer.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah User</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex">

    <div class="flex flex-col flex-1">

        <?php include '../Layout/header.php'; ?>

        <main class="p-6 pt-20">

            <div class="bg-white border border-1 rounded-lg shadow relative m-10">

                <!-- Header Card -->
                <div class="flex items-start justify-between p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold">Tambah User</h3>

                    <a href="kelola_user.php" 
                       class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg p-1.5">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>

                <!-- Body Card -->
                <div class="p-6 space-y-6">

                    <?php if (isset($error)): ?>
                        <p class="text-red-500 font-semibold"><?= $error ?></p>
                    <?php endif; ?>

                    <?php if (isset($_GET['msg'])): ?>
                        <p class="text-green-600 font-semibold"><?= htmlspecialchars($_GET['msg']) ?></p>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="grid grid-cols-6 gap-6">

                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Username</label>
                                <input type="text" name="username" 
                                       class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                       focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Password</label>
                                <input type="password" name="password" 
                                       class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                       focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Role</label>
                                <select name="role" 
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                        focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                                    <option value="owner">Owner</option>
                                    <option value="operator">Operator</option>
                                    <option value="kasir">Kasir</option>
                                </select>
                            </div>

                        </div>
                </div>

                <!-- Footer Card -->
                <div class="p-6 border-t border-gray-200 rounded-b flex gap-3">
                    <button type="submit" 
                        class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 
                        font-medium rounded-lg text-sm px-5 py-2.5">
                        Simpan User
                    </button>

                    <a href="kelola_user.php"
                        class="text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-200 
                        font-medium rounded-lg text-sm px-5 py-2.5">
                        Kembali
                    </a>
                </div>

                </form>
            </div>

        </main>
    </div>
</body>
</html>
