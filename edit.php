<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "midproject_bncc";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        echo "<script>alert('User tidak ditemukan!'); window.location.href='dashboard.php';</script>";
        exit;
    }
}

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($koneksi, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($koneksi, $_POST['last_name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'];
    $bio = mysqli_real_escape_string($koneksi, $_POST['bio']);
    $photo = $user['photo']; // Foto lama tetap jika tidak ada perubahan

    // Proses Upload Foto
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "profile/";
        $photo_name = time() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $photo_name;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Hapus foto lama jika ada
            if (!empty($user['photo']) && file_exists($user['photo'])) {
                unlink($user['photo']);
            }
            $photo = $target_file;
        }
    }

    // Query update
    $query = "UPDATE users SET 
              first_name = '$first_name', 
              last_name = '$last_name', 
              email = '$email', 
              password = '$password', 
              bio = '$bio', 
              photo = '$photo'
              WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('User berhasil diperbarui!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-4">

    <h2 class="mb-3">Edit User</h2>
    <form action="edit.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?= $user['first_name'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?= $user['last_name'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= $user['email'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
            <input type="password" class="form-control" name="password">
        </div>


        <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <input type="text" class="form-control" name="bio" value="<?= $user['bio'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Upload Foto Baru</label>
            <input type="file" class="form-control" name="photo">
            <?php if (!empty($user['photo'])) : ?>
                <br><img src="<?= $user['photo'] ?>" alt="User Photo" width="100">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>

</body>

</html>