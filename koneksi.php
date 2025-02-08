<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "midproject_bncc";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if ($koneksi) {
    echo "Koneksi Berhasil!";
} else {
    echo "Koneksi Gagal: " . mysqli_connect_error();
}
