<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "midproject_bncc";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Cek apakah ada parameter ID di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('User tidak ditemukan!'); window.location.href='dashboard.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data user dari database
$query = "SELECT * FROM users WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('User tidak ditemukan!'); window.location.href='dashboard.php';</script>";
    exit;
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-4">

    <h2 class="mb-3">Detail Profil User</h2>

    <div class="card" style="width: 25rem;">
        <?php if (!empty($user['photo'])) : ?>
            <img src="<?php echo htmlspecialchars($user['photo']); ?>" class="card-img-top" alt="Foto Profil">
        <?php else : ?>
            <img src="profile/default.png" class="card-img-top" alt="Foto Default">
        <?php endif; ?>

        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h5>
            <p class="card-text"><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
            <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p class="card-text"><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio'] ?: "Tidak ada bio"); ?></p>
        </div>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Kembali</a>

</body>

</html>