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

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : "";
$query = "SELECT * FROM users";
if (!empty($search)) {
    $query .= " WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%'";
}
$hasil = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col items-center">
    <nav class="bg-gray-900 w-full py-4 px-6 shadow-md">
        <div class="flex items-center justify-between mx-10">
            <div class="flex space-x-10">
                <h1 class="text-white text-lg font-semibold"><a href="dashboard.php">Dashboard</a></h1>
                <h1 class="text-white text-lg font-semibold"><a href="profile.php">Profile</a></h1>
            </div>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">Logout</a>
        </div>
    </nav>

    <div class="w-full max-w-5xl bg-white shadow-md rounded-lg mt-10 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Data Users</h2>
            <a href="tambah.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Tambah</a>
        </div>

        <form method="GET" class="mb-4 flex items-center gap-2">
            <div class="relative w-80">
                <input type="text" name="search" placeholder="Cari nama atau email" value="<?php echo htmlspecialchars($search); ?>"
                    class="px-4 py-2 pl-10 border border-gray-300 rounded-md w-full focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M15 10a5 5 0 1 0-10 0 5 5 0 0 0 10 0z" />
                </svg>  
            </div>
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">Cari</button>
        </form>


        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Foto</th>
                        <th class="px-4 py-2 text-left">Full Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Bio</th>
                        <th class="px-4 py-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    if (isset($hasil) && mysqli_num_rows($hasil) > 0):
                        $no = 1;
                        while ($d = mysqli_fetch_assoc($hasil)): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-3 text-gray-700"><?php echo $no++; ?></td>
                                <td class="px-4 py-3">
                                    <?php if (!empty($d['photo'])): ?>
                                        <img src="<?php echo htmlspecialchars($d['photo']); ?>" alt="Foto" class="w-10 h-10 rounded-full">
                                    <?php else: ?>
                                        <span class="text-gray-400">Tidak ada foto</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-gray-700"><?php echo htmlspecialchars($d['first_name'] . " " . $d['last_name']); ?></td>
                                <td class="px-4 py-3 text-gray-700"><?php echo htmlspecialchars($d['email']); ?></td>
                                <td class="px-4 py-3 text-gray-700 truncate max-w-xs"><?php echo htmlspecialchars($d['bio']); ?></td>
                                <td class="px-4 py-3 flex gap-2 justify-center">
                                    <a href="detail.php?id=<?php echo $d['id']; ?>" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm">Detail</a>
                                    <a href="edit.php?id=<?php echo $d['id']; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm">Edit</a>
                                    <a href="delete.php?id=<?php echo $d['id']; ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>