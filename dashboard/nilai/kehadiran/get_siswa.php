<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['search'])) {
    $id_guru = $_POST['id_guru'] ?? null;
    $data_kelas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas WHERE wali_kelas = '$id_guru'"));
    $id_kelas = $data_kelas['id_kelas'];
    $search = $_POST['search'];
    $query = "SELECT * FROM siswa WHERE (nama LIKE '$search%' OR nis LIKE '$search%') AND id_kelas = '$id_kelas' LIMIT 5";
    $result = mysqli_query($koneksi, $query);
    if ($search != '') {
        foreach ($result as $siswa) {
            echo "<div class='search-pilihan'><b>" . $siswa['nama'] . "</b> (" . $siswa['nis'] . ") </div>";
        }
    }
} else {
    header("location:./");
}
