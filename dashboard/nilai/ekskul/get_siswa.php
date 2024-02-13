<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}
if (isset($_POST['key'])) {
    $search = $_POST['key'];
    $query = "SELECT * FROM siswa WHERE nama LIKE '$search%' OR nis LIKE '$search%' LIMIT 5";
    $result = mysqli_query($koneksi, $query);
    if ($search != '') {
        foreach ($result as $siswa) {
            echo "<div class='walas-result'><b>" . $siswa['nama'] . "</b> (" . $siswa['nis'] . ") </div>";
        }
    }
} else {
    header("location:./");
}
