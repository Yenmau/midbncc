<?php
session_start();

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "midproject_bncc";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . " " . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body class="flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
        <h3 class="text-center text-3xl font-bold text-gray-800 mb-6">Login</h3>

        <?php if (isset($error)) : ?>
            <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded-lg"> <?php echo $error; ?> </div>
        <?php endif; ?>

        <form action="login.php" method="post" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500" name="email" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500" name="password" required>
            </div>

            <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-3 rounded-lg transition font-semibold">Login</button>
        </form>

    </div>
</body>

</html>