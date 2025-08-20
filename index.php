<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <style>
        body{
            font-family: 'Montserrat',sans-serif;
            display: 100vh;
        }
    </style>
    <div class="flex justify-center items-center h-screen">
        <div class="w-96 p-6 shadow-lg rounded-mg">
            <h2 class="text-2xl font-bold mb-2">Login</h2>
        <form method="POST" action="Config/login.php">
            <input class="border w-full px-2 py-4 border border-gray-300 rounded-md focus:outline-focus focus:ring-2 focus:ring-blue-300" type="text" name="username" placeholder="Username" required><br>
            <input class="border w-full px-2 py-4 border border-gray-300 rounded-md focus:outline-focus focus:ring-2 focus:ring-blue-300" type="password" name="password" placeholder="Password" required><br>
            <button class="text-md w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition cursor-pointer" type="submit">Login</button>
            <?php if (isset($_GET['error'])): ?>
            <p class="text-red-500 text-sm text-center mt-2"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>

        </form>
        </div>
        
    </div>
</body>
</html>
