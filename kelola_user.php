<?php
session_start();
include 'Config/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    die("Akses ditolak. Anda bukan owner.");
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM users";
if (!empty($search)) {
    $query .= " WHERE username LIKE '%$search%' OR username LIKE '%$search%'";
}
$query .= " ORDER BY username ASC";

$result = $conn->query($query);

include 'Layout/sidebar.php';
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
    <section class="flex-1 flex flex-col">
        <?php
        include 'Layout/header.php';
        if (isset($_SESSION['msg'])) {
            echo "<p style='color: green;'>" . $_SESSION['msg'] . "</p>";
            unset($_SESSION['msg']);
        }
        ?>

        <h2>Data User</h2>

        <?php if (isset($_GET['msg'])): ?>
            <div class="notif-sukses" id="notif">
                    <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <script>
        setTimeout(function () {
            const notif = document.getElementById('notif');
            if (notif) {
            notif.classList.add('hide');
            setTimeout(() => notif.remove(), 500);
            }
        }, 3000);
        </script>

        <form method="GET">
            <input type="text" name="search" placeholder="Cari username"
                value="<?php echo htmlspecialchars($search); ?>">
            <button class="cari" type="submit"><svg  width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.6725 16.6412L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></button>
            <a href="../user_management"><button class="reset" type="button"><svg  fill="currentColor" width="15px" height="15px" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg" stroke="#ff9500"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M960 0v213.333c411.627 0 746.667 334.934 746.667 746.667S1371.627 1706.667 960 1706.667 213.333 1371.733 213.333 960c0-197.013 78.4-382.507 213.334-520.747v254.08H640V106.667H53.333V320h191.04C88.64 494.08 0 720.96 0 960c0 529.28 430.613 960 960 960s960-430.72 960-960S1489.387 0 960 0" fill-rule="evenodd"></path> </g></svg></button></a>
        </form>

    <div>
    <table class="border-collapse table-auto" border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td>
                <?php echo $row['id_user']; ?>
            </td>
            <td>
                <?php echo $row['username']; ?>
            </td>
            <td>
                <?php echo $row['role']; ?>
            </td>
            <td>
                <?php if ($row['username'] != $_SESSION['username']) { ?>
                <a href="hapusUser.php?id_user=<?php echo $row['id_user']; ?>"
                    onclick="return confirm('Yakin ingin menghapus user ini?')"><button class="btn-hapus"> <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M6 7H12H18V18C18 19.6569 16.6569 21 15 21H9C7.34315 21 6 19.6569 6 18V7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>Hapus</button></a>
                <?php } else { ?>
                <span style="color:gray;">Tidak bisa dihapus</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    <a class="add" href="tambahUser.php"><svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 12H18M12 6V18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>Tambah User</a>
    </div>
    </section>
</body>

</html>