<?php
session_start();
include '../Config/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'owner') {
    die("Akses ditolak. Anda bukan owner.");
}

// Ambil kata kunci pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query aman menggunakan prepared statement
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? ORDER BY username ASC");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT * FROM users ORDER BY username ASC";
    $result = $conn->query($query);
}

include '../Layout/sidebar.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>

<body class="bg-gray-100 h-screen flex">
<div class="flex-1 flex flex-col">
    <?php include '../Layout/header.php'; ?>

    <main class="p-6 pt-24 pl-32 flex-1">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold mb-4">Data User</h1>

            <!-- Notifikasi -->
            <?php if (isset($_GET['msg'])): ?>
                <div id="notif" class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                    <?= htmlspecialchars($_GET['msg']) ?>
                </div>
            <?php endif; ?>

            <script>
                setTimeout(() => {
                    const notif = document.getElementById('notif');
                    if (notif) notif.remove();
                }, 3000);
            </script>

            <!-- Form Search -->
            <form method="GET" class="mb-4">
                <div class="flex gap-2">
                    <input
                        class="w-64 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400"
                        type="text"
                        name="search"
                        placeholder="Cari username..."
                        value="<?= htmlspecialchars($search) ?>"
                    >
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Cari
                    </button>
                </div>
            </form>

            <!-- Tabel -->
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="p-3 text-left text-sm font-semibold text-gray-700">Username</th>
                            <th class="p-3 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="p-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-3 text-sm"><?= $row['id_user'] ?></td>
                                    <td class="p-3 text-sm"><?= $row['username'] ?></td>
                                    <td class="p-3 text-sm"><?= $row['role'] ?></td>
                                    <td class="p-3 text-sm space-x-1">
                                        <?php if ($row['username'] != $_SESSION['username']): ?>
                                            <a href="editUser.php?id_user=<?= $row['id_user'] ?>"
                                               class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
                                                Edit
                                            </a>
                                            <a href="hapusUser.php?id_user=<?= $row['id_user'] ?>"
                                               onclick="return confirm('Yakin ingin menghapus user ini?')"
                                               class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">
                                                Hapus
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-xs italic">Akun sendiri</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tombol Tambah -->
            <div class="mt-4">
                <a href="tambahUser.php"
                   class="inline-block px-5 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">
                    + Tambah User
                </a>
            </div>
        </div>
    </main>
</div>
</body>
</html>

<?php include '../Layout/footer.php'; ?>
