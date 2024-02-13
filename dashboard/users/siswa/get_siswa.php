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
            echo "<a href='../detail/?data=siswa&nis=" . $siswa['nis']. "'><div class='search-items'><b>" . $siswa['nama'] . "</b> (" . $siswa['nis'] . ") </div></a>";
        }
    }
} else {
    header("location:./");
}