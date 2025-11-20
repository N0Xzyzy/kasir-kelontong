<?php
session_start();
include '../Config/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'owner') {
    die("Akses ditolak. Anda bukan owner.");
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM users";
if (!empty($search)) {
    $query .= " WHERE username LIKE '%$search%' OR username LIKE '%$search%'";
}
$query .= " ORDER BY username ASC";

$result = $conn->query($query);

include '../Layout/sidebar.php';
include '../Layout/footer.php';
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="pagewTable.css">
    <title>Data User</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen flex">
    <div class="flex-1 flex flex-col">
        <?php
        include '../Layout/header.php';
        if (isset($_SESSION['msg'])) {
            echo "<p style='color: green;'>" . $_SESSION['msg'] . "</p>";
            unset($_SESSION['msg']);
        }
        ?>
        <main class="p-6 pt-24 pl-32 flex-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold">Data User</h2>

                    <?php if (isset($_GET['msg'])): ?>
                        <div class="notif-sukses" id="notif">
                            <?= htmlspecialchars($_GET['msg']) ?>
                        </div>
                    <?php endif; ?>

                    <script>
                        setTimeout(function() {
                            const notif = document.getElementById('notif');
                            if (notif) {
                                notif.classList.add('hide');
                                setTimeout(() => notif.remove(), 500);
                            }
                        }, 3000);
                    </script>

                    <form method="GET">
                        <div class="flex item-center mb-4 mt-4">
                            <input class="px-3 py-2 font-semibold placeholder-gray-500 text-black bg-white ring-2 ring-gray-300 focus:ring-gray-500 focus:ring2" type="text" name="search" placeholder="Cari username"
                                value="<?php echo htmlspecialchars($search); ?>">
                            <button class="cursor-pointer p-1.5 bg-blue-400 border-blue-400" type="submit">
                            <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                            </button>
                        </div>
                    </form>

                    <div>
                        <table class="w-full">
                            <thead class="border-b border-gray-200 bg-gray-50">
                                <tr>
                                    <th class="p-3 text-sm text-gray-700 text-left font-semibold">ID</th>
                                    <th class="p-3 text-sm text-gray-700 text-left font-semibold">Username</th>
                                    <th class="p-3 text-sm text-gray-700 text-left font-semibold">Role</th>
                                    <th class="p-3 text-sm text-gray-700 text-left font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        <td class="p-3 text-sm text-gray-700"><?php echo $row['id_user']; ?></td>
                                        <td class="p-3 text-sm text-gray-700"><?php echo $row['username']; ?></td>
                                        <td class="p-3 text-sm text-gray-700"><?php echo $row['role']; ?></td>
                                        <td class="p-3 text-sm text-gray-700">
                                            <?php if ($row['username'] != $_SESSION['username']) { ?>
                                                <a class="p-1.5 tracking-wider bg-green-300 text-green-800 rounded-sm bg-opacity-30 cursor-pointer font-semibold" href="editUser.php?id_user=<?php echo $row['id_user']; ?>">Edit</a>
                                                    |
                                                <a class="p-1.5 tracking-wider bg-red-300 text-red-800 rounded-sm bg-opacity-30 cursor-pointer font-semibold" href="hapusUser.php?id_user=<?php echo $row['id_user']; ?>"
                                                onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                                            <?php } else { ?>
                                                <span style="color:gray;">Tidak bisa dihapus</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <a class="font-semibold text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center" href="tambahUser.php">Tambah User</a>
                    </div>
            </div>
        </main>
    </div>
</body>

</html>