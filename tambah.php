<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "midproject_bncc";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = trim(mysqli_real_escape_string($koneksi, $_POST['id']));
    $first_name = trim(mysqli_real_escape_string($koneksi, $_POST['first_name']));
    $last_name = trim(mysqli_real_escape_string($koneksi, $_POST['last_name']));
    $email = trim(mysqli_real_escape_string($koneksi, $_POST['email']));
    $password = trim($_POST['password']);
    $bio = trim(mysqli_real_escape_string($koneksi, $_POST['bio']));
    $photo = "";

    // 🔴 Validasi Tidak Boleh Kosong (kecuali Bio)
    if (empty($id) || empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $errors[] = "Semua field wajib diisi kecuali Bio!";
    }

    // 🔴 Validasi Format Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid!";
    }

    // 🔴 Validasi Email Tidak Boleh Duplikat
    $cek_email = mysqli_query($koneksi, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        $errors[] = "Email sudah digunakan, gunakan email lain!";
    }

    // Jika ada error, tampilkan pesan
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>alert('$error'); window.history.back();</script>";
        }
        exit;
    }

    // Hash Password
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    // 🔴 Proses Upload Foto
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "profile/";
        $photo_name = time() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $photo_name;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo = $target_file;
        }
    }

    // 🔴 Query Insert Data
    $query = "INSERT INTO users (id, first_name, last_name, email, password, bio, photo) 
              VALUES ('$id', '$first_name', '$last_name', '$email', '$password_hashed', '$bio', '$photo')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('User berhasil ditambahkan!'); window.location.href='dashboard.php';</script>";
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
    <title>Tambah User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-4">

    <h2 class="mb-3">Tambah User</h2>
    <form action="tambah.php" method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="id" class="form-label">ID</label>
            <input type="text" class="form-control" name="id" required>
        </div>

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
            <label for="bio" class="form-label">Bio (Opsional)</label>
            <input type="text" class="form-control" name="bio">
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Upload Foto</label>
            <input type="file" class="form-control" name="photo">
        </div>

        <button type="submit" class="btn btn-primary">Tambah</button>
        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>

</body>

</html>