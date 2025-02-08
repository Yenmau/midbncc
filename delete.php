<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "midproject_bncc";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Ambil nama file foto sebelum menghapus data
    $query = "SELECT photo FROM users WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        // Hapus file foto jika ada
        if (!empty($data['photo']) && file_exists($data['photo'])) {
            unlink($data['photo']);
        }

        // Hapus data user dari database
        $deleteQuery = "DELETE FROM users WHERE id = '$id'";
        if (mysqli_query($koneksi, $deleteQuery)) {
            echo "<script>alert('User berhasil dihapus!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "<script>alert('User tidak ditemukan!'); window.location.href='dashboard.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='dashboard.php';</script>";
}

