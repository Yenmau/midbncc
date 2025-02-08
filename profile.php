<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "midproject_bncc";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$query = "SELECT first_name, last_name, email, bio, photo FROM users WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>



<body class="bg-gray-100 min-h-screen flex flex-col items-center">

    <nav class="bg-gray-900 w-full py-4 px-6 shadow-md ">
        <div class="flex items-center justify-between mx-10">
            <div class="flex space-x-10">
                <h1 class="text-white text-lg font-semibold"><a href="dashboard.php">Dashboard</a></h1>
                <h1 class="text-white text-lg font-semibold"><a href="profile.php">Profile</a></h1>
            </div>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">Logout</a>
        </div>
    </nav>

    <div class="bg-white shadow-md rounded-lg p-6 max-w-md w-full text-center mt-10">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Profile</h2>
        <div class="flex flex-col items-center mb-4">
            <?php if (!empty($user['photo'])): ?>
                <img src="<?= htmlspecialchars($user['photo']) ?>" alt="Foto Profil" class="w-24 h-24 rounded-full shadow">
            <?php else: ?>
                <span class="text-gray-500">Tidak ada foto</span>
            <?php endif; ?>
        </div>
        <p class="text-lg text-gray-700"><strong>Nama:</strong> <?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?></p>
        <p class="text-lg text-gray-700"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p class="text-lg text-gray-700"><strong>Bio:</strong> <?= htmlspecialchars($user['bio']) ?></p>
        <a href="logout.php" class="mt-6 inline-block bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">Logout</a>
    </div>
</body>

</html>