<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT * FROM siswa WHERE nama LIKE '$search%' OR nis LIKE '$search%' LIMIT 5";
    $result = mysqli_query($koneksi, $query);
    if ($search != '') {
        foreach ($result as $siswa) {
            echo "<div class='search-pilihan'><b>" . $siswa['nama'] . "</b> (" . $siswa['nis'] . ") </div>";
        }
    }
} else {
    header("location:./");
}
